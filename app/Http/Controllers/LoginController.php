<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Crypt;
use Session;

use App\Member;

class LoginController extends Controller
{
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function do(Request $request) {
        
        $username = $request->input('username');    
        $password = $request->input('password');

        $count_exist = Member::where('loginUser', $username)->where('status', '1')->count();

        if($count_exist){
            if (Auth::attempt(['samaccountname' => $username, 'password' => $password])) {
                $user = Auth::user();
                Session::put('basic-auth', Crypt::encryptString($user->getAccountName()));
                return redirect('/')->with('message', 'Logged in!');
            }else{
                return redirect('login')->with('message', 'Password หรือ Username ผิด');
            }
        }
        
        return redirect('login')->with('message', 'กรุณาสมัครสมาชิกก่อนเข้าใช้งาน');
        
    }

    public function logout() {
        Session::forget('basic-auth');
        Session::flush();
        Auth::logout();
        return redirect('login')->with('message', 'Logout Successfull');        
    }
}
