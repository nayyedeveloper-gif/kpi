<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_date',
        'sales_person_id',
        'invoice_no',
        'product_id',
        'product_code',
        'item_name',
        'quantity',
        'price',
        'total_amount',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_nrc',
        'discount',
        'net_amount',
        'payment_method',
        'branch',
        'commission_rate',
        'commission_amount',
        'goldsmith_name',
        'shop_number',
        'cashier',
        'color_manager',
        'responsible_signature',
        'item_category',
        'gold_quality',
        'color',
        'length',
        'width',
        'item_k',
        'item_p',
        'item_y',
        'item_tg',
        'unit_price',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'quantity' => 'float',
        'price' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'commission_rate' => 'float',
        'commission_amount' => 'decimal:2',
    ];

    protected $dates = [
        'sale_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the sales person associated with the transaction.
     */
    public function salesPerson(): BelongsTo
    {
        return $this->belongsTo(SalesPerson::class, 'sales_person_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Calculate commission automatically
    protected static function booted()
    {
        static::saving(function ($transaction) {
            $transaction->total_amount = $transaction->quantity * $transaction->unit_price;
            $transaction->commission_amount = ($transaction->total_amount * $transaction->commission_rate) / 100;
        });
    }
}
