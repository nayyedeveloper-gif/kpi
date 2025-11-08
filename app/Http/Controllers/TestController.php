<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesData;

class TestController extends Controller
{
    public function sales()
    {
        return view('sales.index', [
            'sales' => collect([]),
            'branches' => collect([]),
            'categories' => collect([]),
            'salePersons' => collect([]),
            'summary' => ['total_sales' => 0, 'total_quantity' => 0, 'total_tax' => 0, 'total_discount' => 0],
            'svgIcons' => [],
            'sortField' => 'invoiced_date',
            'sortDirection' => 'desc'
        ]);
    }
}
