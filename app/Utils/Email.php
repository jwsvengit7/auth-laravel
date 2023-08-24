<?php
namespace App\Utils;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpSend;

class Email extends Controller
{
    public static function generateOTP()
    {
        $length = 6;
        $otp = '';
        $characters = '0123456789';

        $charLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $otp .= $characters[rand(0, $charLength - 1)];
        }
        return $otp;
    }

    public static function sendEmailWithOTP($email)
    {
        $otp = self::generateOTP(); 
        Cache::put('otp_' . $email, $otp, now()->addMinutes(10));
        try {
            Mail::to($email)->send(new \App\Mail\OtpSend($otp,$email)); 
            return response()->json(['message' => 'OTP sent successfully']);
        } catch (\Exception $e) {
            Log::error('Error sending OTP email: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send OTP email'.$e->getMessage()], 500);
        }
    }
    
}
