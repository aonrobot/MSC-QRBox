<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Member;
use DB;
use Crypt;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = DB::select('SELECT * FROM QRBox.dbo.member as m JOIN MSCMain.dbo.EmployeeNew as en ON m.loginUser = en.Login');
        $employees = DB::select("SELECT * FROM MSCMain.dbo.EmployeeNew as en LEFT JOIN QRBox.dbo.member as m ON en.Login = m.loginUser WHERE m.loginUser IS NULL");
        
        return view('admin.pages.user.index', ['members' => $members, 'employees' => $employees]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $login = $request->input('login');
        $maxFiles = $request->input('maxFile');
        $maxFileSize = $request->input('maxFileSize');
        $maxTotalFileSize = $request->input('maxTotalFileSize');

        Member::create([
            'memberId' => base64_encode($login),
            'loginUser' => $login,
            'role' => 'user',
            'maxFiles' => $maxFiles,
            'maxFileSize' => $maxFileSize,
            'maxTotalFileSize' => $maxTotalFileSize,
            'acceptedFileTypes' => 'default',
            'status' => '1'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * API ZONE
     *
     */

     /**
     * Get user info from database MSCMain
     *
     * @param  string  $loginUserCrypt
     * @return JSON
     */
    public function info($loginUserCrypt){
        try {
            $loginUser = Crypt::decryptString($loginUserCrypt);
        } catch (DecryptException $e) {
            return abort('404');
        }
        
        /**
         * 
         * FullNameEng - [React] Header.js, 
         * 
         */
        $empInfo = DB::connection('MSCMain')->table('EmployeeNew')->where('Login', $loginUser)->get(['FullNameEng']);
        
        return response()->json($empInfo);
    }

    public function isAdmin($loginUserCrypt){
        try {
            $userLogin = Crypt::decryptString($loginUserCrypt);
        } catch (DecryptException $e) {
            return abort('404');
        }
        
        $count = Member::where('loginUser', $userLogin)->where('role', 'admin')->count();

        $result = ($count) ? true : false;
        
        return response()->json($result);
    }
}
