<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComprehensiveKpiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we have users and ranking codes, create if not
        $user = \App\Models\User::first();
        if (!$user) {
            $user = \App\Models\User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }

        $rankingCode = \App\Models\RankingCode::first();
        if (!$rankingCode) {
            $rankingCode = \App\Models\RankingCode::create([
                'name' => 'Test Ranking Code',
                'ranking_id' => 'TEST001',
                'position_name' => 'Test Position',
                'group_name' => 'Test Group',
                'guardian_name' => 'Test Guardian',
                'guardian_code' => 'A',
                'branch_code' => 1,
                'group_code' => 'TG',
                'position_code' => 'TP',
                'id_code' => 1,
            ]);
        }

        // Sample comprehensive KPI data
        $personalityKpis = [
            'hair' => ['label' => 'ဆံပင်', 'checked' => true],
            'shirt' => ['label' => 'အင်္ကျီ', 'checked' => true],
            'appearance' => ['label' => 'မျက်နှာ အသွင်အပြင်', 'checked' => false],
            'hygiene' => ['label' => 'အနံအသက်', 'checked' => true]
        ];

        $teamManagementKpis = [
            // Performance (CS)
            'performance_in_out_log' => ['label' => 'လူဝင်/ထွက်စာရင်း', 'category' => 'Performance (CS)', 'checked' => true],
            'performance_service_food' => ['label' => 'Service Food စာရင်း', 'category' => 'Performance (CS)', 'checked' => true],
            
            // Hospitality (CS)
            'hospitality_3s' => ['label' => '3S (Sweet,Smile,Smart)', 'category' => 'Hospitality (CS)', 'checked' => true],
            'hospitality_greeting' => ['label' => 'မင်္ဂလာနှုတ်ခွန်းဆက်စကား', 'category' => 'Hospitality (CS)', 'checked' => false],
            
            // Learning (CS)
            'learning_calculation' => ['label' => 'ရတနာတွက်ချက်နည်းများ', 'category' => 'Learning (CS)', 'checked' => true],
            'learning_forms' => ['label' => 'Form အသုံးပြုနည်းများ', 'category' => 'Learning (CS)', 'checked' => true],
            
            // Counter Check (SR)
            'counter_check_items' => ['label' => 'ဗန်း/မှန်/လက်အိတ်/မှန်ဘီလူး/လက်တိုင်းကွင်း/လက်တိုင်းတုတ်/Calculator/ဘောင်ချာစာအုပ်/ဘောပင်', 'category' => 'Counter Check (SR)', 'checked' => false],
            
            // Display (SR)
            'display_full_display' => ['label' => 'Display တုံးအပြည့်', 'category' => 'Display (SR)', 'checked' => true],
            'display_stock_knowledge' => ['label' => 'မိမိ၏ Stock အဝင်အထွက်ကို ၃ရက်စာ သိရန်။', 'category' => 'Display (SR)', 'checked' => true],
        ];

        $customerFollowUpKpis = [
            'follow_up_schedule' => ['label' => '1 Day, 1 Week, 1 Month', 'checked' => true],
        ];

        $supervisedLevelKpis = [
            'supervisor_marks' => ['label' => 'သက်ဆိုင်ရာ ကြီးကြပ်သူမှ ပေးသော အမှတ်', 'checked' => false],
        ];

        // Create sample KPI measurement
        \App\Models\KpiMeasurement::create([
            'user_id' => $user->id,
            'ranking_code_id' => $rankingCode->id,
            'measurement_date' => now()->format('Y-m-d'),
            'personality_score' => '3/4',
            'performance_score' => '8/21', // Total team management KPIs
            'hospitality_score' => '8/21', // Same as performance for now
            'customer_follow_up_score' => 10,
            'number_of_people' => 5,
            'supervised_level_score' => 5,
            'notes' => 'Sample comprehensive KPI data for testing',
            'personality_kpis' => $personalityKpis,
            'team_management_kpis' => $teamManagementKpis,
            'customer_follow_up_kpis' => $customerFollowUpKpis,
            'supervised_level_kpis' => $supervisedLevelKpis,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
    }
}
