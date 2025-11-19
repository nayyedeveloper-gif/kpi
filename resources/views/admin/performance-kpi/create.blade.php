@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Create New Performance KPI</h1>
        <a href="{{ route('performance-kpi.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Performance KPI Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('performance-kpi.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="ranking_code_id" class="form-label">Employee <span class="text-danger">*</span></label>
                            <select name="ranking_code_id" id="ranking_code_id" class="form-control @error('ranking_code_id') is-invalid @enderror" required>
                                <option value="">-- Select Employee --</option>
                                @foreach($rankingCodes as $code)
                                    <option value="{{ $code->id }}" {{ old('ranking_code_id') == $code->id ? 'selected' : '' }}>
                                        {{ $code->name }} ({{ $code->position_name }} - {{ $code->ranking_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('ranking_code_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="evaluation_date" class="form-label">Evaluation Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('evaluation_date') is-invalid @enderror" 
                                   id="evaluation_date" name="evaluation_date" 
                                   value="{{ old('evaluation_date', now()->format('Y-m-d')) }}" required>
                            @error('evaluation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold">KPI Scores (0-100%)</h6>
                        <small class="text-muted">Minimum 25% required in each category to be eligible for bonus</small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="personality_score" class="form-label">Personality Score <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" max="100" 
                                               class="form-control kpi-score @error('personality_score') is-invalid @enderror" 
                                               id="personality_score" name="personality_score" 
                                               value="{{ old('personality_score', 0) }}" required>
                                        <span class="input-group-text">%</span>
                                        @error('personality_score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="team_management_score" class="form-label">Team Management Score <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" max="100" 
                                               class="form-control kpi-score @error('team_management_score') is-invalid @enderror" 
                                               id="team_management_score" name="team_management_score" 
                                               value="{{ old('team_management_score', 0) }}" required>
                                        <span class="input-group-text">%</span>
                                        @error('team_management_score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_follow_up_score" class="form-label">Customer Follow-Up Score <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" max="100" 
                                               class="form-control kpi-score @error('customer_follow_up_score') is-invalid @enderror" 
                                               id="customer_follow_up_score" name="customer_follow_up_score" 
                                               value="{{ old('customer_follow_up_score', 0) }}" required>
                                        <span class="input-group-text">%</span>
                                        @error('customer_follow_up_score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="supervised_level_score" class="form-label">Supervised Level Score <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" max="100" 
                                               class="form-control kpi-score @error('supervised_level_score') is-invalid @enderror" 
                                               id="supervised_level_score" name="supervised_level_score" 
                                               value="{{ old('supervised_level_score', 0) }}" required>
                                        <span class="input-group-text">%</span>
                                        @error('supervised_level_score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Total Score: <span id="totalScore">0.00</span>%</h6>
                                                <div class="progress mb-3" style="height: 20px;">
                                                    <div id="totalScoreBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Bonus Eligibility: <span id="bonusStatus" class="fw-bold">Not Eligible</span></h6>
                                                <p class="mb-0">Bonus Amount: <span id="bonusAmount" class="fw-bold">0.00 MMK</span></p>
                                                <small class="text-muted">Minimum 80% total score required for bonus</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save KPI
                    </button>
                    <a href="{{ route('performance-kpi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kpiInputs = document.querySelectorAll('.kpi-score');
        const totalScoreElement = document.getElementById('totalScore');
        const totalScoreBar = document.getElementById('totalScoreBar');
        const bonusStatusElement = document.getElementById('bonusStatus');
        const bonusAmountElement = document.getElementById('bonusAmount');
        
        const MAX_BONUS = 50000; // 50,000 MMK
        const MIN_SCORE_FOR_BONUS = 25; // 25% minimum per category
        const MIN_TOTAL_FOR_BONUS = 80; // 80% total score minimum

        function calculateScores() {
            let total = 0;
            let allMeetMinimum = true;
            
            // Calculate total score
            kpiInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
                
                // Check if this score meets minimum requirement
                if (value < MIN_SCORE_FOR_BONUS) {
                    allMeetMinimum = false;
                }
            });
            
            // Calculate average
            const average = kpiInputs.length > 0 ? total / kpiInputs.length : 0;
            
            // Update UI
            totalScoreElement.textContent = average.toFixed(2);
            totalScoreBar.style.width = `${average}%`;
            totalScoreBar.textContent = `${average.toFixed(2)}%`;
            totalScoreBar.setAttribute('aria-valuenow', average);
            
            // Update bonus eligibility
            const isEligible = allMeetMinimum && average >= MIN_TOTAL_FOR_BONUS;
            
            if (isEligible) {
                // Calculate bonus amount (proportional to score, up to max 50,000)
                const bonusAmount = (average / 100) * MAX_BONUS;
                
                bonusStatusElement.textContent = 'Eligible';
                bonusStatusElement.className = 'fw-bold text-success';
                bonusAmountElement.textContent = `${bonusAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} MMK`;
                bonusAmountElement.className = 'fw-bold text-success';
                
                // Update progress bar color
                totalScoreBar.className = 'progress-bar bg-success';
            } else {
                bonusStatusElement.textContent = 'Not Eligible';
                bonusStatusElement.className = 'fw-bold text-danger';
                bonusAmountElement.textContent = '0.00 MMK';
                bonusAmountElement.className = 'fw-bold text-muted';
                
                // Update progress bar color based on score
                if (average >= 50) {
                    totalScoreBar.className = 'progress-bar bg-warning';
                } else {
                    totalScoreBar.className = 'progress-bar bg-danger';
                }
            }
        }
        
        // Add event listeners to all KPI inputs
        kpiInputs.forEach(input => {
            input.addEventListener('input', calculateScores);
            
            // Add validation for min/max values
            input.addEventListener('change', function() {
                const value = parseFloat(this.value) || 0;
                if (value < 0) this.value = 0;
                if (value > 100) this.value = 100;
            });
        });
        
        // Initial calculation
        calculateScores();
    });
</script>
@endpush

@endsection
