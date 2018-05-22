<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Crypt;
use Session;
use DB;

use App\Member;

class LoginController extends Controller
{
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function doLogin(Request $request) {
        
        $username = $request->input('username');    
        $password = $request->input('password');

        $username = str_replace("METROSYSTEMS\\", "", str_replace("metrosystems\\", "", str_replace("@metrosystems.co.th", "", $username)));

        $count_exist = Member::where('loginUser', $username)->where('status', '1')->count();

        if($count_exist){
            if (Auth::attempt(['samaccountname' => $username, 'password' => $password])) {

                $user = Auth::user();
                //Get user login from AD Attribute
                $userLogin = $user->getAccountName();
                Session::put('basic-auth', Crypt::encryptString($userLogin));

                $userInfo = DB::connection('MSCMain')->table('EmployeeNew')->where('login', $userLogin)->first();   

                //Check have user in EmployeeNew?
                if(!empty($userInfo)){
                    Session::put('user-info', $userInfo);
                }else{
                    return response()->view('error.noLogin');
                }

                if(Session::has('url.intended')){
                    return redirect(Session::get('url.intended'))->with('message', 'Logged in!');                    
                } else {
                    return redirect('/')->with('message', 'Logged in!');                    
                }
            } else {
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
