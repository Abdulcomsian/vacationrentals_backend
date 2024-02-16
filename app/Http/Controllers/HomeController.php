<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Repository\UserHandler;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Notifications\SendEmailForgotPassword;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class HomeController extends Controller
{
      
    public function index()
    {
        return view('index');
    }
    public function categories()
    {
        return view('categories');
    }
    public function companies()
    {
        return view('companies');
    }
    public function packages()
    {
        return view('packages');
    }
}