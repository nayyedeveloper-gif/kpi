<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalesTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SalesTransactionController extends Controller
{
    /**
     * Display a listing of sales transactions.
     */
    public function index(Request $request)
    {
        $query = SalesTransaction::with(['salesPerson', 'product']);

        // Filter by sales person
        if ($request->has('sales_person_id')) {
            $query->where('sales_person_id', $request->sales_person_id);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->where('sale_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('sale_date', '<=', $request->date_to);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('item_name', 'like', '%' . $search . '%')
                  ->orWhere('invoice_no', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->get('per_page', 20);
        $transactions = $query->latest('sale_date')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Store a newly created sales transaction.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sales_person_id' => 'required|exists:users,id',
            'invoice_no' => 'nullable|string|max:50',
            'product_id' => 'nullable|exists:products,id',
            'product_code' => 'nullable|string|max:50',
            'customer_name' => 'required|string|max:255|min:2',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'customer_nrc' => 'nullable|string|max:50',
            'goldsmith_name' => 'nullable|string|max:255',
            'shop_number' => 'nullable|string|max:50',
            'cashier' => 'nullable|string|max:255',
            'color_manager' => 'nullable|string|max:255',
            'responsible_signature' => 'nullable|string|max:255',
            'item_name' => 'required|string|max:255|min:2',
            'item_category' => 'nullable|string|max:100',
            'gold_quality' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'item_k' => 'nullable|numeric|min:0',
            'item_p' => 'nullable|numeric|min:0',
            'item_y' => 'nullable|numeric|min:0',
            'item_tg' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1|max:10000',
            'unit_price' => 'required|numeric|min:0|max:100000000',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'sale_date' => 'required|date|before_or_equal:today|after:2020-01-01',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check for duplicate
        $totalAmount = $request->quantity * $request->unit_price;
        $duplicate = SalesTransaction::where('sales_person_id', $request->sales_person_id)
            ->where('customer_name', $request->customer_name)
            ->where('item_name', $request->item_name)
            ->where('total_amount', $totalAmount)
            ->where('sale_date', $request->sale_date)
            ->exists();

        if ($duplicate) {
            return response()->json([
                'success' => false,
                'message' => 'A similar transaction already exists for this date. Please verify.'
            ], 409);
        }

        // If product_code is provided, try to find product
        if ($request->has('product_code') && $request->product_code) {
            $product = Product::where('code', $request->product_code)->first();
            if ($product) {
                $request->merge(['product_id' => $product->id]);
            }
        }

        $data = $request->only([
            'sales_person_id', 'invoice_no', 'product_id', 'product_code',
            'customer_name', 'customer_phone', 'customer_address', 'customer_nrc',
            'goldsmith_name', 'shop_number', 'cashier', 'color_manager', 'responsible_signature',
            'item_name', 'item_category', 'gold_quality', 'color', 'length', 'width',
            'item_k', 'item_p', 'item_y', 'item_tg',
            'quantity', 'unit_price', 'commission_rate', 'sale_date', 'notes'
        ]);

        $transaction = SalesTransaction::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Sales transaction created successfully',
            'data' => $transaction->load(['salesPerson', 'product'])
        ], 201);
    }

    /**
     * Display the specified sales transaction.
     */
    public function show($id)
    {
        $transaction = SalesTransaction::with(['salesPerson', 'product'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    /**
     * Update the specified sales transaction.
     */
    public function update(Request $request, $id)
    {
        $transaction = SalesTransaction::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'sales_person_id' => 'sometimes|required|exists:users,id',
            'invoice_no' => 'nullable|string|max:50',
            'product_id' => 'nullable|exists:products,id',
            'product_code' => 'nullable|string|max:50',
            'customer_name' => 'sometimes|required|string|max:255|min:2',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'customer_nrc' => 'nullable|string|max:50',
            'goldsmith_name' => 'nullable|string|max:255',
            'shop_number' => 'nullable|string|max:50',
            'cashier' => 'nullable|string|max:255',
            'color_manager' => 'nullable|string|max:255',
            'responsible_signature' => 'nullable|string|max:255',
            'item_name' => 'sometimes|required|string|max:255|min:2',
            'item_category' => 'nullable|string|max:100',
            'gold_quality' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'item_k' => 'nullable|numeric|min:0',
            'item_p' => 'nullable|numeric|min:0',
            'item_y' => 'nullable|numeric|min:0',
            'item_tg' => 'nullable|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:1|max:10000',
            'unit_price' => 'sometimes|required|numeric|min:0|max:100000000',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'sale_date' => 'sometimes|required|date|before_or_equal:today|after:2020-01-01',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // If product_code is provided, try to find product
        if ($request->has('product_code') && $request->product_code) {
            $product = Product::where('code', $request->product_code)->first();
            if ($product) {
                $request->merge(['product_id' => $product->id]);
            }
        }

        $data = $request->only([
            'sales_person_id', 'invoice_no', 'product_id', 'product_code',
            'customer_name', 'customer_phone', 'customer_address', 'customer_nrc',
            'goldsmith_name', 'shop_number', 'cashier', 'color_manager', 'responsible_signature',
            'item_name', 'item_category', 'gold_quality', 'color', 'length', 'width',
            'item_k', 'item_p', 'item_y', 'item_tg',
            'quantity', 'unit_price', 'commission_rate', 'sale_date', 'notes'
        ]);

        $transaction->update($data);
        $transaction->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Sales transaction updated successfully',
            'data' => $transaction->load(['salesPerson', 'product'])
        ]);
    }

    /**
     * Remove the specified sales transaction.
     */
    public function destroy($id)
    {
        $transaction = SalesTransaction::findOrFail($id);
        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sales transaction deleted successfully'
        ]);
    }

    /**
     * Get statistics summary.
     */
    public function getStats(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $query = SalesTransaction::whereBetween('sale_date', [$dateFrom, $dateTo]);

        if ($request->has('sales_person_id')) {
            $query->where('sales_person_id', $request->sales_person_id);
        }

        $stats = [
            'total_revenue' => $query->sum('total_amount'),
            'total_quantity' => $query->sum('quantity'),
            'total_transactions' => $query->count(),
            'total_commission' => $query->sum('commission_amount'),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get transactions for a specific user.
     */
    public function getUserTransactions($userId, Request $request)
    {
        $query = SalesTransaction::where('sales_person_id', $userId)
            ->with(['salesPerson', 'product']);

        if ($request->has('date_from')) {
            $query->where('sale_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('sale_date', '<=', $request->date_to);
        }

        $perPage = $request->get('per_page', 20);
        $transactions = $query->latest('sale_date')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }
}

