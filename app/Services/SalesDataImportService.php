<?php

namespace App\Services;

use App\Models\SalesData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SalesDataImportService
{
    protected $numericFields = [
        'density', 'weight', 'quantity', 'g_price', 'g_gross_amount',
        'm_price', 'm_gross_amount', 'dis', 'promotion_dis', 'special_dis',
        'dis_net_amount', 'promotion_net_amount', 'total_net_amount', 'tax'
    ];

    public function importFromCsv($filePath)
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \RuntimeException("CSV file not found or not readable: " . $filePath);
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Failed to open CSV file: " . $filePath);
        }

        // Read and validate header
        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            throw new \RuntimeException("Empty or invalid CSV file");
        }

        // Clean and normalize header names
        $header = array_map(function($item) {
            // Remove BOM if present
            $item = preg_replace('/\x{FEFF}/u', '', $item);
            // Convert to lowercase and replace spaces and special characters with underscores
            return strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '_', $item)));
        }, $header);

        // Ensure required fields exist
        $requiredFields = ['year', 'month', 'invoiced_date', 'voucher_number', 'branch', 'customer_name', 
                          'item_categories', 'item_group', 'item_name', 'weight', 'unit', 'quantity', 
                          'm_price', 'm_gross_amount', 'total_net_amount'];
        
        $missingFields = array_diff($requiredFields, $header);
        if (!empty($missingFields)) {
            fclose($handle);
            throw new \RuntimeException("Missing required fields in CSV: " . implode(', ', $missingFields));
        }

        $importedCount = 0;
        $rowNumber = 1; // Start from 1 because of header

        // Start a database transaction
        \DB::beginTransaction();

        try {
            // Clear existing data if needed
            // SalesData::truncate(); // Uncomment if you want to clear existing data

            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                
                // Skip empty rows
                if (count(array_filter($row, 'strlen')) === 0) {
                    continue;
                }

                // Combine row with header
                $data = array_combine($header, $row);
                
                // Clean and format the data
                $formattedData = $this->formatData($data);
                
                // Validate the data
                $this->validateData($formattedData, $rowNumber);
                
                // Create the record
                SalesData::create($formattedData);
                $importedCount++;
            }

            \DB::commit();
            fclose($handle);
            
            return [
                'success' => true,
                'imported' => $importedCount,
                'message' => "Successfully imported {$importedCount} records"
            ];
            
        } catch (\Exception $e) {
            \DB::rollBack();
            fclose($handle);
            
            return [
                'success' => false,
                'message' => "Error on row {$rowNumber}: " . $e->getMessage(),
                'imported' => $importedCount
            ];
        }
    }

    protected function formatData(array $data)
    {
        $formatted = [];
        
        // Handle the 'Weight ' field with a space
        if (isset($data['Weight '])) {
            $data['weight'] = $data['Weight '];
            unset($data['Weight ']);
        }
        
        foreach ($data as $key => $value) {
            $key = trim($key);
            $value = trim($value);
            
            // Skip empty values
            if ($value === '') {
                continue;
            }
            
            // Format numeric fields
            if (in_array($key, $this->numericFields)) {
                $value = str_replace([',', ' '], '', $value);
                $formatted[$key] = is_numeric($value) ? (float)$value : null;
            } 
            // Format date fields
            elseif ($key === 'invoiced_date') {
                try {
                    $formatted[$key] = Carbon::parse($value)->format('Y-m-d');
                } catch (\Exception $e) {
                    $formatted[$key] = null;
                }
            }
            // For other fields
            else {
                $formatted[$key] = $value;
            }
        }
        
        return $formatted;
    }
    
    protected function validateData(array $data, $rowNumber)
    {
        $rules = [
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|string|max:20',
            'invoiced_date' => 'required|date',
            'voucher_number' => 'required|string|max:50',
            'branch' => 'required|string|max:100',
            'customer_name' => 'required|string|max:255',
            'item_categories' => 'required|string|max:100',
            'item_name' => 'required|string|max:255',
            'density' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'unit' => 'required|string|max:10',
            'quantity' => 'required|integer|min:1',
            'total_net_amount' => 'required|numeric|min:0',
        ];
        
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            throw new \RuntimeException(implode(' ', $errors));
        }
    }
}
