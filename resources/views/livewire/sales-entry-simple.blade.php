<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Sales Entry - Simple Test</h1>
    
    <!-- Debug Info -->
    <div class="mb-4 p-4 bg-yellow-100 rounded">
        <p><strong>Component Status:</strong> Loaded</p>
        <p><strong>Modal State:</strong> {{ $showModal ? 'OPEN' : 'CLOSED' }}</p>
        <p><strong>Livewire Version:</strong> 3.x</p>
    </div>

    <!-- Test Buttons -->
    <div class="mb-4 space-x-2">
        <button wire:click="openCreateModal" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Open Modal (wire:click)
        </button>
        
        <button onclick="alert('JavaScript works!')" class="px-4 py-2 bg-green-600 text-white rounded">
            Test JavaScript
        </button>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-xl max-w-md">
            <h2 class="text-xl font-bold mb-4">Modal Works!</h2>
            <p class="mb-4">If you see this, Livewire is working correctly.</p>
            <button wire:click="closeModal" class="px-4 py-2 bg-red-600 text-white rounded">
                Close Modal
            </button>
        </div>
    </div>
    @endif

    <!-- Transactions Table -->
    <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                <tr>
                    <td class="px-6 py-4">{{ $transaction->id }}</td>
                    <td class="px-6 py-4">{{ $transaction->customer_name }}</td>
                    <td class="px-6 py-4">
                        <button wire:click="openEditModal({{ $transaction->id }})" class="text-blue-600 hover:underline">
                            Edit
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No transactions</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
