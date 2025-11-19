@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3">Performance KPI List</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('performance-kpi.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New KPI
            </a>
            <a href="{{ route('performance-kpi.import') }}" class="btn btn-success">
                <i class="fas fa-file-import"></i> Import KPI
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="performanceKpiTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Evaluation Date</th>
                            <th>Total Score</th>
                            <th>Bonus Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($performanceKPIs as $kpi)
                            <tr>
                                <td>{{ $kpi->id }}</td>
                                <td>
                                    @if($kpi->rankingCode)
                                        {{ $kpi->rankingCode->name ?? 'N/A' }}
                                        <small class="d-block text-muted">{{ $kpi->rankingCode->position_name ?? '' }}</small>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $kpi->evaluation_date->format('d/m/Y') }}</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar {{ $kpi->total_score >= 80 ? 'bg-success' : ($kpi->total_score >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                             role="progressbar" 
                                             style="width: {{ $kpi->total_score }}%" 
                                             aria-valuenow="{{ $kpi->total_score }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ number_format($kpi->total_score, 2) }}%
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    @if($kpi->is_eligible_for_bonus)
                                        <span class="text-success fw-bold">{{ number_format($kpi->bonus_amount, 2) }} MMK</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($kpi->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($kpi->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('performance-kpi.show', $kpi->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('performance-kpi.edit', $kpi->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           data-bs-toggle="tooltip" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('performance-kpi.destroy', $kpi->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this KPI?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No performance KPIs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $performanceKPIs->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection
