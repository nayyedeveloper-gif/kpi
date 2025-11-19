<?php

use App\Http\Controllers\SalesImportController;
use App\Http\Controllers\Admin\RankingCodeController;
use App\Livewire\AdminDashboard;
use App\Livewire\UserManagement;
use App\Livewire\KpiTracking;
use App\Livewire\EntryLevelTracking;
use App\Livewire\KpiAnalytics;
use App\Livewire\KpiReports;
use App\Livewire\TeamPerformance;
use App\Livewire\BranchManagement;
use App\Livewire\GroupManagement;
use App\Livewire\DepartmentManagement;
use App\Livewire\PositionManagement;
use App\Livewire\RoleManagement;
use App\Livewire\ProfileManagement;
use App\Livewire\CollaborativeBoard;

use App\Livewire\SalesPerformance;
use App\Livewire\BonusSetup;
use App\Livewire\SalesEntry;
use App\Livewire\EnhancedSalesEntry;
use App\Livewire\TestSales;
use App\Livewire\BonusAwardManagement;
use App\Livewire\IndividualPerformance;
use App\Livewire\PerformanceKpi;
use App\Livewire\UserProfile;
use App\Http\Controllers\SalesDataController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/profile', ProfileManagement::class)->name('profile');
    Route::get('/users/{user}/profile', UserProfile::class)->name('users.profile');
    Route::get('/users', UserManagement::class)->name('users.index');
    Route::get('/kpi', KpiTracking::class)->name('kpi.index');
    Route::get('/kpi/entry-level', EntryLevelTracking::class)->name('kpi.entry-level');
    // Route::get('/analytics', KpiAnalytics::class)->name('analytics');
    // Route::get('/reports', KpiReports::class)->name('reports');
    // Route::get('/team-performance', TeamPerformance::class)->name('team.performance');
    Route::get('/performance-kpi', PerformanceKpi::class)->name('performance.kpi');
    
    // Ranking Codes Management
    Route::prefix('ranking-codes')->name('ranking-codes.')->group(function () {
        $controller = 'App\Http\Controllers\Admin\RankingCodeController';
        Route::get('/', [$controller, 'index'])->name('index');
        Route::get('/create', [$controller, 'create'])->name('create');
        Route::post('/', [$controller, 'store'])->name('store');
        Route::get('/{rankingCode}', [$controller, 'show'])->name('show');
        Route::get('/{rankingCode}/edit', [$controller, 'edit'])->name('edit');
        Route::put('/{rankingCode}', [$controller, 'update'])->name('update');
        Route::delete('/{rankingCode}', [$controller, 'destroy'])->name('destroy');
        
        // Import/Export Routes
        // Route::get('/import', [$controller, 'showImportForm'])->name('import.form');
        // Route::post('/import', [$controller, 'import'])->name('import');
        Route::get('/export', [$controller, 'export'])->name('export');
    });
    // Route::get('/branches', BranchManagement::class)->name('branches.index');
    // Route::get('/groups', GroupManagement::class)->name('groups.index');
    // Route::get('/departments', DepartmentManagement::class)->name('departments.index');
    // Route::get('/positions', PositionManagement::class)->name('positions.index');
    // Route::get('/roles', RoleManagement::class)->name('roles.index');
    

    
    // Sales Performance & Bonus
    Route::get('/test-sales', TestSales::class)->name('test.sales');
    Route::get('/sales-entry', SalesEntry::class)->name('sales.entry');
    Route::get('/enhanced-sales-entry', EnhancedSalesEntry::class)->name('enhanced.sales.entry');
    // Route::get('/sales-performance', SalesPerformance::class)->name('sales.performance');
    // Route::get('/sales-performance/{id}', IndividualPerformance::class)->name('sales.individual');
    // Route::get('/bonus-setup', BonusSetup::class)->name('bonus.setup');
    // Route::get('/bonus-awards', BonusAwardManagement::class)->name('bonus.awards');
    
    // Drawing Board (Simple & Working)
    // Route::get('/board', App\Livewire\SimpleBoard::class)->name('board.index');
    
    // Collaborative Board (Advanced - if needed)
    Route::get('/board-advanced', CollaborativeBoard::class)->name('board.advanced');
    Route::get('/board-advanced/{boardId}', CollaborativeBoard::class)->name('board.advanced.show');
    
    // Products Management
    // Route::resource('products', ProductController::class);
    // Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    // Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    
    // Sales Data Routes
    Route::prefix('sales')->name('sales.')->group(function () {
        // CRUD Routes
        Route::get('/', [SalesDataController::class, 'index'])->name('data.index');
        Route::get('/create', [SalesDataController::class, 'create'])->name('data.create');
        Route::post('/', [SalesDataController::class, 'store'])->name('data.store');
        Route::get('/{sale}', [SalesDataController::class, 'show'])->name('data.show');
        Route::get('/{sale}/edit', [SalesDataController::class, 'edit'])->name('data.edit');
        Route::put('/{sale}', [SalesDataController::class, 'update'])->name('data.update');
        Route::delete('/{sale}', [SalesDataController::class, 'destroy'])->name('data.destroy');
        
        // Import Routes
        Route::get('/import', [SalesImportController::class, 'index'])->name('import.index');
        Route::post('/import', [SalesImportController::class, 'import'])->name('import.store');
        
        // Export Routes
        Route::get('/export/csv', [SalesDataController::class, 'exportCsv'])->name('data.export.csv');
        Route::get('/export/excel', [SalesDataController::class, 'exportExcel'])->name('data.export.excel');
        Route::get('/export/pdf', [SalesDataController::class, 'exportPdf'])->name('data.export.pdf');
    });
});

// Home route for authenticated users
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Test route for sales page without authentication
Route::get('/test-sales', [App\Http\Controllers\TestController::class, 'sales'])->name('test.sales');

