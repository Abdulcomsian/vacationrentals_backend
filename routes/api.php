<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,
    BillingController,
    PlanController,
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/send-email-forgot-password', [UserController::class, 'sendEmailPassword']);
Route::post('/update-password', [UserController::class, 'updatePassword']);


Route::middleware('check.authentication')->group(function(){
    Route::post('/checkout', [BillingController::class, 'checkout']);
    Route::get('/plans', [PlanController::class, 'showPlans']);
});


Route::get('/payment_success/{sessionId}', [BillingController::class, 'handleSuccess'])->name('payment.success');
