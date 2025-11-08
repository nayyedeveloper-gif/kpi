<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SalesTransaction;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class SalesEntryBackup extends Component
{
    use WithPagination;

    public $showModal = false;
    public $showInvoiceModal = false;
    public $editMode = false;
    public $transactionId;
    public $viewTransaction = null;
    
    public $sales_person_id;
    public $product_id; // Selected product from products table
    public $product_code; // Product code for search
    public $customer_name;
    public $customer_phone;
    public $item_name;
    public $item_category;
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
        'customer_name' => 'required|string|max:255|min:2',
        'customer_phone' => 'nullable|string|max:20|regex:/^[0-9\-\+\(\)\s]+$/',
        'item_name' => 'required|string|max:255|min:2',
        'item_category' => 'nullable|string|max:100',
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

    public function updatedProductCode($value)
    {
        if ($value) {
            $product = Product::where('code', $value)->first();
            if ($product) {
                $this->product_id = $product->id;
                $this->item_name = $product->item_name ?? $product->name;
                $this->item_category = $product->item_category;
                $this->unit_price = $product->sale_fixed_price;
                
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
        $this->customer_name = '';
        $this->customer_phone = '';
        $this->item_name = '';
        $this->item_category = '';
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
            ->latest('sale_date')
            ->paginate(20);

        $stats = [
            'total_revenue' => SalesTransaction::whereBetween('sale_date', [$this->filterDateFrom, $this->filterDateTo])->sum('total_amount'),
            'total_quantity' => SalesTransaction::whereBetween('sale_date', [$this->filterDateFrom, $this->filterDateTo])->sum('quantity'),
            'total_transactions' => SalesTransaction::whereBetween('sale_date', [$this->filterDateFrom, $this->filterDateTo])->count(),
            'total_commission' => SalesTransaction::whereBetween('sale_date', [$this->filterDateFrom, $this->filterDateTo])->sum('commission_amount'),
        ];

        return view('livewire.sales-entry-backup', [
            'transactions' => $transactions,
            'salesPersons' => $this->salesPersons,
            'products' => $this->products,
            'stats' => $stats,
        ])->layout('layouts.app');
    }
}
