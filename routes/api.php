<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SalesTransactionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/departments', function () {
    return response()->json([
        'success' => true,
        'data' => \App\Models\Department::where('is_active', true)->orderBy('name')->get()
    ]);
});
Route::get('/positions', function () {
    return response()->json([
        'success' => true,
        'data' => \App\Models\Position::where('is_active', true)->orderBy('hierarchy_level')->get()
    ]);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    
    // Sales Transactions routes
    Route::apiResource('sales-transactions', SalesTransactionController::class);
    Route::get('/sales-transactions/stats/summary', [SalesTransactionController::class, 'getStats']);
    Route::get('/sales-transactions/user/{userId}', [SalesTransactionController::class, 'getUserTransactions']);
    
    // Products routes
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/search/{code}', [ProductController::class, 'searchByCode']);
});

