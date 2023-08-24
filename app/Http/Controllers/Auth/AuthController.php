<?php

namespace App\Http\Controllers\Auth;
use App\Utils\Email;
use App\Http\Controllers\Auth\OtpController\createOtp;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Otp;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'register',"otp","reset","resend_otp"]]);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if ($user == null) {
            return response()->json(['error' => 'NOT FOUND'], 401);
        }
    
        if (!$user->status) {
            return response()->json(['error' => 'NOT ACTIVATED'], 401);
        }
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        if (!$token = JWTAuth::attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        return $this->createNewToken($token);
    }
    

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'interest' => 'required|string',
            'phone_number' =>'required|string|regex:/^[0-9\+\-\(\)\/\s]*$/',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'status' =>false,
            'phone_number' =>$request->phone_number,
            'interest' => $request->interest
        ]);
        return $this->sendotpresponse($user->email,$user->id);
    }

    public function resend_otp(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|string|max:100',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 200);
        }
    
        $user = User::find($request->id);
    
        if (!$user) {
            return response()->json("User not found", 404);
        }
    
        return $this->sendotpresponse($user->email,$user->id);
    }


    protected function sendotpresponse($email,$id){
        $otp = Email::generateOTP();
        $send = Email::sendEmailWithOTP($email,$otp);
        $createotp = new OtpController();
        OtpController::createOtp($otp, $id);
      
        return response()->json([
            'message' => 'User successfully registered',
            'otp'=>$otp,
            'email' => $send,
            'user_id' => $id
        ], 201);
    }


    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully logged out']);
    }

    public function userProfile() {
        return response()->json(auth()->user());
    }




    protected function createNewToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 60 * 60
        ]);
    }



}
