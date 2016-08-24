<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\UsersTasksTransformer;
use App\Models\UsersTasks;
use Response;
use DB;
use App\Events\ServerUpdated;

class UsersTasksController extends ApiController
{

    /**
     * @var Transformers\UsersTasksTransformer
     */
    protected $userstasksTransformer;

    public function __construct(UsersTasksTransformer $userstasksTransformer)
    {
        $this->userstasksTransformer = $userstasksTransformer;

        $this->middleware('jwt.auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($task_id)
    {
        $userTasks = DB::table('users_tasks as ut')
            ->where('ut.task_id', '=', $task_id)
            ->leftJoin('files as fu', function ($join) {
                $join->on('fu.user_id', '=', 'ut.user_id')
                     ->where('fu.type', '=', 'user_photo');
            })
            ->leftJoin('users as u', function ($join) {
                $join->on('u.user_id', '=', 'ut.user_id');
            })
            ->select('ut.*', 'fu.*', 'u.username', 'u.last_name', 'u.first_name', 'u.email' )
            ->get();

        // Now send event
        \Event::fire(new ServerUpdated($userTasks, 'userstasks'));
        
        return $this->respond($userTasks);    
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //note: add the new type field creator or member.

        //token user id.
        $uid = tokenUserID();

        // Grab all the input passed in
        $data = Request::all();

        // Create new instance
        $userTask = new UsersTasks();

        // Call fill on the userTask and pass in the data
        $userTask->fill($data);

        // Save to table
        $userTask->save();

        // Return response
        return $this->respondCreated('Successfully assigned task to user.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userTask = UsersTasks::find($id);

        if(!$userTask)
        {
            return $this->respondNotFound('userTask does not exist.');
        }

        return $this->respond([
            'data' => $this->userstasksTransformer->transform($userTask)
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
        $userTask = UsersTasks::find($id);

        // Call fill on the gift and pass in the data
        $userTask->fill($data);

        $userTask->save();

         return $this->respond([
            'data' => $this->userstasksTransformer->transform($userTask)
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
        $userTask = UsersTasks::find($id);

        if(!$userTask)
        {
            return $this->respondNotFound('Delete: userTask does not exist.');
        }

        $userTask->delete();
        
        return $this->respondCreated('userTask successfully deleted.');
    }
}
