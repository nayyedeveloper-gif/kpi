@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-indigo-600 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
        <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Sales Transactions</h1>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 hidden sm:block">Manage and analyze your sales data efficiently</p>
        </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2" x-data="{ newTransactionOpen: false }">
                <div class="relative flex-1 sm:flex-initial">
                    <button @click="newTransactionOpen = !newTransactionOpen" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="hidden sm:inline">New Transaction</span>
                        <span class="sm:hidden">New</span>
                </button>
                    <div x-show="newTransactionOpen" @click.away="newTransactionOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50" style="display: none;">
                        <a class="block px-4 py-2 hover:bg-gray-50 flex items-center" href="{{ route('sales.data.create') }}">
                            <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Standard Entry
                        </a>
                        <a class="block px-4 py-2 hover:bg-gray-50 flex items-center" href="{{ route('sales.entry') }}">
                            <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Quick Sale
                        </a>
            </div>
                </div>
                <a href="{{ route('sales.import.index') }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span class="hidden sm:inline">Import</span>
                    <span class="sm:hidden">Import</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Summary Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                        </div>
                        </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Sales</p>
            <h3 class="text-lg sm:text-2xl font-bold text-gray-900">{{ number_format($summary['total_sales'], 0) }}</h3>
            <p class="text-xs text-gray-500 mt-1 hidden sm:block">MMK • All time revenue</p>
                    </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Quantity</p>
            <h3 class="text-lg sm:text-2xl font-bold text-gray-900">{{ number_format($summary['total_quantity'], 0) }}</h3>
            <p class="text-xs text-gray-500 mt-1 hidden sm:block">Items sold</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                        </div>
                        </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Tax</p>
            <h3 class="text-lg sm:text-2xl font-bold text-gray-900">{{ number_format($summary['total_tax'], 0) }}</h3>
            <p class="text-xs text-gray-500 mt-1 hidden sm:block">MMK • Tax collected</p>
                    </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Discount</p>
            <h3 class="text-lg sm:text-2xl font-bold text-gray-900">{{ number_format($summary['total_discount'], 0) }}</h3>
            <p class="text-xs text-gray-500 mt-1 hidden sm:block">MMK • Discount given</p>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200" x-data="{ filtersOpen: false }" x-init="filtersOpen = window.innerWidth >= 768">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <h3 class="text-sm sm:text-base font-semibold text-gray-900">Filter Options</h3>
            </div>
            <button @click="filtersOpen = !filtersOpen" class="text-gray-500 hover:text-gray-700 transition-colors p-1" type="button">
                <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180': !filtersOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
                </button>
            </div>
        <div x-show="filtersOpen" x-transition>
            <div class="p-4 sm:p-6">
                <form action="{{ route('sales.data.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <div>
                        <label for="branch" class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                        <select name="branch" id="branch" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch }}" {{ request('branch') == $branch ? 'selected' : '' }}>{{ $branch }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="item_categories" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="item_categories" id="item_categories" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">All Categories</option>
                            @foreach($itemCategories as $category)
                                <option value="{{ $category }}" {{ request('item_categories') == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="item_group" class="block text-sm font-medium text-gray-700 mb-2">Item Group</label>
                        <select name="item_group" id="item_group" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">All Groups</option>
                            @foreach($itemGroups as $group)
                                <option value="{{ $group }}" {{ request('item_group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="sale_person" class="block text-sm font-medium text-gray-700 mb-2">Sales Person</label>
                        <select name="sale_person" id="sale_person" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">All Sales Persons</option>
                            @foreach($salePersons as $person)
                                <option value="{{ $person }}" {{ request('sale_person') == $person ? 'selected' : '' }}>{{ $person }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="from_date" class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                        <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label for="to_date" class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                        <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-2 sm:col-span-full">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Apply Filters
                            </button>
                        <a href="{{ route('sales.data.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-gray-900">Sales Records</h2>
                    @if(isset($salesData) && method_exists($salesData, 'total') && $salesData->total() > 0)
                            <p class="text-xs text-gray-500 mt-1">{{ number_format($salesData->total()) }} total records</p>
                    @endif
            </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <!-- Search -->
                <div class="relative flex-1">
                    <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Search transactions...">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <!-- Export Dropdown -->
                <div class="relative" x-data="{ exportOpen: false }">
                    <button @click="exportOpen = !exportOpen" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Export
                    </button>
                    <div x-show="exportOpen" @click.away="exportOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50" style="display: none;">
                        <a class="block px-4 py-2 hover:bg-gray-50 flex items-center" href="#" onclick="exportTo('excel'); return false;">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export to Excel
                        </a>
                        <a class="block px-4 py-2 hover:bg-gray-50 flex items-center" href="#" onclick="exportTo('pdf'); return false;">
                            <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Export to PDF
                        </a>
                        <a class="block px-4 py-2 hover:bg-gray-50 flex items-center" href="#" onclick="exportTo('csv'); return false;">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export as CSV
                        </a>
                    </div>
                </div>
            </div>
                </div>
                
        <!-- Mobile Card View -->
        <div class="lg:hidden divide-y divide-gray-200">
            @forelse($salesData as $index => $sale)
                @php
                    $formattedDate = $sale->invoiced_date 
                        ? \Carbon\Carbon::parse($sale->invoiced_date)->format('d M Y')
                        : 'N/A';
                        @endphp
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <a href="{{ route('sales.data.show', $sale->id) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 mb-1 block">
                                {{ $sale->voucher_number ?? 'N/A' }}
                            </a>
                            <p class="text-xs text-gray-500">{{ $formattedDate }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Completed
                        </span>
                    </div>
                    
                    <div class="space-y-2 mb-3">
                        <div class="flex items-center text-sm">
                            <span class="text-gray-500 w-24">Customer:</span>
                            <span class="font-medium text-gray-900">{{ $sale->customer_name }}</span>
                                </div>
                        <div class="flex items-center text-sm">
                            <span class="text-gray-500 w-24">Item:</span>
                            <span class="font-medium text-gray-900">{{ $sale->item_name }}</span>
                </div>
                        <div class="flex items-center text-sm">
                            <span class="text-gray-500 w-24">Quantity:</span>
                            <span class="font-medium text-gray-900">{{ number_format($sale->quantity, 0) }} {{ $sale->unit ?? '' }}</span>
            </div>
                        <div class="flex items-center text-sm">
                            <span class="text-gray-500 w-24">Amount:</span>
                            <span class="font-semibold text-gray-900">{{ number_format($sale->total_net_amount, 0) }} Ks</span>
        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-gray-500 w-24">Sales Person:</span>
                            <span class="text-gray-900">{{ $sale->sale_person ?? 'N/A' }}</span>
                    </div>
                </div>
                    
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                        <a href="{{ route('sales.data.show', $sale->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="View">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('sales.data.edit', $sale->id) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('sales.data.destroy', $sale->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="text-sm font-medium text-gray-900 mb-1">No sales records found</h3>
                    <p class="text-sm text-gray-500 mb-4">Try adjusting your search or filter to find what you're looking for.</p>
                    <a href="{{ route('sales.data.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add New Sale
                    </a>
                </div>
            @endforelse
            </div>
            
        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'invoiced_date', 'direction' => $sortField == 'invoiced_date' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700">
                                Date
                                @if($sortField == 'invoiced_date')
                                    <svg class="w-4 h-4 ml-1 {{ $sortDirection == 'asc' ? '' : 'transform rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'voucher_number', 'direction' => $sortField == 'voucher_number' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700">
                                Voucher
                                @if($sortField == 'voucher_number')
                                    <svg class="w-4 h-4 ml-1 {{ $sortDirection == 'asc' ? '' : 'transform rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                    @endif
                            </a>
                                </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'customer_name', 'direction' => $sortField == 'customer_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700">
                                Customer
                                @if($sortField == 'customer_name')
                                    <svg class="w-4 h-4 ml-1 {{ $sortDirection == 'asc' ? '' : 'transform rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'item_name', 'direction' => $sortField == 'item_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700">
                                Item
                                @if($sortField == 'item_name')
                                    <svg class="w-4 h-4 ml-1 {{ $sortDirection == 'asc' ? '' : 'transform rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">Group</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'quantity', 'direction' => $sortField == 'quantity' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-end hover:text-gray-700">
                                Quantity
                                @if($sortField == 'quantity')
                                    <svg class="w-4 h-4 ml-1 {{ $sortDirection == 'asc' ? '' : 'transform rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'm_price', 'direction' => $sortField == 'm_price' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-end hover:text-gray-700">
                                Unit Price
                                @if($sortField == 'm_price')
                                    <svg class="w-4 h-4 ml-1 {{ $sortDirection == 'asc' ? '' : 'transform rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'total_net_amount', 'direction' => $sortField == 'total_net_amount' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-end hover:text-gray-700">
                                Amount
                                @if($sortField == 'total_net_amount')
                                    <svg class="w-4 h-4 ml-1 {{ $sortDirection == 'asc' ? '' : 'transform rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'sale_person', 'direction' => $sortField == 'sale_person' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700">
                                Sales Person
                                @if($sortField == 'sale_person')
                                    <svg class="w-4 h-4 ml-1 {{ $sortDirection == 'asc' ? '' : 'transform rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'branch', 'direction' => $sortField == 'branch' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700">
                                Branch
                                @if($sortField == 'branch')
                                    <svg class="w-4 h-4 ml-1 {{ $sortDirection == 'asc' ? '' : 'transform rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($salesData as $index => $sale)
                        @php
                            $formattedDate = $sale->invoiced_date 
                                ? \Carbon\Carbon::parse($sale->invoiced_date)->format('d M Y')
                                : 'N/A';
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ ($salesData->currentPage() - 1) * $salesData->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $formattedDate }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('sales.data.show', $sale->id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                    {{ $sale->voucher_number ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $sale->customer_name }}</div>
                                @if($sale->customer_status)
                                    <div class="text-xs text-gray-500">{{ $sale->customer_status }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $sale->item_name }}</div>
                                @if($sale->item_categories)
                                    <div class="text-xs text-gray-500">{{ $sale->item_categories }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden xl:table-cell">
                                {{ $sale->item_categories ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden xl:table-cell">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $sale->item_group ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                {{ number_format($sale->quantity, 0) }}
                                @if($sale->unit)
                                    <span class="text-gray-500 ml-1">{{ $sale->unit }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right hidden xl:table-cell">
                                {{ number_format($sale->m_price, 0) }} <span class="text-gray-500">Ks</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                                {{ number_format($sale->total_net_amount, 0) }} <span class="text-gray-500">Ks</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden xl:table-cell">
                                {{ $sale->sale_person ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden xl:table-cell">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $sale->branch ?? 'N/A' }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center hidden xl:table-cell">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Completed
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('sales.data.show', $sale->id) }}" class="text-indigo-600 hover:text-indigo-900" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('sales.data.edit', $sale->id) }}" class="text-gray-600 hover:text-gray-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('sales.data.destroy', $sale->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                                    @csrf
                                                    @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                                    </button>
                                                </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <h3 class="text-sm font-medium text-gray-900 mb-1">No sales records found</h3>
                                <p class="text-sm text-gray-500 mb-4">Try adjusting your search or filter to find what you're looking for.</p>
                                <a href="{{ route('sales.data.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add New Sale
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(isset($salesData) && method_exists($salesData, 'hasPages') && $salesData->hasPages())
            @php
                $currentPage = $salesData->currentPage();
                $lastPage = $salesData->lastPage();
                $pagesToShow = min(7, $lastPage);
                $startPage = max(1, min($currentPage - 3, $lastPage - $pagesToShow + 1));
                $endPage = min($lastPage, $startPage + $pagesToShow - 1);
            @endphp
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-col gap-4">
                    <!-- Mobile: Simplified pagination info -->
                    <div class="flex sm:hidden items-center justify-between">
                        <div class="text-xs text-gray-600">
                            Page {{ $currentPage }} of {{ $lastPage }}
                        </div>
                        <div class="text-xs text-gray-600">
                            {{ $salesData->total() }} total
                        </div>
                    </div>
                    <!-- Desktop: Full pagination info -->
                    <div class="hidden sm:block text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $salesData->firstItem() }}</span> to 
                        <span class="font-medium">{{ $salesData->lastItem() }}</span> of 
                        <span class="font-medium">{{ $salesData->total() }}</span> results
                </div>
                
                    <!-- Pagination Controls -->
                    <div class="flex items-center justify-center gap-2">
                        @if($salesData->onFirstPage())
                            <span class="px-3 py-2.5 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed flex items-center justify-center min-w-[44px] min-h-[44px]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                </span>
                        @else
                            <a href="{{ $salesData->previousPageUrl() }}" class="px-3 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 active:bg-gray-200 transition-colors flex items-center justify-center min-w-[44px] min-h-[44px]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        @endif

                        <!-- Desktop: Show page numbers with ellipsis -->
                        <div class="hidden sm:flex items-center gap-1">
                            @if($startPage > 1)
                                <a href="{{ $salesData->url(1) }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors text-sm">1</a>
                                @if($startPage > 2)
                                    <span class="px-2 text-gray-500">...</span>
                                @endif
                            @endif
                            
                            @for($page = $startPage; $page <= $endPage; $page++)
                                @if($page == $currentPage)
                                    <span class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium text-sm min-w-[44px] text-center">{{ $page }}</span>
                            @else
                                    <a href="{{ $salesData->url($page) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors text-sm min-w-[44px] text-center">{{ $page }}</a>
                            @endif
                            @endfor
                            
                            @if($endPage < $lastPage)
                                @if($endPage < $lastPage - 1)
                                    <span class="px-2 text-gray-500">...</span>
                                @endif
                                <a href="{{ $salesData->url($lastPage) }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors text-sm">{{ $lastPage }}</a>
                            @endif
                        </div>
                        
                        <!-- Mobile: Show current page number only -->
                        <div class="sm:hidden flex items-center gap-2">
                            <span class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg font-medium text-sm min-w-[60px] text-center">
                                {{ $currentPage }} / {{ $lastPage }}
                            </span>
                        </div>

                        @if($salesData->hasMorePages())
                            <a href="{{ $salesData->nextPageUrl() }}" class="px-3 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 active:bg-gray-200 transition-colors flex items-center justify-center min-w-[44px] min-h-[44px]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <span class="px-3 py-2.5 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed flex items-center justify-center min-w-[44px] min-h-[44px]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
/* Responsive Table Styles */
@media (max-width: 1024px) {
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
    
    table {
        min-width: 800px;
    }
    
    thead th {
        font-size: 0.75rem;
        padding: 0.75rem 0.5rem;
    }
    
    tbody td {
        font-size: 0.875rem;
        padding: 0.75rem 0.5rem;
    }
}

/* Touch target improvements */
@media (max-width: 640px) {
    button, a {
        min-height: 44px;
        min-width: 44px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Search functionality - works with both table and card views
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value.toLowerCase();
            
            searchTimeout = setTimeout(function() {
                // Search in table rows (desktop)
                const tableRows = document.querySelectorAll('tbody tr');
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
                
                // Search in card views (mobile)
                const cardContainer = document.querySelector('.lg\\:hidden');
                if (cardContainer) {
                    const cardViews = cardContainer.querySelectorAll('div:not(.p-8)');
                    cardViews.forEach(card => {
                        // Skip the empty state div
                        if (card.classList.contains('p-8')) return;
                        const text = card.textContent.toLowerCase();
                        if (searchTerm === '') {
                            card.style.display = '';
                        } else {
                            card.style.display = text.includes(searchTerm) ? '' : 'none';
                        }
                    });
                }
            }, 300);
        });
    }

    // Export functionality
    window.exportTo = function(format) {
        alert('Export to ' + format.toUpperCase() + ' functionality will be implemented soon.');
    };
});
</script>
@endpush
@endsection
