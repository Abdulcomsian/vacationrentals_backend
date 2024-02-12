<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Repository\UserHandler;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class UserController extends Controller
{
    // protected $userHandler;

    // public function __construct(UserHandler $userHandler)
    // {
    //     $this->userHandler = $userHandler;
    //     // $this->middleware('auth:api', ['except' => ['login' , 'register' ,'verifyUser']]);
    // }

    public function register(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                "name" => "required|string",
                "email" => "required|string|unique:users,email",
                "password" => "required|string",
            ]);

            if ($validator->fails()) {                
                return response()->json(["success" => false, "msg" => "Validation error", "error" => $validator->getMessageBag()] , 400);            
            } else {
                $name = $request->name;
                $email = $request->email;
                $password = $request->password;
                $tcStatus = $request->tc_status;
                User::create([
                    "name" => $name,
                    "email" => $email,
                    "password" => Hash::make($password),
                    "tc_status" => $tcStatus,
                ]);
                // return $this->userHandler->findUser($email, $password , "register");
                return response()->json(["success" => true, "msg" => "User Created Successfully", 200]);
            }
        } catch (\Exception $e) {
            return response()->json(["success" => false, "msg" => "Something went wrong", "error" => $e->getMessage()] , 401);        
        }
    }

    public function login(Request $request){
        try{
            $validator =  Validator::make($request->all(), [
                "email" => "required|string|email",
                "password" => "required|string",
            ]);

            if($validator->fails()){
                return response()->json(["success" => false, "msg" => "Validation error", "error" => $validator->getMessageBag()], 400);
            }else{
                $email =  $request->email;
                $password = $request->password;
                if(!$token = auth()->attempt(["email" => $email, "password" => $password])){
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                return $this->respondWithToken($token);
            }
        }catch(\Exception $e){
            return response()->json(['succcess' => false, "msg" => "Something Went Wrong", "error" => $e->getMessage()], 401);
        }
    }


    public function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
