<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $category = '';
    public $showModal = false;
    public $editMode = false;
    public $productId;

    // Form fields - All jewelry fields
    public $code, $name, $staff_name;
    public $is_diamond = false, $is_solid_gold = false;
    public $item_category, $item_name, $gold_quality, $original_code;
    public $length = 0, $width = 0;
    public $goldsmith_name, $goldsmith_date;
    public $color, $supplier, $voucher_no;
    
    // Weight fields
    public $item_k = 0, $item_p = 0, $item_y = 0, $item_tg = 0;
    public $waste_k = 0, $waste_p = 0, $waste_y = 0, $waste_t = 0;
    public $pwaste_k = 0, $pwaste_p = 0, $pwaste_y = 0, $pwaste_tg = 0;
    
    // Price fields
    public $sale_fixed_price = 0, $original_fixed_price = 0;
    public $original_price_tk = 0, $original_price_gram = 0;
    
    // Charges
    public $design_charges = 0, $plating_charges = 0;
    public $mounting_charges = 0, $white_charges = 0, $other_charges = 0;
    
    public $remark, $image, $existing_image;
    public $is_active = true;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'code' => 'required|string',
        'name' => 'nullable|string|max:255',
        'staff_name' => 'nullable|string',
        'item_category' => 'nullable|string',
        'item_name' => 'nullable|string',
        'gold_quality' => 'nullable|string',
        'original_code' => 'nullable|string',
        'length' => 'nullable|numeric|min:0',
        'width' => 'nullable|numeric|min:0',
        'goldsmith_name' => 'nullable|string',
        'goldsmith_date' => 'nullable|date',
        'color' => 'nullable|string',
        'supplier' => 'nullable|string',
        'voucher_no' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('item_name', 'like', '%' . $this->search . '%')
                  ->orWhere('original_code', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $query->where('item_category', $this->category);
        }

        $products = $query->latest()->paginate(10);
        $categories = Product::distinct()->pluck('item_category')->filter();

        return view('livewire.product-management', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->editMode = true;
        $this->productId = $id;
        
        $product = Product::findOrFail($id);
        
        // Basic fields
        $this->code = $product->code;
        $this->name = $product->name;
        $this->staff_name = $product->staff_name;
        $this->is_diamond = $product->is_diamond;
        $this->is_solid_gold = $product->is_solid_gold;
        
        // Item details
        $this->item_category = $product->item_category;
        $this->item_name = $product->item_name;
        $this->gold_quality = $product->gold_quality;
        $this->original_code = $product->original_code;
        $this->length = $product->length;
        $this->width = $product->width;
        
        // Goldsmith
        $this->goldsmith_name = $product->goldsmith_name;
        $this->goldsmith_date = $product->goldsmith_date ? $product->goldsmith_date->format('Y-m-d') : null;
        
        // Other details
        $this->color = $product->color;
        $this->supplier = $product->supplier;
        $this->voucher_no = $product->voucher_no;
        
        // Weights
        $this->item_k = $product->item_k;
        $this->item_p = $product->item_p;
        $this->item_y = $product->item_y;
        $this->item_tg = $product->item_tg;
        
        $this->waste_k = $product->waste_k;
        $this->waste_p = $product->waste_p;
        $this->waste_y = $product->waste_y;
        $this->waste_t = $product->waste_t;
        
        $this->pwaste_k = $product->pwaste_k;
        $this->pwaste_p = $product->pwaste_p;
        $this->pwaste_y = $product->pwaste_y;
        $this->pwaste_tg = $product->pwaste_tg;
        
        // Prices
        $this->sale_fixed_price = $product->sale_fixed_price;
        $this->original_fixed_price = $product->original_fixed_price;
        $this->original_price_tk = $product->original_price_tk;
        $this->original_price_gram = $product->original_price_gram;
        
        // Charges
        $this->design_charges = $product->design_charges;
        $this->plating_charges = $product->plating_charges;
        $this->mounting_charges = $product->mounting_charges;
        $this->white_charges = $product->white_charges;
        $this->other_charges = $product->other_charges;
        
        $this->remark = $product->remark;
        $this->existing_image = $product->image;
        $this->is_active = $product->is_active;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'staff_name' => $this->staff_name,
            'is_diamond' => $this->is_diamond,
            'is_solid_gold' => $this->is_solid_gold,
            'item_category' => $this->item_category,
            'item_name' => $this->item_name,
            'gold_quality' => $this->gold_quality,
            'original_code' => $this->original_code,
            'length' => $this->length,
            'width' => $this->width,
            'goldsmith_name' => $this->goldsmith_name,
            'goldsmith_date' => $this->goldsmith_date,
            'color' => $this->color,
            'supplier' => $this->supplier,
            'voucher_no' => $this->voucher_no,
            'item_k' => $this->item_k,
            'item_p' => $this->item_p,
            'item_y' => $this->item_y,
            'item_tg' => $this->item_tg,
            'waste_k' => $this->waste_k,
            'waste_p' => $this->waste_p,
            'waste_y' => $this->waste_y,
            'waste_t' => $this->waste_t,
            'pwaste_k' => $this->pwaste_k,
            'pwaste_p' => $this->pwaste_p,
            'pwaste_y' => $this->pwaste_y,
            'pwaste_tg' => $this->pwaste_tg,
            'sale_fixed_price' => $this->sale_fixed_price,
            'original_fixed_price' => $this->original_fixed_price,
            'original_price_tk' => $this->original_price_tk,
            'original_price_gram' => $this->original_price_gram,
            'design_charges' => $this->design_charges,
            'plating_charges' => $this->plating_charges,
            'mounting_charges' => $this->mounting_charges,
            'white_charges' => $this->white_charges,
            'other_charges' => $this->other_charges,
            'remark' => $this->remark,
            'is_active' => $this->is_active,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('products', 'public');
            
            if ($this->editMode && $this->existing_image) {
                Storage::disk('public')->delete($this->existing_image);
            }
        }

        if ($this->editMode) {
            $product = Product::findOrFail($this->productId);
            $product->update($data);
            session()->flash('success', 'ကုန်ပစ္စည်းအချက်အလက် ပြင်ဆင်ပြီးပါပြီ။');
        } else {
            Product::create($data);
            session()->flash('success', 'ကုန်ပစ္စည်းအသစ် ထည့်သွင်းပြီးပါပြီ။');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        session()->flash('success', 'ကုန်ပစ္စည်း ဖျက်ပြီးပါပြီ။');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        
        session()->flash('success', 'အခြေအနေ ပြောင်းလဲပြီးပါပြီ။');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'code', 'name', 'staff_name', 'is_diamond', 'is_solid_gold',
            'item_category', 'item_name', 'gold_quality', 'original_code',
            'length', 'width', 'goldsmith_name', 'goldsmith_date',
            'color', 'supplier', 'voucher_no',
            'item_k', 'item_p', 'item_y', 'item_tg',
            'waste_k', 'waste_p', 'waste_y', 'waste_t',
            'pwaste_k', 'pwaste_p', 'pwaste_y', 'pwaste_tg',
            'sale_fixed_price', 'original_fixed_price', 'original_price_tk', 'original_price_gram',
            'design_charges', 'plating_charges', 'mounting_charges', 'white_charges', 'other_charges',
            'remark', 'image', 'existing_image', 'productId'
        ]);
        $this->is_active = true;
        $this->is_diamond = false;
        $this->is_solid_gold = false;
    }
}
