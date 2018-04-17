<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Storage;
use Crypt;

class fileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $user_login = $request->input('login');
        $file = $request->input('file');

        try {
            $user_login = Crypt::decryptString($user_login);
        } catch (DecryptException $e) {
            return 'Sorry!! you can\'t save this file';
        }



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
    public function destroy(Request $request)
    {
        $user_login = $request->input('login');
        $file_id = $request->input('serverId');

        if(!empty($user_login) && !empty($file_id)){
            try {

                $login = Crypt::decryptString($user_login);
                if(strlen($login) > 0){
                    Storage::delete($file_id);
                    return response()->json(['file_id' => $file_id]);
                }
    
            } catch (DecryptException $e) {
                return 'Sorry!! not find this file in database.';
            } 
        }               
    }
}
