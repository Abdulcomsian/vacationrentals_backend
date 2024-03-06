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
        return view('index');
    }
    public function users()
    {
        $users = User::where('type', 'user')->get();
        return view('users', compact('users'));
    }
    public function payments()
    {
        return view('payments');
    }
    public function profile()
    {
        return view('profile');
    }
    public function emails()
    {
        return view('emails');
    }

    public function categories(){
        $categories = Category::with('listings')->where('status', 'activate')->paginate(10);
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
        if(isset($request->users)){
            $listings = Listing::with('getCategories')->where('user_id', $request->users)->where('status', ['1', '2', '3'])->get();
            $users = User::where('type', 'user')->get();
            $user_id = $request->users;
            return view('listings/listings', compact('listings', 'users', 'user_id'));
        }else{
            $listings = Listing::with('getCategories')->where('status', ['1', '2', '3'])->get();
            $users = User::where('type', 'user')->get();
            return view('listings/listings', compact('listings', 'users'));
        }
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
            $toEmail = "admin@vacationrentals.tool";
            Notification::route("mail", $toEmail)->notify(new ContactUsNotification($name, $message, $email));
            return response()->json(["success"=>true, "msg"=>"Your email has been received", "status" => 200], 200);
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong", "status"=> 400], 400);
        }
    }
}
