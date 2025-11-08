<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SalesData extends Model
{
    protected $table = 'sales_data';
    
    protected $fillable = [
        'year',
        'month',
        'invoiced_date',
        'voucher_number',
        'branch',
        'customer_name',
        'customer_status',
        'contact_number',
        'contact_address',
        'township',
        'division',
        'customer_nrc_number',
        'item_categories',
        'item_group',
        'item_name',
        'density',
        'weight',
        'unit',
        'quantity',
        'g_price',
        'g_gross_amount',
        'm_price',
        'm_gross_amount',
        'dis',
        'promotion_dis',
        'special_dis',
        'dis_net_amount',
        'promotion_net_amount',
        'total_net_amount',
        'tax',
        'sale_person',
        'remark'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'invoiced_date' => 'date:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'density' => 'decimal:2',
        'weight' => 'decimal:2',
        'quantity' => 'integer',
        'g_price' => 'decimal:2',
        'g_gross_amount' => 'decimal:2',
        'm_price' => 'decimal:2',
        'm_gross_amount' => 'decimal:2',
        'dis' => 'decimal:2',
        'promotion_dis' => 'decimal:2',
        'special_dis' => 'decimal:2',
        'dis_net_amount' => 'decimal:2',
        'promotion_net_amount' => 'decimal:2',
        'total_net_amount' => 'decimal:2',
        'tax' => 'decimal:2',
    ];

    // Format the invoiced_date attribute
    public function setInvoicedDateAttribute($value)
    {
        $this->attributes['invoiced_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    // Format numeric values with thousands separators for display
    public function getFormattedPriceAttribute($value, $key)
    {
        return $this->attributes[$key] ? number_format($this->attributes[$key]) : null;
    }
}
