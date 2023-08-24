<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\ForgetPasswordController;

Route::middleware('auth:sanctum')->get('/user',function(Request $request){
    return $request->user();

});

Route::group(['middleware'=>'api','prefix'=>'v1'
],function($router){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/otp',[OtpController::class,'otp']);

    Route::post('/forget', [ForgetPasswordController::class, 'forget'])->name('password.reset');
    Route::post('/update-password', [ForgetPasswordController::class, 'updatePassword']);

    Route::post('/resend_otp', [AuthController::class,'resend_otp']);

})


?>