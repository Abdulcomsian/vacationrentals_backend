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

            $name = $validator->errors()->get('name');
            $email = $validator->errors()->get('email');
            foreach($name as $n){
                return response()->json(["success" => false, "msg" => $n, "status" => 400], 400);            
            }
            foreach($email as $em){
                return response()->json(["success" => false, "msg" => $em, "status" => 400], 400);            
            }
            
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
            return response()->json(["success" => true, "msg" => "User Created Successfully", "status" => 200], 200);
        }catch (\Exception $e) {
            return response()->json(["success" => false, "msg" => "Something went wrong", "error" => $e->getMessage()]);        
        }
    }

    public function login(Request $request){
        try{
            $validator =  Validator::make($request->all(), [
                "email" => "required|string|email",
                "password" => "required|string",
            ]);
            $email = $validator->errors()->get('email');
            foreach($email as $em){
                return response()->json(["success" => false, "msg" => $em, "status" => 400], 400);            
            }
            
            $email =  $request->email;
            $password = $request->password;
            if(!$token = auth()->attempt(["email" => $email, "password" => $password])){
                return response()->json(['error' => 'Email or password is incorrect'], 401);
            }
            $jwt =  $this->respondWithToken($token);
            return response()->json(["success"=>true, "msg"=>"User Login Successfully", "token"=>$jwt, "status"=>200], 200);
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
        return response()->json(["success"=>true, "msg"=>"User Logout Successfully", "status"=>200], 200);
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

            $emailVal = $validator->errors()->get('email');
            foreach($emailVal as $em){
                return response()->json(["success" => false, "msg" => $em, "status" => 400], 400);            
            }
            $email = $request->email;
            // Check if email is exist in Database
            $userEmail = User::where('email', $email)->count();
            if($userEmail > 0){
                $verificationCode = rand(1111, 9999);
                User::where('email', $email)->update(['verification_code'=>$verificationCode]);
                Notification::route('mail', $email)->notify(new SendEmailForgotPassword($verificationCode));
                return response(["success"=>true, "msg"=>"OTP has been sent to the given email address", "status"=>200], 200);
            }else{
                return response(["success"=>false, "msg"=>"Email doesn`t exists", "status"=>400], 400);
            }
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong", "error" => $e->getMessage()], 400);
        }
    }


    public function verifyCode(Request $request){
        try{
            $verificationCode = $request->verificationCode;
            $email = $request->email;

            $user = User::where('email', $email)->first();
            if($user['verification_code'] == $verificationCode){
                return response()->json(["success"=>true, "msg"=>"Email Verified Successfully", "status"=> 200], 200);
            }else{
                return response()->json(["success"=>false, "msg"=>"Code doesn`t matched... Incorrect Code", "status" => 400], 400);
            }
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong" ,"error"=>$e->getMessage()], 400);
        }
    }

    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(),[
            "email" => "required|string|email",
            "new_password" => "required|string",
        ]);

        $email = $validator->errors()->get('email');
        $new_password = $validator->errors()->get('new_password');
        foreach($email as $em){
            return response()->json(["success" => false, "msg" => $em, "status" => 400], 400);            
        }
        foreach($new_password as $pass){
            return response()->json(["success" => false, "msg" => $pass, "status" => 400], 400);
        }
        $email = $request->email;
        $newPassword = Hash::make($request->new_password);
        $user = User::where('email', $email)->update(["password"=>$newPassword]);
        return response()->json(["success"=>true, "msg"=>"Password changed successfully", "status"=>200]);
    }
}
