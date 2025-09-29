<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HRDController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\kabagController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\MasterUserController;
use App\Http\Controllers\QuotaController;
use App\Http\Controllers\RekapController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// route rekap cuti
Route::middleware(['auth', 'role:hrd,super_admin'])
    ->prefix('hrd')->name('hrd.')
    ->group(function () {
        Route::get('rekap', [RekapController::class, 'index'])->name('rekap.index');
    });

// route dahsboard user
// Route::middleware(['auth', 'role:super_admin'])->group(function () {
//     Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
// });

// Route::middleware(['auth', 'role:hrd'])->group(function () {
//     Route::get('/hrd/dashboard', [DashboardController::class, 'hrd'])->name('hrd.dashboard');
// });

// Route::middleware(['auth', 'role:kabag'])->group(function () {
//     Route::get('/kabag/dashboard', [DashboardController::class, 'kabag'])->name('kabag.dashboard');
// });
// Route::middleware(['auth', 'role:kasie'])->group(function () {
//     Route::get('/kasie/dashboard', [DashboardController::class, 'kasie'])->name('kasie.dashboard');
// });

// Route::middleware(['auth', 'role:staff'])->group(function () {
//     Route::get('/staff/dashboard', [DashboardController::class, 'staff'])->name('staff.dashboard');
// });

//route cuti & approve
Route::middleware(['auth'])->group(function () {

    Route::prefix('cuti')->name('cuti.')->group(function () {
        Route::resource('', LeaveController::class)
            ->parameters(['' => 'leave'])
            ->only(['index', 'create', 'store', 'destroy']); // tambah edit/update/destroy kalau perlu
    });

    Route::prefix('approval')->name('approval.')->group(function () {
        Route::get('/', [ApprovalController::class, 'index'])->name('index');
        Route::get('/history', [ApprovalController::class, 'history'])->name('history');
        Route::patch('/{approval}/approve', [ApprovalController::class, 'approve'])->name('approve');
        Route::patch('/{approval}/reject', [ApprovalController::class, 'reject'])->name('reject');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// route set kuota cuti
Route::middleware(['auth', 'role:hrd,super_admin'])->prefix('hrd')->name('hrd.')->group(function () {
    Route::get('quota', [QuotaController::class, 'index'])->name('quota.index');
    Route::post('quota/reset', [QuotaController::class, 'resetAll'])->name('quota.reset');
    Route::post('quota/reset-division', [QuotaController::class, 'resetDivision'])->name('quota.resetDivision');
    Route::post('quota/{user}', [QuotaController::class, 'update'])->name('quota.update');
});

// route master for super admin
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('divisions', DivisionController::class);
    Route::resource('users', MasterUserController::class);
    Route::post('users/{user}/reset-password', [MasterUserController::class, 'resetPassword'])->name('users.resetPassword');
});

require __DIR__ . '/auth.php';
