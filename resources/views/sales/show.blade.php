@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 space-y-6">
    <!-- Header with Back Button and Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <!-- Back Button -->
                <a href="{{ route('sales.data.index') }}" 
                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Sales Record Details</h1>
                    <p class="text-sm text-gray-600 mt-1">Voucher: {{ $sale->voucher_number }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('sales.data.edit', $sale->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('sales.data.destroy', $sale->id) }}" 
                      method="POST" 
                      class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this record? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Transaction Details Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h2 class="text-lg font-bold text-gray-900">Transaction Details</h2>
                </div>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center border-b border-gray-100 pb-4">
                        <dt class="text-sm font-medium text-gray-500 w-full sm:w-40 mb-1 sm:mb-0">Voucher Number</dt>
                        <dd class="text-base font-semibold text-gray-900">{{ $sale->voucher_number }}</dd>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center border-b border-gray-100 pb-4">
                        <dt class="text-sm font-medium text-gray-500 w-full sm:w-40 mb-1 sm:mb-0">Date</dt>
                        <dd class="text-base text-gray-900">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $formattedDate ?? ($sale->invoiced_date ? \Carbon\Carbon::parse($sale->invoiced_date)->format('d M Y') : 'N/A') }}
                            </div>
                        </dd>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center border-b border-gray-100 pb-4">
                        <dt class="text-sm font-medium text-gray-500 w-full sm:w-40 mb-1 sm:mb-0">Branch</dt>
                        <dd class="text-base text-gray-900">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ $sale->branch }}
                            </div>
                        </dd>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center border-b border-gray-100 pb-4">
                        <dt class="text-sm font-medium text-gray-500 w-full sm:w-40 mb-1 sm:mb-0">Sales Person</dt>
                        <dd class="text-base text-gray-900">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $sale->sale_person ?? 'N/A' }}
                            </div>
                        </dd>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <dt class="text-sm font-medium text-gray-500 w-full sm:w-40 mb-1 sm:mb-0">Customer Status</dt>
                        <dd class="text-base">
                            @if($sale->customer_status === 'New')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    New Customer
                                </span>
                            @elseif($sale->customer_status === 'Returning')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    Returning Customer
                                </span>
                            @elseif($sale->customer_status === 'VIP')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    VIP Customer
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    {{ $sale->customer_status ?? 'N/A' }}
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Customer Information Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <h2 class="text-lg font-bold text-gray-900">Customer Information</h2>
                </div>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-start border-b border-gray-100 pb-4">
                        <dt class="text-sm font-medium text-gray-500 w-full sm:w-40 mb-1 sm:mb-0">Customer Name</dt>
                        <dd class="text-base font-semibold text-gray-900">{{ $sale->customer_name }}</dd>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center border-b border-gray-100 pb-4">
                        <dt class="text-sm font-medium text-gray-500 w-full sm:w-40 mb-1 sm:mb-0">Contact Number</dt>
                        <dd class="text-base text-gray-900">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $sale->contact_number ?? 'N/A' }}
                            </div>
                        </dd>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-start border-b border-gray-100 pb-4">
                        <dt class="text-sm font-medium text-gray-500 w-full sm:w-40 mb-1 sm:mb-0">Address</dt>
                        <dd class="text-base text-gray-900">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $sale->contact_address ?? 'N/A' }}</span>
                            </div>
                        </dd>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center border-b border-gray-100 pb-4">
                        <dt class="text-sm font-medium text-gray-500 w-full sm:w-40 mb-1 sm:mb-0">Township</dt>
                        <dd class="text-base text-gray-900">{{ $sale->township ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center border-b border-gray-100 pb-4">
                        <dt class="text-sm font-medium text-gray-500 w-full sm:w-40 mb-1 sm:mb-0">Division</dt>
                        <dd class="text-base text-gray-900">{{ $sale->division ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <dt class="text-sm font-medium text-gray-500 w-full sm:w-40 mb-1 sm:mb-0">NRC Number</dt>
                        <dd class="text-base text-gray-900 font-mono text-sm">{{ $sale->customer_nrc_number ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Item Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">Item Details</h2>
            </div>
        </div>
        <div class="p-6">
            <!-- Item Table -->
            <div class="overflow-x-auto -mx-6 px-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price/Unit</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $sale->item_name }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $sale->item_categories ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $sale->item_group ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($sale->quantity, 2) }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right">
                                <div class="text-sm text-gray-900">{{ number_format($sale->weight, 2) }} {{ $sale->unit ?? '' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($sale->m_price, 2) }} MMK</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-bold text-gray-900">{{ number_format($sale->m_gross_amount, 2) }} MMK</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Additional Item Information and Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t border-gray-200">
                <!-- Left Column - Item Properties -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Item Properties</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-600">Density</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $sale->density ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-600">G Price</dt>
                            <dd class="text-sm font-semibold text-gray-900">
                                {{ $sale->g_price ? number_format($sale->g_price, 2) . ' MMK' : 'N/A' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Right Column - Financial Summary -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Financial Summary</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-600">Discount</dt>
                            <dd class="text-sm font-semibold text-gray-900">
                                @if($sale->dis || $sale->promotion_dis || $sale->special_dis)
                                    @php
                                        $totalDiscount = ($sale->dis ?? 0) + ($sale->promotion_dis ?? 0) + ($sale->special_dis ?? 0);
                                    @endphp
                                    {{ number_format($totalDiscount, 2) }} MMK
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-600">Tax</dt>
                            <dd class="text-sm font-semibold text-gray-900">
                                {{ $sale->tax ? number_format($sale->tax, 2) . ' MMK' : 'N/A' }}
                            </dd>
                        </div>
                        <div class="flex justify-between items-center py-3 pt-4 border-t-2 border-gray-300 mt-4">
                            <dt class="text-base font-bold text-gray-900">Net Amount</dt>
                            <dd class="text-xl font-bold text-green-600">
                                {{ number_format($sale->total_net_amount, 2) }} MMK
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Remarks Card (if exists) -->
    @if($sale->remark)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">Remarks</h2>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-700 leading-relaxed">{{ $sale->remark }}</p>
        </div>
    </div>
    @endif

    <!-- Footer Information -->
    <div class="bg-gray-50 rounded-xl border border-gray-200 px-6 py-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-sm text-gray-600">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Created: {{ $sale->created_at->format('d M Y H:i') }}
            </div>
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Updated: {{ $sale->updated_at->format('d M Y H:i') }}
            </div>
        </div>
    </div>
</div>
@endsection
