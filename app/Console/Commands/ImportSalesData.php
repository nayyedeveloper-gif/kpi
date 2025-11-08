<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SalesDataImportService;
use Illuminate\Support\Facades\Storage;

class ImportSalesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:import {file? : Path to the CSV file} {--truncate : Clear existing data before import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import sales data from CSV file';

    /**
     * The sales data import service.
     *
     * @var SalesDataImportService
     */
    protected $importService;

    /**
     * Create a new command instance.
     *
     * @param SalesDataImportService $importService
     * @return void
     */
    public function __construct(SalesDataImportService $importService)
    {
        parent::__construct();
        $this->importService = $importService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filePath = $this->argument('file') ?: storage_path('app/sales-master-data.csv');
        
        // Check if file exists
        if (!file_exists($filePath)) {
            $this->error("The file does not exist at path: {$filePath}");
            return 1;
        }

        $this->info("Starting import from: {$filePath}");
        
        try {
            // Clear existing data if the --truncate option is set
            if ($this->option('truncate')) {
                $this->info('Clearing existing data...');
                \App\Models\SalesData::truncate();
            }
            
            // Import the data
            $result = $this->importService->importFromCsv($filePath);
            
            if ($result['success']) {
                $this->info('Import completed successfully!');
                $this->info("Imported {$result['imported']} records.");
                return 0;
            } else {
                $this->error('Import failed!');
                $this->error($result['message']);
                $this->info("Successfully imported {$result['imported']} records before the error occurred.");
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('An error occurred during import:');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
