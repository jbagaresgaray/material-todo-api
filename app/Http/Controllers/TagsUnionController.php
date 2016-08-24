<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use App\Models\TagsUnion;
use App\Models\Tags;
use Response;
use DB;
use App\Events\ServerUpdated;

class TagsUnionController extends ApiController
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
        $tagsunion = DB::table('tags_union')
            ->where('tags_union.associated_id', $aid)
            ->where('tags_union.department', '=', $department)
            ->join('tags', 'tags_union.tag_id', '=', 'tags.id')
            ->select('tags_union.id', 'tags.name')
            ->get();

        return $this->respond($tagsunion);
    }

    public function tagsByUser()
    {
        //token user id.
        $uid = tokenUserID();

        $tagsunion = DB::table('tags_union')
            ->where('tags_union.user_id', $uid)
            ->join('tags', 'tags_union.tag_id', '=', 'tags.id')
            ->select('tags.*')
            ->groupBy('tag_id')
            ->get();

        // Now send event
        \Event::fire(new ServerUpdated($tagsunion));

        return $this->respond($tagsunion);
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
        $tagunion = new TagsUnion();

        // Call fill on the tag and pass in the data
        $tagunion->fill($data);

        $tagunion->user_id = $uid;

        // Save to table
        $tagunion->save();

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
        $tagunion = TagsUnion::find($id);

        if(!$tagunion)
        {
            return $this->respondNotFound('Delete: TagUnion does not exist.');
        }

        $tagcheck = TagsUnion::where('tag_id', '=', $tagunion->tag_id)->count();
        if($tagcheck === 1)
        {
            $tag = Tags::find($tagunion->tag_id);
            $tag->delete();
        }

        $tagunion->delete();


        return $this->respond('success');
    }
}
