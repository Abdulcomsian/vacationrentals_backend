<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\{
    UserController,
    BillingController,
    PlanController,
    HomeController,
    CategoryController,
};
Auth::routes();

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
// Callback URLs of STRIPE after payment
Route::get('/payment_success', [BillingController::class, 'handleSuccess'])->name('payment.success');
Route::get('/payment_cancel', [BillingController::class, 'handleCancel'])->name('payment.cancel');


// Admin Routes Starts here
Route::middleware(['auth:web', 'admin'])->group(function(){
    Route::get('dashboard', [HomeController::class, 'index'])->name('home');
    Route::get('categories', [HomeController::class, 'categories'])->name('categories');
    Route::get('companies', [HomeController::class, 'companies'])->name('companies');
    Route::get('packages', [HomeController::class, 'packages'])->name('packages');

    // Categories Route
    Route::post('add-category', [CategoryController::class, 'storeCategory'])->name('store.category');
    Route::post('delete-modal', [CategoryController::class, 'deleteCategory'])->name('delete.category');
});

// Admin Routes ends here