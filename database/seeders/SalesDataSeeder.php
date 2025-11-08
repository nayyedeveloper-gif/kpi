<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SalesTransaction;
use App\Models\BonusTier;
use App\Models\User;
use Carbon\Carbon;

class SalesDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Bonus Tiers
        $this->createBonusTiers();
        
        // Create Sample Sales Transactions
        $this->createSalesTransactions();
    }

    private function createBonusTiers()
    {
        $tiers = [
            // Revenue Tiers
            [
                'name' => 'Bronze Tier',
                'type' => 'revenue',
                'threshold' => 1000000,
                'bonus_amount' => 50000,
                'bonus_percentage' => 0,
                'calculation_method' => 'fixed',
                'priority' => 3,
                'is_active' => true,
                'description' => 'Entry level revenue bonus',
            ],
            [
                'name' => 'Silver Tier',
                'type' => 'revenue',
                'threshold' => 2000000,
                'bonus_amount' => 150000,
                'bonus_percentage' => 0,
                'calculation_method' => 'fixed',
                'priority' => 2,
                'is_active' => true,
                'description' => 'Mid-level revenue bonus',
            ],
            [
                'name' => 'Gold Tier',
                'type' => 'revenue',
                'threshold' => 5000000,
                'bonus_amount' => 500000,
                'bonus_percentage' => 0,
                'calculation_method' => 'fixed',
                'priority' => 1,
                'is_active' => true,
                'description' => 'High-level revenue bonus',
            ],
            [
                'name' => 'Platinum Tier',
                'type' => 'revenue',
                'threshold' => 10000000,
                'bonus_amount' => 1200000,
                'bonus_percentage' => 0,
                'calculation_method' => 'fixed',
                'priority' => 0,
                'is_active' => true,
                'description' => 'Top achiever revenue bonus',
            ],
            
            // Quantity Tiers
            [
                'name' => 'Quantity Starter',
                'type' => 'quantity',
                'threshold' => 50,
                'bonus_amount' => 100000,
                'bonus_percentage' => 0,
                'calculation_method' => 'fixed',
                'priority' => 2,
                'is_active' => true,
                'description' => '50+ items sold',
            ],
            [
                'name' => 'Quantity Pro',
                'type' => 'quantity',
                'threshold' => 100,
                'bonus_amount' => 250000,
                'bonus_percentage' => 0,
                'calculation_method' => 'fixed',
                'priority' => 1,
                'is_active' => true,
                'description' => '100+ items sold',
            ],
            [
                'name' => 'Quantity Master',
                'type' => 'quantity',
                'threshold' => 200,
                'bonus_amount' => 600000,
                'bonus_percentage' => 0,
                'calculation_method' => 'fixed',
                'priority' => 0,
                'is_active' => true,
                'description' => '200+ items sold',
            ],
            
            // Commission Rate
            [
                'name' => 'Base Commission',
                'type' => 'commission',
                'threshold' => 0,
                'bonus_amount' => 0,
                'bonus_percentage' => 3,
                'calculation_method' => 'percentage',
                'priority' => 0,
                'is_active' => true,
                'description' => '3% commission on all sales',
            ],
        ];

        foreach ($tiers as $tier) {
            BonusTier::create($tier);
        }

        $this->command->info('✅ Created ' . count($tiers) . ' bonus tiers');
    }

    private function createSalesTransactions()
    {
        $users = User::active()->get();
        
        if ($users->isEmpty()) {
            $this->command->warn('⚠️ No active users found. Please create users first.');
            return;
        }

        $customers = [
            ['name' => 'Aung Aung', 'phone' => '09-123-456-789'],
            ['name' => 'Zaw Zaw', 'phone' => '09-234-567-890'],
            ['name' => 'Kyaw Kyaw', 'phone' => '09-345-678-901'],
            ['name' => 'Hla Hla', 'phone' => '09-456-789-012'],
            ['name' => 'Mya Mya', 'phone' => '09-567-890-123'],
            ['name' => 'Nwe Nwe', 'phone' => '09-678-901-234'],
            ['name' => 'Thida', 'phone' => '09-789-012-345'],
            ['name' => 'Soe Soe', 'phone' => '09-890-123-456'],
        ];

        $items = [
            ['name' => 'Laptop Dell XPS 15', 'category' => 'Electronics', 'price' => 2500000],
            ['name' => 'iPhone 15 Pro', 'category' => 'Mobile', 'price' => 1800000],
            ['name' => 'Samsung Galaxy S24', 'category' => 'Mobile', 'price' => 1500000],
            ['name' => 'iPad Air', 'category' => 'Tablet', 'price' => 900000],
            ['name' => 'MacBook Air M2', 'category' => 'Electronics', 'price' => 3200000],
            ['name' => 'Sony Headphones', 'category' => 'Audio', 'price' => 450000],
            ['name' => 'Canon Camera', 'category' => 'Camera', 'price' => 1200000],
            ['name' => 'Smart Watch', 'category' => 'Wearable', 'price' => 350000],
            ['name' => 'Gaming Mouse', 'category' => 'Accessories', 'price' => 80000],
            ['name' => 'Mechanical Keyboard', 'category' => 'Accessories', 'price' => 150000],
        ];

        $count = 0;
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        // Create 100 sample transactions
        for ($i = 0; $i < 100; $i++) {
            $user = $users->random();
            $customer = $customers[array_rand($customers)];
            $item = $items[array_rand($items)];
            $quantity = rand(1, 5);
            $saleDate = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp)
            );

            SalesTransaction::create([
                'sales_person_id' => $user->id,
                'customer_name' => $customer['name'],
                'customer_phone' => $customer['phone'],
                'item_name' => $item['name'],
                'item_category' => $item['category'],
                'quantity' => $quantity,
                'unit_price' => $item['price'],
                'commission_rate' => rand(2, 5),
                'sale_date' => $saleDate,
                'notes' => $i % 3 == 0 ? 'Regular customer' : null,
            ]);

            $count++;
        }

        $this->command->info('✅ Created ' . $count . ' sample sales transactions');
    }
}
