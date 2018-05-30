<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Testing\MimeType;
use Illuminate\Contracts\Encryption\DecryptException;

use Storage;
use Crypt;

use App\FileModel;

class ShareController extends Controller
{

    public function show($id, $name = ""){

        $shareId = substr(base64_decode($id) ,0 ,9);

        $file_detail = FileModel::where('shareId', $shareId)->first();

        if(count($file_detail) === 0 || !$file_detail->isShare){
            return view('errors.404-NotShare');
        }

        if($name != "" && $name != $file_detail->filename){
            return redirect('/share/' . $file_detail->shareLink . '/' . $file_detail->filename);
        }

        // find path(filename with extension) by id from DB
        $public_path = 'public/' . $file_detail->loginUser;        
        $filename = $file_detail->nameId . '.' . $file_detail->fileExtension;
        $path = $public_path . '/' . $filename;

        if(!Storage::exists($path)){
            return view('errors.404-NotShare');
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type, true, 200);
        $response->header("Content-Disposition", "inline");
        $response->header("filename", (string) $file_detail->filename);
        $response->header("Content-Location", (string) $file_detail->serverId);

        return $response;
    }
}
