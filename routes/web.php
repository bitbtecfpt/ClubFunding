<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pay/{purposeCode}/{phone}/{name}', [PaymentController::class, 'displayPaymentForm'])->name('pay');

Route::post('/receive_transaction', [PaymentController::class, 'storeTransaction'])->withoutMiddleware([VerifyCsrfToken::class])->name('receive_transaction');

Route::get('/check-transaction/{transactionDescription}', [PaymentController::class, 'checkTransaction']);

Route::get('/transaction-history', [PaymentController::class, 'allTransactions']);
