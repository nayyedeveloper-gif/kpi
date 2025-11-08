<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SalesData;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportSalesCsvFixed extends Command
{
    protected $signature = 'sales:import:fixed {file} {--truncate}';
    protected $description = 'Import sales data from CSV file with fixed mapping';

    public function handle()
    {
        $file = $this->argument('file');
        
        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $handle = fopen($file, 'r');
        if ($handle === false) {
            $this->error("Failed to open file: {$file}");
            return 1;
        }

        // Clear existing data if --truncate option is set
        if ($this->option('truncate')) {
            $this->info('Truncating existing sales data...');
            DB::table('sales_data')->truncate();
        }

        // Skip header
        $header = fgetcsv($handle);
        if ($header === false) {
            $this->error('Empty or invalid CSV file');
            return 1;
        }

        $count = 0;
        $batchSize = 1000;
        $dataToInsert = [];

        $this->info('Starting import...');
        $bar = $this->output->createProgressBar();

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 30) {
                $this->warn("Skipping invalid row: " . implode(',', $row));
                continue;
            }

            try {
                // Parse the date from format 'd-M' and assume current year
                $dateParts = explode('-', trim($row[2]));
                $day = (int)$dateParts[0];
                $month = date('m', strtotime($dateParts[1]));
                $year = date('Y');
                $invoicedDate = Carbon::createFromDate($year, $month, $day);
                
                $data = [
                    'year' => $invoicedDate->year,
                    'month' => $invoicedDate->format('F'),
                    'invoiced_date' => $invoicedDate->format('Y-m-d'),
                    'voucher_number' => trim($row[3]),
                    'branch' => trim($row[4]),
                    'customer_name' => trim($row[5]),
                    'customer_status' => trim($row[6]) ?: 'Unknown',
                    'contact_number' => trim($row[7]) ?: null,
                    'contact_address' => trim($row[8]) ?: null,
                    'township' => trim($row[9]) ?: null,
                    'division' => trim($row[10]) ?: 'Unknown',
                    'customer_nrc_number' => trim($row[11]) ?: null,
                    'item_categories' => trim($row[12]) ?: 'Unknown',
                    'item_group' => trim($row[13]) ?: 'Unknown',
                    'item_name' => trim($row[14]) ?: 'Unknown',
                    'density' => $this->parseNumeric($row[15]),
                    'weight' => (float)trim($row[16]),
                    'unit' => trim($row[17]) ?: 'P',
                    'quantity' => (int)trim($row[18]),
                    'g_price' => $this->parseNumeric($row[19]),
                    'g_gross_amount' => $this->parseNumeric($row[20]),
                    'm_price' => $this->parseNumeric($row[21]),
                    'm_gross_amount' => $this->parseNumeric($row[22]),
                    'dis' => $this->parseNumeric($row[23]),
                    'promotion_dis' => $this->parseNumeric($row[24]),
                    'special_dis' => $this->parseNumeric($row[25]),
                    'dis_net_amount' => $this->parseNumeric($row[26]),
                    'promotion_net_amount' => $this->parseNumeric($row[27]),
                    'total_net_amount' => $this->parseNumeric($row[28]) ?: 0,
                    'tax' => $this->parseNumeric($row[29]),
                    'sale_person' => trim($row[30]) ?: 'Unknown',
                    'remark' => trim(($row[31] ?? '') . ' ' . ($row[32] ?? '') . ' ' . ($row[33] ?? '')),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $dataToInsert[] = $data;
                $count++;

                // Insert in batches
                if (count($dataToInsert) >= $batchSize) {
                    DB::table('sales_data')->insert($dataToInsert);
                    $bar->advance(count($dataToInsert));
                    $dataToInsert = [];
                }
            } catch (\Exception $e) {
                $this->warn("Error processing row: " . $e->getMessage());
                continue;
            }
        }

        // Insert remaining records
        if (!empty($dataToInsert)) {
            DB::table('sales_data')->insert($dataToInsert);
            $bar->advance(count($dataToInsert));
        }

        fclose($handle);
        $bar->finish();
        $this->newLine(2);
        $this->info("Successfully imported {$count} sales records.");
        
        return 0;
    }

    protected function parseNumeric($value)
    {
        if (empty($value) || trim($value) === '-' || strtolower(trim($value)) === 'null') {
            return null;
        }
        // Remove any non-numeric characters except decimal point and minus sign
        $value = preg_replace('/[^0-9.-]/', '', $value);
        return is_numeric($value) ? (float)$value : null;
    }
}
