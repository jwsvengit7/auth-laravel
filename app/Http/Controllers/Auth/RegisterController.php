<?php

namespace App\Http\Controllers\Auth;
use App\Utils\Email;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Otp;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'register',"otp"]]);

    }

    public function otp(Request $request) {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string',
        ]);

    }

public function login(Request $request) {
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }
    if (! $token = JWTAuth::attempt($validator->validated())) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    return $this->createNewToken($token);
}

    protected function createNewToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 60 * 60, // 60
            'user' => auth()->user()
        ]);
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

        $otp = Email::generateOTP();
        $id = $user->id;
        echo $id;
        $send = Email::sendEmailWithOTP($request->email,$otp);
        saveOtp($otp,$id);
        return response()->json([
            'message' => 'User successfully registered',
            'otp'=>$otp,
            'email' => $send,
            'user' => $user
        ], 201);
    }



    protected function saveOtp($otp,$id){
        $saveOTP = Otp::create([
            'otp' => $otp,
            'userid' => $id
        ]);
    }

    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully logged out']);
    }

    public function userProfile() {
        return response()->json(auth()->user());
    }


}
