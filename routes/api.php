<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OtpController;

Route::middleware('auth:sanctum')->get('/user',function(Request $request){
    return $request->user();

});

Route::group(['middleware'=>'api','prefix'=>'v1'

],function($router){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/otp',[OtpController::class,'otp']);
})


?>