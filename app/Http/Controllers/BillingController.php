<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\{
    User,
    Plan,
    Subscription,
    Listing,
};
use Carbon\Carbon;

class BillingController extends Controller
{

    public function checkout(Request $request){
        $validator = Validator::make($request->all(), [
            'price_id' => 'required',
            'website_link' => 'required',
        ]);

        $price_id = $validator->errors()->get('price_id');
        $websiteLink = $validator->errors()->get('website_link');

        foreach($price_id as $price){
            return response()->json(["success" => false, "msg" => $price, "status" => 400], 400); 
        }

        foreach($websiteLink as $website){
            return response()->json(["success" => false, "msg" => $website, "status" => 400], 400);
        }
        try{
            // Stripe Checkout Page Integration Start here
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            header('Content-Type: application/json');

            $user = Auth::user();

            $customer = \Stripe\Customer::create([
                'email' => $user->email,
            ]);

            $priceId = $request->price_id;
            $isCouponEligible = false;
            if($priceId === "price_1OrHLaLyI7mncMRJA9dBBgIa"){
                $isCouponEligible = true;
            }
            
            if($isCouponEligible){
                $checkout_session = \Stripe\Checkout\Session::create([
                    'customer' => $customer->id,
                    'line_items' => [[
                        'price' => $request->price_id,
                        'quantity' => 1,
                    ]],
                    'discounts' => [[
                        'coupon' => 'fvpqsWdu',
                    ]],
                    'mode' => 'subscription',
                    'cancel_url' => url('https://vacationrentals.tools/dashboard/addlisting'),
                    'success_url' => url('payment_success?session_id={CHECKOUT_SESSION_ID}'),
                    'metadata' => [
                        'website_link' => $request->website_link,
                        'price_id' => $request->price_id
                    ],
                    ]);
            }else{
                $checkout_session = \Stripe\Checkout\Session::create([
                    'customer' => $customer->id,
                    'line_items' => [[
                        'price' => $request->price_id,
                        'quantity' => 1,
                    ]],
                    'mode' => 'subscription',
                    'cancel_url' => url('https://vacationrentals.tools/dashboard/addlisting'),
                    'success_url' => url('payment_success?session_id={CHECKOUT_SESSION_ID}'),
                    'metadata' => [
                        'website_link' => $request->website_link,
                        'price_id' => $request->price_id
                    ],
                    ]);
            }
            $url = $checkout_session->url; // Stripe checkout page URL
            $sessiondId = $checkout_session->id;
            $session = Session::put('session_id', $sessiondId);
            return response()->json(["success"=>true, "redirectURL"=>$url, 'sessionId'=>$sessiondId, "status"=>200], 200);
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something went wrong", "error"=>$e->getMessage(), "status"=>400], 400);
        }
    }


    public function handleSuccess(Request $request){
        try{
            $sessionId = $request->session_id;
            if(isset($sessionId)){
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $checkout_session = $stripe->checkout->sessions->retrieve($sessionId, []);
                $user_email = $checkout_session->customer_details->email;
                $userData = User::where('email', $user_email)->first();
                // Stripe gives us the amount in the smallest currency in this case its giving us in Cents so am converting cents to dollars
                $stripePrice = number_format($checkout_session->amount_total / 100, 2);
                $timestamp = $checkout_session->expires_at;
                $expirationDate = date('Y-m-d H:i:s', $timestamp);

                // getting the plan Id from price ID here
                $price_id = $checkout_session->metadata->price_id;
                $planId = Plan::where('plan_id', $price_id)->first();

                // Storing tool link
                $toolLink = new Listing();
                $toolLink->user_id = $userData->id;
                $toolLink->company_link = $checkout_session->metadata->website_link;
                $toolLink->plan_id = $planId->id;
                $toolLink->save();

                // Store plan data
                $subscription = Subscription::create([
                    'user_id' => $userData->id,
                    'type' => $checkout_session->mode,
                    'stripe_id' =>  $checkout_session->id,
                    'payment_status' => $checkout_session->payment_status,
                    'stripe_price' => $stripePrice,
                    'currency' => $checkout_session->currency,
                    'stripe_subscription_id' => $checkout_session->subscription,
                    'invoice_id' => $checkout_session->invoice,
                    'price_id' => $checkout_session->metadata->price_id,
                    'listing_id' => $toolLink->id
                ]);               

                $is_featured = "false";
                if($planId->plan_type == 'Featured'){
                    $is_featured = "true";
                }

                // return response()->json(["success"=>true, "msg"=>"Subscription added Successfully"], 200);
                return redirect('https://vacationrentals.tools/dashboard/addtool?id=' . $toolLink->id . '&featured=' . $is_featured);
            }else{
                return response()->json(["success"=>false, "msg"=>"Unauthorized User", "status"=>401],401);
            }
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something went wrong", "error"=>$e->getMessage(), "status"=>400], 400);
        }
        
    }

    public function handleCancel(){
        try{
            return response()->json(["success"=>true, "msg"=>"Sorry your payment has been cancelled. Please try again later"]);
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong", "error"=>$e->getMessage(), "status"=>400], 400);
        }
    }

    public function cancelSubscription(Request $request){
        try{
            $subscriptionId = $request->subscription_id;
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $stripe->subscriptions->update(
                $subscriptionId,
                ['pause_collection' => ['behavior' => 'void']]
              );
            // Getting the subscription data against subscription Id
            $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();
            // Getting the listing Id to update the column subscription status in listings table
            $listingId = $subscription->listing_id;
            // updating.....
            $listing = Listing::where('id', $listingId)->update(["subscription_status" => "cancelled"]);
            // updating the subscription
            $subscription->update(['subscription_cancel_date' => Carbon::now()]);
            return response()->json(["success"=>true, "msg"=>"Subcription cancelled successfully", "status"=>200], 200);
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong", "error"=>$e->getMessage(), "status"=>400], 400); 
        }
    }

    public function resumeSubscription(Request $request){
        try{
            $subscriptionId = $request->subscription_id;
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            \Stripe\Subscription::update(
            $subscriptionId,
            [
                'pause_collection' => '',
            ]
            );
            // Getting the subscription data against subscription Id
            $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();
            // Getting the listing Id to update the column subscription status in listings table
            $listingId = $subscription->listing_id;
            // updating.....
            $listing = Listing::where('id', $listingId)->update(["subscription_status" => "active"]);
            // updating the subscription
            $subscription->update(['subscription_cancel_date' => NULL]);
            return response()->json(["success"=>true, "msg"=>"Subcription resumed successfully", "status"=>200], 200);
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong", "error"=>$e->getMessage(), "status"=>400], 400);
        }
    }
}
