<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Repository\UserHandler;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Notifications\SendEmailForgotPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use App\Models\{
    User,
    Plan,
    Subscription,
    Listing,
    Category,
    ListingCategory,
    Deal,
    Email
};
use Carbon\Carbon;

class UserController extends Controller
{
    // ================== API Functions Start ====================
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
                "email_verification_token" => rand(11111, 99999),
            ]);
            $user->assignRole('user');

            // Getting data for the email from Database
            $url = "<a href='" . url('verify-email', ['user_id' => $user->id, 'token' => $user->email_verification_token]) . "'>Verify Email</a>";
            $emailData = Email::where('type', 'signup_email_verification')->first();
            $emailSub = $emailData->subject;
            $emailSubject = str_replace("[BUTTON_LINK]", $url, $emailSub);
            $emailMessage = $emailData->message;
            $emailContent = str_replace("[BUTTON_LINK]", $url, $emailMessage);
            // Sending Email for Verification
            Notification::route("mail", $request->email)->notify(new VerifyEmail($emailSubject, $emailContent));
            return response()->json(["success" => true, "msg" => "Verification Email has been sent to the given email address", "status" => 200], 200);
        }catch (\Exception $e) {
            return response()->json(["success" => false, "msg" => "Something went wrong", "error" => $e->getMessage()]);        
        }
    }

    // Web function for verifying Email
    public function verifyEmail($user_id, $token){
        $user = User::where('id', $user_id)->first();
        if($user->email_verification_token == $token){
            $user->email_verification_token = NULL;
            $user->email_verified_at = Carbon::now();
            $user->save();
            return redirect('https://vacationrentals.tools/signin')->with(["msg"=>"Email Verified Successfully"]);
        }else{
            echo "Verification Token Doesn`t matched";
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
            // checking email is verified or not
            $user = User::where('email', $email)->first();
            if($user->email_verified_at == NULL){                
                return response()->json(["success"=>false, "msg"=>"Your email is not verified. Please verify your email", "status"=>400], 400);
            }

            if(!$token = auth()->attempt(["email" => $email, "password" => $password])){
                return response()->json(['success'=>false, 'msg' => 'Email or password is incorrect', 'status'=>401], 401);
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
            // 'expires_in' => auth()->factory()->getTTL() * 60
            'expires_in' => 86400, // means 1 day this time is in seconds
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
        $validator = Validator::make($request->all(), [
            "email" => "required|string|email",
        ]);

        $emailVal = $validator->errors()->get('email');
        foreach($emailVal as $em){
            return response()->json(["success" => false, "msg" => $em, "status" => 400], 400);            
        }
        try{
            $email = $request->email;
            // Check if email is exist in Database
            $userEmail = User::where('email', $email)->count();
            if($userEmail > 0){
                $verificationCode = rand(1111, 9999);
                User::where('email', $email)->update(['verification_code'=>$verificationCode]);
                // Getting the email data from Database
                $emailData = Email::where('type', 'forgot_password')->first();
                $emailSub = $emailData->subject;
                $emailSubject = str_replace("[OTP]", $verificationCode, $emailSub);
                $emailMessage = $emailData->message;
                $emailContent = str_replace("[OTP]", $verificationCode, $emailMessage);
                Notification::route('mail', $email)->notify(new SendEmailForgotPassword($emailSubject, $emailContent));
                return response(["success"=>true, "msg"=>"OTP has been sent to the given email address", "status"=>200], 200);
            }else{
                return response(["success"=>false, "msg"=>"Email doesn`t exists", "status"=>400], 400);
            }
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong", "error" => $e->getMessage(), "status" => 400], 400);
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


    // ==================== API Function ends here ================================





    // ==================== Admin Functions Starts here ============================

    public function deleteUser(Request $request){
        try{
            $userId = $request->user_id;
            $listings = Listing::where('user_id', $userId)->delete();
            User::where('id', $userId)->delete();
            // foreach($listings as $listing){
            //     ListingCategory::where('listing_id', $listing->id)->delete();
            //     Deal::where('listing_id', $listing->id)->delete();
            //     $listing->delete();
            // }
            return redirect()->back()->with(['success' => "User Deleted Successfully"]);
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => "Something Went Wrong.... Please try again later"]);
        }
    }

    public function restoreUser(Request $request){
        try{
            $userId = $request->user_id;
            User::where('id', $userId)->restore();
            $listing = Listing::where('user_id', $userId)->restore();
            return redirect()->back()->with(['success'=>"User Restored Successfully"]);
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function updateAdmin(Request $request){
        $request->validate([
            "full_name" => "required",
            "email" => "required|email|unique:users,email",
        ],[
            "full_name.required" => "Name is required.",
            "email.required" => "Email is required.",
            "email.email" => "Must be a type of email"
        ]);
        try{
            $name = $request->full_name;
            $email = $request->email;
            $id = $request->admin_id;
            $user = User::where('id', $id)->update([
                'name' => $name,
                'email' => $email
            ]);
            if($user){
                return redirect()->back()->with(['success'=>"Admin Details Updated Successfully"]);
            }
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => 'Something Went Wrong. Please try again later']);
        }
    }

    public function changePassword(Request $request){
        $request->validate([
            'new_password' => 'required',
        ]);
        try{
            $password = $request->new_password;
            $user = User::where('id', $request->admin_id)->update([
                'password' => Hash::make($password)
            ]);
            if($user){
                return redirect()->back()->with(['success'=>"Password Updated Successfull"]);
            }
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function updateUser(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);
        try{
            $userId = $request->user_id;
            $name = $request->name;
            $email = $request->email;
            $user = User::where('id', $userId)->update([
                'name' => $name,
                'email' => $email,
            ]);
            if($user){
                return redirect()->back()->with(['success'=>"User Details Updated Successfully"]);
            }
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function fetchUser(Request $request){
        try{
            $id = $request->id;
            $user = User::where('id', $id)->first();
            return response()->json(['user'=>$user]);
        }catch(\Exception $e){
            dd($e->getMessage());
        }       
    }
}
