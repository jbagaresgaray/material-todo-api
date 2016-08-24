<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\FoldersTransformer;
use App\Models\Folders;

class FoldersController extends ApiController
{
    /**
     * @var Transformers\FoldersTransformer
     */
    protected $foldersTransformer;

    public function __construct(FoldersTransformer $foldersTransformer)
    {
        $this->foldersTransformer = $foldersTransformer;

        $this->middleware('jwt.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $folders = Folders::all();
        return $this->respond($folders);
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

        // Create new instance
        $folder = new Folders();

        // Call fill on the folder and pass in the data
        $folder->fill($data);

        $folder->user_id = $uid;

        // Save to table
        $folder->save();

        // Return response
        //return $this->respondCreated('Successfully created.');
        return $this->respondOK('success');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $folder = Folders::find($id);

        if(!$folder)
        {
            return $this->respondNotFound('Folder does not exist.');
        }

        return $this->respond([
            'data' => $this->foldersTransformer->transform($folder)
        ]);
    }

    public function checkFolderNameIfExistIfNotCreated($folder_name)
    {
        $foldercheck = Folders::where('name', '=', $folder_name)->first();

        if($foldercheck === null)
        {
            $folder = new Folders();
            $folder->name = $folder_name;
            $folder->save();

            return $this->respond($folder->id);
        }
        else
        {
            return $this->respond($foldercheck->id);
        }

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
        $folder = Folders::find($id);

        // Call fill on the folder and pass in the data
        $folder->fill($data);

        $folder->save();

        /*return $this->respond([
            'data' => $this->foldersTransformer->transform($folder)
        ]);*/
        return $this->respondOK('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $folder = Folders::find($id);

        if(!$folder)
        {
            return $this->respondNotFound('Delete: Folder does not exist.');
        }

        $folder->delete();
        
        //return $this->respondCreated('Folder successfully deleted.');
        return $this->respondOK('success');
    }
}
