<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;


class DashboardController extends Controller{
    public function index(){
        $userId = Session::get('userId');

        return view('welcome', compact('userId'));
    }


}


?>



