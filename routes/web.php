<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\{
    UserController,
    BillingController,
    PlanController,
    HomeController,
    CategoryController,
    ListingController,
    EmailController,
};
use App\Models\{
    User,
    Plan,
    Subscription,
    Listing,
    Category,
    ListingCategory,
    Deal,
    Email
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

// Verifying Email
Route::get('verify-email/{user_id}/{token}', [UserController::class, 'verifyEmail']);

// Admin Routes Starts here
Route::middleware(['auth:web', 'admin'])->group(function(){
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('categories', [HomeController::class, 'categories'])->name('categories');
    Route::any('listings', [HomeController::class, 'listings'])->name('listings');
    Route::any('users', [HomeController::class, 'users'])->name('users');
    Route::get('packages', [HomeController::class, 'packages'])->name('packages');
    Route::get('payments', [HomeController::class, 'payments'])->name('payments');
    Route::get('profile', [HomeController::class, 'profile'])->name('profile');
    Route::get('emails', [HomeController::class, 'emails'])->name('emails');

    // listing filter
    Route::post('listing-filter', [HomeController::class, 'listingFilter'])->name('listing.filter');

    // Categories Route
    Route::post('add-category', [CategoryController::class, 'storeCategory'])->name('store.category');
    Route::post('delete-modal', [CategoryController::class, 'deleteCategory'])->name('delete.category');
    Route::post('show-edit-category', [CategoryController::class, 'showEditCategory'])->name('show.edit');
    Route::post('store-edit-category', [CategoryController::class, 'updateCategory'])->name('update.category');

    // Listing Routes
    Route::get('add-listings', [ListingController::class, 'add_listing']);
    Route::get('edit-listing/{id}', [ListingController::class, 'editListing']);
    Route::post('store-listing', [ListingController::class, 'storeListing'])->name('store.listing');
    Route::post('update-listing', [ListingController::class, 'updateListing'])->name('update.listing');
    Route::post('delete-listing', [ListingController::class, 'deleteListing'])->name('delete.listing');
    Route::post('listing-datatable', [ListingController::class, 'listingDataTable'])->name('listing.datatable');

    // Plan or Package Routes
    Route::post('update-plan', [PlanController::class, 'updatePlan'])->name('update.plan');
    Route::post('show-edit-plan' ,[PlanController::class, 'showEdit'])->name('show.edit.plan');

    // User Routes for Admin
    Route::post('delete-user', [UserController::class, 'deleteUser'])->name('delete.user');
    Route::post('update-admin-profile', [UserController::class, 'updateAdmin'])->name('update.admin.profile');
    Route::post('change-password', [UserController::class, 'changePassword'])->name('change.password');
    Route::post('restore-user', [UserController::class, 'restoreUser'])->name('restore.user');

    // Email Routes
    Route::post('store-email', [EmailController::class, 'storeEmail'])->name('store.email');
});

// Admin Routes ends here


Route::get('test', function(){
    $listings = Listing::where('slug', NULL)->get();
    foreach($listings as $listing){
        // Creating Slug
        $companyName = $listing->company_name;
        $slug = strtolower($companyName);
        $slug = preg_replace('/[^a-z0-9]+/', '_', $slug);
        $slug = trim($slug, '_');
        // Checking if slug is already in the database
        $originalSlug = $slug;
        for($i = 1; Listing::where('slug', $slug)->exists(); $i++){
            $slug = $originalSlug . '_' . $i;
        }

        Listing::where('id', $listing->id)->update(['slug'=> $slug]);
        echo "succes";

    }
});