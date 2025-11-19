@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Ranking Code Details</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.ranking-codes.edit', $rankingCode) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                Edit
            </a>
            <a href="{{ route('admin.ranking-codes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">{{ $rankingCode->ranking_id }}</h2>
            <p class="mt-1 text-sm text-gray-500">Created {{ $rankingCode->created_at->diffForHumans() }}</p>
        </div>
        
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Employee Name</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $rankingCode->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Position</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $rankingCode->position_name }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Group</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $rankingCode->group_name }} ({{ $rankingCode->group_code }})</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Branch Code</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $rankingCode->branch_code }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Guardian</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $rankingCode->guardian_name }} ({{ $rankingCode->guardian_code }})</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Position Code</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $rankingCode->position_code }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">ID Code</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $rankingCode->id_code }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Last Updated</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $rankingCode->updated_at->format('M d, Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <form action="{{ route('admin.ranking-codes.destroy', $rankingCode) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ranking code? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Delete Ranking Code
            </button>
        </form>
    </div>
</div>
@endsection
