@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Performance KPI Details</h1>
        <div>
            <a href="{{ route('performance-kpi.edit', $performanceKPI->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('performance-kpi.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">KPI Details</h6>
                    <span class="badge bg-{{ $performanceKPI->status === 'approved' ? 'success' : ($performanceKPI->status === 'rejected' ? 'danger' : 'warning') }}">
                        {{ ucfirst($performanceKPI->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Employee Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Name</th>
                                    <td>{{ $performanceKPI->rankingCode->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Position</th>
                                    <td>{{ $performanceKPI->rankingCode->position_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Ranking ID</th>
                                    <td>{{ $performanceKPI->rankingCode->ranking_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Evaluation Date</th>
                                    <td>{{ $performanceKPI->evaluation_date->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Performance Summary</h5>
                            <div class="text-center mb-3">
                                <div class="position-relative d-inline-block" style="width: 160px; height: 160px;">
                                    <canvas id="scoreChart" width="160" height="160"></canvas>
                                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                                        <h3 class="mb-0">{{ number_format($performanceKPI->total_score, 1) }}<small>%</small></h3>
                                        <small class="text-muted">Total Score</small>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                @if($performanceKPI->is_eligible_for_bonus)
                                    <div class="alert alert-success py-2">
                                        <strong>Bonus Eligible:</strong> {{ number_format($performanceKPI->bonus_amount, 2) }} MMK
                                    </div>
                                @else
                                    <div class="alert alert-warning py-2">
                                        <strong>Not Eligible for Bonus</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3">Performance Metrics</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Category</th>
                                    <th width="15%" class="text-center">Score</th>
                                    <th width="60%">Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $categories = [
                                        'personality_score' => 'Personality',
                                        'team_management_score' => 'Team Management',
                                        'customer_follow_up_score' => 'Customer Follow-Up',
                                        'supervised_level_score' => 'Supervised Level'
                                    ];
                                @endphp

                                @foreach($categories as $field => $label)
                                    @php
                                        $score = $performanceKPI->$field;
                                        $progressClass = $score >= 80 ? 'bg-success' : ($score >= 50 ? 'bg-warning' : 'bg-danger');
                                        $isMinimumMet = $score >= 25;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $label }}
                                            @if(!$isMinimumMet)
                                                <span class="badge bg-danger ms-2">Below Minimum</span>
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold">{{ number_format($score, 1) }}%</td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar {{ $progressClass }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $score }}%" 
                                                     aria-valuenow="{{ $score }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ $score }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($performanceKPI->notes)
                        <div class="mt-4">
                            <h5>Notes</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    {!! nl2br(e($performanceKPI->notes)) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    @if(auth()->user()->can('approve', $performanceKPI) && $performanceKPI->status !== 'approved')
                        <form action="{{ route('performance-kpi.update-status', $performanceKPI->id) }}" method="POST" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success btn-block mb-2">
                                <i class="fas fa-check"></i> Approve KPI
                            </button>
                            <div class="form-group">
                                <label for="approve_notes" class="form-label">Notes (Optional)</label>
                                <textarea name="notes" id="approve_notes" class="form-control" rows="2">{{ old('notes', $performanceKPI->notes) }}</textarea>
                            </div>
                        </form>
                    @endif

                    @if(auth()->user()->can('reject', $performanceKPI) && $performanceKPI->status !== 'rejected')
                        <form action="{{ route('performance-kpi.update-status', $performanceKPI->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-danger btn-block mb-2">
                                <i class="fas fa-times"></i> Reject KPI
                            </button>
                            <div class="form-group">
                                <label for="reject_notes" class="form-label">Reason for Rejection (Required)</label>
                                <textarea name="notes" id="reject_notes" class="form-control @error('notes') is-invalid @enderror" rows="3" required>{{ old('notes', $performanceKPI->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    @endif

                    <div class="mt-4">
                        <h6>KPI History</h6>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Created
                                <span class="badge bg-primary rounded-pill">
                                    {{ $performanceKPI->created_at->format('d/m/Y H:i') }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Last Updated
                                <span class="badge bg-info rounded-pill">
                                    {{ $performanceKPI->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </li>
                            @if($performanceKPI->status !== 'pending')
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ ucfirst($performanceKPI->status) }} By
                                    <span class="badge bg-{{ $performanceKPI->status === 'approved' ? 'success' : 'danger' }} rounded-pill">
                                        {{ $performanceKPI->updatedBy->name ?? 'System' }}
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bonus Calculation</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Base Bonus</th>
                            <td class="text-end">50,000 MMK</td>
                        </tr>
                        <tr>
                            <th>Minimum Score per Category</th>
                            <td class="text-end">25%</td>
                        </tr>
                        <tr>
                            <th>Minimum Total Score</th>
                            <td class="text-end">80%</td>
                        </tr>
                        <tr class="table-{{ $performanceKPI->is_eligible_for_bonus ? 'success' : 'danger' }}">
                            <th>Eligible for Bonus</th>
                            <td class="text-end fw-bold">
                                {{ $performanceKPI->is_eligible_for_bonus ? 'Yes' : 'No' }}
                            </td>
                        </tr>
                        @if($performanceKPI->is_eligible_for_bonus)
                            <tr class="table-success">
                                <th>Calculated Bonus</th>
                                <td class="text-end fw-bold">
                                    {{ number_format($performanceKPI->bonus_amount, 2) }} MMK
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Radar Chart
        const ctx = document.getElementById('scoreChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Personality', 'Team Management', 'Customer Follow-Up', 'Supervised Level'],
                datasets: [{
                    label: 'Scores',
                    data: [
                        {{ $performanceKPI->personality_score }},
                        {{ $performanceKPI->team_management_score }},
                        {{ $performanceKPI->customer_follow_up_score }},
                        {{ $performanceKPI->supervised_level_score }}
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(54, 162, 235, 1)'
                }]
            },
            options: {
                scale: {
                    angleLines: {
                        display: true
                    },
                    ticks: {
                        suggestedMin: 0,
                        suggestedMax: 100,
                        stepSize: 25
                    }
                },
                elements: {
                    line: {
                        borderWidth: 3
                    }
                }
            }
        });
    });
</script>
@endpush

@endsection
