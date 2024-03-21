<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{
    User,
    Plan,
    Subscription,
    Listing,
    Category,
    Email
};
use Yajra\DataTables\Contracts\DataTable;
use DataTables;
use App\Notifications\ContactUsNotification;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->gaurd('web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */
    public function index()
    {
        $users = User::where('type', 'user')->count();
        $listings = Listing::where('status', '1')->orWhere('status', '2')->orWhere('status', '3')->count();
        $payments = Subscription::select("stripe_price")->get();
        $totalPayment = 0;
        foreach($payments as $payment){
            $totalPayment = $totalPayment + $payment->stripe_price;
        }
        return view('index', compact('users', 'listings', 'totalPayment'));
    }
    public function users()
    {
        $users = User::where('type', 'user')->withTrashed()->get();
        return view('users', compact('users'));
    }
    public function payments()
    {
        $payments = Subscription::with('user')->get();
        return view('payments', compact('payments'));
    }
    public function profile()
    {
        $adminDetail = User::where('type', 'admin')->first();
        return view('profile', compact('adminDetail'));
    }
    public function emails()
    {
        $forgotPasswordEmail = Email::where('type', 'forgot_password')->first();
        $signupEmail = Email::where('type', 'signup_email_verification')->first();
        $contactUsEmail = Email::where('type', 'contact_us_email')->first();
        $listingSubmit = Email::where('type', 'listing_submission')->first();
        $listingApproval = Email::where('type', 'listing_approval')->first();
        return view('emails', compact('forgotPasswordEmail', 'signupEmail', 'contactUsEmail', 'listingSubmit', 'listingApproval'));
    }

    public function categories(){
        $categories = Category::with('listings')->where('status', 'activate')->where("id", "!=", "1")->paginate(10);
        return view('categories', compact('categories'));
    }
    
    public function listings(Request $request){
        /*
            Listing Status Meanings
            0 - Draft
            1 - Pending
            2 - Approved
            3 - Rejected
        */        
        $listings = Listing::with(['getCategories', 'plan'])->where('status', '1')->orWhere('status', '2')->orWhere('status', '3')->get();
        $users = User::where('type', 'user')->get();
        return view('listings/listings', compact('listings', 'users'));
    }

    public function packages(){
        $plans = Plan::get();
        return view('packages', compact('plans'));
    }

    // contact form api function
    public function contactForm(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $validations = ['name', 'email'];
        $errors = [];
        foreach($validations as $val){
            foreach($validator->errors()->get($val) as $error){
                $errors[] = $error;
            }
        }

        if(!empty($errors)){
            return response()->json(["success"=>false, "error"=>$errors, "status"=>400], 400);
        }

        try{
            $name = $request->name;
            $email = $request->email;
            $message = $request->message;
            // $toEmail = "peterfischerflorez@gmail.com";
            $toEmail = "abc@getnada.com";
            // Getting the Email Content from Database
            $emailData = Email::where('type', 'contact_us_email')->first();
            if(isset($emailData) && count($emailData) > 0){
                $subject = $emailData->subject;
                $emailSubject = str_replace("[NAME]", $name, $subject);
                $emailMessage = $emailData->message;
                $emailContent = str_replace(["[NAME]", "[EMAIL]", "[MESSAGE]"], [$name, $email, $message], $emailMessage);
                Notification::route("mail", $toEmail)->notify(new ContactUsNotification($emailSubject, $emailContent));
                return response()->json(["success"=>true, "msg"=>"Your email has been received", "status" => 200], 200);
            }else{
                return response()->json(["success"=>false, "msg"=>"Admin haven`t added email content", "status" => 400], 400);
            }            
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong","error"=>$e->getMessage(), "line"=>$e->getLine(), "status"=> 400], 400);
        }
    }
}
