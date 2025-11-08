@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">{{ isset($sale) ? 'Edit' : 'Add New' }} Sales Record</h4>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ isset($sale) ? route('sales.data.update', $sale->id) : route('sales.data.store') }}" method="POST">
                @csrf
                @if(isset($sale))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="voucher_number">Voucher Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="voucher_number" name="voucher_number" 
                                           value="{{ old('voucher_number', $sale->voucher_number ?? '') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="invoiced_date">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="invoiced_date" name="invoiced_date" 
                                           value="{{ old('invoiced_date', isset($sale) ? $sale->invoiced_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="branch">Branch <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="branch" name="branch" required>
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch }}" {{ (old('branch', $sale->branch ?? '') == $branch) ? 'selected' : '' }}>
                                                {{ $branch }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="sale_person">Sales Person <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="sale_person" name="sale_person" required>
                                        <option value="">Select Sales Person</option>
                                        @foreach($salePersons as $person)
                                            <option value="{{ $person }}" {{ (old('sale_person', $sale->sale_person ?? '') == $person) ? 'selected' : '' }}>
                                                {{ $person }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Customer Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="customer_name">Customer Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                           value="{{ old('customer_name', $sale->customer_name ?? '') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="customer_status">Customer Status</label>
                                    <select class="form-control" id="customer_status" name="customer_status">
                                        <option value="New" {{ (old('customer_status', $sale->customer_status ?? '') == 'New') ? 'selected' : '' }}>New</option>
                                        <option value="Returning" {{ (old('customer_status', $sale->customer_status ?? '') == 'Returning') ? 'selected' : '' }}>Returning</option>
                                        <option value="VIP" {{ (old('customer_status', $sale->customer_status ?? '') == 'VIP') ? 'selected' : '' }}>VIP</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="contact_number">Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" 
                                           value="{{ old('contact_number', $sale->contact_number ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Item Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="item_category">Category <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="item_category" name="item_category" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}" {{ (old('item_category', $sale->item_category ?? '') == $category) ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="item_group">Group <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="item_group" name="item_group" required>
                                        <option value="">Select Group</option>
                                        @foreach($itemGroups as $group)
                                            <option value="{{ $group }}" {{ (old('item_group', $sale->item_group ?? '') == $group) ? 'selected' : '' }}>
                                                {{ $group }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="item_name">Item Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="item_name" name="item_name" 
                                           value="{{ old('item_name', $sale->item_name ?? '') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" 
                                           value="{{ old('quantity', $sale->quantity ?? 1) }}" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="weight">Weight <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control" id="weight" name="weight" 
                                               value="{{ old('weight', $sale->weight ?? '') }}" required>
                                        <div class="input-group-append">
                                            <select class="form-control" name="unit" id="unit">
                                                <option value="P" {{ (old('unit', $sale->unit ?? 'P') == 'P') ? 'selected' : '' }}>P</option>
                                                <option value="g" {{ (old('unit', $sale->unit ?? '') == 'g') ? 'selected' : '' }}>g</option>
                                                <option value="kg" {{ (old('unit', $sale->unit ?? '') == 'kg') ? 'selected' : '' }}>kg</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="m_price">Price per Unit (MMK) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="m_price" name="m_price" 
                                           value="{{ old('m_price', $sale->m_price ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="m_gross_amount">Total Amount (MMK) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="m_gross_amount" name="m_gross_amount" 
                                           value="{{ old('m_gross_amount', $sale->m_gross_amount ?? '') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount">Discount (%)</label>
                                    <input type="number" step="0.01" class="form-control" id="discount" name="discount" 
                                           value="{{ old('discount', $sale->discount ?? '') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tax">Tax (MMK)</label>
                                    <input type="number" step="0.01" class="form-control" id="tax" name="tax" 
                                           value="{{ old('tax', $sale->tax ?? '') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_net_amount">Net Amount (MMK)</label>
                                    <input type="number" step="0.01" class="form-control" id="total_net_amount" name="total_net_amount" 
                                           value="{{ old('total_net_amount', $sale->total_net_amount ?? '') }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="remark">Remarks</label>
                    <textarea class="form-control" id="remark" name="remark" rows="3">{{ old('remark', $sale->remark ?? '') }}</textarea>
                </div>

                <div class="form-group text-right">
                    <a href="{{ route('sales.import.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ isset($sale) ? 'Update' : 'Save' }} Sale
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px;
        padding: 5px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true
        });

        // Calculate total amount when quantity or price changes
        $('#quantity, #m_price').on('input', function() {
            calculateTotal();
        });

        // Calculate discount and net amount when discount changes
        $('#discount').on('input', function() {
            calculateTotal();
        });

        function calculateTotal() {
            const quantity = parseFloat($('#quantity').val()) || 0;
            const price = parseFloat($('#m_price').val()) || 0;
            const discount = parseFloat($('#discount').val()) || 0;
            const tax = parseFloat($('#tax').val()) || 0;
            
            // Calculate gross amount
            const grossAmount = quantity * price;
            
            // Calculate discount amount
            const discountAmount = grossAmount * (discount / 100);
            
            // Calculate net amount
            const netAmount = grossAmount - discountAmount + tax;
            
            // Update the fields
            $('#m_gross_amount').val(grossAmount.toFixed(2));
            $('#total_net_amount').val(netAmount.toFixed(2));
        }
    });
</script>
@endpush
