<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\OrderController;
use App\Models\Event;

// 1. RUTE PUBLIK
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. RUTE AUTH MANUAL (Gak pake ribet)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. RUTE MEMBER (Wajib Login)
Route::middleware('auth')->group(function () {
    // Dashboard Member
    Route::get('/dashboard', function () {
        $events = Event::latest()->get();
        return view('homepage', compact('events'));
    })->name('dashboard');
    // --- (TIKET SAYA) ---
    Route::get('/my-tickets', [HomeController::class, 'myTickets'])->name('my.tickets');
    // Belanja Tiket
    Route::get('/event/{id}/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
});

// 4. RUTE ADMIN (Wajib Login & Wajib Admin)
Route::middleware(['auth'])->prefix('admin')->group(function () {


    Route::post('/check-ticket', [AdminController::class, 'checkTicket'])->name('admin.ticket.check');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Rute buat nambah & hapus event
    Route::post('/event', [AdminController::class, 'store'])->name('admin.event.store');
    Route::delete('/event/{id}', [AdminController::class, 'destroy'])->name('admin.event.destroy');
    Route::put('/event/{id}', [AdminController::class, 'update'])->name('admin.event.update');

});