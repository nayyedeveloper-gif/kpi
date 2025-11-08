<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\SalesTransaction;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class EnhancedSalesEntry extends Component
{
    use WithPagination, WithFileUploads;

    // UI State
    public $showModal = false;
    public $showImportModal = false;
    public $editMode = false;
    public $transactionId;
    public $viewTransaction = null;
    public $file;
    public $headers = [];
    public $csvData = [];
    public $mapping = [];
    public $hasHeader = true;
    
    // Filters
    public $search = '';
    public $dateFrom;
    public $dateTo;
    public $category = '';
    public $salesPersonId = '';
    public $branch = '';
    
    // Form Fields
    public $sale_date;
    public $invoice_no;
    public $customer_name;
    public $customer_phone;
    public $customer_address;
    public $customer_nrc;
    public $item_category;
    public $item_group;
    public $item_name;
    public $density;
    public $k = 0;
    public $p = 0;
    public $y = 0;
    public $g = 0;
    public $quantity = 1;
    public $g_price = 0;
    public $g_gross_amount = 0;
    public $m_price = 0;
    public $m_gross_amount = 0;
    public $discount = 0;
    public $promotion_discount = 0;
    public $special_discount = 0;
    public $net_amount = 0;
    public $tax = 0;
    public $sale_person;
    public $remark = '';
    public $branch_name;

    protected $rules = [
        'sale_date' => 'required|date',
        'invoice_no' => 'required|string|max:50',
        'customer_name' => 'required|string|max:255',
        'item_name' => 'required|string|max:255',
        'quantity' => 'required|numeric|min:1',
        'g_price' => 'required|numeric|min:0',
        'm_price' => 'required|numeric|min:0',
        'net_amount' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->sale_date = now()->format('Y-m-d');
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function render()
    {
        $query = SalesTransaction::query()
            ->when($this->search, function($query) {
                $search = "%{$this->search}%";
                $query->where('invoice_no', 'like', $search)
                    ->orWhere('customer_name', 'like', $search)
                    ->orWhere('item_name', 'like', $search);
            })
            ->when($this->dateFrom && $this->dateTo, function($query) {
                $query->whereBetween('sale_date', [
                    $this->dateFrom,
                    Carbon::parse($this->dateTo)->endOfDay()
                ]);
            })
            ->when($this->category, function($query) {
                $query->where('item_category', $this->category);
            })
            ->when($this->salesPersonId, function($query) {
                $query->where('sale_person', $this->salesPersonId);
            })
            ->when($this->branch, function($query) {
                $query->where('branch', $this->branch);
            })
            ->latest('sale_date');

        $transactions = $query->paginate(20);
        // Get users with the 'sales' role through the role relationship
        $salesPersons = User::whereHas('role', function($query) {
            $query->where('name', 'sales');
        })->get();
        $branches = SalesTransaction::select('branch')->distinct()->pluck('branch');
        $categories = SalesTransaction::select('item_category')->distinct()->pluck('item_category');

        // Calculate summary
        $summary = [
            'total_sales' => $query->sum('net_amount'),
            'total_transactions' => $query->count(),
            'total_quantity' => $query->sum('quantity'),
            'avg_sale' => $query->avg('net_amount'),
        ];

        return view('livewire.enhanced-sales-entry', [
            'transactions' => $transactions,
            'salesPersons' => $salesPersons,
            'branches' => $branches,
            'categories' => $categories,
            'summary' => $summary
        ]);
    }

    public function openImportModal()
    {
        $this->reset(['file', 'headers', 'csvData', 'mapping']);
        $this->showImportModal = true;
    }

    public function updatedFile()
    {
        $this->validate(['file' => 'required|file|mimes:csv,txt|max:10240']);
        
        $path = $this->file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        
        $this->headers = $this->hasHeader ? array_shift($data) : array_fill(0, count($data[0]), '');
        $this->csvData = array_slice($data, 0, 5); // Show first 5 rows for mapping
        
        // Auto-detect mapping based on column names
        $possibleFields = [
            'invoice_no' => ['invoice', 'voucher'],
            'sale_date' => ['date'],
            'customer_name' => ['customer', 'name'],
            'item_name' => ['item', 'product'],
            'quantity' => ['qty', 'quantity'],
            'net_amount' => ['amount', 'total', 'net'],
        ];
        
        foreach ($this->headers as $index => $header) {
            $header = strtolower($header);
            foreach ($possibleFields as $field => $keywords) {
                foreach ($keywords as $keyword) {
                    if (str_contains($header, $keyword)) {
                        $this->mapping[$field] = $index;
                        break 2;
                    }
                }
            }
        }
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
            'mapping.invoice_no' => 'required',
            'mapping.sale_date' => 'required',
            'mapping.customer_name' => 'required',
            'mapping.item_name' => 'required',
            'mapping.net_amount' => 'required',
        ]);

        $path = $this->file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        
        if ($this->hasHeader) {
            array_shift($data); // Skip header
        }

        $imported = 0;
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            foreach ($data as $row) {
                try {
                    $transaction = new SalesTransaction();
                    
                    // Map CSV columns to database fields
                    foreach ($this->mapping as $field => $index) {
                        if (isset($row[$index])) {
                            $transaction->$field = trim($row[$index]);
                        }
                    }
                    
                    // Set default values for required fields if not mapped
                    $transaction->sale_date = $transaction->sale_date ?? now();
                    $transaction->quantity = $transaction->quantity ?? 1;
                    $transaction->branch = $transaction->branch ?? 'Default';
                    
                    // Clean numeric values
                    $numericFields = ['quantity', 'g_price', 'm_price', 'net_amount', 'tax'];
                    foreach ($numericFields as $field) {
                        if (isset($transaction->$field)) {
                            $transaction->$field = (float) preg_replace('/[^0-9.]/', '', $transaction->$field);
                        }
                    }
                    
                    $transaction->save();
                    $imported++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Error on row: " . implode(',', $row) . " - " . $e->getMessage();
                }
            }
            
            DB::commit();
            
            session()->flash('message', [
                'type' => 'success',
                'text' => "Successfully imported {$imported} transactions" . (count($errors) ? ". " . count($errors) . " rows had errors." : '')
            ]);
            
            if (!empty($errors)) {
                session()->flash('import-errors', $errors);
            }
            
            $this->showImportModal = false;
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('message', [
                'type' => 'error',
                'text' => 'Import failed: ' . $e->getMessage()
            ]);
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'dateFrom', 'dateTo', 'category', 'salesPersonId', 'branch']);
        $this->resetPage();
    }

    public function exportCsv()
    {
        $query = SalesTransaction::query()
            ->when($this->dateFrom && $this->dateTo, function($query) {
                $query->whereBetween('sale_date', [
                    $this->dateFrom,
                    Carbon::parse($this->dateTo)->endOfDay()
                ]);
            })
            ->when($this->category, function($query) {
                $query->where('item_category', $this->category);
            })
            ->when($this->salesPersonId, function($query) {
                $query->where('sale_person', $this->salesPersonId);
            })
            ->when($this->branch, function($query) {
                $query->where('branch', $this->branch);
            })
            ->latest('sale_date');

        $transactions = $query->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sales-export-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            // Headers
            fputcsv($file, [
                'Invoice No', 'Date', 'Customer Name', 'Phone', 'Address',
                'Item Category', 'Item Group', 'Item Name', 'Quantity',
                'Gold Quality', 'Weight (g)', 'Price (MMK)', 'Total Amount (MMK)',
                'Discount', 'Tax', 'Net Amount', 'Sales Person', 'Branch', 'Remarks'
            ]);
            
            // Data rows
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->invoice_no,
                    $transaction->sale_date->format('Y-m-d'),
                    $transaction->customer_name,
                    $transaction->customer_phone,
                    $transaction->customer_address,
                    $transaction->item_category,
                    $transaction->item_group,
                    $transaction->item_name,
                    $transaction->quantity,
                    $transaction->gold_quality,
                    $transaction->weight_gram,
                    $transaction->price_gram,
                    $transaction->gross_amount,
                    $transaction->discount,
                    $transaction->tax,
                    $transaction->net_amount,
                    $transaction->sale_person,
                    $transaction->branch,
                    $transaction->remark
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    // Other methods for CRUD operations would go here...
    // create(), update(), delete(), view(), etc.
}
