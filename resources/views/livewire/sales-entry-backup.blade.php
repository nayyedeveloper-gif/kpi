<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #invoiceContent, #invoiceContent * {
            visibility: visible;
        }
        #invoiceContent {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
        @page {
            size: A4;
            margin: 1cm;
        }
    }
</style>

<div class="p-6">
    @if (session()->has('message'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('message') }}</div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Sales Transactions</h1>
            <p class="mt-2 text-sm text-gray-600">Record and manage all sales transactions</p>
            <p class="text-xs text-gray-400">Modal State: {{ $showModal ? 'Open' : 'Closed' }}</p>
        </div>
        <button onclick="@this.call('openCreateModal')" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium cursor-pointer">+ Add Sale</button>
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
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white">
            <p class="text-amber-100 text-sm">Commission</p>
            <h3 class="text-2xl font-bold mt-2">{{ number_format($stats['total_commission'], 2) }} MMK</h3>
        </div>
    </div>

    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" wire:model.live="search" placeholder="Search customer or item..." class="px-3 py-2 border rounded-lg">
            <select wire:model.live="filterPerson" class="px-3 py-2 border rounded-lg">
                <option value="">All Sales Persons</option>
                @foreach($salesPersons as $person)
                <option value="{{ $person->id }}">{{ $person->name }}</option>
                @endforeach
            </select>
            <input type="date" wire:model.live="filterDateFrom" class="px-3 py-2 border rounded-lg">
            <input type="date" wire:model.live="filterDateTo" class="px-3 py-2 border rounded-lg">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales Person</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->sale_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $transaction->salesPerson->name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-900">{{ $transaction->customer_name }}</div>
                        @if($transaction->customer_phone)
                        <div class="text-xs text-gray-500">{{ $transaction->customer_phone }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-900">{{ $transaction->item_name }}</div>
                        @if($transaction->item_category)
                        <div class="text-xs text-gray-500">{{ $transaction->item_category }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right text-sm">{{ number_format($transaction->quantity) }}</td>
                    <td class="px-6 py-4 text-right text-sm">{{ number_format($transaction->unit_price, 2) }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="text-sm font-bold text-emerald-600">{{ number_format($transaction->total_amount, 2) }} MMK</div>
                        <div class="text-xs text-gray-500">Comm: {{ number_format($transaction->commission_amount, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2">
                            <button onclick="@this.call('viewInvoice', {{ $transaction->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer" title="View Invoice">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            <button onclick="@this.call('openEditModal', {{ $transaction->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button onclick="if(confirm('Are you sure you want to delete this transaction?')) @this.call('delete', {{ $transaction->id }})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">No transactions found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $transactions->links() }}</div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-lg bg-white" wire:click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold">{{ $editMode ? 'Edit' : 'Add' }} Sale</h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Sales Person *</label>
                        <select wire:model="sales_person_id" class="w-full px-3 py-2 border rounded-lg">
                            <option value="">Select</option>
                            @foreach($salesPersons as $person)
                            <option value="{{ $person->id }}">{{ $person->name }}</option>
                            @endforeach
                        </select>
                        @error('sales_person_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Sale Date *</label>
                        <input type="date" wire:model="sale_date" class="w-full px-3 py-2 border rounded-lg">
                        @error('sale_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Customer Name *</label>
                        <input type="text" wire:model="customer_name" class="w-full px-3 py-2 border rounded-lg">
                        @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Customer Phone</label>
                        <input type="text" wire:model="customer_phone" class="w-full px-3 py-2 border rounded-lg">
                        @error('customer_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">🔍 Product Code Search</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model.live.debounce.500ms="product_code" 
                                id="productCodeInput"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="ကုဒ်နံပါတ်ရိုက်ထည့်ပါ (e.g., G001)"
                            >
                            <div id="codeStatus" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <span id="foundIcon" class="text-green-500 text-xl hidden">✓</span>
                                <span id="notFoundIcon" class="text-red-500 text-xl hidden">✗</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">ကုဒ်ရိုက်ထည့်လျှင် အလိုအလျောက် ရှာဖွေပေးပါမည်</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Or Select Product</label>
                        <select wire:model.live="product_id" class="w-full px-3 py-2 border rounded-lg">
                            <option value="">-- ရွေးချယ်ပါ --</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->code }} - {{ $product->item_name ?? $product->name }} 
                                @if($product->item_category) ({{ $product->item_category }}) @endif
                                - {{ number_format($product->sale_fixed_price, 0) }} MMK
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Item Name *</label>
                        <input type="text" wire:model="item_name" class="w-full px-3 py-2 border rounded-lg" placeholder="ပစ္စည်းအမည်">
                        @error('item_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Category</label>
                        <input type="text" wire:model="item_category" class="w-full px-3 py-2 border rounded-lg">
                        @error('item_category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Quantity *</label>
                        <input type="number" wire:model="quantity" class="w-full px-3 py-2 border rounded-lg">
                        @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Unit Price (MMK) *</label>
                        <input type="number" step="0.01" wire:model="unit_price" class="w-full px-3 py-2 border rounded-lg">
                        @error('unit_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Commission Rate (%)</label>
                        <input type="number" step="0.01" wire:model="commission_rate" class="w-full px-3 py-2 border rounded-lg">
                        @error('commission_rate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Total Amount</label>
                        <input type="text" value="{{ number_format(($quantity ?? 0) * ($unit_price ?? 0), 2) }} MMK" class="w-full px-3 py-2 border rounded-lg bg-gray-50" readonly>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-2">Notes</label>
                        <textarea wire:model="notes" class="w-full px-3 py-2 border rounded-lg" rows="2"></textarea>
                        @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">{{ $editMode ? 'Update' : 'Create' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Invoice Modal -->
    @if($showInvoiceModal && $viewTransaction)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeInvoiceModal">
        <div class="relative top-10 mx-auto p-8 border w-full max-w-4xl shadow-2xl rounded-lg bg-white" wire:click.stop>
            <!-- Print Button -->
            <div class="flex justify-between items-center mb-6 no-print">
                <h3 class="text-2xl font-bold text-gray-800">ငွေတောင်းခံလွှာ / Invoice</h3>
                <div class="space-x-2">
                    <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        🖨️ Print
                    </button>
                    <button wire:click="closeInvoiceModal" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        ✕ Close
                    </button>
                </div>
            </div>

            <!-- Invoice Content (Printable) -->
            <div id="invoiceContent" class="bg-white p-8 border-2 border-gray-300">
                <!-- Header -->
                <div class="text-center mb-6 border-b-2 border-gray-800 pb-4">
                    <h1 class="text-2xl font-bold mb-2">အောင်:အခမဲ့: ပွင့်:ထိန်ထိန် ဆောက်ရွာ</h1>
                    <p class="text-lg font-semibold">Voucher No - {{ str_pad($viewTransaction->id, 12, '0', STR_PAD_LEFT) }}</p>
                </div>

                <!-- Customer Info -->
                <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                    <div class="space-y-2">
                        <div class="flex border-b border-dashed border-gray-400 pb-1">
                            <span class="font-semibold w-24">အမည်</span>
                            <span class="flex-1">{{ $viewTransaction->customer_name }}</span>
                        </div>
                        <div class="flex border-b border-dashed border-gray-400 pb-1">
                            <span class="font-semibold w-24">ဖုန်း</span>
                            <span class="flex-1">{{ $viewTransaction->customer_phone ?? '-' }}</span>
                        </div>
                        <div class="flex border-b border-dashed border-gray-400 pb-1">
                            <span class="font-semibold w-24">ရွှေသားဖြစ်ပုံ</span>
                            <span class="flex-1">-</span>
                        </div>
                        <div class="flex border-b border-dashed border-gray-400 pb-1">
                            <span class="font-semibold w-24">မွေးသက္ကရာဇ်</span>
                            <span class="flex-1">-</span>
                        </div>
                    </div>
                    <div class="space-y-2 text-right">
                        <div class="flex justify-between border-b border-dashed border-gray-400 pb-1">
                            <span class="font-semibold">ရက်စွဲ</span>
                            <span>{{ $viewTransaction->sale_date->format('d-m-Y h:i A') }}</span>
                        </div>
                        <div class="flex justify-between border-b border-dashed border-gray-400 pb-1">
                            <span class="font-semibold">ရွှေနှုန်း</span>
                            <span>-</span>
                        </div>
                        <div class="flex justify-between border-b border-dashed border-gray-400 pb-1">
                            <span class="font-semibold">ဆိုင်အမှတ်</span>
                            <span>-</span>
                        </div>
                        <div class="flex justify-between border-b border-dashed border-gray-400 pb-1">
                            <span class="font-semibold">ပွင့်ထိန်ဆောက်</span>
                            <span></span>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <table class="w-full mb-6 border-2 border-gray-800">
                    <thead>
                        <tr class="border-b-2 border-gray-800">
                            <th class="border-r border-gray-800 px-2 py-2 text-center text-sm font-bold">စဉ်</th>
                            <th class="border-r border-gray-800 px-4 py-2 text-center text-sm font-bold">အမျိုးအစား</th>
                            <th class="border-r border-gray-800 px-4 py-2 text-center text-sm font-bold">သင်းငွေ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-800">
                            <td class="border-r border-gray-800 px-2 py-3 text-center">1.</td>
                            <td class="border-r border-gray-800 px-4 py-3">
                                <div class="font-semibold">{{ $viewTransaction->item_name }}</div>
                                @if($viewTransaction->item_category)
                                <div class="text-xs text-gray-600">{{ $viewTransaction->item_category }}</div>
                                @endif
                                <div class="text-xs text-gray-600">Qty: {{ number_format($viewTransaction->quantity) }}</div>
                            </td>
                            <td class="border-r border-gray-800 px-4 py-3 text-right font-semibold">
                                {{ number_format($viewTransaction->total_amount, 0) }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-800">
                            <td colspan="2" class="border-r border-gray-800 px-4 py-2 text-right font-semibold">(ပေါင်း)</td>
                            <td class="border-r border-gray-800 px-4 py-2 text-right font-semibold">
                                {{ number_format($viewTransaction->total_amount, 0) }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Summary -->
                <div class="grid grid-cols-2 gap-8 mb-8">
                    <div></div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between border-b border-dashed border-gray-400 pb-1">
                            <span class="font-semibold">ရွှေဝယ်ငွေ:ကျသင့်ငွေ</span>
                            <span class="font-semibold">{{ number_format($viewTransaction->total_amount, 0) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-dashed border-gray-400 pb-1">
                            <span class="font-semibold">ပေးသည်: အရောင်းငွေ</span>
                            <span class="font-semibold">{{ number_format($viewTransaction->total_amount, 0) }}</span>
                        </div>
                        <div class="flex justify-between border-b-2 border-gray-800 pb-1">
                            <span class="font-semibold">[ဘိုးနှုန်းငွေ</span>
                            <span class="font-semibold text-red-600">0</span>
                        </div>
                        <div class="flex justify-between border-b border-dashed border-gray-400 pb-1">
                            <span class="font-semibold">အခွန်</span>
                            <span class="font-semibold">0</span>
                        </div>
                        <div class="flex justify-between border-b border-dashed border-gray-400 pb-1">
                            <span class="font-semibold">ဝန်ဆောင်</span>
                            <span class="font-semibold">{{ number_format($viewTransaction->commission_amount, 0) }}</span>
                        </div>
                        <div class="flex justify-between border-b-2 border-gray-800 pb-1 pt-2">
                            <span class="font-bold text-lg">ကျန်ငွေ</span>
                            <span class="font-bold text-lg">0</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Signatures -->
                <div class="grid grid-cols-3 gap-8 text-center text-sm border-t-2 border-gray-300 pt-6">
                    <div>
                        <div class="border-b border-gray-800 mb-2 pb-8"></div>
                        <p class="font-semibold">Cashier</p>
                    </div>
                    <div>
                        <div class="border-b border-gray-800 mb-2 pb-8"></div>
                        <p class="font-semibold">Customer Sign</p>
                    </div>
                    <div>
                        <div class="border-b border-gray-800 mb-2 pb-8"></div>
                        <p class="font-semibold">Manager</p>
                        <p class="text-xs mt-1">{{ $viewTransaction->salesPerson->name ?? '' }}</p>
                    </div>
                </div>

                <!-- Notes -->
                @if($viewTransaction->notes)
                <div class="mt-6 p-3 bg-gray-50 border border-gray-300 rounded">
                    <p class="text-xs font-semibold mb-1">မှတ်ချက်:</p>
                    <p class="text-sm">{{ $viewTransaction->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    @script
    <script>
        console.log('Livewire Sales Entry loaded');
        console.log('$wire object:', $wire);
        
        // Listen for product found/not found events
        $wire.on('product-found', () => {
                const codeStatus = document.getElementById('codeStatus');
                const foundIcon = document.getElementById('foundIcon');
                const notFoundIcon = document.getElementById('notFoundIcon');
                const input = document.getElementById('productCodeInput');
                
                if (codeStatus && foundIcon && notFoundIcon && input) {
                    codeStatus.classList.remove('hidden');
                    foundIcon.classList.remove('hidden');
                    notFoundIcon.classList.add('hidden');
                    input.classList.remove('border-red-500');
                    input.classList.add('border-green-500');
                    
                    // Hide after 2 seconds
                    setTimeout(() => {
                        codeStatus.classList.add('hidden');
                        input.classList.remove('border-green-500');
                    }, 2000);
                }
        });
        
        $wire.on('product-not-found', () => {
            const codeStatus = document.getElementById('codeStatus');
            const foundIcon = document.getElementById('foundIcon');
            const notFoundIcon = document.getElementById('notFoundIcon');
            const input = document.getElementById('productCodeInput');
            
            if (codeStatus && foundIcon && notFoundIcon && input) {
                codeStatus.classList.remove('hidden');
                foundIcon.classList.add('hidden');
                notFoundIcon.classList.remove('hidden');
                input.classList.remove('border-green-500');
                input.classList.add('border-red-500');
                
                // Hide after 2 seconds
                setTimeout(() => {
                    codeStatus.classList.add('hidden');
                    input.classList.remove('border-red-500');
                }, 2000);
            }
        });
    </script>
    @endscript
</div>
