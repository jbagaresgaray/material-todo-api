<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use App\Models\FoldersUnion;
use App\Models\Folders;
use Response;
use DB;
use App\Events\ServerUpdated;

class FoldersUnionController extends ApiController
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($department, $aid)
    {
        $foldersunion = DB::table('folders_union')
            ->where('folders_union.associated_id', $aid)
            ->where('folders_union.department', '=', $department)
            ->join('folders', 'folders_union.folder_id', '=', 'folders.id')
            ->select('folders_union.id', 'folders.name')
            ->get();

        return $this->respond($foldersunion);
    }

    public function foldersByUser()
    {
        //token user id.
        $uid = tokenUserID();

        $foldersunion = DB::table('folders_union as fu')
            ->where('fu.user_id', '=', $uid)
            ->leftJoin('folders as f', function ($join) {
                    $join->on('fu.folder_id', '=', 'f.id');
                })
            ->select('*')
            ->get();

        // Now send event
        \Event::fire(new ServerUpdated($foldersunion, '', 'folders'));

        return $this->respond($foldersunion);
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
        $folderunion = new FoldersUnion();

        // Call fill on the tag and pass in the data
        $folderunion->fill($data);

        $folderunion->user_id = $uid;

        // Save to table
        $folderunion->save();

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
        $folderunion = FoldersUnion::find($id);

        if(!$folderunion)
        {
            return $this->respondNotFound('Delete: FolderUnion does not exist.');
        }

        $foldercheck = FoldersUnion::where('folder_id', '=', $folderunion->folder_id)->count();
        if($foldercheck === 1)
        {
            $folder = Folders::find($folderunion->folder_id);
            $folder->delete();
        }

        $foldercheck->delete();


        return $this->respond('success');
    }
}
