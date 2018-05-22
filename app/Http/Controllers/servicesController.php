<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Session;
use File;
use Response;

use QrCode;

class servicesController extends Controller
{

    public function genQrCode($id){
        //$url = $request->input('url');
        $response = Response::make(QrCode::format('png')->merge('/public/images/msc10.png', .15)->size(500)->generate(env('APP_URL') . 'share/' . $id), 200);
        $response->header("Content-Type", "image/png");
        return $response;
    }
    
    public function genCustomQrCode(Request $request){
        $url = $request->input('url');
        if(empty($url)) return '';
        //$url = $request->input('url');
        $response = base64_encode(QrCode::format('png')->merge('/public/images/msc10.png', .15)->size(500)->generate($url));
        //$response->header("Content-Type", "image/png");
        return $response;
    }    

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

    }
}
