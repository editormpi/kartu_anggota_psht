<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BillController as AdminBillController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\PaymentHistoryController as AdminPaymentHistoryController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Portal\BillController;
use App\Http\Controllers\Portal\CertificateController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\PaymentHistoryController;
use App\Http\Controllers\Portal\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// ── Admin ──────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function (): void {
    // Guest (belum login admin)
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    // Protected (sudah login admin)
    Route::middleware('admin')->group(function (): void {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('members', AdminMemberController::class);
        Route::post('members/{member}/activate', [AdminMemberController::class, 'activate'])->name('members.activate');
        Route::post('members/{member}/deactivate', [AdminMemberController::class, 'deactivate'])->name('members.deactivate');
        Route::post('members/{member}/reset-password', [AdminMemberController::class, 'resetPassword'])->name('members.reset-password');

        Route::resource('bills', AdminBillController::class);

        Route::resource('payments', AdminPaymentHistoryController::class);

        Route::resource('certificates', AdminCertificateController::class);
        Route::post('certificates/{certificate}/generate-pdf', [AdminCertificateController::class, 'generatePdf'])->name('certificates.generate-pdf');
    });
});

Route::middleware('guest:member')->group(function (): void {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle.login');
});

Route::middleware('auth:member')->group(function (): void {
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::get('/password/change', [ChangePasswordController::class, 'show'])
        ->name('password.change');
    Route::post('/password/change', [ChangePasswordController::class, 'store']);

    Route::middleware('require.password.change')->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'show'])
            ->name('portal.dashboard');
        Route::get('/profile', [ProfileController::class, 'show'])
            ->name('portal.profile');
        Route::get('/bills', [BillController::class, 'index'])
            ->name('portal.bills');
        Route::get('/payment-history', [PaymentHistoryController::class, 'index'])
            ->name('portal.payment-history');
        Route::get('/certificates', [CertificateController::class, 'index'])
            ->name('portal.certificates');
        Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])
            ->name('portal.certificates.download');
    });
});
