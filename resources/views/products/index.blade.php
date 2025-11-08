@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Products Management</h1>
                <p class="text-gray-600 mt-1">Productsကို ထည့်သွင်း၊ ပြင်ဆင်၊ ဖျက်ပစ်နိုင်ပါသည်</p>
            </div>
            <div class="flex space-x-3">
                <!-- Excel Import Button -->
                <button onclick="document.getElementById('importModal').classList.remove('hidden')" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <span>Excel Import</span>
                </button>

                <!-- Excel Export Button -->
                <a href="{{ route('products.export') }}" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Excel Export</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Livewire Component -->
        @livewire('product-management')
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Excel ဖိုင် Import လုပ်ရန်</h3>
            
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Excel ဖိုင်ရွေးချယ်ပါ</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">ပံ့ပိုးသော format များ: .xlsx, .xls, .csv</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                    <p class="text-sm text-blue-800 font-medium mb-2">Excel ဖိုင်တွင် ပါဝင်ရမည့် Column များ:</p>
                    <ul class="text-xs text-blue-700 space-y-1">
                        <li>• ကုဒ် (code) - လိုအပ်သည်</li>
                        <li>• အမည် (name) - လိုအပ်သည်</li>
                        <li>• ဖော်ပြချက် (description)</li>
                        <li>• အမျိုးအစား (category)</li>
                        <li>• ယူနစ် (unit)</li>
                        <li>• စျေးနှုန်း (price)</li>
                        <li>• ကုန်ကျစရိတ် (cost)</li>
                        <li>• လက်ကျန် (stock_quantity)</li>
                        <li>• အနည်းဆုံးလက်ကျန် (min_stock)</li>
                        <li>• ပေးသွင်းသူ (supplier)</li>
                        <li>• ဘားကုဒ် (barcode)</li>
                    </ul>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        ပယ်ဖျက်ရန်
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Import လုပ်ရန်
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
