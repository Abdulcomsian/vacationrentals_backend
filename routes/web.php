<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\{
    UserController,
    BillingController,
    PlanController,
};

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
});

// Callback URLs of STRIPE after payment
Route::get('/payment_success', [BillingController::class, 'handleSuccess'])->name('payment.success');
Route::get('/payment_cancel', [BillingController::class, 'handleCancel'])->name('payment.cancel');


// Route::get('test', function(Request $request){
//     // dd($request->all());
//     $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
//     $checkout = $stripe->checkout->sessions->create([
//     'success_url' => url('successTest', CHECKOUT_SESSION_ID),
//     'line_items' => [
//         [
//         'price' => 'price_1OjJrqLyI7mncMRJxo1KhAy6',
//         'quantity' => 1,
//         ],
//     ],
//     'mode' => 'subscription',
//     ]);
    
//     $sessionUrl = $checkout->url;
//     // Session::create(['stripe_session' => $checkout->id]);
//     return redirect()->route($sessionUrl);

// });

// Route::get('successTest/{id}', function(Request $request){
//     dd($request->all());
//     $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
//     $stripeSession = Session::get('stripe_session');
//     $sessionData = $stripe->checkout->sessions->retrieve($stripeSession , []);
//     dd($sessionData);
//     // dd($request->all());
// });