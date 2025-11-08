<?php

namespace App\Http\Controllers;

use App\Models\SalesData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Statement;

class SalesImportController extends Controller
{
    public function index()
    {
        $sales = SalesData::orderBy('invoiced_date', 'desc')
            ->paginate(50);
            
        $branches = SalesData::select('branch')->distinct()->pluck('branch');
        $categories = SalesData::select('item_categories')->distinct()->pluck('item_categories');
        
        return view('sales.index', compact('sales', 'branches', 'categories'));
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('csv_file');
        
        try {
            // Read the entire file to handle line breaks in headers
            
            // Remove BOM if it exists
            $bom = pack('H*','EFBBBF');
            $content = preg_replace("/^$bom/", '', $content);
            
            // Normalize line endings and remove any empty lines
            $content = preg_replace('/\r\n|\r/', "\n", $content);
            $lines = explode("\n", $content);
            
            // Remove empty lines
            $lines = array_filter($lines, function($line) {
                return trim($line) !== '';
            });
            
            // Get the header row (first non-empty line)
            $header = str_getcsv(array_shift($lines), ',', '"', '\\');
            
            // Clean up the header - remove BOM, trim whitespace, and normalize
            $header = array_map(function($item) {
                // Remove BOM and other non-printable characters
                $item = preg_replace('/[\x00-\x1F\x7F\xA0\x{FEFF}]/u', '', $item);
                // Trim whitespace and quotes
                return trim($item, " \t\n\r\0\x0B\"'`");
            }, $header);
            
            // Process the remaining rows
            $records = [];
            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                
                // Parse the CSV line
                $row = str_getcsv($line, ',', '"', '\\');
                
                // Skip if the row doesn't have the same number of columns as the header
                if (count($row) !== count($header)) {
                    continue;
                }
                
                // Combine with header and clean up values
                $record = [];
                foreach ($header as $index => $key) {
                    if (isset($row[$index])) {
                        $record[$key] = trim($row[$index], " \t\n\r\0\x0B\"'`");
                    } else {
                        $record[$key] = null;
                    }
                }
                
                // Skip empty rows
                if (!empty(array_filter($record, function($value) { 
                    return $value !== null && $value !== ''; 
                }))) {
                    $records[] = $record;
                }
            }
            
            DB::beginTransaction();
            
            $importedCount = 0;
            $skippedCount = 0;
            
            foreach ($records as $record) {
                try {
                    // Clean the record data
                    $cleanedRecord = [];
                    foreach ($record as $key => $value) {
                        $cleanedRecord[trim($key)] = is_string($value) ? trim($value) : $value;
                    }
                    
                    // Map the CSV columns to database columns
                    $saleData = $this->mapCsvToModel($cleanedRecord);
                    
                    // Skip if required fields are missing
                    if (empty($saleData['voucher_number']) || empty($saleData['invoiced_date'])) {
                        $skippedCount++;
                        continue;
                    }
                    
                    // Create or update the record
                    SalesData::updateOrCreate(
                        ['voucher_number' => $saleData['voucher_number']],
                        $saleData
                    );
                    
                    $importedCount++;
                    
                } catch (\Exception $e) {
                    // Log the error but continue with other records
                    \Log::error('Error importing record: ' . $e->getMessage(), [
                        'record' => $record,
                        'exception' => $e
                    ]);
                    $skippedCount++;
                }
            }
            
            DB::commit();
            
            $message = "Successfully imported {$importedCount} records.";
            if ($skippedCount > 0) {
                $message .= " {$skippedCount} records were skipped due to errors or missing data.";
            }
            
            return redirect()->route('sales.import.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('CSV Import Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error importing CSV: ' . $e->getMessage())
                        ->with('error_details', 'File: ' . $e->getFile() . ', Line: ' . $e->getLine());
        }
    }
    
    public function create()
    {
        $branches = SalesData::select('branch')->distinct()->orderBy('branch')->pluck('branch');
        $categories = SalesData::select('item_categories')->distinct()->orderBy('item_categories')->pluck('item_categories');
        $itemGroups = SalesData::select('item_group')->distinct()->orderBy('item_group')->pluck('item_group');
        $salePersons = SalesData::select('sale_person')->distinct()->orderBy('sale_person')->pluck('sale_person');
        
        return view('sales.create', compact('branches', 'categories', 'itemGroups', 'salePersons'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voucher_number' => 'required|string|max:50',
            'invoiced_date' => 'required|date',
            'branch' => 'required|string|max:100',
            'customer_name' => 'required|string|max:255',
            'customer_status' => 'required|string|max:50',
            'contact_number' => 'nullable|string|max:50',
            'item_categories' => 'required|string|max:100',
            'item_group' => 'required|string|max:100',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0',
            'unit' => 'required|string|max:10',
            'm_price' => 'required|numeric|min:0',
            'm_gross_amount' => 'required|numeric|min:0',
            'sale_person' => 'required|string|max:100',
        ]);
        
        // Set year and month from invoiced_date
        $date = new \DateTime($validated['invoiced_date']);
        $validated['year'] = $date->format('Y');
        $validated['month'] = $date->format('F');
        
        // Create the sales record
        $sale = SalesData::create($validated);
        
        return redirect()->route('sales.data.show', $sale->id)
            ->with('success', 'Sales record created successfully.');
    }
    
    public function show($id)
    {
        $sale = SalesData::findOrFail($id);
        return view('sales.show', compact('sale'));
    }
    
    public function edit($id)
    {
        $sale = SalesData::findOrFail($id);
        $branches = SalesData::select('branch')->distinct()->orderBy('branch')->pluck('branch');
        $categories = SalesData::select('item_categories')->distinct()->orderBy('item_categories')->pluck('item_categories');
        $itemGroups = SalesData::select('item_group')->distinct()->orderBy('item_group')->pluck('item_group');
        $salePersons = SalesData::select('sale_person')->distinct()->orderBy('sale_person')->pluck('sale_person');
        
        return view('sales.edit', compact('sale', 'branches', 'categories', 'itemGroups', 'salePersons'));
    }
    
    public function update(Request $request, $id)
    {
        $sale = SalesData::findOrFail($id);
        
        $validated = $request->validate([
            'voucher_number' => 'required|string|max:50',
            'invoiced_date' => 'required|date',
            'branch' => 'required|string|max:100',
            'customer_name' => 'required|string|max:255',
            'customer_status' => 'required|string|max:50',
            'contact_number' => 'nullable|string|max:50',
            'item_categories' => 'required|string|max:100',
            'item_group' => 'required|string|max:100',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0',
            'unit' => 'required|string|max:10',
            'm_price' => 'required|numeric|min:0',
            'm_gross_amount' => 'required|numeric|min:0',
            'sale_person' => 'required|string|max:100',
        ]);
        
        // Set year and month from invoiced_date
        $date = new \DateTime($validated['invoiced_date']);
        $validated['year'] = $date->format('Y');
        $validated['month'] = $date->format('F');
        
        $sale->update($validated);
        
        return redirect()->route('sales.data.show', $sale->id)
            ->with('success', 'Sales record updated successfully.');
    }
    
    public function destroy($id)
    {
        $sale = SalesData::findOrFail($id);
        $sale->delete();
    }

    protected function cleanNumber($value)
    {
        if (empty($value) || $value === '-' || $value === 'N/A') {
            return 0;
        }

        if (is_numeric($value)) {
            return (float)$value;
        }

        // Remove any non-numeric characters except decimal point and minus sign
        $cleaned = preg_replace('/[^0-9.-]/', '', $value);
        
        // Handle cases where there are multiple decimal points
        if (substr_count($cleaned, '.') > 1) {
            $parts = explode('.', $cleaned);
            $cleaned = $parts[0] . '.' . implode('', array_slice($parts, 1));
        }

        return (float)$cleaned;
    }
    
    /**
     * Map CSV data to the database model fields
     *
     * @param array $csvData
     * @return array
     */
    protected function mapCsvToModel(array $csvData)
    {
        // Normalize the CSV data keys (case-insensitive and trim spaces)
        $normalizedData = [];
        foreach ($csvData as $key => $value) {
            $normalizedKey = strtolower(trim($key));
            $normalizedData[$normalizedKey] = $value;
        }
        
        // Helper function to get value with case-insensitive key matching
        $getValue = function($keys, $default = null) use ($normalizedData) {
            if (!is_array($keys)) {
                $keys = [$keys];
            }
            
            foreach ($keys as $key) {
                $key = strtolower(trim($key));
                if (isset($normalizedData[$key])) {
                    return $normalizedData[$key];
                }
            }
            return $default;
        };
        
        // Parse date if it exists
        $invoicedDate = $getValue(['invoiced date', 'date']);
        if ($invoicedDate) {
            try {
                $invoicedDate = \Carbon\Carbon::parse($invoicedDate)->format('Y-m-d');
            } catch (\Exception $e) {
                $invoicedDate = null;
            }
        }
        
        // Map CSV columns to database columns with fallbacks for common variations
        return [
            'year' => $getValue('year'),
            'month' => $getValue('month'),
            'invoiced_date' => $invoicedDate,
            'voucher_number' => $getValue(['voucher number', 'voucher_no', 'invoice_no']),
            'branch' => $getValue('branch'),
            'customer_name' => $getValue(['customer name', 'customer']),
            'customer_status' => $getValue(['customer status', 'status']),
            'contact_number' => $getValue(['contact number', 'phone', 'mobile']),
            'contact_address' => $getValue(['contact address', 'address']),
            'township' => $getValue('township'),
            'division' => $getValue('division'),
            'customer_nrc_number' => $getValue(['customer nrc number', 'nrc', 'nrc_no']),
            'item_categories' => $getValue(['item categories', 'category', 'item_categories']),
            'item_group' => $getValue(['item group', 'group']),
            'item_name' => $getValue(['item name', 'product', 'item']),
            'density' => $this->cleanNumber($getValue('density')),
            'weight' => $this->cleanNumber($getValue('weight')),
            'unit' => strtoupper(trim($getValue('unit', 'P'))), // Default to 'P' for pieces
            'quantity' => (int)$this->cleanNumber($getValue('quantity', 1)),
            'g_price' => $this->cleanNumber($getValue(['g price', 'gold_price'])),
            'g_gross_amount' => $this->cleanNumber($getValue(['g gross amount', 'gold_amount'])),
            'unit_price' => $this->cleanNumber($getValue(['m price', 'price', 'unit_price'])),
            'm_gross_amount' => $this->cleanNumber($getValue(['m gross amount', 'gross_amount'])),
            'discount' => $this->cleanNumber($getValue(['dis', 'discount'])),
            'promotion_discount' => $this->cleanNumber($getValue(['promotion dis', 'promotion_discount'])),
            'special_discount' => $this->cleanNumber($getValue(['special dis', 'special_discount'])),
            'discount_net_amount' => $this->cleanNumber($getValue(['dis net amount', 'discount_amount'])),
            'promotion_net_amount' => $this->cleanNumber($getValue(['promotion net amount', 'promotion_amount'])),
            'total_net_amount' => $this->cleanNumber($getValue(['total net amount', 'net_amount', 'amount'])),
            'tax' => $this->cleanNumber($getValue('tax')),
            'sale_person' => $getValue(['sale person', 'sales_person', 'salesman']),
            'remark' => $getValue(['remark', 'notes', 'comment']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
