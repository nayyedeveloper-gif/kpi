<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerformanceKPI;
use App\Models\RankingCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PerformanceKPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PerformanceKPI::with('rankingCode')
            ->orderBy('evaluation_date', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $performanceKPIs = $query->paginate(20);

        return view('admin.performance-kpi.index', compact('performanceKPIs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rankingCodes = RankingCode::orderBy('name')->get();
        return view('admin.performance-kpi.create', compact('rankingCodes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ranking_code_id' => 'required|exists:ranking_codes,id',
            'evaluation_date' => 'required|date',
            'personality_score' => 'required|numeric|min:0|max:100',
            'team_management_score' => 'required|numeric|min:0|max:100',
            'customer_follow_up_score' => 'required|numeric|min:0|max:100',
            'supervised_level_score' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $kpi = new PerformanceKPI($validated);
            $kpi->status = 'pending';
            $kpi->save();

            DB::commit();

            return redirect()
                ->route('performance-kpi.index')
                ->with('success', 'Performance KPI အောင်မြင်စွာထည့်သွင်းပြီးပါပြီ။');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating performance KPI: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Performance KPI ထည့်သွင်းရာတွင် အမှားတစ်ခုဖြစ်နေပါသည်။');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PerformanceKPI $performanceKPI)
    {
        return view('admin.performance-kpi.show', compact('performanceKPI'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PerformanceKPI $performanceKPI)
    {
        $rankingCodes = RankingCode::orderBy('name')->get();
        return view('admin.performance-kpi.edit', compact('performanceKPI', 'rankingCodes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PerformanceKPI $performanceKPI)
    {
        $validated = $request->validate([
            'ranking_code_id' => 'required|exists:ranking_codes,id',
            'evaluation_date' => 'required|date',
            'personality_score' => 'required|numeric|min:0|max:100',
            'team_management_score' => 'required|numeric|min:0|max:100',
            'customer_follow_up_score' => 'required|numeric|min:0|max:100',
            'supervised_level_score' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $performanceKPI->update($validated);

            DB::commit();

            return redirect()
                ->route('performance-kpi.show', $performanceKPI)
                ->with('success', 'Performance KPI အောင်မြင်စွာပြင်ဆင်ပြီးပါပြီ။');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating performance KPI: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Performance KPI ပြင်ဆင်ရာတွင် အမှားတစ်ခုဖြစ်နေပါသည်။');
        }
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, PerformanceKPI $performanceKPI)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $performanceKPI->update([
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? $performanceKPI->notes,
            ]);

            DB::commit();

            return back()
                ->with('success', 'Status အောင်မြင်စွာပြောင်းလဲပြီးပါပြီ။');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating performance KPI status: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Status ပြောင်းလဲရာတွင် အမှားတစ်ခုဖြစ်နေပါသည်။');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerformanceKPI $performanceKPI)
    {
        try {
            $performanceKPI->delete();
            
            return redirect()
                ->route('performance-kpi.index')
                ->with('success', 'Performance KPI အောင်မြင်စွာဖျက်ပစ်လိုက်ပါပြီ။');
                
        } catch (\Exception $e) {
            Log::error('Error deleting performance KPI: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Performance KPI ဖျက်ပစ်ရာတွင် အမှားတစ်ခုဖြစ်နေပါသည်။');
        }
    }
}
