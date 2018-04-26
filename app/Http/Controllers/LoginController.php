<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Crypt;
use Session;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function do(Request $request){
        
        $username = $request->input('username');    
        $password = $request->input('password');
        if (Auth::attempt(['samaccountname' => $username, 'password' => $password])) {
            $user = Auth::user();
            Session::put('basic-auth', Crypt::encryptString($user->getAccountName()));
            return redirect('/')->with('message', 'Logged in!');
        }
        
        return redirect('login')->with('message', 'Hmm... Your username or password is incorrect');
    }

    public function logout(){
        Session::forget('basic-auth');
        Session::flush();
        Auth::logout();
        return redirect('login')->with('message', 'Logout Successfull');        
    }
}
