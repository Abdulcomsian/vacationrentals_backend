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
        $users = User::where('type', 'user')->get();
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
        return view('emails');
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
            $toEmail = "peterfischerflorez@gmail.com";
            Notification::route("mail", $toEmail)->notify(new ContactUsNotification($name, $message, $email));
            return response()->json(["success"=>true, "msg"=>"Your email has been received", "status" => 200], 200);
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong", "status"=> 400], 400);
        }
    }
}
