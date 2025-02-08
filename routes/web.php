<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

// หน้า Welcome
Route::get('/', function () {
    return view('welcome');
});

// Navigation หลัง Login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role === 'user') {
            return redirect()->route('user.equipment');
        }
        return abort(403, 'Unauthorized action.');
    })->name('dashboard');
});


// Route สำหรับ User
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'equipmentList'])->name('user.equipment');
    Route::post('/user/borrow', [UserController::class, 'borrow'])->name('user.borrow');
    Route::post('/user/return', [UserController::class, 'return'])->name('user.return');
    Route::get('/user/history', [UserController::class, 'history'])->name('user.history');
    Route::get('/user/return/{id}', [UserController::class, 'showReturnForm'])->name('user.return.form');

    // แสดงคำขอที่รออนุมัติ
    Route::get('/user/pending', [UserController::class, 'pendingRequests'])->name('user.pending');

    // ฟอร์มและการจัดการคำขอ
    Route::get('/user/pending/edit/{id}', [UserController::class, 'editPendingRequest'])->name('user.pending.edit');
    Route::put('/user/pending/update/{id}', [UserController::class, 'updatePendingRequest'])->name('user.pending.update');
    Route::delete('/user/pending/cancel/{id}', [UserController::class, 'cancelRequest'])->name('user.pending.cancel');
    Route::get('/user/history/pdf', [UserController::class, 'exportHistoryAsPDF'])->name('user.history.pdf');
    
});


// Route สำหรับ Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/equipment/create', [AdminController::class, 'create'])->name('admin.equipment.create');
    Route::post('/admin/equipment/store', [AdminController::class, 'store'])->name('admin.equipment.store');
    Route::get('/admin/borrow/approval', [AdminController::class, 'approvalList'])->name('admin.approval');
    Route::patch('/admin/approve/{id}', [AdminController::class, 'approve'])->name('admin.approve');
    Route::get('/admin/borrow/history', [AdminController::class, 'borrowHistory'])->name('admin.borrow.history');
    Route::get('/admin/equipment/{id}/edit', [AdminController::class, 'edit'])->name('admin.equipment.edit');
    Route::delete('/admin/equipment/{id}', [AdminController::class, 'destroy'])->name('admin.equipment.destroy');
    Route::put('/admin/equipment/{id}', [AdminController::class, 'update'])->name('admin.equipment.update');
    Route::patch('/admin/reject/{id}', [AdminController::class, 'reject'])->name('admin.reject');
});

// Route สำหรับโปรไฟล์
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth Routes
Auth::routes();
require __DIR__ . '/auth.php';
