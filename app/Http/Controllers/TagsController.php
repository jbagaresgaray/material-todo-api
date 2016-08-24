<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\TagsTransformer;
use App\Models\Tags;

class TagsController extends ApiController
{

    /**
     * @var Transformers\TagsTransformer
     */
    protected $tagsTransformer;

    function __construct(TagsTransformer $tagsTransformer)
    {
        $this->tagsTransformer = $tagsTransformer;

        $this->middleware('jwt.auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tags::all();
        return $this->respond($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Grab all the input passed in
        $data = Request::all();

        // Create new instance
        $tag = new Tags();

        // Call fill on the tag and pass in the data
        $tag->fill(strtolower($data));

        // Save to table
        $tag->save();

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
        $tag = Tags::find($id);

        if(!$tag)
        {
            return $this->respondNotFound('Tag does not exist.');
        }

        return $this->respond([
            'tag' => $this->tagsTransformer->transform($tag)
        ]);
    }

    
    public function checkTagNameIfExistIfNotCreated($tag_name)
    {
        $tagcheck = Tags::where('name', '=', $tag_name)->first();
        //return $this->respond($tag_name);

        if($tagcheck === null)
        {
            //return $this->respondNotFound('Tag does not exist.');
            $tag = new Tags();
            $tag->name = strtolower($tag_name);
            $tag->save();

            return $this->respond($tag->id);
        }
        else
        {
            return $this->respond($tagcheck->id);
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
        $tag = Tags::find($id);

        // Call fill on the gift and pass in the data
        $tag->fill($data);

        $tag->save();

        /*return $this->respond([
            'data' => $this->tagsTransformer->transform($tag)
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
        $tag = Tags::find($id);

        if(!$tag)
        {
            return $this->respondNotFound('Delete: Tag does not exist.');
        }

        $tag->delete();
        
        //return $this->respondCreated('Tag successfully deleted.');
        return $this->respondOK('success');
    }
}
