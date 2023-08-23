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

class OtpController extends Controller
{
    /**
     * Create a new OTPController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'otp']]);
    }

    /**
     * Verify OTP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'otp' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'errors' => $validator->errors()], 400);
        }

        $userOtp = Otp::where('user_id', $request->user_id)->latest()->first();

        if (!$userOtp) {
            return response()->json(['error' => 'OTP not found'], 404);
        }

        if ($userOtp->otp !== $request->otp) {
            return response()->json(['error' => 'Invalid OTP'], 422);
        }

        $otpExpirationThreshold = now()->subMinutes(config('auth.otp_expiration_threshold'));
        if ($userOtp->created_at < $otpExpirationThreshold) {
            return response()->json(['error' => 'OTP has expired'], 422);
        }

        $user = $userOtp->user;
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->status = true;
        $user->save();

        return response()->json(['message' => 'OTP verified successfully', 'user' => $user], 200);
    }

    /**
     * Create a new OTP record.
     *
     * @param  string  $otp
     * @param  int  $id
     * @return void
     */
    public static function createOtp($otp, $id)
    {
        Otp::create([
            'otp' => $otp,
            'user_id' => $id,
        ]);
    }
}
