<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/inventory/export', [InventoryController::class, 'export'])->middleware(['auth', 'verified'])->name('inventory.export');
Route::get('/inventory', [InventoryController::class, 'index'])->middleware(['auth', 'verified'])->name('inventory.index');
Route::post('/inventory', [InventoryController::class, 'store'])->middleware(['auth', 'verified'])->name('inventory.store');
Route::put('/inventory/{equipment}', [InventoryController::class, 'update'])->middleware(['auth', 'verified'])->name('inventory.update');
Route::delete('/inventory/{equipment}', [InventoryController::class, 'destroy'])->middleware(['auth', 'verified'])->name('inventory.destroy');

use App\Http\Controllers\BorrowController;
Route::get('/borrow', [BorrowController::class, 'index'])->middleware(['auth', 'verified'])->name('borrow.index');
Route::post('/borrow', [BorrowController::class, 'store'])->middleware(['auth', 'verified'])->name('borrow.store');
Route::patch('/borrow/{record}/return', [BorrowController::class, 'returnItem'])->middleware(['auth', 'verified'])->name('borrow.return');

use App\Http\Controllers\ReportController;
Route::get('/reports', [ReportController::class, 'index'])->middleware(['auth', 'verified'])->name('reports.index');
Route::get('/reports/export', [ReportController::class, 'export'])->middleware(['auth', 'verified'])->name('reports.export');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
