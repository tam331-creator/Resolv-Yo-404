<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Broadcast;

// -------------------------------------------------------------
// PUBLIC ROUTES (GUEST ONLY / ACCESSIBLE BY ALL)
// -------------------------------------------------------------
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::any('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// --- LALUAN FORGOT & RESET PASSWORD (DILETAKKAN DI SINI) ---
Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [LoginController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [LoginController::class, 'showResetPasswordForm'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [LoginController::class, 'resetPassword'])->middleware('guest')->name('password.update');

// Route AI Integrasi (Penyelesaian Tiket & Chatbot)
Route::post('/ai/suggest-solution', [AiController::class, 'suggestSolution'])->name('ai.suggest');
Route::post('/ai/chat', [AiController::class, 'chat'])->name('ai.chat');

// -------------------------------------------------------------
// PROTECTED ROUTES (MUST BE LOGGED IN)
// -------------------------------------------------------------
Broadcast::routes(['middleware' => ['auth']]);
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/software/export', [AdminController::class, 'exportSoftwareCsv'])->name('admin.software.export');

    // Redireksi / dan /dashboard ke senarai tiket
    Route::get('/', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/dashboard', function () { 
        return redirect()->route('tickets.index'); 
    })->name('dashboard');

    // Route Notifikasi Pop-Up Browser
    Route::get('/notifications/check', [TicketController::class, 'checkNotifications'])->name('notifications.check');

    // Route Tiket Standard
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::post('/tickets/{ticket}/typing', [TicketController::class, 'typing'])->name('tickets.typing');
    Route::patch('/tickets/{id}/assign', [TicketController::class, 'assignTechnician'])->name('tickets.assign');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    
    // Route Penilaian Bintang (Star Rating)
    Route::post('/tickets/{id}/rating', [TicketController::class, 'submitRating'])->name('tickets.rating');

    // Route Analytics (Diubahsuai untuk menggunakan AnalyticsController)
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Route Modul ITAM
    Route::get('/assets/{asset}/history', [AssetController::class, 'getHistory'])->name('assets.history');
    Route::resource('assets', AssetController::class);

    // Route Administration Center
    Route::get('/administration-center', [AdminController::class, 'center'])->name('admin.center');
    Route::post('/administration-center/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::post('/administration-center/assets', [AdminController::class, 'storeAsset'])->name('admin.assets.store');
    Route::post('/admin/software/store', [AdminController::class, 'storeSoftware'])->name('admin.software.store');
    Route::put('/admin/software/{id}', [AdminController::class, 'updateSoftware'])->name('admin.software.update');
    Route::delete('/admin/software/{id}', [AdminController::class, 'destroySoftware'])->name('admin.software.destroy');


    // Route Policies & Knowledge Base
    Route::get('/policies', function() {
        return view('policies.index');
    })->name('policies.index');

    // -------------------------------------------------------------
    // ROUTE PROFILE & SETTINGS
    // -------------------------------------------------------------
    Route::get('/settings', [ProfileController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [ProfileController::class, 'updateProfile'])->name('settings.profile.update');
    Route::post('/settings/password', [ProfileController::class, 'updatePassword'])->name('settings.password.update');
});