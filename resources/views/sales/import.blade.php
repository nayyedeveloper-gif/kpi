@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Import Sales Data</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
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
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Import from CSV</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('sales.import.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="csv_file">Choose CSV File</label>
                                    <input type="file" class="form-control-file" id="csv_file" name="csv_file" accept=".csv" required>
                                    <small class="form-text text-muted">Please upload a CSV file with the correct format.</small>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Upload and Import
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Add New Sale</h5>
                        </div>
                        <div class="card-body">
                            <p>Manually add a new sales record.</p>
                            <a href="{{ route('sales.data.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Add New Sale
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            @if(isset($sales) && $sales->count() > 0)
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Sales</h5>
                        <a href="{{ route('sales.data.index') }}" class="btn btn-sm btn-outline-primary">
                            View All Sales
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice #</th>
                                        <th>Customer</th>
                                        <th>Item</th>
                                        <th class="text-right">Amount</th>
                                        <th>Sales Person</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sales as $sale)
                                        <tr>
                                            <td>{{ $sale->invoiced_date->format('d M Y') }}</td>
                                            <td>{{ $sale->voucher_number }}</td>
                                            <td>{{ $sale->customer_name }}</td>
                                            <td>{{ $sale->item_name }} ({{ $sale->item_category }})</td>
                                            <td class="text-right">{{ number_format($sale->total_net_amount) }} MMK</td>
                                            <td>{{ $sale->sale_person }}</td>
                                            <td>
                                                <a href="{{ route('sales.data.show', $sale->id) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('sales.data.edit', $sale->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <div class="mt-3">
                                {{ $sales->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush
