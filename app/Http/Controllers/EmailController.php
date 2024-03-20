<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    User,
    Plan,
    Subscription,
    Listing,
    Category,
    ListingCategory,
    Deal,
    Email,
};

class EmailController extends Controller
{
    public function storeEmail(Request $request){
        $request->validate([
            'subject'=>'required',
            'email_description'=>'required'
        ]);
        try{
            if($request->type == "forgot_password"){
                $email = Email::updateOrCreate(
                    ['type' => 'forgot_password'],
                    [
                        'subject' => $request->subject,
                        'message' => $request->email_description,
                        'type' => $request->type,
                    ]
                );
            }elseif($request->type == "signup_email_verification"){
                $email = Email::updateOrCreate(
                    ['type' => 'signup_email_verification'],
                    [
                        'subject' => $request->subject,
                        'message' => $request->email_description,
                        'type' => $request->type,
                    ]
                );
            }elseif($request->type == "contact_us_email"){
                $email = Email::updateOrCreate(
                    ['type' => 'contact_us_email'],
                    [
                        'subject' => $request->subject,
                        'message' => $request->email_description,
                        'type' => $request->type,
                    ]
                );
            }elseif($request->type == "listing_submission"){
                $email = Email::updateOrCreate(
                    ['type' => 'contact_us_email'],
                    [
                        'subject' => $request->subject,
                        'message' => $request->email_description,
                        'type' => $request->type,
                    ]
                );
            }
            
            if($email){
                return redirect()->back()->with(["success"=>"Email data saved successfully"]);
            }
        }catch(\Exception $e){
            return redirect()->back()->with(["error"=>"Something Went Wrong. Please try again Later"]);
        }
    }
}
