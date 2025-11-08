<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'staff_name',
        'is_diamond',
        'is_solid_gold',
        'item_category',
        'item_name',
        'gold_quality',
        'original_code',
        'length',
        'width',
        'goldsmith_name',
        'goldsmith_date',
        'color',
        'supplier',
        'voucher_no',
        'item_k',
        'item_p',
        'item_y',
        'item_tg',
        'waste_k',
        'waste_p',
        'waste_y',
        'waste_t',
        'pwaste_k',
        'pwaste_p',
        'pwaste_y',
        'pwaste_tg',
        'sale_fixed_price',
        'original_fixed_price',
        'original_price_tk',
        'original_price_gram',
        'design_charges',
        'plating_charges',
        'mounting_charges',
        'white_charges',
        'other_charges',
        'remark',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_diamond' => 'boolean',
        'is_solid_gold' => 'boolean',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'goldsmith_date' => 'date',
        'item_k' => 'decimal:2',
        'item_p' => 'decimal:2',
        'item_y' => 'decimal:2',
        'item_tg' => 'decimal:3',
        'waste_k' => 'decimal:2',
        'waste_p' => 'decimal:2',
        'waste_y' => 'decimal:2',
        'waste_t' => 'decimal:3',
        'pwaste_k' => 'decimal:2',
        'pwaste_p' => 'decimal:2',
        'pwaste_y' => 'decimal:2',
        'pwaste_tg' => 'decimal:3',
        'sale_fixed_price' => 'decimal:2',
        'original_fixed_price' => 'decimal:2',
        'original_price_tk' => 'decimal:2',
        'original_price_gram' => 'decimal:2',
        'design_charges' => 'decimal:2',
        'plating_charges' => 'decimal:2',
        'mounting_charges' => 'decimal:2',
        'white_charges' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * အသုံးပြုနေသော Products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * အမျိုးအစားအလိုက် စစ်ထုတ်ခြင်း
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('item_category', $category);
    }

    /**
     * စိန်ပါသော ပစ္စည်းများ
     */
    public function scopeDiamond($query)
    {
        return $query->where('is_diamond', true);
    }

    /**
     * ရွှေစင်ပစ္စည်းများ
     */
    public function scopeSolidGold($query)
    {
        return $query->where('is_solid_gold', true);
    }

    /**
     * စုစုပေါင်း အလေးချိန် (ကျပ်သား)
     */
    public function getTotalWeightAttribute()
    {
        return $this->item_tg + $this->waste_t + $this->pwaste_tg;
    }

    /**
     * စုစုပေါင်း ကုန်ကျစရိတ်
     */
    public function getTotalCostAttribute()
    {
        return $this->original_fixed_price + 
               $this->design_charges + 
               $this->plating_charges + 
               $this->mounting_charges + 
               $this->white_charges + 
               $this->other_charges;
    }

    /**
     * အမြတ်ငွေ
     */
    public function getProfitAttribute()
    {
        return $this->sale_fixed_price - $this->total_cost;
    }

    /**
     * အမြတ်ရာခိုင်နှုန်း
     */
    public function getProfitMarginAttribute()
    {
        if ($this->total_cost == 0) {
            return 0;
        }
        return (($this->sale_fixed_price - $this->total_cost) / $this->total_cost) * 100;
    }
}
