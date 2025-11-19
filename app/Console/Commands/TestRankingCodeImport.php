<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RankingCode;

class TestRankingCodeImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ranking-code:test-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test ranking code import functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing ranking code import...');
        
        // Test data
        $testData = [
            'group_name' => 'A',
            'position_name' => 'Test Position',
            'name' => 'Test User',
            'guardian_name' => 'Test Guardian',
            'guardian_code' => 'T',
            'branch_code' => 999,
            'group_code' => 'A',
            'position_code' => 'TP',
            'id_code' => 999,
        ];
        
        try {
            // Generate ranking_id
            $testData['ranking_id'] = RankingCode::generateRankingId((object)$testData);
            
            // Validate the data
            $validator = \Illuminate\Support\Facades\Validator::make($testData, RankingCode::rules(), RankingCode::validationMessages());
            
            if ($validator->fails()) {
                $this->error('Validation failed:');
                foreach ($validator->errors()->all() as $error) {
                    $this->error('  - ' . $error);
                }
                return 1;
            }
            
            // Create the ranking code
            $rankingCode = new RankingCode($testData);
            $rankingCode->save();
            
            $this->info('Test ranking code created successfully with ID: ' . $rankingCode->ranking_id);
            
            // Clean up
            $rankingCode->delete();
            $this->info('Test ranking code deleted.');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}