<div class="p-6 space-y-6">
    @if (session()->has('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-green-800 text-sm font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Product Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage jewelry products and inventory</p>
                </div>
            </div>
            <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                အသစ်ထည့်ရန်
            </button>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
            <svg class="w-5 h-5 text-gray-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h3 class="text-base font-semibold text-gray-900">Search & Filter</h3>
        </div>
        <div class="flex gap-4">
            <input type="text" wire:model.live="search" placeholder="ရှာဖွေရန်..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            <select wire:model.live="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">အမျိုးအစားအားလုံး</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">Products List</h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ကုဒ်</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ပစ္စည်း</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">အမျိုးအစား</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">အလေးချိန်</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">ရောင်းစျေး</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">လုပ်ဆောင်ချက်</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $product->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $product->item_name ?? $product->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900">{{ $product->item_category }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="text-sm font-semibold text-gray-900">{{ number_format($product->item_tg, 3) }}</div>
                                <div class="text-xs text-gray-500">grams</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="text-sm font-semibold text-gray-900">{{ number_format($product->sale_fixed_price, 0) }}</div>
                                <div class="text-xs text-gray-500">MMK</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-2">
                                    <button wire:click="edit({{ $product->id }})" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="ပြင်ဆင်ရန်">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $product->id }})" wire:confirm="ဖျက်မှာသေချာပါသလား?" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="ဖျက်ပစ်ရန်">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p>Products မရှိသေးပါ</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">{{ $products->links() }}</div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto z-50">
        <div class="relative top-5 mx-auto p-5 border w-full max-w-6xl bg-white rounded-lg shadow-lg mb-10">
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-bold">{{ $editMode ? 'ပြင်ဆင်ရန်' : 'အသစ်ထည့်ရန်' }}</h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form wire:submit.prevent="save">
                <div class="max-h-[70vh] overflow-y-auto space-y-6 px-2">
                    
                    <!-- Basic Info -->
                    <div class="border-b pb-4">
                        <h4 class="font-semibold mb-3">အခြေခံအချက်အလက်</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div><label class="block text-sm mb-1">ကုဒ် *</label><input type="text" wire:model="code" required class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ပစ္စည်းအမည်</label><input type="text" wire:model="item_name" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ဝန်ထမ်းအမည်</label><input type="text" wire:model="staff_name" class="w-full px-3 py-2 border rounded"></div>
                        </div>
                    </div>

                    <!-- Type -->
                    <div class="border-b pb-4">
                        <h4 class="font-semibold mb-3">အမျိုးအစား</h4>
                        <div class="grid grid-cols-4 gap-4">
                            <div class="flex items-center"><input type="checkbox" wire:model="is_diamond" class="mr-2">စိန်ပါသည်</div>
                            <div class="flex items-center"><input type="checkbox" wire:model="is_solid_gold" class="mr-2">ရွှေစင်</div>
                            <div><label class="block text-sm mb-1">အမျိုးအစား</label><input type="text" wire:model="item_category" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ရွှေအရည်အသွေး</label><input type="text" wire:model="gold_quality" class="w-full px-3 py-2 border rounded"></div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="border-b pb-4">
                        <h4 class="font-semibold mb-3">အသေးစိတ်</h4>
                        <div class="grid grid-cols-4 gap-4">
                            <div><label class="block text-sm mb-1">မူလကုဒ်</label><input type="text" wire:model="original_code" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">အရှည်</label><input type="number" step="0.01" wire:model="length" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">အနံ</label><input type="number" step="0.01" wire:model="width" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">အရောင်</label><input type="text" wire:model="color" class="w-full px-3 py-2 border rounded"></div>
                        </div>
                    </div>

                    <!-- Goldsmith -->
                    <div class="border-b pb-4">
                        <h4 class="font-semibold mb-3">ပန်းထိမ်သမား</h4>
                        <div class="grid grid-cols-4 gap-4">
                            <div><label class="block text-sm mb-1">အမည်</label><input type="text" wire:model="goldsmith_name" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ရက်စွဲ</label><input type="date" wire:model="goldsmith_date" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ပေးသွင်းသူ</label><input type="text" wire:model="supplier" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ဘောင်ချာနံပါတ်</label><input type="text" wire:model="voucher_no" class="w-full px-3 py-2 border rounded"></div>
                        </div>
                    </div>

                    <!-- Item Weight -->
                    <div class="border-b pb-4">
                        <h4 class="font-semibold mb-3">ပစ္စည်းအလေးချိန်</h4>
                        <div class="grid grid-cols-4 gap-4">
                            <div><label class="block text-sm mb-1">ကျပ် (K)</label><input type="number" step="0.01" wire:model="item_k" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ပဲ (P)</label><input type="number" step="0.01" wire:model="item_p" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ရွေး (Y)</label><input type="number" step="0.01" wire:model="item_y" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">စုစုပေါင်း (TG)</label><input type="number" step="0.001" wire:model="item_tg" class="w-full px-3 py-2 border rounded"></div>
                        </div>
                    </div>

                    <!-- Waste -->
                    <div class="border-b pb-4">
                        <h4 class="font-semibold mb-3">အညစ်အကြေး (Waste)</h4>
                        <div class="grid grid-cols-4 gap-4">
                            <div><label class="block text-sm mb-1">ကျပ်</label><input type="number" step="0.01" wire:model="waste_k" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ပဲ</label><input type="number" step="0.01" wire:model="waste_p" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ရွေး</label><input type="number" step="0.01" wire:model="waste_y" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">စုစုပေါင်း</label><input type="number" step="0.001" wire:model="waste_t" class="w-full px-3 py-2 border rounded"></div>
                        </div>
                    </div>

                    <!-- PWaste -->
                    <div class="border-b pb-4">
                        <h4 class="font-semibold mb-3">PWaste</h4>
                        <div class="grid grid-cols-4 gap-4">
                            <div><label class="block text-sm mb-1">ကျပ်</label><input type="number" step="0.01" wire:model="pwaste_k" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ပဲ</label><input type="number" step="0.01" wire:model="pwaste_p" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ရွေး</label><input type="number" step="0.01" wire:model="pwaste_y" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">စုစုပေါင်း</label><input type="number" step="0.001" wire:model="pwaste_tg" class="w-full px-3 py-2 border rounded"></div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="border-b pb-4">
                        <h4 class="font-semibold mb-3">စျေးနှုန်းများ</h4>
                        <div class="grid grid-cols-4 gap-4">
                            <div><label class="block text-sm mb-1">ရောင်းစျေး</label><input type="number" step="0.01" wire:model="sale_fixed_price" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">မူလစျေး</label><input type="number" step="0.01" wire:model="original_fixed_price" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">မူလစျေး TK</label><input type="number" step="0.01" wire:model="original_price_tk" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ဂရမ်စျေး</label><input type="number" step="0.01" wire:model="original_price_gram" class="w-full px-3 py-2 border rounded"></div>
                        </div>
                    </div>

                    <!-- Charges -->
                    <div class="border-b pb-4">
                        <h4 class="font-semibold mb-3">ကုန်ကျစရိတ်များ</h4>
                        <div class="grid grid-cols-5 gap-4">
                            <div><label class="block text-sm mb-1">ဒီဇိုင်းခ</label><input type="number" step="0.01" wire:model="design_charges" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">မွှေးခ</label><input type="number" step="0.01" wire:model="plating_charges" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">တပ်ဆင်ခ</label><input type="number" step="0.01" wire:model="mounting_charges" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">ဖြူခ</label><input type="number" step="0.01" wire:model="white_charges" class="w-full px-3 py-2 border rounded"></div>
                            <div><label class="block text-sm mb-1">အခြားခ</label><input type="number" step="0.01" wire:model="other_charges" class="w-full px-3 py-2 border rounded"></div>
                        </div>
                    </div>

                    <!-- Remark & Image -->
                    <div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-sm mb-1">မှတ်ချက်</label><textarea wire:model="remark" rows="3" class="w-full px-3 py-2 border rounded"></textarea></div>
                            <div>
                                <label class="block text-sm mb-1">ပုံ</label>
                                <input type="file" wire:model="image" accept="image/*" class="w-full px-3 py-2 border rounded">
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="mt-2 w-32 h-32 object-cover rounded">
                                @elseif($existing_image)
                                    <img src="{{ Storage::url($existing_image) }}" class="mt-2 w-32 h-32 object-cover rounded">
                                @endif
                            </div>
                        </div>
                        <div class="mt-4"><label class="flex items-center"><input type="checkbox" wire:model="is_active" class="mr-2">အသုံးပြုနေသည်</label></div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3 border-t pt-4">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">ပယ်ဖျက်ရန်</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">{{ $editMode ? 'ပြင်ဆင်ရန်' : 'သိမ်းဆည်းရန်' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
