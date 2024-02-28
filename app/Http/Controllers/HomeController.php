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

    public function categories(){
        $categories = Category::with('listings')->where('status', 'activate')->get();
        return view('categories', compact('categories'));
    }

    public function listings(){
        $listings = Listing::get();
        return view('listings/listings', compact('listings'));
    }

    public function packages(){
        return view('packages');
    }
}
