@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6 bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ isset($sale) ? 'Edit' : 'Add New' }} Sales Record</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ isset($sale) ? 'Update sales transaction details' : 'Create a new sales transaction record' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('sales.data.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Sales
                </a>
                <a href="{{ route('sales.import.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Import Data
                </a>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 mb-2">Please correct the following errors:</h3>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ isset($sale) ? route('sales.data.update', $sale->id) : route('sales.data.store') }}" method="POST" class="space-y-6" id="salesForm">
        @csrf
        @if(isset($sale))
            @method('PUT')
        @endif

        <!-- Basic Information & Customer Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Voucher Number & Date Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="voucher_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Voucher Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="voucher_number"
                                   name="voucher_number"
                                   value="{{ old('voucher_number', $sale->voucher_number ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   placeholder="e.g., INV-2025-001"
                                   required>
                        </div>
                        <div>
                            <label for="invoiced_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Invoice Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   id="invoiced_date"
                                   name="invoiced_date"
                                   value="{{ old('invoiced_date', isset($sale) ? $sale->invoiced_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   required>
                        </div>
                    </div>

                    <!-- Branch & Sales Person Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="branch" class="block text-sm font-medium text-gray-700 mb-2">
                                Branch <span class="text-red-500">*</span>
                            </label>
                            <select id="branch"
                                    name="branch"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white"
                                    required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch }}" {{ (old('branch', $sale->branch ?? '') == $branch) ? 'selected' : '' }}>
                                        {{ $branch }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="sale_person" class="block text-sm font-medium text-gray-700 mb-2">
                                Sales Person <span class="text-red-500">*</span>
                            </label>
                            <select id="sale_person"
                                    name="sale_person"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white"
                                    required>
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

            <!-- Customer Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Customer Name & Status Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Customer Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="customer_name"
                                   name="customer_name"
                                   value="{{ old('customer_name', $sale->customer_name ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   placeholder="Enter customer full name"
                                   required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_status" class="block text-sm font-medium text-gray-700 mb-2">
                                Customer Status
                            </label>
                            <select id="customer_status"
                                    name="customer_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors bg-white">
                                <option value="New" {{ (old('customer_status', $sale->customer_status ?? 'New') == 'New') ? 'selected' : '' }}>New Customer</option>
                                <option value="Returning" {{ (old('customer_status', $sale->customer_status ?? '') == 'Returning') ? 'selected' : '' }}>Returning Customer</option>
                                <option value="VIP" {{ (old('customer_status', $sale->customer_status ?? '') == 'VIP') ? 'selected' : '' }}>VIP Customer</option>
                            </select>
                        </div>
                        <div>
                            <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Number
                            </label>
                            <input type="text"
                                   id="contact_number"
                                   name="contact_number"
                                   value="{{ old('contact_number', $sale->contact_number ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   placeholder="e.g., +95 9 123 456 789">
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">Address Information</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text"
                                   name="township"
                                   value="{{ old('township', $sale->township ?? '') }}"
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   placeholder="Township">
                            <input type="text"
                                   name="division"
                                   value="{{ old('division', $sale->division ?? '') }}"
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   placeholder="Division/State">
                        </div>
                        <input type="text"
                               name="contact_address"
                               value="{{ old('contact_address', $sale->contact_address ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                               placeholder="Full address">
                        <input type="text"
                               name="customer_nrc_number"
                               value="{{ old('customer_nrc_number', $sale->customer_nrc_number ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                               placeholder="NRC Number (optional)">
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Details Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Item Details</h3>
                </div>
            </div>
            <div class="p-6">
                <!-- Item Selection Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label for="item_categories" class="block text-sm font-medium text-gray-700 mb-2">
                            Item Category <span class="text-red-500">*</span>
                        </label>
                        <select id="item_categories"
                                name="item_categories"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors bg-white"
                                required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ (old('item_categories', $sale->item_categories ?? '') == $category) ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="item_group" class="block text-sm font-medium text-gray-700 mb-2">
                            Item Group <span class="text-red-500">*</span>
                        </label>
                        <select id="item_group"
                                name="item_group"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors bg-white"
                                required>
                            <option value="">Select Group</option>
                            @foreach($itemGroups as $group)
                                <option value="{{ $group }}" {{ (old('item_group', $sale->item_group ?? '') == $group) ? 'selected' : '' }}>
                                    {{ $group }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Item Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="item_name"
                               name="item_name"
                               value="{{ old('item_name', $sale->item_name ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                               placeholder="Enter item name"
                               required>
                    </div>
                </div>

                <!-- Quantity and Weight Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            Quantity <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number"
                                   id="quantity"
                                   name="quantity"
                                   min="0.01"
                                   step="0.01"
                                   value="{{ old('quantity', $sale->quantity ?? 1) }}"
                                   class="w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   required>
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">pcs</span>
                        </div>
                    </div>
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                            Weight <span class="text-red-500">*</span>
                        </label>
                        <div class="flex">
                            <input type="number"
                                   id="weight"
                                   name="weight"
                                   min="0.01"
                                   step="0.01"
                                   value="{{ old('weight', $sale->weight ?? '') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   required>
                            <select name="unit"
                                    id="unit"
                                    class="px-3 py-2 border border-l-0 border-gray-300 rounded-r-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors bg-gray-50">
                                <option value="P" {{ (old('unit', $sale->unit ?? 'P') == 'P') ? 'selected' : '' }}>P</option>
                                <option value="g" {{ (old('unit', $sale->unit ?? '') == 'g') ? 'selected' : '' }}>g</option>
                                <option value="kg" {{ (old('unit', $sale->unit ?? '') == 'kg') ? 'selected' : '' }}>kg</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="density" class="block text-sm font-medium text-gray-700 mb-2">
                            Density (optional)
                        </label>
                        <input type="number"
                               id="density"
                               name="density"
                               min="0"
                               step="0.01"
                               value="{{ old('density', $sale->density ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                               placeholder="Density value">
                    </div>
                </div>

                <!-- Pricing Section -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pricing Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="m_price" class="block text-sm font-medium text-gray-700 mb-2">
                                Unit Price (MMK) <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   id="m_price"
                                   name="m_price"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('m_price', $sale->m_price ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   required>
                        </div>
                        <div>
                            <label for="m_gross_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Gross Amount (MMK) <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   id="m_gross_amount"
                                   name="m_gross_amount"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('m_gross_amount', $sale->m_gross_amount ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors bg-gray-50"
                                   readonly>
                        </div>
                        <div>
                            <label for="dis" class="block text-sm font-medium text-gray-700 mb-2">
                                Discount (%)
                            </label>
                            <input type="number"
                                   id="dis"
                                   name="dis"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                   value="{{ old('dis', $sale->dis ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                        </div>
                        <div>
                            <label for="tax" class="block text-sm font-medium text-gray-700 mb-2">
                                Tax (MMK)
                            </label>
                            <input type="number"
                                   id="tax"
                                   name="tax"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('tax', $sale->tax ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                        </div>
                    </div>

                    <!-- Net Amount Display -->
                    <div class="mt-4 p-4 bg-white rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-semibold text-gray-900">Net Amount (MMK):</span>
                            <span id="total_net_amount_display" class="text-2xl font-bold text-green-600">
                                {{ number_format(old('total_net_amount', $sale->total_net_amount ?? 0), 2) }}
                            </span>
                        </div>
                        <input type="hidden"
                               id="total_net_amount"
                               name="total_net_amount"
                               value="{{ old('total_net_amount', $sale->total_net_amount ?? '') }}">
                    </div>
                </div>

                <!-- Remarks -->
                <div>
                    <label for="remark" class="block text-sm font-medium text-gray-700 mb-2">
                        Remarks/Notes
                    </label>
                    <textarea id="remark"
                              name="remark"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                              placeholder="Additional notes or remarks...">{{ old('remark', $sale->remark ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Required fields are marked with </span>
                    <span class="text-red-500">*</span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('sales.data.index') }}"
                       class="inline-flex items-center px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ isset($sale) ? 'Update' : 'Create' }} Sales Record
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate amounts when inputs change
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('m_price');
    const discountInput = document.getElementById('dis');
    const taxInput = document.getElementById('tax');
    const grossAmountInput = document.getElementById('m_gross_amount');
    const netAmountInput = document.getElementById('total_net_amount');
    const netAmountDisplay = document.getElementById('total_net_amount_display');

    function calculateAmounts() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const discountPercent = parseFloat(discountInput.value) || 0;
        const tax = parseFloat(taxInput.value) || 0;

        // Calculate gross amount
        const grossAmount = quantity * price;
        grossAmountInput.value = grossAmount.toFixed(2);

        // Calculate discount amount
        const discountAmount = grossAmount * (discountPercent / 100);

        // Calculate net amount
        const netAmount = grossAmount - discountAmount + tax;
        netAmountInput.value = netAmount.toFixed(2);
        netAmountDisplay.textContent = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(netAmount);
    }

    // Add event listeners
    [quantityInput, priceInput, discountInput, taxInput].forEach(input => {
        input.addEventListener('input', calculateAmounts);
    });

    // Initial calculation
    calculateAmounts();

    // Auto-generate voucher number if empty
    const voucherInput = document.getElementById('voucher_number');
    const dateInput = document.getElementById('invoiced_date');

    function generateVoucherNumber() {
        if (!voucherInput.value && dateInput.value) {
            const date = new Date(dateInput.value);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            voucherInput.value = `INV-${year}${month}${day}-001`;
        }
    }

    dateInput.addEventListener('change', generateVoucherNumber);

    // Form validation enhancement
    const form = document.getElementById('salesForm');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });

    // Real-time validation feedback
    const inputs = form.querySelectorAll('input[required], select[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim()) {
                this.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                this.classList.add('border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
            } else {
                this.classList.remove('border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
                this.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            }
        });
    });
});
</script>
@endpush
@endsection
