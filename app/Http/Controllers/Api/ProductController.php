<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter active products
        if ($request->get('active_only', true)) {
            $query->where('is_active', true);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%')
                  ->orWhere('item_name', 'like', '%' . $search . '%')
                  ->orWhere('original_code', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('item_category', $request->category);
        }

        $perPage = $request->get('per_page', 50);
        $products = $query->orderBy('item_category')->orderBy('item_name')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Search product by code.
     */
    public function searchByCode($code)
    {
        $product = Product::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }
}

