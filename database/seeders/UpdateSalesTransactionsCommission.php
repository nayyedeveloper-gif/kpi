<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SalesTransaction;
use Illuminate\Support\Facades\DB;

class UpdateSalesTransactionsCommission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update net_amount to match total_amount if not set
        DB::table('sales_transactions')
            ->whereNull('net_amount')
            ->orWhere('net_amount', 0)
            ->update([
                'net_amount' => DB::raw('total_amount'),
                'updated_at' => now()
            ]);

        // Update commission_amount based on commission_rate if not set
        DB::table('sales_transactions')
            ->where(function($query) {
                $query->whereNull('commission_amount')
                      ->orWhere('commission_amount', 0);
            })
            ->where('commission_rate', '>', 0)
            ->update([
                'commission_amount' => DB::raw('(quantity * unit_price) * (commission_rate / 100)'),
                'updated_at' => now()
            ]);

        // For records with no commission_rate, set a default 5% commission
        DB::table('sales_transactions')
            ->where(function($query) {
                $query->whereNull('commission_amount')
                      ->orWhere('commission_amount', 0);
            })
            ->where(function($query) {
                $query->whereNull('commission_rate')
                      ->orWhere('commission_rate', 0);
            })
            ->update([
                'commission_rate' => 5,
                'commission_amount' => DB::raw('(quantity * unit_price) * 0.05'),
                'updated_at' => now()
            ]);
    }
}