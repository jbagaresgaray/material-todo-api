<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\UsersNotesTransformer;
use App\Models\UsersNotes;

class UsersNotesController extends ApiController
{

    /**
     * @var Transformers\UsersNotesTransformer
     */
    protected $usersnotesTransformer;

    function __construct(UsersNotesTransformer $usersnotesTransformer)
    {
        $this->usersnotesTransformer = $usersnotesTransformer;

        //$this->beforeFilter('auth.basic', ['on' => 'post']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit') ?: 5;       
        
        $uid = Auth::user()->id;
        $usersNotes = UsersNotes::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($usersNotes, [
            'data' => $this->usersnotesTransformer->transformCollection($usersNotes->all())
        ]);
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
        // Grab all the input passed in
        $data = Request::all();

        // Create new instance
        $usersNote = new UsersNotes();

        // Call fill on the usersNote and pass in the data
        $usersNote->fill($data);

        // Save to table
        $usersNote->save();

        // Return response
        return $this->respondCreated('Successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usersNote = UsersNotes::find($id);

        if(!$usersNote)
        {
            return $this->respondNotFound('usersNote does not exist.');
        }

        return $this->respond([
            'data' => $this->usersnotesTransformer->transform($usersNote)
        ]);
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
        // Grab all the input passed in
        $data = Request::all();

        // Use Eloquent to grab the gift record that we want to update,
        // referenced by the ID passed to the REST endpoint
        $usersNote = UsersNotes::find($id);

        // Call fill on the usersNote and pass in the data
        $usersNote->fill($data);

        $usersNote->save();

         return $this->respond([
            'data' => $this->usersnotesTransformer->transform($usersNote)
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
        $usersNote = UsersNotes::find($id);

        if(!$usersNote)
        {
            return $this->respondNotFound('Delete: usersNote does not exist.');
        }

        $usersNote->delete();
        
        return $this->respondCreated('usersNote successfully deleted.');
    }
}
