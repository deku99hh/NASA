<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FernController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/dashboard', function () {  
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chat-fern', function () {
        return view('chat-fern-1.fern1');
    })->name('chat-fern-1.fern1');


    
    Route::get('/chat-fern', [FernController::class, 'index'])->name('chat-fern-1.fern1');
    Route::post('/chat-fern', [FernController::class, 'store'])->name('fern.store');


    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');
});

require __DIR__.'/auth.php';
