<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\StatusTransformer;
use App\Models\Status;

class StatusController extends ApiController
{

    /**
     * @var Transformers\StatusTransformer
     */
    protected $statusTransformer;

    function __construct(StatusTransformer $statusTransformer)
    {
        $this->statusTransformer = $statusTransformer;

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
        $statuses = Status::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($statuses, [
            'data' => $this->statusTransformer->transformCollection($statuses->all())
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
        $status = new Status();

        // Call fill on the status and pass in the data
        $status->fill($data);

        // Save to table
        $status->save();

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
        $status = Status::find($id);

        if(!$status)
        {
            return $this->respondNotFound('Status does not exist.');
        }

        return $this->respond([
            'data' => $this->statusTransformer->transform($status)
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
        $status = Status::find($id);

        // Call fill on the status and pass in the data
        $status->fill($data);

        $status->save();

        return $this->respond([
            'data' => $this->statusTransformer->transform($status)
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
        $status = Status::find($id);

        if(!$status)
        {
            return $this->respondNotFound('Delete: Status does not exist.');
        }

        $status->delete();
        
        return $this->respondCreated('Status successfully deleted.');
    }
}
