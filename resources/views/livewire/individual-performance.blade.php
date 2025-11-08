<div class="p-6">
    {{-- The Master doesn't talk, he acts. --}}
    <div class="mb-6">
        <a href="{{ route('sales.performance') }}" class="text-indigo-600 hover:text-indigo-900 mb-4 inline-block">← Back to Leaderboard</a>
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-20 h-20 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                    {{ strtoupper(substr($salesPerson->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $salesPerson->name }}</h1>
                    <p class="text-gray-600">{{ $salesPerson->email }}</p>
                    <p class="text-sm text-gray-500">{{ $salesPerson->position?->name }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Period</p>
                <p class="font-bold">{{ \Carbon\Carbon::parse($periodStart)->format('M d') }} - {{ \Carbon\Carbon::parse($periodEnd)->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_revenue'], 0) }}</h3>
            <p class="text-xs text-gray-500 mt-1">MMK</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Items Sold</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_quantity']) }}</h3>
            <p class="text-xs text-gray-500 mt-1">Products</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Transactions</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_transactions']) }}</h3>
            <p class="text-xs text-gray-500 mt-1">Sales</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Total Earnings</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_commission'] + $summary['total_bonus'], 0) }}</h3>
            <p class="text-xs text-gray-500 mt-1">Commission + Bonus (MMK)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">Earnings Breakdown</h2>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Commission</p>
                        <p class="text-xs text-gray-500">From sales transactions</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-gray-900">{{ number_format($summary['total_commission'], 0) }}</p>
                        <p class="text-xs text-gray-500">MMK</p>
                    </div>
                </div>
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Bonuses</p>
                        <p class="text-xs text-gray-500">{{ $summary['bonus_count'] }} awards</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-gray-900">{{ number_format($summary['total_bonus'], 0) }}</p>
                        <p class="text-xs text-gray-500">MMK</p>
                    </div>
                </div>
                <div class="flex justify-between items-center p-4 bg-indigo-50 rounded-lg border-2 border-indigo-200">
                    <div>
                        <p class="text-sm font-bold text-gray-900">Total Earnings</p>
                        <p class="text-xs text-gray-500">Commission + Bonuses</p>
                    </div>
                    <p class="text-3xl font-bold text-indigo-600">{{ number_format($summary['total_commission'] + $summary['total_bonus'], 2) }} MMK</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Performance Metrics</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">Average Transaction</span>
                        <span class="text-sm font-bold">{{ number_format($summary['avg_transaction'], 2) }} MMK</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(($summary['avg_transaction'] / 1000000) * 100, 100) }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">Revenue Target</span>
                        <span class="text-sm font-bold">{{ number_format(($summary['total_revenue'] / 10000000) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ min(($summary['total_revenue'] / 10000000) * 100, 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Target: 10,000,000.00 MMK</p>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">Items Target</span>
                        <span class="text-sm font-bold">{{ number_format(($summary['total_quantity'] / 100) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ min(($summary['total_quantity'] / 100) * 100, 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Target: 100 items</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Bonus Awards ({{ $bonusAwards->count() }})</h2>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($bonusAwards as $award)
                <div class="p-4 border rounded-lg hover:bg-gray-50">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-bold text-gray-900">{{ number_format($award->bonus_amount, 2) }} MMK</p>
                            <p class="text-xs text-gray-500">{{ $award->awarded_at?->format('M d, Y') }}</p>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $award->status === 'paid' ? 'bg-green-100 text-green-800' : ($award->status === 'approved' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ ucfirst($award->status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">{{ $award->reason }}</p>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No bonus awards yet</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Recent Transactions ({{ $transactions->count() }})</h2>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($transactions as $transaction)
                <div class="p-4 border rounded-lg hover:bg-gray-50">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-bold text-gray-900">{{ $transaction->item_name }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->customer_name }} • {{ $transaction->sale_date->format('M d, Y') }}</p>
                        </div>
                        <p class="font-bold text-emerald-600">{{ number_format($transaction->total_amount, 2) }} MMK</p>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Qty: {{ $transaction->quantity }}</span>
                        <span>Commission: {{ number_format($transaction->commission_amount, 2) }} MMK</span>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No transactions yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
