<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\LogsTransformer;
use App\Models\Logs;

class LogsController extends ApiController
{

    /**
     * @var Transformers\LogsTransformer
     */
    protected $logsTransformer;

    function __construct(LogsTransformer $logsTransformer)
    {
        $this->logsTransformer = $logsTransformer;

        //$this->beforeFilter('auth.basic', ['on' => 'post']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$limit = Input::get('limit') ?: 5;       
        
        $uid = Auth::user()->id;
        $logs = Logs::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($logs, [
            'data' => $this->logsTransformer->transformCollection($logs->all())
        ]);*/

        $logs = Logs::all();
        return $this->respond($logs);
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
        $log = new Logs();

        // Call fill on the log and pass in the data
        $log->fill($data);

        // Save to table
        $log->save();

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
        $log = Logs::find($id);

        if(!$log)
        {
            return $this->respondNotFound('Log does not exist.');
        }

        return $this->respond([
            'data' => $this->logsTransformer->transform($log)
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
        $log = Logs::find($id);

        // Call fill on the log and pass in the data
        $log->fill($data);

        $log->save();

        return $this->respond([
            'data' => $this->logsTransformer->transform($log)
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
        $log = Logs::find($id);

        if(!$log)
        {
            return $this->respondNotFound('Delete: Log does not exist.');
        }

        $log->delete();
        
        //return $this->respondCreated('Log successfully deleted.');
        return $this->respondOK('success');
    }
}
