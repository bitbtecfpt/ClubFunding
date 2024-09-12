<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;



Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pay/{purposeCode}/{phone}', [PaymentController::class, 'displayPaymentForm'])->name('pay');

