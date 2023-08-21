<?php

use App\Http\Controllers\Auth\RegisterController;

Route::middleware('auth:sanctum')->get('/user',function(Request $request){
    return $request->user();

});

Route::group(['middleware'=>'api','prefix'=>'v1'

],function($router){
    Route::post('/register',[RegisterController::class,'register']);
    Route::post('/login',[RegisterController::class,'login']);
})


?>