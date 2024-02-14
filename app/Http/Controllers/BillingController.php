<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Repository\UserHandler;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    User,
    Plan,
    Subscription,
};

class BillingController extends Controller
{

    public function checkout(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'price_id' => 'required',
            ]);

            if($validator->fails()){
                return respone(["success"=>false, "msg"=>"Validation error", "error"=>$validator->getMessageBag]);
            }

            // Stripe Checkout Page Integration Start here
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            header('Content-Type: application/json');

            $user = Auth::user();

            $customer = \Stripe\Customer::create([
                'email' => $user->email,
            ]);

            $checkout_session = \Stripe\Checkout\Session::create([
            'customer' => $customer->id,
            'line_items' => [[
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                'price' => $request->price_id,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('payment.success', ['sessionId'=>'{CHECKOUT_SESSION_ID}']),
            'cancel_url' => 'http://127.0.0.1:8000/cancel.html',
            ]);
            $url = $checkout_session->url; // Stripe checkout page URL
            $sessiondId = $checkout_session->id;
            return response()->json(["success"=>true, "redirectURL"=>$url, 'sessionId'=>$sessiondId], 200);
        }catch(\Exception $e){
            return response()->json(["success"=>true, "msg"=>"Something went wrong", "error"=>$e->getMessage()], 400);
        }
    }

    public function handleSuccess(Request $request, $sessionId){
        try{
            if(isset($sessionId)){
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $checkout_session = $stripe->checkout->sessions->retrieve($sessionId, []);
                $user_email = $checkout_session->customer_details->email;
                $userData = User::where('email', $user_email)->first();

                // Stripe gives us the amount in the smallest currency in this case its giving us in Cents so am converting cents to dollars
                $stripePrice = number_format($checkout_session->amount_total / 100, 2);
                $timestamp = $checkout_session->expires_at;
                $expirationDate = date('Y-m-d H:i:s', $timestamp);

                $subscription = Subscription::create([
                    'user_id' => $userData->id,
                    'type' => $checkout_session->mode,
                    'stripe_id' =>  $checkout_session->id,
                    'payment_status' => $checkout_session->payment_status,
                    'stripe_price' => $stripePrice,
                    'currency' => $checkout_session->currency,
                    'stripe_subscription_id' => $checkout_session->subscription,
                    'invoice_id' => $checkout_session->invoice,
                    'ends_at' => $expirationDate
                ]);

                return response()->json(["success"=>true, "msg"=>"Subscription added Successfully"], 200);
            }else{
                return response()->json(["success"=>false, "msg"=>"Unauthorized User"],401);
            }
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something went wrong", "error"=>$e->getMessage()], 400);
        }
        
    }
}
