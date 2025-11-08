<div class="p-4 sm:p-6">
    <!-- Flash Messages -->
    @if (session('message'))
    <div class="mb-4 p-4 rounded-lg {{ session('message.type') === 'error' ? 'bg-red-100 border border-red-400 text-red-700' : 'bg-green-100 border border-green-400 text-green-700' }}">
        {{ session('message.text') }}
    </div>
    @endif

    <div class="mb-6 flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Sales Transactions</h1>
            <p class="mt-1 text-sm text-gray-600">Manage and track all sales transactions</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3 w-full sm:w-auto">
            <button 
                wire:click="openImportModal" 
                class="w-full px-3 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2 whitespace-nowrap"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Import CSV
            </button>
            <button 
                wire:click="exportCsv"
                class="w-full px-3 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2 whitespace-nowrap"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Export CSV
            </button>
            <button 
                wire:click="$toggle('showModal')" 
                class="w-full px-3 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 whitespace-nowrap col-span-2 sm:col-span-1"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Sale
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg p-4 text-white h-full">
            <div class="text-sm font-medium opacity-90">Total Sales</div>
            <div class="text-2xl font-bold">{{ number_format($summary['total_sales'] ?? 0) }} MMK</div>
            <div class="text-xs opacity-80 mt-1">{{ $summary['total_transactions'] ?? 0 }} transactions</div>
        </div>
        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-lg p-4 text-white h-full">
            <div class="text-sm font-medium opacity-90">Items Sold</div>
            <div class="text-2xl font-bold">{{ number_format($summary['total_quantity'] ?? 0) }}</div>
            <div class="text-xs opacity-80 mt-1">across all transactions</div>
        </div>
        <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-lg p-4 text-white h-full">
            <div class="text-sm font-medium opacity-90">Avg. Sale</div>
            <div class="text-2xl font-bold">{{ number_format($summary['avg_sale'] ?? 0) }} MMK</div>
            <div class="text-xs opacity-80 mt-1">per transaction</div>
        </div>
        <div class="bg-gradient-to-br from-amber-600 to-amber-700 rounded-lg p-4 text-white h-full">
            <div class="text-sm font-medium opacity-90">This Month</div>
            <div class="text-2xl font-bold">{{ now()->format('F Y') }}</div>
            <div class="text-xs opacity-80 mt-1">{{ now()->format('M d, Y') }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5 mb-6">
        <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:w-1/2 lg:w-1/3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search invoices, customers, items..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 w-full sm:w-2/3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <div class="flex gap-2">
                        <input 
                            type="date" 
                            wire:model.live="dateFrom"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        >
                        <span class="flex items-center">to</span>
                        <input 
                            type="date" 
                            wire:model.live="dateTo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        >
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select 
                        wire:model.live="category"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sales Person</label>
                    <select 
                        wire:model.live="salesPersonId"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">All Sales Persons</option>
                        @foreach($salesPersons as $person)
                            <option value="{{ $person->id }}">{{ $person->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        
        <div class="mt-4 flex justify-end">
            <button 
                wire:click="resetFilters"
                class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800"
            >
                Reset Filters
            </button>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Invoice #</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Date</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Customer</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Item</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Sales Person</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-blue-600">{{ $transaction->invoice_no }}</div>
                                <div class="text-xs text-gray-500">{{ $transaction->branch }}</div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $transaction->sale_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $transaction->sale_date->diffForHumans() }}</div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $transaction->customer_name }}</div>
                                <div class="text-xs text-gray-500">{{ $transaction->customer_phone }}</div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $transaction->item_name }}</div>
                                <div class="text-xs text-gray-500">{{ $transaction->item_category }}</div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-gray-500 text-right">
                                {{ number_format($transaction->quantity) }}
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-right">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($transaction->net_amount) }} MMK</div>
                                @if($transaction->discount > 0)
                                    <div class="text-xs text-red-500">-{{ number_format($transaction->discount) }} MMK</div>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $transaction->sale_person }}</div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-right text-sm font-medium space-x-1">
                                <button 
                                    wire:click="view({{ $transaction->id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-3"
                                    title="View Details"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button 
                                    wire:click="edit({{ $transaction->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                                    title="Edit"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button 
                                    wire:click="confirmDelete({{ $transaction->id }})"
                                    class="text-red-600 hover:text-red-900"
                                    title="Delete"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-600">No transactions found</p>
                                <p class="text-sm text-gray-500 mt-1">Try adjusting your search or filter to find what you're looking for.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Import CSV Modal -->
    <x-modal wire:model="showImportModal">
        <x-slot name="title">Import Sales Data from CSV</x-slot>
        
        <div class="space-y-4">
            @if(empty($csvData))
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <div class="mt-4 flex text-sm text-gray-600">
                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                            <span>Upload a file</span>
                            <input id="file-upload" wire:model="file" type="file" class="sr-only">
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">CSV files up to 10MB</p>
                    @error('file') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <div class="flex items-center">
                    <input id="has-header" wire:model="hasHeader" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="has-header" class="ml-2 block text-sm text-gray-700">First row contains headers</label>
                </div>
            @else
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Map CSV Columns</h3>
                    <p class="text-xs text-gray-500 mb-3">Match your CSV columns to the correct fields in our system.</p>
                    
                    <div class="space-y-3">
                        @php
                            $requiredFields = ['invoice_no', 'sale_date', 'customer_name', 'item_name', 'net_amount'];
                            $fieldLabels = [
                                'invoice_no' => 'Invoice Number',
                                'sale_date' => 'Sale Date',
                                'customer_name' => 'Customer Name',
                                'customer_phone' => 'Customer Phone',
                                'item_name' => 'Item Name',
                                'item_category' => 'Item Category',
                                'quantity' => 'Quantity',
                                'net_amount' => 'Net Amount',
                                'discount' => 'Discount',
                                'tax' => 'Tax',
                                'sale_person' => 'Sales Person',
                                'branch' => 'Branch',
                                'remark' => 'Remarks'
                            ];
                        @endphp
                        
                        @foreach($fieldLabels as $field => $label)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 items-center">
                                <label class="text-sm font-medium text-gray-700">
                                    {{ $label }}
                                    @if(in_array($field, $requiredFields))
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <select 
                                    wire:model="mapping.{{ $field }}" 
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                >
                                    <option value="">-- Select Column --</option>
                                    @foreach($headers as $index => $header)
                                        <option value="{{ $index }}" {{ isset($mapping[$field]) && $mapping[$field] == $index ? 'selected' : '' }}>
                                            {{ $header }} (Column {{ $index + 1 }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-xs text-gray-500">
                                    @if(isset($mapping[$field]) && isset($csvData[0][$mapping[$field]]))
                                        <span class="font-medium">Sample:</span> {{ Str::limit($csvData[0][$mapping[$field]] ?? '', 30) }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Preview (First 5 Rows)</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-xs">
                                <thead class="bg-gray-100">
                                    <tr>
                                        @foreach($headers as $index => $header)
                                            <th class="px-3 py-2 text-left text-gray-500">{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($csvData as $row)
                                        <tr>
                                            @foreach($row as $cell)
                                                <td class="px-3 py-2 whitespace-nowrap">{{ $cell }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <input id="has-header" wire:model="hasHeader" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="has-header" class="ml-2 block text-sm text-gray-700">First row contains headers</label>
                </div>
            @endif
            
            @if(session('import-errors'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mt-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Import completed with {{ count(session('import-errors')) }} errors</h3>
                            <div class="mt-2 text-sm text-red-700 max-h-40 overflow-y-auto">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach(session('import-errors') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <x-slot name="footer">
            <button 
                wire:click="$set('showImportModal', false)" 
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Cancel
            </button>
            @if(!empty($csvData))
                <button 
                    wire:click="import" 
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Import {{ count($csvData) }} {{ Str::plural('row', count($csvData)) }}
                </button>
            @endif
        </x-slot>
    </x-modal>

    <!-- Add/Edit Sale Modal -->
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ $editMode ? 'Edit Sale' : 'Add New Sale' }}
        </x-slot>
        
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Invoice Number <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        wire:model="invoice_no" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="e.g. INV-001"
                    >
                    @error('invoice_no') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sale Date <span class="text-red-500">*</span></label>
                    <input 
                        type="date" 
                        wire:model="sale_date" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                    @error('sale_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer Name <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            wire:model="customer_name" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Customer name"
                        >
                        @error('customer_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input 
                            type="text" 
                            wire:model="customer_phone" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="e.g. 09123456789"
                        >
                        @error('customer_phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <input 
                            type="text" 
                            wire:model="customer_address" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Customer address"
                        >
                        @error('customer_address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NRC Number</label>
                        <input 
                            type="text" 
                            wire:model="customer_nrc" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="e.g. 12/ABC(N)123456"
                        >
                        @error('customer_nrc') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Item Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Item Category <span class="text-red-500">*</span></label>
                        <select 
                            wire:model="item_category" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="">Select Category</option>
                            <option value="Gold">Gold</option>
                            <option value="Diamond">Diamond</option>
                            <option value="Platinum">Platinum</option>
                            <option value="Silver">Silver</option>
                            <option value="Gemstone">Gemstone</option>
                        </select>
                        @error('item_category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Item Group</label>
                        <input 
                            type="text" 
                            wire:model="item_group" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="e.g. Ring, Necklace, Earrings"
                        >
                        @error('item_group') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Item Name <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            wire:model="item_name" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="e.g. Diamond Ring, Gold Chain"
                        >
                        @error('item_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Carat/Karat</label>
                        <input 
                            type="text" 
                            wire:model="density" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="e.g. 18K, 24K, 1.0ct"
                        >
                        @error('density') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Weight (grams)</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input 
                                type="number" 
                                wire:model="g" 
                                step="0.001"
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="0.000"
                            >
                            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">g</span>
                        </div>
                        @error('g') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Quantity <span class="text-red-500">*</span></label>
                        <input 
                            type="number" 
                            wire:model="quantity" 
                            min="1"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                        @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price per Gram (MMK)</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">MMK</span>
                            <input 
                                type="number" 
                                wire:model="g_price" 
                                step="0.01"
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="0.00"
                            >
                        </div>
                        @error('g_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pricing</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gross Amount (MMK)</label>
                        <div class="mt-1">
                            <input 
                                type="number" 
                                wire:model="g_gross_amount" 
                                readonly
                                class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            >
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Discount (MMK)</label>
                        <div class="mt-1">
                            <input 
                                type="number" 
                                wire:model="discount" 
                                step="0.01"
                                class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="0.00"
                            >
                        </div>
                        @error('discount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tax (MMK)</label>
                        <div class="mt-1">
                            <input 
                                type="number" 
                                wire:model="tax" 
                                step="0.01"
                                class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="0.00"
                            >
                        </div>
                        @error('tax') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="md:col-span-3">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Net Amount (MMK)</span>
                                <span class="text-xl font-bold text-blue-700">{{ number_format($net_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sales Person</label>
                        <select 
                            wire:model="sale_person" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="">Select Sales Person</option>
                            @foreach($salesPersons as $person)
                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                            @endforeach
                        </select>
                        @error('sale_person') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Branch</label>
                        <input 
                            type="text" 
                            wire:model="branch_name" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="e.g. Downtown, Mall Branch"
                        >
                        @error('branch_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Remarks</label>
                        <textarea 
                            wire:model="remark" 
                            rows="2"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Any additional notes or comments"
                        ></textarea>
                        @error('remark') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <x-slot name="footer">
            <button 
                type="button" 
                wire:click="$set('showModal', false)" 
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Cancel
            </button>
            <button 
                type="button" 
                wire:click="save" 
                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                {{ $editMode ? 'Update Sale' : 'Create Sale' }}
            </button>
        </x-slot>
    </x-modal>
</div>

@push('scripts')
<script>
    // Auto-calculate gross amount when price or quantity changes
    document.addEventListener('livewire:load', function () {
        Livewire.on('calculateAmounts', () => {
            // This will trigger the Livewire component to recalculate
        });
    });
    
    // Format currency inputs
    function formatCurrency(input) {
        // Remove non-numeric characters
        let value = input.value.replace(/[^0-9.]/g, '');
        
        // Format with commas
        if (value) {
            const parts = value.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            input.value = parts.length > 1 ? parts[0] + '.' + parts[1] : parts[0];
        }
    }
</script>
@endpush
