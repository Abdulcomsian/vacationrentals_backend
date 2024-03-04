<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    User,
    Plan,
    Subscription,
    Listing,
    Category,

};

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
        return view('users');
    }

    public function categories(){
        $categories = Category::with('listings')->where('status', 'activate')->paginate(10);
        return view('categories', compact('categories'));
    }
    
    public function listings(Request $request){
        if(isset($request->users)){
            $listings = Listing::with('getCategories')->where('user_id', $request->users)->get();
            $users = User::where('type', 'user')->get();
            $user_id = $request->users;
            return view('listings/listings', compact('listings', 'users', 'user_id'));
        }else{
            $listings = Listing::with('getCategories')->get();
            $users = User::where('type', 'user')->get();
            return view('listings/listings', compact('listings', 'users'));
        }
    }

    public function packages(){
        $plans = Plan::get();
        return view('packages', compact('plans'));
    }
}
