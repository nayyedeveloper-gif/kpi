<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product([
            'code' => $row['no'] ?? $row['code'] ?? null,
            'name' => $row['itemname'] ?? $row['item_name'] ?? null,
            'staff_name' => $row['staffname'] ?? $row['staff_name'] ?? null,
            'is_diamond' => $this->parseBool($row['isdiamond'] ?? $row['is_diamond'] ?? false),
            'is_solid_gold' => $this->parseBool($row['issolidgold'] ?? $row['is_solid_gold'] ?? false),
            'item_category' => $row['itemcategory'] ?? $row['item_category'] ?? null,
            'item_name' => $row['itemname'] ?? $row['item_name'] ?? null,
            'gold_quality' => $row['goldquality'] ?? $row['gold_quality'] ?? null,
            'original_code' => $row['originalcode'] ?? $row['original_code'] ?? null,
            'length' => $row['length'] ?? 0,
            'width' => $row['width'] ?? 0,
            'goldsmith_name' => $row['goldsmithname'] ?? $row['goldsmith_name'] ?? null,
            'goldsmith_date' => $this->parseDate($row['goldsmithdate'] ?? $row['goldsmith_date'] ?? null),
            'color' => $row['color'] ?? null,
            'supplier' => $row['supplier'] ?? null,
            'voucher_no' => $row['voucherno'] ?? $row['voucher_no'] ?? null,
            'item_k' => $row['itemk'] ?? $row['item_k'] ?? 0,
            'item_p' => $row['itemp'] ?? $row['item_p'] ?? 0,
            'item_y' => $row['itemy'] ?? $row['item_y'] ?? 0,
            'item_tg' => $row['itemtg'] ?? $row['item_tg'] ?? 0,
            'waste_k' => $row['wastek'] ?? $row['waste_k'] ?? 0,
            'waste_p' => $row['wastep'] ?? $row['waste_p'] ?? 0,
            'waste_y' => $row['wastey'] ?? $row['waste_y'] ?? 0,
            'waste_t' => $row['wastetg'] ?? $row['wastet'] ?? $row['waste_tg'] ?? $row['waste_t'] ?? 0,
            'pwaste_k' => $row['pwastek'] ?? $row['pwaste_k'] ?? 0,
            'pwaste_p' => $row['pwastep'] ?? $row['pwaste_p'] ?? 0,
            'pwaste_y' => $row['pwastey'] ?? $row['pwaste_y'] ?? 0,
            'pwaste_tg' => $row['pwastetg'] ?? $row['pwaste_tg'] ?? 0,
            'sale_fixed_price' => $row['salefixedprice'] ?? $row['sale_fixed_price'] ?? 0,
            'original_fixed_price' => $row['originalfixedprice'] ?? $row['original_fixed_price'] ?? 0,
            'original_price_tk' => $row['originalpricetk'] ?? $row['original_price_tk'] ?? 0,
            'original_price_gram' => $row['originalpricegram'] ?? $row['original_price_gram'] ?? 0,
            'design_charges' => $row['designcharges'] ?? $row['design_charges'] ?? 0,
            'plating_charges' => $row['platingcharges'] ?? $row['plating_charges'] ?? 0,
            'mounting_charges' => $row['mountingcharges'] ?? $row['mounting_charges'] ?? 0,
            'white_charges' => $row['whitecharges'] ?? $row['white_charges'] ?? 0,
            'other_charges' => $row['othercharges'] ?? $row['other_charges'] ?? 0,
            'remark' => $row['remark'] ?? null,
            'is_active' => true,
        ]);
    }

    /**
     * Parse boolean value
     */
    private function parseBool($value)
    {
        if (is_bool($value)) {
            return $value;
        }
        return in_array(strtolower($value), ['yes', 'true', '1', 'y']);
    }

    /**
     * Parse date value
     */
    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            // No validation rules - allow flexible import
        ];
    }
}
