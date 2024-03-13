<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,
    BillingController,
    CategoryController,
    HomeController,
    PlanController,
    ListingController,
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
Route::post('/verfiy-code', [UserController::class, 'verifyCode']);
Route::post('/update-password', [UserController::class, 'updatePassword']);


Route::middleware(['check.authentication'])->group(function(){
    Route::post('/checkout', [BillingController::class, 'checkout']);
    // Protected Route Plan API
    Route::get('/show-plans-protected', [PlanController::class, 'showPlans']);
    
    // Listing Apis
    Route::post('/listing-detail', [ListingController::class, 'showListingDetail']);
    Route::post('/add-listing', [ListingController::class, 'addListing']);
    Route::post('/update-listing', [ListingController::class, 'addListing']);
    Route::get('/show-all-listings', [ListingController::class, 'showAllListing']);
    Route::post('/delete-listing', [ListingController::class, 'deleteListingUser']);
});

// Categories Apis Detail
Route::get('/show-category', [CategoryController::class, 'showCategory']);
Route::get('/show-category-element', [CategoryController::class, 'showCategoryElement']);

// Unprotected Plans API
Route::get('/plans', [PlanController::class, 'showPlans']);

// Contact Form API
Route::post('/contact-form', [HomeController::class, 'contactForm']);