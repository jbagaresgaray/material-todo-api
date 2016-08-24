<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\RepeatTaskTransformer;
use App\Models\RepeatTask;
use DB;
use Response;
use App\Events\ServerUpdated;

class RepeatTaskController extends ApiController
{
    /**
     * @var Transformers\TasksTransformer
     */
    protected $repeatTaskTransformer;

    public function __construct(RepeatTaskTransformer $repeatTaskTransformer)
    {
        $this->repeatTaskTransformer = $repeatTaskTransformer;

        $this->middleware('jwt.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //token user id.
        $uid = tokenUserID();
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
        $repeatTask = new RepeatTask();

        // Call fill on the repeattask and pass in the data
        $repeatTask->fill($data);

        // Save to table
        $repeatTask->save();

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
        $repeatTask = RepeatTask::find($id);

        if(!$repeatTask)
        {
            return $this->respondNotFound('RepeatTask does not exist.');
        }

        return $this->respond([
            'data' => $this->repeatTaskTransformer->transform($repeatTask)
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
        $repeatTask = Chats::find($id);

        // Call fill on the gift and pass in the data
        $repeatTask->fill($data);

        $repeatTask->save();

        return $this->respond([
            'data' => $this->repeatTaskTransformer->transform($repeatTask)
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
        $repeatTask = Chats::find($id);

        if(!$repeatTask)
        {
            return $this->respondNotFound('Delete: RepeatTask does not exist.');
        }

        $repeatTask->delete();
        
        return $this->respondCreated('RepeatTask successfully deleted.');
    }
}
