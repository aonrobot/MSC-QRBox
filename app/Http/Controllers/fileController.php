<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Testing\MimeType;

use Storage;
use Crypt;
use Session;

use Carbon\Carbon;

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
            return abort('404');
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

    private function checkExistId($id, $col){
        $count = FileModel::where($col, $id)->count();
        if($count > 0) {
            do{
                $newId = str_random(9);
                $count = FileModel::where($col, $newId)->count();
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
            return abort('404');
        }

        $temp_folder_name = 'temp-' . $user_login;
        $public_folder_name = 'public/' . $user_login;

        foreach($files as $file) {

            $file_id = explode('.', str_replace($temp_folder_name . '/', '', $file['serverId']))[0];
            $file_serverId = str_replace($temp_folder_name . '/', $public_folder_name . '/', $file['serverId']);

            $file_name = $file_id . '.' . $file['fileExtension'];
            $public_path = $public_folder_name . '/' . $file_name;

            //$mimeType = MimeType::get($file['fileExtension']);

            //gen share id
            $shareId = $this->checkExistId(str_random(9), 'shareId');
            $random1 = str_random(1);
            //create share link
            $shareLink = base64_encode($shareId . $random1);
            
            $f = new FileModel;
            $f->fileId = $this->checkExistId($file['id'], 'fileId');
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
            $f->shareId = $shareId;
            $f->shareLink = $shareLink;
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

    private function capacityUnit($value, $i = 0){
        $value = ($value < 1000) ? $value : $value / 1000;
        if($value >= 1000){
            return $this->capacityUnit($value, ++$i);
        } else {
            $i++;
            return $value . ['bytes', 'Kb', 'Mb', 'Gb', 'Tb', '', '', ''][$i];
        }
    }

    public function listFileTable(Request $request){
        $columns = ['created_at', 'filename', 'fileSize']; //Name must same in database column name

        $totalData = FileModel::count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        //Don't
        if(empty($request->input('search.value'))){
            $files = FileModel::skip($start)->take($limit)->orderBy($order, $dir)->get();
            $totalFilltered = $totalData;
        }else{
            $search = $request->input('search.value');
            $files = FileModel::where('filename', "%{$search}%")
                                ->orWhere('fileSize', "%{intval($search)}%")
                                ->skip($start)
                                ->take($limit)
                                ->orderBy($order, $dir)
                                ->get();
            $totalFilltered = FileModel::where('filename', "%{$search}%")
                                ->orWhere('fileSize', "%{intval($search)}%")
                                ->count();
        }

        $data = [];

        if($files){
            foreach($files as $f){
                $fileId = strval($f->fileId);
                $data[] = [
                    "<input type='checkbox' name='chkBoxFile' value={$f->fileId} />",
                    "{$f->filename}",
                    "{$this->capacityUnit($f->fileSize)}",
                    "Preview",
                    "QR",
                    "
                        <div className='m-2'>
                            <button className='btn btn-light mr-3 mb-3' onclick=\"alert('Share Setting Modal')\"><i className='fa fa-cog'></i> Share Setting</button>
                            <button className='btn btn-danger mr-3 mb-3' onClick=\"window.removeFile('{$fileId}')\"><i className='fa fa-trash-alt'></i> Delete</button>
                        </div>
                    "

                ];
            }
        }

        $json_data = [
            "drow" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFilltered),
            "data" => $data
        ];

        return json_encode($json_data);
    }

    public function getIsShare(Request $request){
        $isShare = FileModel::where('fileId', $request->input('id'))->first()->isShare;
        $json_data = [
            "isShare" => intval($isShare)
        ];

        return json_encode($json_data);
    }

    public function share(Request $request){
        $id = $request->input('id');
        FileModel::where('fileId', $id)->update(['isShare' => 1]);
    }

    public function unShare(Request $request){
        $id = $request->input('id');
        FileModel::where('fileId', $id)->update(['isShare' => 0]);        
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

        if($id == 'undefined') {
            abort('404');
        }

        $file_detail = FileModel::where('fileId', $id)->first();
        if(empty($file_detail)) abort('404');

        $file_login = $file_detail->loginUser;        

        try {
            $user_login = Crypt::decryptString($user_login);
        } catch (DecryptException $e) {
            return abort('404');
        }

        if($user_login !== $file_login){
            return abort('404');
        }

        // find path(filename with extension) by id from DB
        $public_path = 'public/' . $user_login;        
        $filename = $file_detail->nameId . '.' . $file_detail->fileExtension;
        $path = $public_path . '/' . $filename;

        if(!Storage::exists($path)){
            return abort('404');
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

    private function getFileName($str){
        return substr($str, 0, strrpos($str, '.'));
    }
    private function getFileExtension($str){
        return substr($str, strrpos($str, '.') + 1);
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
        $newFile = $request->file('file_data');
        $oldFile_id = $request->input('file_id');
        $user_login = $request->input('login');

        $public_folder_name = 'public/' . $user_login;

        $oldFileDetail = FileModel::where('fileId', $oldFile_id)->first();
        $oldFileName = $oldFileDetail->nameId . '.' . $oldFileDetail->fileExtension;                    
        $oldFilePath = $public_folder_name . '/' . $oldFileName;
        
        try {
                       
            if(strlen($user_login) > 0){

                //Move old file to revison path
                $revision_path = $public_folder_name . '/revision/' . explode('.', $oldFileName)[0] . '_' . Carbon::now()->timestamp . '.' . explode('.', $oldFileName)[1];
                Storage::move($oldFilePath, $revision_path);

                //Create new file
                $newFileExtension = $request->file_data->extension();
                $newFileName = explode('.', $oldFileName)[0] . '.' . $newFileExtension;
                $newFilePath = $public_folder_name . '/' . $newFileName;
                $newFileOriginalName = $request->file_data->getClientOriginalName();

                Storage::putFileAs($public_folder_name, $newFile, $newFileName);
                $newFileType = Storage::mimeType($newFilePath);

                //Update Database
                FileModel::where('fileId', $oldFile_id)->update([
                    'serverId' => $newFilePath,
                    'filename' => $newFileOriginalName,
                    'filenameWithoutExtension' => $this->getFileName($newFileOriginalName),
                    'fileType' => $newFileType,
                    'fileExtension' => $newFileExtension,
                    'updated_at' => Carbon::now()
                ]);

            }

        } catch (DecryptException $e) {
            return abort('404');
        } 

        

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
                return abort('404');
            } 
        }               
    }
}
