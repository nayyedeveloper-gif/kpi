@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ isset($rankingCode) ? 'Edit' : 'Create' }} Ranking Code
        </h1>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($rankingCode) ? route('ranking-codes.update', $rankingCode) : route('ranking-codes.store') }}" method="POST">
        @csrf
        @if(isset($rankingCode))
            @method('PUT')
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Group Name -->
                    <div>
                        <label for="group_name" class="block text-sm font-medium text-gray-700">Group Name</label>
                        <select id="group_name" name="group_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('group_name') border-red-500 @enderror">
                            <option value="A" {{ (isset($rankingCode) && $rankingCode->group_name === 'A') ? 'selected' : '' }}>A</option>
                            <option value="B" {{ (isset($rankingCode) && $rankingCode->group_name === 'B') ? 'selected' : '' }}>B</option>
                            <option value="A+B" {{ (isset($rankingCode) && $rankingCode->group_name === 'A+B') ? 'selected' : '' }}>A+B</option>
                        </select>
                        @error('group_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position Name -->
                    <div>
                        <label for="position_name" class="block text-sm font-medium text-gray-700">Position Name</label>
                        <input type="text" name="position_name" id="position_name" 
                               value="{{ $rankingCode->position_name ?? old('position_name') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('position_name') border-red-500 @enderror"
                               required>
                        @error('position_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Employee Name (Optional)</label>
                        <input type="text" name="name" id="name" 
                               value="{{ $rankingCode->name ?? old('name') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Guardian Name -->
                    <div>
                        <label for="guardian_name" class="block text-sm font-medium text-gray-700">Guardian Name</label>
                        <input type="text" name="guardian_name" id="guardian_name" 
                               value="{{ $rankingCode->guardian_name ?? old('guardian_name') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('guardian_name') border-red-500 @enderror"
                               required>
                        @error('guardian_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Guardian Code -->
                    <div>
                        <label for="guardian_code" class="block text-sm font-medium text-gray-700">Guardian Code</label>
                        <input type="text" name="guardian_code" id="guardian_code" 
                               value="{{ $rankingCode->guardian_code ?? old('guardian_code') }}"
                               maxlength="1"
                               class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('guardian_code') border-red-500 @enderror"
                               required>
                        @error('guardian_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Branch Code -->
                    <div>
                        <label for="branch_code" class="block text-sm font-medium text-gray-700">Branch Code</label>
                        <input type="number" name="branch_code" id="branch_code" 
                               value="{{ $rankingCode->branch_code ?? old('branch_code') }}"
                               class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('branch_code') border-red-500 @enderror"
                               required>
                        @error('branch_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Group Code -->
                    <div>
                        <label for="group_code" class="block text-sm font-medium text-gray-700">Group Code</label>
                        <select id="group_code" name="group_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('group_code') border-red-500 @enderror">
                            <option value="A" {{ (isset($rankingCode) && $rankingCode->group_code === 'A') ? 'selected' : '' }}>A</option>
                            <option value="B" {{ (isset($rankingCode) && $rankingCode->group_code === 'B') ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ (isset($rankingCode) && $rankingCode->group_code === 'AB') ? 'selected' : '' }}>AB</option>
                        </select>
                        @error('group_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position Code -->
                    <div>
                        <label for="position_code" class="block text-sm font-medium text-gray-700">Position Code</label>
                        <input type="text" name="position_code" id="position_code" 
                               value="{{ $rankingCode->position_code ?? old('position_code') }}"
                               class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('position_code') border-red-500 @enderror"
                               required>
                        <p class="mt-1 text-xs text-gray-500">E.g., -BM, -AM, -SS, -SL, -SR, -CS</p>
                        @error('position_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ID Code -->
                    <div>
                        <label for="id_code" class="block text-sm font-medium text-gray-700">ID Code</label>
                        <input type="number" name="id_code" id="id_code" 
                               value="{{ $rankingCode->id_code ?? old('id_code') }}"
                               min="1"
                               class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('id_code') border-red-500 @enderror"
                               required>
                        @error('id_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ranking ID (hidden, auto-generated) -->
                    <input type="hidden" name="ranking_id" id="ranking_id" value="{{ $rankingCode->ranking_id ?? old('ranking_id') }}">
                </div>
            </div>

            <div class="px-6 py-3 bg-gray-50 text-right">
                <a href="{{ route('ranking-codes.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ isset($rankingCode) ? 'Update' : 'Create' }} Ranking Code
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Auto-generate position code when position name changes
    document.getElementById('position_name').addEventListener('change', function() {
        const positionName = this.value.trim();
        if (positionName) {
            // Take first 2 characters and make uppercase
            const positionCode = '-' + positionName.substring(0, 2).toUpperCase();
            document.getElementById('position_code').value = positionCode;
        }
    });
    
    // Auto-generate guardian code when guardian name changes
    document.getElementById('guardian_name').addEventListener('change', function() {
        const guardianName = this.value.trim();
        if (guardianName) {
            // Take first character and make uppercase
            const guardianCode = guardianName.charAt(0).toUpperCase();
            document.getElementById('guardian_code').value = guardianCode;
        }
    });
</script>
@endpush
@endsection
