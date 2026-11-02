<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepairRequestController;
use Illuminate\Support\Facades\Route;

// หน้าแรก ให้ Redirect ไปหน้าแจ้งซ่อม
Route::get('/', function () {
    return redirect()->route('repairs.index');
});

// หน้า Dashboard ให้ Redirect ไปหน้าแจ้งซ่อม
Route::get('/dashboard', function () {
    return redirect()->route('repairs.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// เส้นทางที่ต้องผ่านการเข้าสู่ระบบ (Auth)
Route::middleware('auth')->group(function () {
    // ระบบแจ้งซ่อม
    Route::get('/repairs', [RepairRequestController::class, 'index'])->name('repairs.index');
    Route::get('/repairs/create', [RepairRequestController::class, 'create'])->name('repairs.create');
    Route::post('/repairs', [RepairRequestController::class, 'store'])->name('repairs.store');
    Route::get('/repairs/{repair}', [RepairRequestController::class, 'show'])->name('repairs.show');
    Route::patch('/repairs/{repair}/status', [RepairRequestController::class, 'updateStatus'])->name('repairs.updateStatus');

    // จัดการโปรไฟล์ของ Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
