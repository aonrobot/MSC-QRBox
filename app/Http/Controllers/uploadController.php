<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Crypt;

class uploadController extends Controller
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
        $input = $request->all();
        $user_login = $request->header('BASIC-AUTH');

        $allowType = explode(',', env('ALLOW_FILE_TYPE'));
        $allowType = explode('\'', env('ALLOW_FILE_TYPE'));
        $mimeType = $request->file('filepond')->getMimeType();
        $fileSize = $request->file('filepond')->getSize() / 1024 / 1024;
        
        //print_r($allowType);return 1;
        $notAllowType = true;
        foreach($allowType as $at) {
            if(strpos($at, '*') !== false){
                $fileCoreType = explode('/', $mimeType)[0];
                $allowCoreType = explode('/', (string) $at)[0];
                $notAllowType = ($fileCoreType !== $allowCoreType) ? true : false;
                if(!$notAllowType) break;
            }else{
                $notAllowType = ($mimeType !== (string) $at) ? true : false;
                if(!$notAllowType) break;                
            }
        }

        if($notAllowType) abort(503);
        if($fileSize > env('MAX_FILE_SIZE', 1000000)) abort(503);

        try {
            $user_login = Crypt::decryptString($user_login);
        } catch (DecryptException $e) {
            return 'Sorry!!';
        }

        return Storage::put('temp-' . $user_login, $input['filepond']);
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
        $file_name = $request->getContent();
        $user_login = $request->header('BASIC-AUTH');

        try {
            $user_login = Crypt::decryptString($user_login);
        } catch (DecryptException $e) {
            return 'Sorry!!';
        }

        Storage::delete($file_name);
        return 'ok';
    }
}
