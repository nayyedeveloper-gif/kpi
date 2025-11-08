<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Test Sales Component</h1>
    
    <div class="mb-4 p-4 bg-blue-100 rounded">
        <p class="font-bold">Status: {{ $message }}</p>
        <p>Modal State: {{ $showModal ? 'OPEN' : 'CLOSED' }}</p>
    </div>

    <button wire:click="openModal" class="px-4 py-2 bg-blue-600 text-white rounded">
        Open Modal (wire:click)
    </button>

    <button onclick="@this.call('openModal')" class="ml-2 px-4 py-2 bg-green-600 text-white rounded">
        Open Modal (@this.call)
    </button>

    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-xl">
            <h2 class="text-xl font-bold mb-4">Test Modal</h2>
            <p class="mb-4">Modal is working!</p>
            <button wire:click="closeModal" class="px-4 py-2 bg-red-600 text-white rounded">
                Close
            </button>
        </div>
    </div>
    @endif
</div>
