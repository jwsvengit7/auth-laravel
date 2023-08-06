<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class LoginController extends Controller{
    public function login_(Request $request){
        $credientials = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|string',
        ]);
        if(Auth::attempt($credientials)){
            $userId = Auth::user()->id;
            Session::put('user_id',$userId);

            return redirect("/")->with([
                'success'=>'Login successful',
                'user_id'=>$userId,
        
        ]);
        }else{
            return redirect()->back()->withErrors(['login_error'=>'Invalid credientials']);
        }




    }
}


?>