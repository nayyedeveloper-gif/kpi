@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Import Performance KPIs</h1>
        <a href="{{ route('performance-kpi.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Import KPI Data</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('import_errors'))
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">Import completed with {{ count(session('import_errors')) }} errors:</h6>
                            <ul class="mb-0">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Import Instructions</h6>
                        <ol class="mb-0">
                            <li>Download the <a href="{{ route('performance-kpi.template') }}" class="alert-link">KPI Import Template</a>.</li>
                            <li>Fill in the data following the template format.</li>
                            <li>Save the file as a CSV (Comma Separated Values) file.</li>
                            <li>Click "Choose File" and select your CSV file.</li>
                            <li>Click "Import KPIs" to upload and process the file.</li>
                        </ol>
                    </div>

                    <form action="{{ route('performance-kpi.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('file') is-invalid @enderror" id="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                                <label class="custom-file-label" for="file">Choose CSV file</label>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Maximum file size: 10MB. Only .csv files are allowed.</small>
                        </div>

                        <div class="form-group form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="has_headers" name="has_headers" checked>
                            <label class="form-check-label" for="has_headers">File contains header row</label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-import me-1"></i> Import KPIs
                        </button>
                        <a href="{{ route('performance-kpi.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">CSV Format</h6>
                </div>
                <div class="card-body">
                    <p>Your CSV file should include the following columns in this exact order:</p>
                    
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Column</th>
                                <th>Description</th>
                                <th>Required</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ranking_code_id</td>
                                <td>Employee ID from the system</td>
                                <td class="text-center"><i class="fas fa-check text-success"></i></td>
                            </tr>
                            <tr>
                                <td>evaluation_date</td>
                                <td>Date of evaluation (YYYY-MM-DD)</td>
                                <td class="text-center"><i class="fas fa-check text-success"></i></td>
                            </tr>
                            <tr>
                                <td>personality_score</td>
                                <td>Personality score (0-100)</td>
                                <td class="text-center"><i class="fas fa-check text-success"></i></td>
                            </tr>
                            <tr>
                                <td>team_management_score</td>
                                <td>Team management score (0-100)</td>
                                <td class="text-center"><i class="fas fa-check text-success"></i></td>
                            </tr>
                            <tr>
                                <td>customer_follow_up_score</td>
                                <td>Customer follow-up score (0-100)</td>
                                <td class="text-center"><i class="fas fa-check text-success"></i></td>
                            </tr>
                            <tr>
                                <td>supervised_level_score</td>
                                <td>Supervised level score (0-100)</td>
                                <td class="text-center"><i class="fas fa-check text-success"></i></td>
                            </tr>
                            <tr>
                                <td>notes</td>
                                <td>Additional notes (optional)</td>
                                <td class="text-center"><i class="fas fa-times text-muted"></i></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="alert alert-warning small mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> The first row of your CSV file should contain the column headers exactly as shown above.
                    </div>
                </div>
            </div>

            @if(session('stats'))
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Import Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Total Rows Processed:</th>
                                    <td class="text-end">{{ session('stats.imported', 0) + session('stats.updated', 0) + session('stats.skipped', 0) }}</td>
                                </tr>
                                <tr class="table-success">
                                    <th>Successfully Imported:</th>
                                    <td class="text-end">{{ session('stats.imported', 0) }}</td>
                                </tr>
                                <tr class="table-info">
                                    <th>Updated:</th>
                                    <td class="text-end">{{ session('stats.updated', 0) }}</td>
                                </tr>
                                <tr class="table-warning">
                                    <th>Skipped:</th>
                                    <td class="text-end">{{ session('stats.skipped', 0) }}</td>
                                </tr>
                                @if(session('stats.errors') > 0)
                                    <tr class="table-danger">
                                        <th>Errors:</th>
                                        <td class="text-end">{{ session('stats.errors') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update the file input label to show the selected file name
    document.getElementById('file').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file';
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    // Validate file size before upload
    document.querySelector('form').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('file');
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        
        if (fileInput.files.length > 0) {
            const fileSize = fileInput.files[0].size;
            
            if (fileSize > maxSize) {
                e.preventDefault();
                alert('File size exceeds the maximum limit of 10MB.');
                return false;
            }
            
            // Check file extension
            const fileName = fileInput.files[0].name;
            const fileExt = fileName.split('.').pop().toLowerCase();
            
            if (fileExt !== 'csv') {
                e.preventDefault();
                alert('Only CSV files are allowed.');
                return false;
            }
        }
    });
</script>
@endpush

@endsection
