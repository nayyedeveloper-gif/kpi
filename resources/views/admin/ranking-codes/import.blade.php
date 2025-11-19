@extends('layouts.app')

@section('title', 'Ranking Codes Import')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-file-import me-2"></i> Ranking Codes တင်သွင်းရန်</h4>
                    <div class="mt-2">
                        <a href="{{ route('ranking-codes.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-left me-1"></i> စာရင်းသို့ ပြန်သွားရန်
                        </a>
                        <a href="{{ route('ranking-codes.export') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel me-1"></i> Template ဒေါင်းလုတ်ရယူရန်
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title text-info mb-4">
                                        <i class="fas fa-info-circle me-2"></i> အသိပေးချက်
                                    </h5>
                                    
                                    <div class="alert alert-warning">
                                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> Import လုပ်ဆောင်ချက် ရပ်ဆိုင်းထားသည်</h6>
                                        <hr>
                                        <p class="mb-0">လောလောဆယ် Ranking Codes များကို Import လုပ်ဆောင်ချက်ကို ရပ်ဆိုင်းထားပါသည်။ လိုအပ်ပါက IT ဌာနသို့ ဆက်သွယ်ပါ။</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card border-0 shadow-sm mt-4">
                                <div class="card-body p-4">
                                    <h5 class="card-title text-primary mb-3">
                                        <i class="fas fa-question-circle me-2"></i> အကူအညီလိုအပ်ပါသလား?
                                    </h5>
                                    <div class="alert alert-light">
                                        <ul class="mb-0">
                                            <li>Ranking Codes စာရင်းကို ကြည့်ရှုရန် စာရင်းသို့ ပြန်သွားရန် ခလုတ်ကို နိပ်ပါ။</li>
                                            <li>အကူအညီလိုအပ်ပါက IT ဌာနသို့ ဆက်သွယ်ပါ။</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // No import functionality - page is informational only
    });
</script>
@endpush
