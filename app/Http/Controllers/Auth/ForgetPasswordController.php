<?php

namespace App\Http\Controllers\Auth;

use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Models\User;

class ForgetPasswordController extends Controller{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ["forget","update-password'"]]);
    }
    public function forget(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User Not found']);
        }

        $user->createPasswordResetToken();
        $resetLink = route('password.reset', $user->password_reset_token);

        Mail::to($user->email)->send(new ResetPasswordMail($resetLink));

        return response()->json(['message' => 'Password reset link sent successfully']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'token' => 'required',
        ]);

        $user = User::where('email', $request->email)
            ->where('password_reset_token', $request->token)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid email or token'], 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'password_reset_token' => null,
            'password_reset_token_created_at' => null,
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }
}
