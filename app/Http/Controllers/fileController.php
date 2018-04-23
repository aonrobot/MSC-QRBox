<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Testing\MimeType;

use Storage;
use Crypt;
use Session;

use App\FileModel;

class fileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($login)
    {
        try {
            $user_login = Crypt::decryptString($login);
        } catch (DecryptException $e) {
            return 'Sorry!!';
        }

        $files = FileModel::where('loginUser', $user_login)->orderBy('created_at', 'desc')->get();

        return Response::json($files);
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

    private function checkExistFileId($id){
        $count = FileModel::where('fileId', $id)->count();
        if($count > 0) {
            do{
                $newId = str_random(9);
                $count = FileModel::where('fileId', $newId)->count();
            } while($count > 0);
            return $newId;
        } else {
            return $id;
        }
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
        $files = $request->input('files');

        try {
            $user_login = Crypt::decryptString($user_login);
        } catch (DecryptException $e) {
            return 'Sorry!! you can\'t save this file';
        }

        $temp_folder_name = 'temp-' . $user_login;
        $public_folder_name = 'public/' . $user_login;

        foreach($files as $file) {

            $file_id = explode('.', str_replace($temp_folder_name . '/', '', $file['serverId']))[0];
            $file_serverId = str_replace($temp_folder_name . '/', $public_folder_name . '/', $file['serverId']);

            $file_name = $file_id . '.' . $file['fileExtension'];
            $public_path = $public_folder_name . '/' . $file_name;

            //$mimeType = MimeType::get($file['fileExtension']);
            
            $f = new FileModel;
            $f->fileId = $this->checkExistFileId($file['id']);
            $f->nameId = $file_id;
            $f->serverId = $file_serverId;
            $f->filename = $file['filename'];
            $f->filenameWithoutExtension = $file['filenameWithoutExtension'];
            $f->fileType = $file['fileType'];
            $f->fileExtension = $file['fileExtension'];
            //$f->mimeType = $mimeType; 
            $f->fileSize = $file['fileSize'];
            $f->fileLastModified = (string) $file['fileLastModified'];
            $f->isShare = 0;
            $f->loginUser = $user_login;
            $f->save();

            if(Storage::exists($file['serverId'])){
                // 1. Move file from temp to public
                Storage::move($file['serverId'], $public_path);
            }
        }

        Storage::deleteDirectory($temp_folder_name);

        return json_encode($files);

    }

    public function share($id){
        // 0. gen share id
        // 1. create share link
        // 2. save link to db
        // 3. return response with link
    }

    /**
     * Display the specified share resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showShare($shareId)
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
        // Check only owener can see this file!!!

        $user_login = Session::get('basic-auth');
        $file_detail = FileModel::where('fileId', $id)->first();
        $file_login = $file_detail->loginUser;        

        try {
            $user_login = Crypt::decryptString($user_login);
        } catch (DecryptException $e) {
            return 'Sorry!!';
        }

        if($user_login !== $file_login){
            return view('404');
        }

        // find path(filename with extension) by id from DB
        $public_path = 'public/' . $user_login;        
        $filename = $file_detail->nameId . '.' . $file_detail->fileExtension;
        $path = $public_path . '/' . $filename;

        if(!Storage::exists($path)){
            return view('404');
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        $response->header("Content-Disposition", "inline");
        $response->header("filename", (string) $file_detail->filename);
        $response->header("Content-Location", (string) $file_detail->serverId);

        return $response;
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
        $file_id = $request->input('id');

        if(!empty($user_login) && !empty($file_id)){
            try {

                $user_login = Crypt::decryptString($user_login);
                $public_folder_name = 'public/' . $user_login;

                if(strlen($user_login) > 0){
                    $file_detail = FileModel::where('fileId', $file_id)->first();
                    $public_path = $public_folder_name . '/' . $file_detail->nameId . '.' . $file_detail->fileExtension;
                    $trash_path = $public_folder_name . '/trash/' . $file_detail->nameId . '.' . $file_detail->fileExtension;

                    FileModel::where('fileId', $file_id)->delete();

                    Storage::move($public_path, $trash_path);

                    return response()->json(['file_id' => $file_id]);
                }
    
            } catch (DecryptException $e) {
                return 'Sorry!! not find this file.';
            } 
        }               
    }
}
