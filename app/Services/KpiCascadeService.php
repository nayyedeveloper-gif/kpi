<?php

namespace App\Services;

use App\Models\KpiLog;
use App\Models\KpiMeasurement;
use App\Models\KpiConfiguration;
use App\Models\KpiCascadeImpact;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KpiCascadeService
{
    /**
     * Apply cascading impact when a KPI log is created.
     *
     * @param KpiLog $kpiLog
     * @param KpiMeasurement|null $kpiMeasurement
     * @return array
     */
    public function applyCascadingImpact(KpiLog $kpiLog, ?KpiMeasurement $kpiMeasurement = null)
    {
        try {
            DB::beginTransaction();

            $user = $kpiLog->user;
            $impactType = $kpiLog->type; // 'good' or 'bad'

            // Get active configuration for this user's role
            $configuration = $this->getConfigurationForUser($user);

            if (!$configuration || !$configuration->cascade_enabled) {
                Log::info("Cascading disabled or no configuration found for user: {$user->id}");
                return ['success' => false, 'message' => 'Cascading not enabled'];
            }

            // Get supervisor chain
            $supervisorChain = $this->getSupervisorChain($user, $configuration->max_cascade_levels);

            $impacts = [];
            $hierarchyLevel = 0;

            // Apply impact to the direct user first (level 0)
            $directImpact = $this->createImpact(
                $kpiLog,
                $kpiMeasurement,
                $user,
                $user,
                $hierarchyLevel,
                $impactType,
                $configuration,
                "Direct performance log"
            );
            $impacts[] = $directImpact;

            // Apply cascading impact to supervisors
            foreach ($supervisorChain as $supervisor) {
                $hierarchyLevel++;
                
                $positionName = $user->position ? $user->position->name : 'N/A';
                $impact = $this->createImpact(
                    $kpiLog,
                    $kpiMeasurement,
                    $user,
                    $supervisor,
                    $hierarchyLevel,
                    $impactType,
                    $configuration,
                    "Cascading from {$user->name} ({$positionName})"
                );
                
                $impacts[] = $impact;
            }

            DB::commit();

            Log::info("Cascading impact applied successfully", [
                'kpi_log_id' => $kpiLog->id,
                'source_user' => $user->id,
                'affected_users' => count($impacts),
            ]);

            return [
                'success' => true,
                'impacts' => $impacts,
                'affected_count' => count($impacts),
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to apply cascading impact: " . $e->getMessage(), [
                'kpi_log_id' => $kpiLog->id ?? null,
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create a cascade impact record.
     */
    protected function createImpact(
        KpiLog $kpiLog,
        ?KpiMeasurement $kpiMeasurement,
        User $sourceUser,
        User $affectedUser,
        int $hierarchyLevel,
        string $impactType,
        KpiConfiguration $configuration,
        string $reason
    ) {
        $impactScore = $configuration->getImpactScore($hierarchyLevel, $impactType);
        $weightMultiplier = $this->calculateWeightMultiplier($hierarchyLevel);

        $impact = KpiCascadeImpact::create([
            'kpi_log_id' => $kpiLog->id,
            'kpi_measurement_id' => $kpiMeasurement ? $kpiMeasurement->id : null,
            'source_user_id' => $sourceUser->id,
            'affected_user_id' => $affectedUser->id,
            'hierarchy_level' => $hierarchyLevel,
            'hierarchy_position' => $affectedUser->position ? $affectedUser->position->name : null,
            'impact_type' => $impactType,
            'impact_score' => $impactScore * $weightMultiplier,
            'weight_multiplier' => $weightMultiplier,
            'reason' => $reason,
            'applied_at' => now(),
        ]);

        // Update user's performance score in real-time
        $this->updateUserPerformanceScore($affectedUser, $impactScore * $weightMultiplier);

        return $impact;
    }

    /**
     * Get supervisor chain for a user.
     */
    protected function getSupervisorChain(User $user, int $maxLevels = 5)
    {
        $chain = [];
        $current = $user;
        $level = 0;

        while ($current->supervisor && $level < $maxLevels) {
            $chain[] = $current->supervisor;
            $current = $current->supervisor;
            $level++;
        }

        return $chain;
    }

    /**
     * Get configuration for a user based on their role.
     */
    protected function getConfigurationForUser(User $user)
    {
        return KpiConfiguration::active()
            ->where('target_role_id', $user->role_id)
            ->first();
    }

    /**
     * Calculate weight multiplier based on hierarchy level.
     */
    protected function calculateWeightMultiplier(int $hierarchyLevel)
    {
        // Direct user gets full impact (1.0)
        // Each level up gets reduced impact
        return max(0.1, 1.0 - ($hierarchyLevel * 0.2));
    }

    /**
     * Update user's performance score.
     */
    protected function updateUserPerformanceScore(User $user, float $impactScore)
    {
        $currentPerformance = $user->getCurrentPerformance();
        
        if ($currentPerformance && $currentPerformance->exists) {
            // Update existing performance score
            $newScore = $currentPerformance->overall_score + $impactScore;
            $currentPerformance->update([
                'overall_score' => max(0, min(100, $newScore)), // Keep between 0-100
            ]);
        }
    }

    /**
     * Get cascade impact summary for a user.
     */
    public function getUserImpactSummary(User $user, $startDate = null, $endDate = null)
    {
        $query = KpiCascadeImpact::forUser($user->id);

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $impacts = $query->get();

        return [
            'total_impacts' => $impacts->count(),
            'good_impacts' => $impacts->where('impact_type', 'good')->count(),
            'bad_impacts' => $impacts->where('impact_type', 'bad')->count(),
            'total_score_impact' => $impacts->sum('impact_score'),
            'direct_impacts' => $impacts->where('hierarchy_level', 0)->count(),
            'cascaded_impacts' => $impacts->where('hierarchy_level', '>', 0)->count(),
            'by_level' => $impacts->groupBy('hierarchy_level')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_score' => $group->sum('impact_score'),
                ];
            }),
        ];
    }

    /**
     * Get team cascade impact (for managers/supervisors).
     */
    public function getTeamImpactSummary(User $user, $startDate = null, $endDate = null)
    {
        $subordinates = $user->getAllSubordinates();
        $subordinateIds = $subordinates->pluck('id')->toArray();

        $query = KpiCascadeImpact::whereIn('source_user_id', $subordinateIds)
            ->where('affected_user_id', $user->id);

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $impacts = $query->get();

        return [
            'total_team_impacts' => $impacts->count(),
            'good_from_team' => $impacts->where('impact_type', 'good')->count(),
            'bad_from_team' => $impacts->where('impact_type', 'bad')->count(),
            'total_score_from_team' => $impacts->sum('impact_score'),
            'top_contributors' => $impacts->groupBy('source_user_id')
                ->map(function ($group) {
                    return [
                        'user' => User::find($group->first()->source_user_id),
                        'impact_count' => $group->count(),
                        'total_score' => $group->sum('impact_score'),
                    ];
                })
                ->sortByDesc('total_score')
                ->take(5)
                ->values(),
        ];
    }

    /**
     * Recalculate all cascading impacts for a specific period.
     */
    public function recalculatePeriod($startDate, $endDate)
    {
        // Delete existing impacts for the period
        KpiCascadeImpact::whereBetween('created_at', [$startDate, $endDate])->delete();

        // Get all KPI logs in the period
        $kpiLogs = KpiLog::whereBetween('logged_at', [$startDate, $endDate])->get();

        $results = [
            'processed' => 0,
            'success' => 0,
            'failed' => 0,
        ];

        foreach ($kpiLogs as $kpiLog) {
            $results['processed']++;
            
            $result = $this->applyCascadingImpact($kpiLog, $kpiLog->kpiMeasurement);
            
            if ($result['success']) {
                $results['success']++;
            } else {
                $results['failed']++;
            }
        }

        return $results;
    }
}
