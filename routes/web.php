<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

// หน้า Welcome
Route::get('/', function () {
    return view('welcome');
});

// Route สำหรับ Navigation หลัง Login
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
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'equipmentList'])->name('equipment');
    Route::post('/borrow', [UserController::class, 'borrow'])->name('borrow');
    Route::post('/return/{id}', [UserController::class, 'return'])->name('return');
    Route::get('/history', [UserController::class, 'history'])->name('history');
    Route::get('/return/{id}', [UserController::class, 'showReturnForm'])->name('return.form');
    Route::get('/report/damage', [UserController::class, 'showReportDamageForm'])->name('report.damage.form');
    Route::post('/report/damage', [UserController::class, 'reportDamage'])->name('report.damage');
    Route::get('/history/pdf', [UserController::class, 'exportHistoryAsPDF'])->name('history.pdf');
    Route::get('/pending', [UserController::class, 'pendingRequests'])->name('pending');
    Route::get('/user/pending', [UserController::class, 'pendingRequests'])->name('user.pending');
    Route::put('/pending/update/{id}', [UserController::class, 'updatePendingRequest'])->name('pending.update');
    Route::delete('/pending/cancel/{id}', [UserController::class, 'cancelRequest'])->name('pending.cancel');
    Route::get('/pending/edit/{id}', [UserController::class, 'editPendingRequest'])->name('pending.edit');
    Route::get('/user/{id}', [UserController::class, 'showUser'])->name('user.show');
    Route::post('/return', [UserController::class, 'return'])->name('user.return');
});

// Route สำหรับ Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('equipment', AdminController::class)->except(['show']);
    Route::get('/borrow/approval', [AdminController::class, 'approvalList'])->name('approval');
    Route::patch('/approve/{id}', [AdminController::class, 'approve'])->name('approve');
    Route::get('/borrow/history', [AdminController::class, 'borrowHistory'])->name('borrow.history');
    Route::patch('/reject/{id}', [AdminController::class, 'reject'])->name('reject');
    Route::get('/damage/requests', [AdminController::class, 'showDamageRequests'])->name('damage.requests');
    Route::patch('/damage/approve/{id}', [AdminController::class, 'approveDamageRequest'])->name('damage.approve');
    Route::patch('/damage/reject/{id}', [AdminController::class, 'rejectDamageRequest'])->name('damage.reject');
    Route::get('/borrow-history/pdf', [AdminController::class, 'exportBorrowHistoryPDF'])->name('borrow.history.pdf');
    Route::get('/user/{id}', [AdminController::class, 'showUser'])->name('user.profile');
});

// Route สำหรับโปรไฟล์
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route แสดงรูปภาพจาก Storage
Route::get('/show-image/{filename}', function ($filename) {
    $path = storage_path('app/public/damage_reports/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    return Response::file($path);
})->name('show.image');

// Auth Routes
Auth::routes();
require __DIR__ . '/auth.php';
