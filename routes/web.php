<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountProcessingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::middleware(['auth', 'verified'])->group(function () {
    // account processing routes
    Route::get('/dashboard', [AccountProcessingController::class, 'dashboard'])->name('dashboard');
    Route::get('/deposits', [AccountProcessingController::class, 'deposits'])->name('deposits');
    Route::get('/deposit', [AccountProcessingController::class, 'deposit'])->name('deposit');
    Route::post('/deposit', [AccountProcessingController::class, 'depositSubmit'])->name('depositSubmit');
    Route::get('/withdraws', [AccountProcessingController::class, 'withdraws'])->name('withdraws');
    Route::get('/withdraw', [AccountProcessingController::class, 'withdraw'])->name('withdraw');
    Route::post('/withdraw', [AccountProcessingController::class, 'withdrawSubmit'])->name('withdrawSubmit');
    // account processing routes

});

require __DIR__.'/auth.php';
