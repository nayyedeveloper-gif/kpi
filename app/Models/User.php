<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'department_id',
        'branch_id',
        'position_id',
        'supervisor_id',
        'phone_number',
        'is_active',
        'profile_photo',
        'phone',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function subordinates()
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }

    public function kpiMeasurements()
    {
        return $this->hasMany(KpiMeasurement::class);
    }

    public function kpiLogs()
    {
        return $this->hasMany(KpiLog::class);
    }

    public function performanceScores()
    {
        return $this->hasMany(PerformanceScore::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user')
            ->withPivot('role_in_group', 'joined_at')
            ->withTimestamps();
    }

    public function ledGroups()
    {
        return $this->hasMany(Group::class, 'leader_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeByPosition($query, $positionId)
    {
        return $query->where('position_id', $positionId);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->position && $this->position->hierarchy_level <= 1; // CEO or Director
    }

    public function canManageUser($user)
    {
        if ($this->isAdmin()) {
            return true;
        }

        // Check if user is a subordinate
        return $this->subordinates->contains($user);
    }

    // Organization Chart methods
    public function getHierarchyData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->position?->name ?? 'No Position',
            'department' => $this->department?->name ?? 'No Department',
            'role' => $this->role?->display_name ?? 'No Role',
            'email' => $this->email,
            'phone' => $this->phone_number,
            'supervisor_id' => $this->supervisor_id,
            'is_active' => $this->is_active,
            'subordinates' => $this->subordinates->map(function ($subordinate) {
                return $subordinate->getHierarchyData();
            }),
        ];
    }

    public static function getOrganizationTree()
    {
        // Get CEO/top-level users (users without supervisors)
        $topLevelUsers = self::with(['position', 'department', 'role', 'subordinates'])
            ->whereNull('supervisor_id')
            ->active()
            ->get();

        return $topLevelUsers->map(function ($user) {
            return $user->getHierarchyData();
        });
    }

    public function getSupervisorChain()
    {
        $chain = collect();
        $current = $this;

        while ($current->supervisor) {
            $chain->push($current->supervisor);
            $current = $current->supervisor;
        }

        return $chain;
    }

    public function getAllSubordinates()
    {
        $subordinates = collect();

        foreach ($this->subordinates as $subordinate) {
            $subordinates->push($subordinate);
            $subordinates = $subordinates->merge($subordinate->getAllSubordinates());
        }

        return $subordinates;
    }

    /**
     * Get current month performance score.
     */
    public function getCurrentPerformance()
    {
        return $this->performanceScores()
            ->currentMonth()
            ->first() ?? $this->calculateCurrentPerformance();
    }

    /**
     * Calculate current performance if not exists.
     */
    public function calculateCurrentPerformance()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // KPI Score - based on total_score average
        $kpiMeasurements = $this->kpiMeasurements()
            ->whereBetween('measurement_date', [$startOfMonth, $endOfMonth])
            ->get();
        
        $kpiTotal = $kpiMeasurements->count();
        $kpiAvgScore = $kpiMeasurements->avg('total_score') ?? 0;
        $kpiScore = $kpiTotal > 0 ? ($kpiAvgScore / 6) * 100 : 0; // 6 is max score
        $kpiCompleted = $kpiMeasurements->where('total_score', '>=', 4)->count(); // 4+ out of 6 is considered completed

        // Task Score (from KPI logs - good vs bad)
        $goodLogs = $this->kpiLogs()
            ->whereBetween('logged_at', [$startOfMonth, $endOfMonth])
            ->where('status', 'good')
            ->count();
        $badLogs = $this->kpiLogs()
            ->whereBetween('logged_at', [$startOfMonth, $endOfMonth])
            ->where('status', 'bad')
            ->count();
        $taskTotal = $goodLogs + $badLogs;
        $taskCompleted = $goodLogs;
        $taskScore = $taskTotal > 0 ? ($goodLogs / $taskTotal) * 100 : 0;

        // Quality Score (based on percentage average)
        $qualityScore = $kpiMeasurements->avg('percentage') ?? 0;

        // Attendance Score (placeholder - can be integrated with attendance system)
        $attendanceScore = 85; // Default

        // Collaboration Score (based on team activities)
        $collaborationScore = 80; // Default

        $performance = new PerformanceScore([
            'user_id' => $this->id,
            'period_date' => now()->startOfMonth(),
            'period_type' => 'monthly',
            'kpi_score' => round($kpiScore, 2),
            'task_score' => round($taskScore, 2),
            'quality_score' => round($qualityScore, 2),
            'attendance_score' => $attendanceScore,
            'collaboration_score' => $collaborationScore,
            'tasks_completed' => $taskCompleted,
            'tasks_total' => $taskTotal,
            'kpis_completed' => $kpiCompleted,
            'kpis_total' => $kpiTotal,
        ]);

        $performance->calculateOverallScore();
        
        return $performance;
    }

    /**
     * Get team performance (user + subordinates).
     */
    public function getTeamPerformance()
    {
        $team = collect([$this])->merge($this->getAllSubordinates());
        
        $teamScores = $team->map(function ($member) {
            return $member->getCurrentPerformance();
        })->filter();

        return [
            'team_size' => $team->count(),
            'average_score' => $teamScores->avg('overall_score') ?? 0,
            'top_performer' => $teamScores->sortByDesc('overall_score')->first(),
            'lowest_performer' => $teamScores->sortBy('overall_score')->first(),
            'total_kpis_completed' => $teamScores->sum('kpis_completed'),
            'total_tasks_completed' => $teamScores->sum('tasks_completed'),
            'members' => $teamScores,
        ];
    }

    /**
     * Get performance trend (last 6 months).
     */
    public function getPerformanceTrend($months = 6)
    {
        $trend = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i)->startOfMonth();
            $score = $this->performanceScores()
                ->forPeriod($date, 'monthly')
                ->first();
            
            $trend[] = [
                'month' => $date->format('M Y'),
                'score' => $score ? $score->overall_score : 0,
            ];
        }
        
        return $trend;
    }
}
