<div class="p-6">
    @if (session()->has('message'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('message') }}</div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Sales Transactions</h1>
            <p class="mt-2 text-sm text-gray-600">Record and manage all sales transactions</p>
        </div>
        <div class="flex space-x-3">
            <div x-data="{ isVisible: true }" x-init="console.log('Import button initialized')">
                <button 
                    type="button" 
                    wire:click="openImportModal" 
                    @click="console.log('Import button clicked');"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center"
                    x-show="isVisible"
                >
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Import CSV
                </button>
                <div x-show="!isVisible" class="text-xs text-red-500">Button is hidden</div>
            </div>
            <button type="button" wire:click="openCreateModal" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition-colors flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Sale
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
            <p class="text-emerald-100 text-sm">Total Revenue</p>
            <h3 class="text-2xl font-bold mt-2">{{ number_format($stats['total_revenue'], 2) }} MMK</h3>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <p class="text-blue-100 text-sm">Items Sold</p>
            <h3 class="text-2xl font-bold mt-2">{{ number_format($stats['total_quantity']) }}</h3>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <p class="text-purple-100 text-sm">Transactions</p>
            <h3 class="text-2xl font-bold mt-2">{{ number_format($stats['total_transactions']) }}</h3>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <p class="text-orange-100 text-sm">Commission</p>
            <h3 class="text-2xl font-bold mt-2">{{ number_format($stats['total_commission'], 2) }} MMK</h3>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-lg bg-white" wire:click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold">{{ $editMode ? 'Edit' : 'Add' }} Sale</h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <form wire:submit.prevent="save">
                <!-- Product Code Search -->
                <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-xl shadow-sm">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <label class="text-sm font-bold text-blue-900">Product Code Search (ကုဒ်နံပါတ်ရိုက်ထည့်ပါ)</label>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.500ms="product_code" 
                        class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg font-mono transition"
                        placeholder="G001, G002, etc..."
                    >
                    <div class="flex items-center mt-2 text-xs text-blue-700">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span>ကုဒ်ရိုက်ထည့်လျှင် အလိုအလျောက် ဖြည့်ပေးပါမည်</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Transaction Info -->
                    <div class="col-span-2 flex items-center border-b-2 border-gray-200 pb-3 mb-4">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h4 class="font-bold text-gray-800 text-base">Transaction Information</h4>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sales Person *</label>
                        <select wire:model="sales_person_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Choose --</option>
                            @foreach($salesPersons as $person)
                            <option value="{{ $person->id }}">{{ $person->name }}</option>
                            @endforeach
                        </select>
                        @error('sales_person_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sale Date *</label>
                        <input type="date" wire:model="sale_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('sale_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">ပြေစာအမှတ် / Invoice No</label>
                        <input type="text" wire:model="invoice_no" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="INV-001">
                        @error('invoice_no') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Customer Info -->
                    <div class="col-span-2 flex items-center border-b-2 border-gray-200 pb-3 mb-4 mt-6">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h4 class="font-bold text-gray-800 text-base">Customer Information</h4>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Customer Name *</label>
                        <input type="text" wire:model="customer_name" class="w-full px-3 py-2 border rounded-lg">
                        @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Phone No</label>
                        <input type="text" wire:model="customer_phone" class="w-full px-3 py-2 border rounded-lg">
                        @error('customer_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Address</label>
                        <input type="text" wire:model="customer_address" class="w-full px-3 py-2 border rounded-lg">
                        @error('customer_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">NRC</label>
                        <input type="text" wire:model="customer_nrc" class="w-full px-3 py-2 border rounded-lg">
                        @error('customer_nrc') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Product Details -->
                    <div class="col-span-2 flex items-center border-b-2 border-gray-200 pb-3 mb-4 mt-6">
                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <h4 class="font-bold text-gray-800 text-base">Product Details</h4>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Item Name *</label>
                        <input type="text" wire:model="item_name" class="w-full px-3 py-2 border rounded-lg bg-yellow-50">
                        @error('item_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Category</label>
                        <input type="text" wire:model="item_category" class="w-full px-3 py-2 border rounded-lg bg-yellow-50">
                        @error('item_category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Gold Quality</label>
                        <input type="text" wire:model="gold_quality" class="w-full px-3 py-2 border rounded-lg bg-yellow-50">
                        @error('gold_quality') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Color</label>
                        <input type="text" wire:model="color" class="w-full px-3 py-2 border rounded-lg bg-yellow-50">
                        @error('color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Length</label>
                        <input type="number" step="0.01" wire:model="length" class="w-full px-3 py-2 border rounded-lg bg-yellow-50">
                        @error('length') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Width</label>
                        <input type="number" step="0.01" wire:model="width" class="w-full px-3 py-2 border rounded-lg bg-yellow-50">
                        @error('width') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Weight Details -->
                    <div class="col-span-2 flex items-center border-b-2 border-gray-200 pb-3 mb-4 mt-6">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                        <h4 class="font-bold text-gray-800 text-base">Weight (အလေးချိန်)</h4>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">K (ကျပ်)</label>
                        <input type="number" step="0.01" wire:model="item_k" class="w-full px-3 py-2 border rounded-lg bg-yellow-50">
                        @error('item_k') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">P (ပဲ)</label>
                        <input type="number" step="0.01" wire:model="item_p" class="w-full px-3 py-2 border rounded-lg bg-yellow-50">
                        @error('item_p') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Y (ရွေး)</label>
                        <input type="number" step="0.01" wire:model="item_y" class="w-full px-3 py-2 border rounded-lg bg-yellow-50">
                        @error('item_y') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">TG (Total Grams)</label>
                        <input type="number" step="0.001" wire:model="item_tg" class="w-full px-3 py-2 border rounded-lg bg-yellow-50">
                        @error('item_tg') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Staff Information -->
                    <div class="col-span-2 flex items-center border-b-2 border-gray-200 pb-3 mb-4 mt-6">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h4 class="font-bold text-gray-800 text-base">Staff Information</h4>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ပန်းထိမ်ဆရာ</label>
                        <input type="text" wire:model="goldsmith_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-yellow-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('goldsmith_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ဆိုင်အမှတ်</label>
                        <select wire:model="shop_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Choose --</option>
                            <option value="Shop 1">Shop 1</option>
                            <option value="Shop 2">Shop 2</option>
                            <option value="Shop 3">Shop 3</option>
                            <option value="Shop 4">Shop 4</option>
                            <option value="Shop 5">Shop 5</option>
                        </select>
                        @error('shop_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ငွေကိုင်</label>
                        <select wire:model="cashier" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Choose --</option>
                            @foreach($salesPersons as $person)
                            <option value="{{ $person->name }}">{{ $person->name }}</option>
                            @endforeach
                        </select>
                        @error('cashier') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">အရောင်မန်နေဂျာ</label>
                        <select wire:model="color_manager" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Choose --</option>
                            @foreach($salesPersons as $person)
                            <option value="{{ $person->name }}">{{ $person->name }}</option>
                            @endforeach
                        </select>
                        @error('color_manager') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">တာဝန်ခံလက်မှတ်</label>
                        <select wire:model="responsible_signature" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Choose --</option>
                            @foreach($salesPersons as $person)
                            <option value="{{ $person->name }}">{{ $person->name }}</option>
                            @endforeach
                        </select>
                        @error('responsible_signature') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Pricing -->
                    <div class="col-span-2 flex items-center border-b-2 border-gray-200 pb-3 mb-4 mt-6">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h4 class="font-bold text-gray-800 text-base">Pricing</h4>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Quantity *</label>
                        <input type="number" wire:model="quantity" class="w-full px-3 py-2 border rounded-lg">
                        @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Unit Price (MMK) *</label>
                        <input type="number" step="0.01" wire:model="unit_price" class="w-full px-3 py-2 border rounded-lg bg-yellow-50">
                        @error('unit_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Total Amount</label>
                        <input type="text" value="{{ number_format(($quantity ?? 0) * ($unit_price ?? 0), 2) }} MMK" class="w-full px-3 py-2 border rounded-lg bg-green-50 font-bold text-green-700" readonly>
                    </div>
                    
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-2">Notes</label>
                        <textarea wire:model="notes" class="w-full px-3 py-2 border rounded-lg" rows="2"></textarea>
                        @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="closeModal" class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">{{ $editMode ? 'Update Sale' : 'Create Sale' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">No</th>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">Date</th>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">Inv No</th>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">Customer</th>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">Address</th>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">NRC</th>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">Ph No</th>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">ပန်းထိမ်ဆရာ</th>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">ဆိုင်အမှတ်</th>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">ကုတ်</th>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">အကြောင်းအရာ</th>
                    <th class="px-3 py-3 text-right text-xs font-bold text-gray-700 uppercase">အလေးချိန်</th>
                    <th class="px-3 py-3 text-right text-xs font-bold text-gray-700 uppercase">လက်ခ</th>
                    <th class="px-3 py-3 text-right text-xs font-bold text-gray-700 uppercase">သင့်ငွေ</th>
                    <th class="px-3 py-3 text-right text-xs font-bold text-gray-700 uppercase">စုစုပေါင်း</th>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">ငွေကိုင်</th>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">အရောင်းမန်နေဂျာ</th>
                    <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase">တာဝန်ခံ</th>
                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transactions as $index => $transaction)
                <tr class="hover:bg-blue-50 transition-colors">
                    <td class="px-3 py-3 whitespace-nowrap font-medium text-gray-900">{{ $transactions->firstItem() + $index }}</td>
                    <td class="px-3 py-3 whitespace-nowrap">{{ $transaction->sale_date->format('d/m/Y') }}</td>
                    <td class="px-3 py-3 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded">{{ $transaction->invoice_no ?? '-' }}</span>
                    </td>
                    <td class="px-3 py-3">
                        <div class="font-medium text-gray-900">{{ $transaction->customer_name }}</div>
                    </td>
                    <td class="px-3 py-3">
                        <div class="text-xs text-gray-600 max-w-xs truncate">{{ $transaction->customer_address ?? '-' }}</div>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-xs">{{ $transaction->customer_nrc ?? '-' }}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-xs">{{ $transaction->customer_phone ?? '-' }}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-xs">{{ $transaction->goldsmith_name ?? '-' }}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-xs">{{ $transaction->shop_number ?? '-' }}</td>
                    <td class="px-3 py-3 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-mono bg-yellow-100 text-yellow-800 rounded">{{ $transaction->product_code ?? '-' }}</span>
                    </td>
                    <td class="px-3 py-3">
                        <div class="font-medium text-gray-900">{{ $transaction->item_name }}</div>
                        @if($transaction->item_category)
                        <div class="text-xs text-gray-500">{{ $transaction->item_category }}</div>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-right whitespace-nowrap">
                        @if($transaction->item_tg > 0)
                        <div class="text-xs font-semibold text-purple-700">{{ number_format($transaction->item_tg, 3) }} g</div>
                        @endif
                        @if($transaction->item_k > 0 || $transaction->item_p > 0 || $transaction->item_y > 0)
                        <div class="text-xs text-gray-600">{{ $transaction->item_k }}K {{ $transaction->item_p }}P {{ $transaction->item_y }}Y</div>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-right whitespace-nowrap">
                        <div class="text-xs font-medium text-orange-600">{{ number_format($transaction->commission_amount, 0) }}</div>
                    </td>
                    <td class="px-3 py-3 text-right whitespace-nowrap">
                        <div class="text-xs font-medium">{{ number_format($transaction->unit_price, 0) }}</div>
                    </td>
                    <td class="px-3 py-3 text-right whitespace-nowrap">
                        <div class="font-bold text-emerald-700">{{ number_format($transaction->total_amount, 0) }}</div>
                        <div class="text-xs text-gray-500">MMK</div>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-xs">{{ $transaction->cashier ?? '-' }}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-xs">{{ $transaction->color_manager ?? '-' }}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-xs">{{ $transaction->responsible_signature ?? '-' }}</td>
                    <td class="px-3 py-3 text-center">
                        <div class="flex justify-center space-x-1">
                            <button wire:click="viewInvoice({{ $transaction->id }})" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="View Details">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            <button wire:click="openEditModal({{ $transaction->id }})" class="p-1.5 text-indigo-600 hover:bg-indigo-100 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button wire:click="delete({{ $transaction->id }})" wire:confirm="Are you sure you want to delete this transaction?" class="p-1.5 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="19" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="font-medium">No transactions found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 bg-gray-50 border-t">{{ $transactions->links() }}</div>
    </div>

    <!-- Invoice/Details Modal -->
    @if($showInvoiceModal && $viewTransaction)
    <div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4" wire:click="closeInvoiceModal">
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
            <!-- Header -->
            <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-blue-600 text-white px-6 py-4 rounded-t-xl flex items-center justify-between z-10">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div>
                        <h3 class="text-2xl font-bold">Sale Details</h3>
                        <p class="text-blue-100 text-sm">Invoice: {{ $viewTransaction->invoice_no ?? 'N/A' }}</p>
                    </div>
                </div>
                <button wire:click="closeInvoiceModal" class="text-white hover:text-gray-200 text-3xl font-bold">&times;</button>
            </div>

            <div class="p-6">
                <!-- Transaction Info -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">Sale Date</span>
                        </div>
                        <p class="text-lg font-bold text-gray-900">{{ $viewTransaction->sale_date->format('d F Y') }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">Sales Person</span>
                        </div>
                        <p class="text-lg font-bold text-gray-900">{{ $viewTransaction->salesPerson->name ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b-2 border-gray-200">
                        <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h4 class="text-lg font-bold text-gray-800">Customer Information</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Name</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->customer_name }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Phone</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->customer_phone ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Address</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->customer_address ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">NRC</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->customer_nrc ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b-2 border-gray-200">
                        <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <h4 class="text-lg font-bold text-gray-800">Product Details</h4>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Product Code</label>
                            <p class="text-base font-mono font-bold text-yellow-700 bg-yellow-50 px-2 py-1 rounded inline-block">{{ $viewTransaction->product_code ?? '-' }}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="text-xs font-semibold text-gray-500 uppercase">Item Name</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->item_name }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Category</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->item_category ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Gold Quality</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->gold_quality ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Color</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->color ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Length</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->length ? number_format($viewTransaction->length, 2) : '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Width</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->width ? number_format($viewTransaction->width, 2) : '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Weight Details -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b-2 border-gray-200">
                        <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                        <h4 class="text-lg font-bold text-gray-800">Weight (အလေးချိန်)</h4>
                    </div>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="bg-purple-50 p-3 rounded-lg text-center">
                            <label class="text-xs font-semibold text-purple-700 uppercase block mb-1">K (ကျပ်)</label>
                            <p class="text-xl font-bold text-purple-900">{{ number_format($viewTransaction->item_k, 2) }}</p>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-lg text-center">
                            <label class="text-xs font-semibold text-purple-700 uppercase block mb-1">P (ပဲ)</label>
                            <p class="text-xl font-bold text-purple-900">{{ number_format($viewTransaction->item_p, 2) }}</p>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-lg text-center">
                            <label class="text-xs font-semibold text-purple-700 uppercase block mb-1">Y (ရွေး)</label>
                            <p class="text-xl font-bold text-purple-900">{{ number_format($viewTransaction->item_y, 2) }}</p>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-lg text-center">
                            <label class="text-xs font-semibold text-purple-700 uppercase block mb-1">TG (Grams)</label>
                            <p class="text-xl font-bold text-purple-900">{{ number_format($viewTransaction->item_tg, 3) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Staff Information -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b-2 border-gray-200">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h4 class="text-lg font-bold text-gray-800">Staff Information</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">ပန်းထိမ်ဆရာ</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->goldsmith_name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">ဆိုင်အမှတ်</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->shop_number ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">ငွေကိုင်</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->cashier ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">အရောင်းမန်နေဂျာ</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->color_manager ?? '-' }}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="text-xs font-semibold text-gray-500 uppercase">တာဝန်ခံလက်မှတ်</label>
                            <p class="text-base font-medium text-gray-900">{{ $viewTransaction->responsible_signature ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pricing Summary -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl border-2 border-green-200">
                    <div class="flex items-center mb-4 pb-2 border-b-2 border-green-300">
                        <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h4 class="text-lg font-bold text-gray-800">Pricing Summary</h4>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Quantity:</span>
                            <span class="text-lg font-bold text-gray-900">{{ number_format($viewTransaction->quantity) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Unit Price:</span>
                            <span class="text-lg font-bold text-gray-900">{{ number_format($viewTransaction->unit_price, 2) }} MMK</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">လက်ခ (Commission {{ $viewTransaction->commission_rate }}%):</span>
                            <span class="text-lg font-bold text-orange-600">{{ number_format($viewTransaction->commission_amount, 2) }} MMK</span>
                        </div>
                        <div class="border-t-2 border-green-300 pt-3 flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-800">စုစုပေါင်းကျသင့်ငွေ:</span>
                            <span class="text-3xl font-bold text-green-700">{{ number_format($viewTransaction->total_amount, 2) }} MMK</span>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($viewTransaction->notes)
                <div class="mt-6">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        <label class="text-sm font-semibold text-gray-700">Notes</label>
                    </div>
                    <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $viewTransaction->notes }}</p>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button wire:click="closeInvoiceModal" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition-colors">Close</button>
                    <button onclick="window.print()" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Import CSV Modal -->
    @if($showImportModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="$set('showImportModal', false)">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white" wire:click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold">Import Sales from CSV</h3>
                <button wire:click="$set('showImportModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            <div class="mb-6">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">CSV Import Instructions</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>1. Download the <a href="#" wire:click.prevent="downloadTemplate" class="text-blue-600 underline">CSV template</a> for the correct format</p>
                                <p>2. Fill in your sales data</p>
                                <p>3. Upload the file below</p>
                                <p class="mt-2 font-semibold">Required fields: Sale Date, Product Code, Quantity, Price</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">CSV File</label>
                    <div class="mt-1 flex items-center">
                        <input type="file" wire:model="csvFile" accept=".csv,.txt" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100">
                    </div>
                    @error('csvFile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                @if(!empty($csvHeaders) && !empty($csvData))
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Map CSV Columns</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($csvMappings as $field => $value)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ ucwords(str_replace('_', ' ', $field)) }}</label>
                            <select wire:model="csvMappings.{{ $field }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">-- Select Column --</option>
                                @foreach($csvHeaders as $header)
                                <option value="{{ $header }}" @if($header === $value) selected @endif>{{ $header }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($importErrors))
                <div class="mt-6 bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                {{ count($importErrors) }} error(s) occurred during import
                            </h3>
                            <div class="mt-2 text-sm text-red-700 max-h-40 overflow-y-auto">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($importErrors as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($importSuccess)
                <div class="mt-6 bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">
                                Successfully imported {{ $importedCount }} {{ Str::plural('record', $importedCount) }}!
                            </h3>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" wire:click="$set('showImportModal', false)" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </button>
                @if(empty($csvHeaders))
                <button type="button" disabled class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-300 cursor-not-allowed">
                    Next
                </button>
                @else
                <button type="button" wire:click="importCsv" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Import Data
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
