<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Product::all();
    }

    /**
     * Column headings
     */
    public function headings(): array
    {
        return [
            'ID',
            'ကုဒ်',
            'အမည်',
            'ဖော်ပြချက်',
            'အမျိုးအစား',
            'ယူနစ်',
            'စျေးနှုန်း',
            'ကုန်ကျစရိတ်',
            'လက်ကျန်',
            'အနည်းဆုံးလက်ကျန်',
            'ပေးသွင်းသူ',
            'ဘားကုဒ်',
            'အသုံးပြုနေသည်',
            'ထည့်သွင်းသည့်ရက်',
        ];
    }

    /**
     * Map data for each row
     */
    public function map($product): array
    {
        return [
            $product->id,
            $product->code,
            $product->name,
            $product->description,
            $product->category,
            $product->unit,
            $product->price,
            $product->cost,
            $product->stock_quantity,
            $product->min_stock,
            $product->supplier,
            $product->barcode,
            $product->is_active ? 'Yes' : 'No',
            $product->created_at->format('Y-m-d'),
        ];
    }
}
