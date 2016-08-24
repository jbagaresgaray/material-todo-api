<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use Request;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\NotesTransformer;
use App\Models\Notes;
use DB;
use Response;

class NotesController extends ApiController
{

    /**
     * @var Transformers\UsersTransformer
     */
    protected $notesTransformer;

    public function __construct(NotesTransformer $notesTransformer)
    {
        $this->notesTransformer = $notesTransformer;

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
            case 'tasks':
                $note = Notes::where('associated_id', '=', $aid)
                ->where('type', '=', 'tasks')
                ->get();
                break;            

            default:
                break;
        }
        
        return $this->respond($note);
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
        $note = new Notes();

        // Call fill on the note and pass in the data
        $note->fill($data);

        $note->user_id = $uid;

        // Save to table
        $note->save();

        // Return response
        //return $this->respondCreated('Successfully created.');
        return $this->respond([
            'data' => $this->notesTransformer->transform($note)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = Notes::find($id);

        if(!$note)
        {
            return $this->respondNotFound('Note does not exist.');
        }

        return $this->respond([
            'data' => $this->notesTransformer->transform($note)
        ]);
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
        $note = Notes::find($id);

        // Call fill on the note and pass in the data
        $note->fill($data);

        $note->save();

        /*return $this->respond([
            'data' => $this->notesTransformer->transform($note)
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
        $note = Notes::find($id);

        if(!$note)
        {
            return $this->respond('Delete: Note does not exist.');
        }

        $note->delete();
        
        return $this->respond('Note successfully deleted.');
    }
}
