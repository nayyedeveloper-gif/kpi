<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\SalesTransaction;
use App\Models\SalesPerson;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;

class SalesEntry extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $showInvoiceModal = false;
    public $editMode = false;
    public $transactionId;
    public $viewTransaction = null;
    
    // CSV Import
    public $showImportModal = false;
    public $csvFile;
    public $csvHeaders = [];
    public $csvData = [];
    public $csvMappings = [
        'sale_date' => '',
        'invoice_no' => '',
        'product_code' => '',
        'product_name' => '',
        'category' => '',
        'quantity' => '',
        'unit_price' => '',
        'total_amount' => '',
        'discount' => '',
        'net_amount' => '',
        'payment_method' => '',
        'branch' => '',
        'sales_person' => '',
        'customer_name' => '',
        'customer_phone' => '',
        'customer_address' => '',
        'customer_nrc' => ''
    ];
    public $importErrors = [];
    public $importSuccess = false;
    public $importedCount = 0;
    
    public $sales_person_id;
    public $product_id; // Selected product from products table
    public $product_code; // Product code for search
    
    // Customer Details
    public $customer_name;
    public $customer_phone;
    public $customer_address;
    public $customer_nrc;
    
    // Staff/Personnel
    public $goldsmith_name; // ပန်းထိမ်ဆရာ
    public $shop_number; // ဆိုင်အမှတ်
    public $cashier; // ငွေကိုင်
    public $color_manager; // အရောင်မန်နေဂျာ
    public $responsible_signature; // တာဝန်ခံလက်မှတ်
    
    // Product Details
    public $item_name;
    public $item_category;
    public $gold_quality;
    public $color;
    public $length;
    public $width;
    
    // Weight
    public $item_k = 0;
    public $item_p = 0;
    public $item_y = 0;
    public $item_tg = 0;
    
    // Transaction Details
    public $invoice_no; // ပြေစာအမှတ်
    public $quantity = 1;
    public $unit_price;
    public $commission_rate = 3;
    public $sale_date;
    public $notes;
    
    public $search = '';
    public $filterPerson = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    protected $rules = [
        'sales_person_id' => 'required|exists:users,id',
        'invoice_no' => 'nullable|string|max:50',
        'customer_name' => 'required|string|max:255|min:2',
        'customer_phone' => 'nullable|string|max:20|regex:/^[0-9\-\+\(\)\s]+$/',
        'customer_address' => 'nullable|string|max:500',
        'customer_nrc' => 'nullable|string|max:50',
        'goldsmith_name' => 'nullable|string|max:255',
        'shop_number' => 'nullable|string|max:50',
        'cashier' => 'nullable|string|max:255',
        'color_manager' => 'nullable|string|max:255',
        'responsible_signature' => 'nullable|string|max:255',
        'item_name' => 'required|string|max:255|min:2',
        'item_category' => 'nullable|string|max:100',
        'gold_quality' => 'nullable|string|max:50',
        'color' => 'nullable|string|max:50',
        'length' => 'nullable|numeric|min:0',
        'width' => 'nullable|numeric|min:0',
        'item_k' => 'nullable|numeric|min:0',
        'item_p' => 'nullable|numeric|min:0',
        'item_y' => 'nullable|numeric|min:0',
        'item_tg' => 'nullable|numeric|min:0',
        'quantity' => 'required|integer|min:1|max:10000',
        'unit_price' => 'required|numeric|min:0|max:100000000',
        'commission_rate' => 'nullable|numeric|min:0|max:100',
        'sale_date' => 'required|date|before_or_equal:today|after:2020-01-01',
        'notes' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'customer_name.min' => 'Customer name must be at least 2 characters.',
        'customer_phone.regex' => 'Invalid phone number format.',
        'quantity.max' => 'Quantity cannot exceed 10,000 items.',
        'unit_price.max' => 'Unit price cannot exceed 100,000,000.00 MMK.',
        'sale_date.before_or_equal' => 'Sale date cannot be in the future.',
        'sale_date.after' => 'Sale date must be after 2020.',
    ];

    public function mount()
    {
        $this->sale_date = Carbon::now()->format('Y-m-d');
        $this->filterDateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->filterDateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedProductId($value)
    {
        if ($value) {
            $product = Product::find($value);
            if ($product) {
                $this->product_code = $product->code;
                $this->item_name = $product->item_name ?? $product->name;
                $this->item_category = $product->item_category;
                $this->unit_price = $product->sale_fixed_price;
            }
        }
    }

    public function openImportModal()
    {
        $this->showImportModal = true;
        $this->reset(['csvFile', 'csvHeaders', 'csvData', 'csvMappings', 'importErrors', 'importSuccess', 'importedCount']);
    }

    public function updatedProductCode($value)
    {
        if ($value) {
            $product = Product::where('code', $value)->first();
            if ($product) {
                // Basic Info
                $this->product_id = $product->id;
                $this->item_name = $product->item_name ?? $product->name;
                $this->item_category = $product->item_category;
                $this->unit_price = $product->sale_fixed_price;
                
                // Product Details
                $this->gold_quality = $product->gold_quality;
                $this->color = $product->color;
                $this->length = $product->length;
                $this->width = $product->width;
                
                // Weight Details
                $this->item_k = $product->item_k;
                $this->item_p = $product->item_p;
                $this->item_y = $product->item_y;
                $this->item_tg = $product->item_tg;
                
                // Staff Info
                $this->goldsmith_name = $product->goldsmith_name;
                
                // Visual feedback - success
                $this->dispatch('product-found');
            } else {
                // Visual feedback - not found
                $this->dispatch('product-not-found');
            }
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $transaction = SalesTransaction::findOrFail($id);
        $this->transactionId = $transaction->id;
        $this->sales_person_id = $transaction->sales_person_id;
        $this->customer_name = $transaction->customer_name;
        $this->customer_phone = $transaction->customer_phone;
        $this->item_name = $transaction->item_name;
        $this->item_category = $transaction->item_category;
        $this->quantity = $transaction->quantity;
        $this->unit_price = $transaction->unit_price;
        $this->commission_rate = $transaction->commission_rate;
        $this->sale_date = $transaction->sale_date->format('Y-m-d');
        $this->notes = $transaction->notes;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Check for potential duplicate (same person, customer, item, amount on same day)
        if (!$this->editMode) {
            $totalAmount = $this->quantity * $this->unit_price;
            $duplicate = SalesTransaction::where('sales_person_id', $this->sales_person_id)
                ->where('customer_name', $this->customer_name)
                ->where('item_name', $this->item_name)
                ->where('total_amount', $totalAmount)
                ->where('sale_date', $this->sale_date)
                ->exists();

            if ($duplicate) {
                $this->addError('customer_name', 'A similar transaction already exists for this date. Please verify.');
                return;
            }
        }

        $data = [
            'sales_person_id' => $this->sales_person_id,
            'customer_name' => trim($this->customer_name),
            'customer_phone' => $this->customer_phone ? trim($this->customer_phone) : null,
            'item_name' => trim($this->item_name),
            'item_category' => $this->item_category ? trim($this->item_category) : null,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'commission_rate' => $this->commission_rate ?? 0,
            'sale_date' => $this->sale_date,
            'notes' => $this->notes ? trim($this->notes) : null,
        ];

        if ($this->editMode) {
            SalesTransaction::find($this->transactionId)->update($data);
            session()->flash('message', 'Transaction updated successfully!');
        } else {
            SalesTransaction::create($data);
            session()->flash('message', 'Transaction created successfully!');
        }

        $this->closeModal();
    }

    public function viewInvoice($id)
    {
        $this->viewTransaction = SalesTransaction::with('salesPerson')->findOrFail($id);
        $this->showInvoiceModal = true;
    }

    public function closeInvoiceModal()
    {
        $this->showInvoiceModal = false;
        $this->viewTransaction = null;
    }

    public function delete($id)
    {
        SalesTransaction::find($id)->delete();
        session()->flash('message', 'Transaction deleted successfully!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->transactionId = null;
        $this->sales_person_id = null;
        $this->product_id = null;
        $this->product_code = '';
        
        // Customer
        $this->customer_name = '';
        $this->customer_phone = '';
        $this->customer_address = '';
        $this->customer_nrc = '';
        
        // Staff
        $this->goldsmith_name = '';
        $this->shop_number = '';
        $this->cashier = '';
        $this->color_manager = '';
        $this->responsible_signature = '';
        
        // Product
        $this->item_name = '';
        $this->item_category = '';
        $this->gold_quality = '';
        $this->color = '';
        $this->length = null;
        $this->width = null;
        
        // Weight
        $this->item_k = 0;
        $this->item_p = 0;
        $this->item_y = 0;
        $this->item_tg = 0;
        
        // Transaction
        $this->invoice_no = '';
        $this->quantity = 1;
        $this->unit_price = null;
        $this->commission_rate = 3;
        $this->sale_date = Carbon::now()->format('Y-m-d');
        $this->notes = '';
        
        $this->resetErrorBag();
    }

    public function getSalesPersonsProperty()
    {
        return User::active()->orderBy('name')->get();
    }

    public function getProductsProperty()
    {
        return Product::active()
            ->orderBy('item_category')
            ->orderBy('item_name')
            ->get();
    }

    public function render()
    {
        $transactions = SalesTransaction::query()
            ->with(['salesPerson'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('customer_name', 'like', '%' . $this->search . '%')
                      ->orWhere('item_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterPerson, function ($query) {
                $query->where('sales_person_id', $this->filterPerson);
            })
            ->when($this->filterDateFrom, function ($query) {
                $query->where('sale_date', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function ($query) {
                $query->where('sale_date', '<=', $this->filterDateTo);
            })
            ->paginate(20);

        return view('livewire.sales-entry', [
            'transactions' => $transactions,
            'salesPersons' => $this->salesPersons,
            'products' => $this->products,
            'stats' => [
                'total_revenue' => SalesTransaction::sum('net_amount'),
                'total_quantity' => SalesTransaction::sum('quantity'),
                'total_transactions' => SalesTransaction::count(),
                'total_commission' => SalesTransaction::sum('commission')
            ],
            'branches' => SalesTransaction::select('branch')->distinct()->pluck('branch'),
            'categories' => SalesTransaction::select('category')->distinct()->pluck('category'),
            'salesPeople' => User::whereHas('role', function($query) {
                $query->whereIn('name', ['admin', 'sales']);
            })->select('id', 'name')->get()
        ]);
    }

    /**
     * Clean and deduplicate CSV headers
     */
    protected function cleanCsvHeaders(array $headers): array
    {
        $cleanHeaders = [];
        $headerCounts = [];
        $headerIndices = [];
        
        foreach ($headers as $index => $header) {
            // Clean the header
            $cleanHeader = trim($header);
            $cleanHeader = preg_replace('/[^\p{L}\p{N}_-]/u', '_', $cleanHeader);
            $cleanHeader = preg_replace('/_+/', '_', $cleanHeader);
            $cleanHeader = trim($cleanHeader, '_');
            
            // If header is empty after cleaning, use a default name
            if (empty($cleanHeader)) {
                $cleanHeader = 'column_' . ($index + 1);
            }
            
            // Track how many times we've seen this header
            if (!isset($headerCounts[$cleanHeader])) {
                $headerCounts[$cleanHeader] = 0;
            } else {
                $headerCounts[$cleanHeader]++;
                $cleanHeader = $cleanHeader . '_' . $headerCounts[$cleanHeader];
            }
            
            // Ensure the header is unique
            $baseHeader = $cleanHeader;
            $counter = 1;
            while (in_array($cleanHeader, $headerIndices)) {
                $cleanHeader = $baseHeader . '_' . $counter;
                $counter++;
            }
            
            $headerIndices[] = $cleanHeader;
            $cleanHeaders[] = $cleanHeader;
        }
        
        return $cleanHeaders;
    }
    
    public function updatedCsvFile()
    {
        $this->validate(['csvFile' => 'required|file|mimes:csv,txt|max:10240']);
        
        try {
            $path = $this->csvFile->getRealPath();
            
            // Read the file content to handle BOM and normalize line endings
            $content = file_get_contents($path);
            
            // Remove BOM if it exists
            $bom = pack('H*','EFBBBF');
            $content = preg_replace("/^$bom/", '', $content);
            
            // Normalize line endings
            $content = preg_replace('/\r\n|\r/', "\n", $content);
            
            // Split into lines
            $lines = explode("\n", $content);
            
            // Remove empty lines
            $lines = array_filter($lines, function($line) {
                return trim($line) !== '';
            });
            
            // Get header row (first non-empty line)
            $headerRow = array_shift($lines);
            $originalHeaders = str_getcsv($headerRow);
            
            // Clean and deduplicate headers
            $this->csvHeaders = $this->cleanCsvHeaders($originalHeaders);
            
            // Process the remaining rows
            $this->csvData = [];
            
            foreach ($lines as $line) {
                $row = str_getcsv($line);
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Pad the row with empty strings if it has fewer columns than headers
                $row = array_pad($row, count($this->csvHeaders), '');
                
                // Combine with headers and add to data
                $this->csvData[] = array_combine(
                    $this->csvHeaders,
                    array_slice($row, 0, count($this->csvHeaders))
                );
            }
            
            // Auto-detect column mappings (only once)
            $this->autoDetectMappings();
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('CSV Import Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->addError('csvFile', 'Error processing CSV file: ' . $e->getMessage());
        }
    }

    protected function autoDetectMappings()
    {
        if (empty($this->csvHeaders)) {
            return;
        }

        // Reset mappings
        $this->csvMappings = array_fill_keys(array_keys($this->csvMappings), '');

        // Common header patterns for each field with more variations
        $patterns = [
            'sale_date' => ['date', 'saledate', 'transaction_date', 'sale date', 'date of sale', 'saledate', 'transaction date'],
            'invoice_no' => ['invoice', 'invoiceno', 'invoice_no', 'invoice no', 'receipt', 'receiptno', 'receipt no', 'bill no', 'billno'],
            'product_code' => ['productcode', 'product_code', 'product code', 'code', 'itemcode', 'item_code', 'item code', 'sku', 'product id'],
            'product_name' => ['productname', 'product_name', 'product name', 'name', 'itemname', 'item_name', 'item name', 'description', 'product'],
            'category' => ['category', 'itemcategory', 'item_category', 'item category', 'productcategory', 'product_category', 'product category', 'type', 'producttype', 'product type'],
            'quantity' => ['quantity', 'qty', 'amount', 'number', 'count', 'qty.', 'quantity sold', 'units', 'unit count'],
            'unit_price' => ['price', 'unitprice', 'unit_price', 'unit price', 'cost', 'unitcost', 'unit cost', 'sale price', 'selling price'],
            'total_amount' => ['total', 'totalamount', 'total_amount', 'total amount', 'amount', 'grand total', 'total price', 'total cost'],
            'discount' => ['discount', 'discountamount', 'discount_amount', 'discount amount', 'discount amt', 'deduction', 'discount percent', 'discount %'],
            'net_amount' => ['net', 'netamount', 'net_amount', 'net amount', 'total after discount', 'final amount', 'amount due', 'payable amount'],
            'payment_method' => ['payment', 'paymentmethod', 'payment_method', 'payment method', 'payment type', 'paytype', 'pay method', 'payment option'],
            'branch' => ['branch', 'location', 'store', 'shop', 'outlet', 'branch name', 'store name', 'location name'],
            'sales_person' => ['salesperson', 'sales_person', 'sales person', 'staff', 'employee', 'seller', 'agent', 'sales rep', 'salesrep', 'salesrep name'],
            'customer_name' => ['customername', 'customer_name', 'customer name', 'name', 'client', 'clientname', 'client_name', 'client name', 'buyer', 'buyer name'],
            'customer_phone' => ['phone', 'customerphone', 'customer_phone', 'customer phone', 'mobile', 'contact', 'phonenumber', 'phone number', 'telephone', 'tel', 'mobile no', 'phone no'],
            'customer_address' => ['address', 'customeraddress', 'customer_address', 'customer address', 'shippingaddress', 'shipping_address', 'shipping address', 'delivery address', 'billing address'],
            'customer_nrc' => ['nrc', 'customernrc', 'customer_nrc', 'customer nrc', 'nrc no', 'nrc number', 'id number', 'id no', 'identification number']
        ];

        // First pass: exact matches
        foreach ($this->csvHeaders as $header) {
            $headerLower = strtolower(trim($header));
            
            foreach ($patterns as $field => $keywords) {
                if (in_array($headerLower, $keywords) && empty($this->csvMappings[$field])) {
                    $this->csvMappings[$field] = $header;
                    break;
                }
            }
        }

        // Second pass: partial matches for any remaining unmapped fields
        foreach ($this->csvHeaders as $header) {
            $headerLower = strtolower(trim($header));
            
            foreach ($patterns as $field => $keywords) {
                if (empty($this->csvMappings[$field])) {
                    foreach ($keywords as $keyword) {
                        if (str_contains($headerLower, $keyword)) {
                            $this->csvMappings[$field] = $header;
                            break 2; // Move to next header once a match is found
                        }
                    }
                }
            }
        }
        
        // Special handling for common date columns
        if (empty($this->csvMappings['sale_date'])) {
            foreach ($this->csvHeaders as $header) {
                $headerLower = strtolower($header);
                if (str_contains($headerLower, 'date')) {
                    $this->csvMappings['sale_date'] = $header;
                    break;
                }
            }
        }
        
        // Special handling for amount columns
        if (empty($this->csvMappings['total_amount'])) {
            foreach ($this->csvHeaders as $header) {
                $headerLower = strtolower($header);
                if (str_contains($headerLower, 'total') || str_contains($headerLower, 'amount')) {
                    $this->csvMappings['total_amount'] = $header;
                    break;
                }
            }
        }
        
        // Special handling for customer name
        if (empty($this->csvMappings['customer_name'])) {
            foreach ($this->csvHeaders as $header) {
                $headerLower = strtolower($header);
                if (str_contains($headerLower, 'name')) {
                    $this->csvMappings['customer_name'] = $header;
                    break;
                }
            }
        }
    }

    public function importCsv()
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:10240',
        ]);
        
        // If no mappings are set yet, try to auto-detect them
        if (empty($this->csvMappings) || empty($this->csvData)) {
            $this->updatedCsvFile();
        }
        
        // Ensure we have the CSV data
        if (empty($this->csvData)) {
            $path = $this->csvFile->getRealPath();
            $csv = Reader::createFromPath($path, 'r');
            $csv->setHeaderOffset(0);
            $this->csvHeaders = $csv->getHeader();
            $this->csvData = iterator_to_array($csv->getRecords());
        }

        // Auto-detect mappings based on common column names
        $this->autoDetectMappings();

        DB::beginTransaction();
        $imported = 0;
        $errors = [];

        try {
            foreach ($this->csvData as $index => $row) {
                try {
                    $data = [];
                    
                    // Map CSV data to database fields
                    foreach ($this->csvMappings as $field => $csvHeader) {
                        $value = $row[$csvHeader] ?? null;
                        
                        if ($value !== null) {
                            // Clean and format the value
                            $value = trim($value);
                            
                            // Format numeric values (remove commas and non-numeric characters except decimal point)
                            if (in_array($field, ['quantity', 'unit_price', 'total_amount', 'net_amount', 'discount', 'commission_amount'])) {
                                $value = (float) preg_replace('/[^0-9.-]/', '', $value);
                            }
                            
                            $data[$field] = $value;
                        }
                    }
                    
                    // Set default values for required fields
                    $data['sale_date'] = $data['sale_date'] ?? now()->format('Y-m-d');
                    $data['product_code'] = $data['product_code'] ?? 'ITEM-' . ($index + 1);
                    $data['product_name'] = $data['product_name'] ?? $data['product_code'];
                    $data['quantity'] = (float)($data['quantity'] ?? 1);
                    $data['unit_price'] = (float)($data['unit_price'] ?? 0);
                    $data['total_amount'] = (float)($data['total_amount'] ?? ($data['quantity'] * $data['unit_price']));
                    $data['net_amount'] = (float)($data['net_amount'] ?? $data['total_amount']);
                    
                    // Handle sales person - find or create
                    $salesPersonName = $data['sales_person'] ?? 'Unknown Sales Person';
                    $salesPerson = \App\Models\SalesPerson::firstOrCreate(
                        ['name' => $salesPersonName],
                        ['email' => strtolower(str_replace(' ', '.', $salesPersonName)) . '@example.com', 'phone' => '']
                    );
                    
                    $data['sales_person_id'] = $salesPerson->id;
                    $data['branch'] = $data['branch'] ?? 'Default';
                    $data['customer_name'] = $data['customer_name'] ?? 'Walk-in Customer';
                    
                    // Format date if needed (e.g., 5-Nov -> 2025-11-05)
                    if (preg_match('/^(\d{1,2})-([A-Za-z]{3})$/i', $data['sale_date'], $matches)) {
                        $monthMap = [
                            'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05', 'Jun' => '06',
                            'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'
                        ];
                        $month = $monthMap[ucfirst(strtolower($matches[2]))] ?? '01';
                        $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                        $data['sale_date'] = date('Y') . '-' . $month . '-' . $day;
                    }
                    
                    // Create or update product
                    $product = Product::firstOrCreate(
                        ['code' => $data['product_code']],
                        [
                            'name' => $data['product_name'],
                            'category' => $data['category'] ?? 'Imported',
                            'price' => $data['unit_price'] > 0 ? $data['unit_price'] : 0
                        ]
                    );
                    
                    // Create sale transaction
                    $sale = new SalesTransaction([
                        'sale_date' => $data['sale_date'],
                        'invoice_no' => $data['invoice_no'] ?? ('IMP-' . date('Ymd') . '-' . ($index + 1)),
                        'product_id' => $product->id,
                        'product_code' => $product->code,
                        'item_name' => $data['product_name'],
                        'quantity' => $data['quantity'],
                        'unit_price' => $data['unit_price'],
                        'total_amount' => $data['total_amount'],
                        'net_amount' => $data['net_amount'],
                        'sales_person_id' => $salesPerson->id,
                        'customer_name' => $data['customer_name'],
                        'customer_phone' => $data['customer_phone'] ?? '',
                        'customer_address' => $data['customer_address'] ?? '',
                        'customer_nrc' => $data['customer_nrc'] ?? '',
                        'branch' => $data['branch'],
                        'category' => $data['category'] ?? 'Imported',
                        'commission_rate' => $this->commission_rate,
                        'commission_amount' => ($this->commission_rate / 100) * $data['net_amount'],
                    ]);
                    
                    $sale->save();
                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                    Log::error('CSV Import Error: ' . $e->getMessage(), [
                        'row' => $row,
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            DB::commit();

            $this->importedCount = $imported;
            $this->importErrors = $errors;
            $this->importSuccess = true;

            // Reset file input
            $this->reset('csvFile');

            // Refresh the transactions list
            $this->resetPage();

            session()->flash('message',
                'Successfully imported ' . $imported . ' transactions. ' .
                (count($errors) ? count($errors) . ' rows had errors.' : '')
            );

        } catch (\Exception $e) {
            DB::rollBack();

            $this->importErrors = ['An error occurred during import: ' . $e->getMessage()];
            Log::error('CSV Import Failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'sale_date', 'invoice_no', 'product_code', 'product_name', 'category',
            'quantity', 'price', 'total_amount', 'discount', 'net_amount',
            'payment_method', 'branch', 'sales_person', 'customer_name',
            'customer_phone', 'customer_address', 'customer_nrc'
        ];

        $filename = 'sales_import_template_' . date('Y-m-d') . '.csv';

        $handle = fopen('php://output', 'w');
        fputcsv($handle, $headers);
        fclose($handle);

        return response()->stream(
            function () use ($headers) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, $headers);
                fclose($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }
}
