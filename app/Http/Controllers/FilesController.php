<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Request;

use Request;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\FilesTransformer;
use App\Models\Files;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Filesystem\Filesystem;
use Response;
use Image;
use DB;

class FilesController extends ApiController
{

    /**
     * @var Transformers\FilesTransformer
     */
    protected $filesTransformer;

    public function __construct(FilesTransformer $filesTransformer)
    {
        $this->filesTransformer = $filesTransformer;

        $this->middleware('jwt.auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($department, $aid)
    {
        switch ($department) {
            case 'user_photo':
                $files = DB::table('files')
                    ->where('type', '=', $department)
                    ->where('user_id', '=', $aid)
                    ->select('*')
                    ->get();
                break;

            case 'tasks':
                $files = DB::table('files as ft')
                    ->where('ft.type', '=', $department)
                    ->where('ft.task_id', $aid)
                    ->leftJoin('files as fu', function ($join) {
                        $join->on('fu.user_id', '=', 'ft.user_id')
                             ->where('fu.type', '=', 'user_photo');
                    })
                    ->select('ft.*', 'fu.s3_file_uri as s3_file_uri_user_photo')
                    ->get();
                break;

            default:
                break;
        }
        
        return $this->respond($files);     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //token user id.
        $uid = tokenUserID();

        // Grab all the input passed in
        $data = Request::all();
        $fileField = $data['filefield'];
        $fileType = $data['type'];

        $fileSize = formatBytes($fileField->getSize());

        $fileHash = hash_file('md5', $fileField);
        $extension = $fileField->getClientOriginalExtension();
        $fileName = $fileHash . '.' . $extension;

        if ($fileType == 'user_photo') 
        {
            if (Storage::disk('s3')->exists($fileName))
            {
                return $this->respond('File already exist.');
            }

            $fup_check = Files::where('user_id', '=', $uid)
            ->where('type', $fileType)
            ->first();

            if ($fup_check != null)
            {
                //return $this->respondUnprocessableEntity('Can only add one (1) user profile photo.');
                $fileLineItem = Files::find($fup_check->id);
                $fileExtension = explode(".", $fileLineItem->file_name);  

                //call function
                $this->checkFileUsageBeforeDeleting($fileHash, $fileExtension[1]);

                $fileLineItem->delete();
            }
        }
        
        if ($fileField)
        {
            Storage::disk('s3')->put($fileName,  File::get($fileField));
        }
        
        $fileS3URI = Storage::disk('s3')->getDriver()->getAdapter()->getClient()->getObjectUrl('youproductive', $fileName);

        // Create new instance
        $file = new Files();

        // Call fill on the file and pass in the data
        $file->fill($data);

        $file->hash = $fileHash;
        $file->mime = $fileField->getClientMimeType();
        $file->file_name = $fileField->getClientOriginalName();
        $file->file_size = $fileSize;
        $file->user_id = $uid;
        $file->s3_file_uri = $fileS3URI;

        // Save to table
        $file->save();


        // Return response
        // this portion needs refactoring talk to Phil.
        //return $this->respondCreated('Successfully created.');
        /*return response()->json(['user_id' => $uid, 
            'file' => $fileS3URI,
            'file_id' => $file->id,
            'file_mime' => $file->mine]);*/
        
        return $this->respond($file);
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $file = Files::find($id);

        if(!$file)
        {
            return $this->respondNotFound('file does not exist.');
        }
        

        $fileName = $file->hash;
        $fileExtension = explode(".", $file->file_name);
        $f = $fileName . '.' . $fileExtension[1];

        //bucket name and filename on getObjectUrl
        $y = Storage::disk('s3')->getDriver()->getAdapter()->getClient()->getObjectUrl('youproductive', $f);

        return $y;
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
        // Grab all the input passed in
        $data = Request::all();

        // Use Eloquent to grab the gift record that we want to update,
        // referenced by the ID passed to the REST endpoint
        $file = Files::find($id);

        // Call fill on the file and pass in the data
        $file->fill($data);

        $file->save();

        return $this->respond([
            'data' => $this->filesTransformer->transform($file)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = Files::find($id);

        $fileName = $file->hash;
        $fileExtension = explode(".", $file->file_name);       

        if(!$file)
        {
            return $this->respondNotFound('Delete: File does not exist.');
        }
        
        //call function
        $this->checkFileUsageBeforeDeleting($fileName, $fileExtension[1]);

        //delete file item on table
        $file->delete();

        return $this->respondCreated('File successfully deleted.');
    }

    //check file for deleting on file table and amazon s3
    public function checkFileUsageBeforeDeleting($fileName, $fileExt)
    {
        //check usage before deleting file
        $filesUsage = DB::table('files')
            ->where('hash', '=', $fileName)
            ->count();
        
        if ($filesUsage == 1)
        {
            //delete file on amazon s3
            Storage::disk('s3')->delete($fileName . '.' . $fileExt);
        }
    }
}
