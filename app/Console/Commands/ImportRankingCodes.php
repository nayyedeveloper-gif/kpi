<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RankingCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ImportRankingCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ranking-codes:import {file} {--skip-validation : Skip validation for faster import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import ranking codes from CSV file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        
        // Check if file exists
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }
        
        // Check if file is readable
        if (!is_readable($filePath)) {
            $this->error("File is not readable: {$filePath}");
            return 1;
        }
        
        $this->info("Importing ranking codes from: {$filePath}");
        
        // Open the file
        $handle = fopen($filePath, 'r');
        
        if ($handle === false) {
            $this->error("Cannot open file: {$filePath}");
            return 1;
        }
        
        // Read the first row to get headers
        $header = fgetcsv($handle);
        
        // Check if file is empty or has no headers
        if ($header === false || count($header) < 8) {
            fclose($handle);
            $this->error("CSV file is empty or has no headers");
            return 1;
        }
        
        // Trim all header values and remove BOM if present
        $header = array_map(function($value) {
            return trim(preg_replace('/\x{FEFF}/u', '', $value));
        }, $header);
        
        $requiredFields = [
            'group_name', 'position_name', 'guardian_name', 
            'guardian_code', 'branch_code', 'group_code', 
            'position_code', 'id_code'
        ];
        
        // Map CSV headers to expected field names
        $headerMap = [
            'Group Name' => 'group_name',
            'Position Name' => 'position_name',
            'Name' => 'name',
            'Guardian Name' => 'guardian_name',
            'Guardian Code' => 'guardian_code',
            'Branch Code' => 'branch_code',
            'Group Code' => 'group_code',
            'Position Code' => 'position_code',
            'ID Code' => 'id_code',
            'Ranking ID' => 'ranking_id'
        ];
        
        // Map actual headers to expected field names
        $mappedHeader = array_map(function($header) use ($headerMap) {
            return $headerMap[$header] ?? $header;
        }, $header);
        
        // Check for missing required columns using mapped headers
        $mappedRequiredFields = array_map(function($field) use ($headerMap) {
            // Find the CSV header that maps to this required field
            $csvHeader = array_search($field, $headerMap);
            return $csvHeader ?: $field;
        }, $requiredFields);
        
        $missingColumns = array_diff($mappedRequiredFields, $header);
        if (!empty($missingColumns)) {
            fclose($handle);
            $this->error("Missing required columns: " . implode(', ', $missingColumns));
            return 1;
        }
        
        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];
        $line = 1; // Start from line 1 (header is line 1)
        
        $skipValidation = $this->option('skip-validation');
        
        // Start database transaction
        DB::beginTransaction();
        
        try {
            $progressBar = $this->output->createProgressBar();
            $progressBar->start();
            
            // Read each row of the CSV file
            while (($row = fgetcsv($handle)) !== false) {
                $line++;
                $progressBar->advance();
                
                // Skip empty rows
                if (count(array_filter($row)) === 0) {
                    $skipped++;
                    continue;
                }
                
                // Ensure row has same number of columns as header
                if (count($row) !== count($header)) {
                    $errors[] = "Line {$line}: Column count mismatch";
                    $skipped++;
                    continue;
                }
                
                // Combine header with row values and trim
                $data = [];
                foreach ($header as $index => $key) {
                    $mappedKey = $mappedHeader[$index] ?? $key;
                    $data[$mappedKey] = isset($row[$index]) ? trim($row[$index]) : '';
                }
                
                // Skip if all required fields are empty
                $allEmpty = true;
                foreach ($requiredFields as $field) {
                    if (!empty($data[$field])) {
                        $allEmpty = false;
                        break;
                    }
                }
                
                if ($allEmpty) {
                    $skipped++;
                    continue;
                }
                
                // Prepare data with proper types
                $importData = [
                    'group_name' => $data['group_name'] ?? '',
                    'position_name' => $data['position_name'] ?? '',
                    'name' => $data['name'] ?? null,
                    'guardian_name' => $data['guardian_name'] ?? '',
                    'guardian_code' => strtoupper(trim($data['guardian_code'] ?? '')),
                    'branch_code' => (int)$data['branch_code'],
                    'group_code' => strtoupper(trim($data['group_code'] ?? '')),
                    'position_code' => strtoupper(trim($data['position_code'] ?? '')),
                    'id_code' => (int)$data['id_code'],
                ];
                
                // Generate ranking_id if not provided or empty
                if (empty($data['ranking_id'])) {
                    $importData['ranking_id'] = $this->generateRankingId($importData);
                } else {
                    $importData['ranking_id'] = trim($data['ranking_id']);
                }
                
                // Validate the data unless skipped
                if (!$skipValidation) {
                    $rules = RankingCode::rules();
                    // Remove the unique constraint for ranking_id during import since we're using updateOrCreate
                    $rules['ranking_id'] = 'required|string|max:50';
                    $validator = Validator::make($importData, $rules, RankingCode::validationMessages());
                    
                    if ($validator->fails()) {
                        $errors[] = "Line {$line}: " . implode(' ', $validator->errors()->all());
                        $skipped++;
                        continue;
                    }
                }
                
                // Update or create the record
                $result = RankingCode::updateOrCreate(
                    ['ranking_id' => $importData['ranking_id']],
                    $importData
                );
                
                $result->wasRecentlyCreated ? $imported++ : $updated++;
                
                // Clear the model cache for the next iteration
                $result = null;
            }
            
            $progressBar->finish();
            $this->line('');
            
            // Commit the transaction
            DB::commit();
            
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            fclose($handle);
            $this->error("Import error: " . $e->getMessage());
            return 1;
        }
        
        fclose($handle);
        
        // Show results
        $this->info("Import completed successfully!");
        $this->line("Imported: {$imported}");
        $this->line("Updated: {$updated}");
        $this->line("Skipped: {$skipped}");
        $this->line("Errors: " . count($errors));
        
        // Show errors if any
        if (count($errors) > 0) {
            $this->warn("The following errors occurred:");
            foreach ($errors as $error) {
                $this->line("  - {$error}");
            }
        }
        
        return 0;
    }
    
    /**
     * Generate ranking ID based on the pattern
     */
    protected function generateRankingId($data)
    {
        $branch = abs($data['branch_code']);
        $group = $data['group_code'];
        $position = $data['position_code'];
        $idCode = str_pad($data['id_code'], 3, '0', STR_PAD_LEFT);
        
        return "{$data['guardian_code']}-{$branch}{$group}{$position}{$idCode}";
    }
}