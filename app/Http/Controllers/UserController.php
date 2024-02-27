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

class UserController extends Controller
{
    public function register(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                "name" => "required|string",
                "email" => "required|email|unique:users,email",
                "password" => "required|string",
            ]);

            if ($validator->errors()->has('name')) {                
                return response()->json(["success" => false, "msg" => "Name field is required", "status" => 400]);            
            }elseif($validator->errors()->has('email')){
                return response()->json(["success" => false, "msg" => "This email has already been taken", "status" => 400]);            
            } else {
                $name = $request->name;
                $email = $request->email;
                $password = $request->password;
                $tcStatus = $request->tc_status;
                $user = User::create([
                    "name" => $name,
                    "email" => $email,
                    "password" => Hash::make($password),
                    "tc_status" => $tcStatus,
                ]);
                $user->assignRole('user');
                return response()->json(["success" => true, "msg" => "User Created Successfully", "status" => 200]);
            }
        } catch (\Exception $e) {
            return response()->json(["success" => false, "msg" => "Something went wrong", "error" => $e->getMessage()]);        
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
                $jwt =  $this->respondWithToken($token);
                return response()->json(["success"=>true, "msg"=>"User Login Successfully", "token"=>$jwt, "status"=>200]);
            }
        }catch(\Exception $e){
            return response()->json(['succcess' => false, "msg" => "Something Went Wrong", "error" => $e->getMessage()]);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(){
        auth()->logout();
        return response()->json(["success"=>true, "msg"=>"User Logout Successfully", "status"=>200]);
    }

     /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function sendEmailPassword(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                "email" => "required|string|email",
            ]);
    
            if($validator->fails()){
                return response()->json(["success"=>false, "msg"=>"Validation Error", "error"=>$validator->getMessageBag()]);
            }else{
                $email = $request->email;
                Notification::route('mail', $email)->notify(new SendEmailForgotPassword());
                return response()->json(["success"=>true, "msg"=>"Email sent successfully", "status"=>200]);
            }
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong", "error" => $e->getMessage()]);
        }
    }


    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(),[
            "email" => "required|string|email",
            "new_password" => "required|string",
        ]);

        if($validator->fails()){
            return response()->json(["success"=>false, "msg"=>"Validation Error", "error"=>$validator->getMessageBag()]);
        }else{
            $email = $request->email;
            $newPassword = $request->new_password;

            $user = User::where('email', $email)->first();
            $user->password = Hash::make($newPassword);
            if($user->save()){
                return response()->json(["success"=>true, "msg"=>"Password changed successfully", "status"=>200]);
            }
        }
    }
}
