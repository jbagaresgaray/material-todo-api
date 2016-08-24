<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\TasksTransformer;
use App\Models\Tasks;
use App\Models\Notes;
use App\Models\Comments;
use App\Models\Files;
use App\Models\UsersTasks;
use App\Models\URLShortener;
use DB;
use Response;
use App\Events\ServerUpdated;

class TasksController extends ApiController
{

    /**
     * @var Transformers\TasksTransformer
     */
    protected $tasksTransformer;

    public function __construct(TasksTransformer $tasksTransformer)
    {
        $this->tasksTransformer = $tasksTransformer;

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

        //assign tasks
        $userAssigned = DB::table('users_tasks as ut')
            ->where('ut.user_id', $uid)
            ->select('ut.task_id as tid')
            ->get();

        $data = array();
        foreach ($userAssigned as $t)
        {
            $data[] = $this->getTaskData($t->tid, $uid);
        }
        
        return $this->respond(
            $data
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($associatedID)
    {
        //main task item
        $task = Tasks::find($associatedID);

        if(!$task)
        {
            return $this->respondNotFound('Task does not exist.');
        }

        //subtasks
        $subTasks = Tasks::where('parent_task_id', '=', $associatedID)
            ->select('*')
            ->get();

        //notes
        $notes = Notes::where('associated_id', '=', $associatedID)
            ->where('type', '=', 'tasks')
            ->get();

        //comments
        $comments = Comments::where('task_id', '=', $associatedID)
            ->where('type', '=', 'tasks')
            ->where(DB::raw('created_at'), '=', DB::raw('updated_at'))
            ->get();

        //files
        $files = DB::table('files as ft')
            ->where('ft.type', '=', 'tasks')
            ->where('ft.task_id', $associatedID)
            ->leftJoin('files as fu', function ($join) {
                $join->on('fu.user_id', '=', 'ft.user_id')
                     ->where('fu.type', '=', 'user_photo');
            })
            ->select('ft.*', 'fu.s3_file_uri as s3_file_uri_user_photo')
            ->get();

        //assign user
        $userTasks = DB::table('users_tasks as ut')
            ->where('ut.task_id', '=', $associatedID)
            ->leftJoin('files as fu', function ($join) {
                $join->on('fu.user_id', '=', 'ut.user_id')
                     ->where('fu.type', '=', 'user_photo');
            })
            ->leftJoin('users as u', function ($join) {
                $join->on('u.user_id', '=', 'ut.user_id');
            })
            ->select('ut.*', 'fu.*', 'u.username', 'u.last_name', 'u.first_name', 'u.email' )
            ->get();

        //tags
        $tagsunion = DB::table('tags_union')
            ->where('tags_union.associated_id', $associatedID)
            ->where('tags_union.department', '=', 'tasks')
            ->join('tags', 'tags_union.tag_id', '=', 'tags.id')
            ->select('tags_union.id', 'tags.name')
            ->get();

        //share
        $urlSlug = DB::table('url_shortener as us')
            ->where('type', '=', 'tasks')
            ->where('associated_id', '=', $associatedID)
            ->select('us.slug')
            ->get();

        // response the collection
        return ['task' => $task->toArray(), 
                'subtasks' => $subTasks->toArray(),
                'notes' => $notes->toArray(),
                'comments' => $comments->toArray(),
                'files' => $files,
                'assigned_users' => $userTasks,
                'tags' => $tagsunion,
                'urlSlug' => $urlSlug]; 
    }

    //overall task data
    public function getTaskData($task_id, $uid)
    {
        //main task item
        $task = Tasks::find($task_id);

        //subtasks
        $subTasks = Tasks::where('parent_task_id', '=', $task_id)
            ->select('*')
            ->get();

        //notes
        $notes = Notes::where('associated_id', '=', $task_id)
            ->where('type', '=', 'tasks')
            ->get();

        //comments
        $comments = Comments::where('task_id', '=', $task_id)
            ->where('type', '=', 'tasks')
            ->where(DB::raw('created_at'), '=', DB::raw('updated_at'))
            ->get();

        //files
        $files = DB::table('files as ft')
            ->where('ft.type', '=', 'tasks')
            ->where('ft.task_id', $task_id)
            ->leftJoin('files as fu', function ($join) {
                $join->on('fu.user_id', '=', 'ft.user_id')
                     ->where('fu.type', '=', 'user_photo');
            })
            ->select('ft.*', 'fu.s3_file_uri as s3_file_uri_user_photo')
            ->get();

        //assign user
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

        //tags
        $tagsunion = DB::table('tags_union')
            ->where('tags_union.associated_id', $task_id)
            ->where('tags_union.department', '=', 'tasks')
            ->join('tags', 'tags_union.tag_id', '=', 'tags.id')
            ->select('tags_union.id', 'tags.name')
            ->get();

        //share
        $urlSlug = DB::table('url_shortener as us')
            ->where('type', '=', 'tasks')
            ->where('associated_id', '=', $task_id)
            ->select('us.slug')
            ->get();

        // response the collection
        return ['task' => $task->toArray(), 
                'subtasks' => $subTasks->toArray(),
                'notes' => $notes->toArray(),
                'comments' => $comments->toArray(),
                'files' => $files,
                'assigned_users' => $userTasks,
                'tags' => $tagsunion,
                'urlSlug' => $urlSlug];
    }

    //tasks by group
    public function tasksByGroup($taskgroup)
    {
        //token user id.
        $uid = tokenUserID();

        switch ($taskgroup) {
            case 'inbox':
                $tasks = Tasks::where('user_id', '=', $uid)
                ->where('status', false)
                ->where(DB::raw('created_at'), '=', DB::raw('updated_at'))
                ->get();
                break;

            case 'starred':
                $tasks = Tasks::where('user_id', '=', $uid)
                ->where('starred', true)
                ->where('status', false)
                ->get();
                break;

            case 'priorities':
                $tasks = Tasks::where('user_id', '=', $uid)
                ->whereNotIn('priority', ['', 'null'])
                ->where('status', false)
                ->get();
                break;

            case 'duedate':
                $tasks = Tasks::where('user_id', '=', $uid)
                ->whereNotNull('end_date')
                ->where('status', false)
                ->get();
                break;

            case 'recentlyadded':
                $tasks = Tasks::where('user_id', '=', $uid)
                ->where('status', false)
                ->where(DB::raw('created_at'), '!=', DB::raw('updated_at'))
                ->orderBy('updated_at', 'desc')              
                ->get();
                break;

            case 'completed':
                $tasks = Tasks::where('user_id', '=', $uid)
                ->where('status', true)
                ->get();
                break;

            default:
                $tasks = Tasks::where('user_id', '=', $uid)
                ->get();
                break;
        }
        
        return $this->respond($tasks);
    }

    

    //total items all states
    public function allCounterTasks()
    {
        //token user id.
        $uid = tokenUserID();

        $tasksInbox = Tasks::where('user_id', '=', $uid)
                ->where('status', false)
                ->where(DB::raw('created_at'), '=', DB::raw('updated_at'))
                ->count();

        $tasksStarred = Tasks::where('user_id', '=', $uid)
                ->where('starred', true)
                ->where('status', false)
                ->count();

        $tasksPriorities = Tasks::where('user_id', '=', $uid)
                ->whereNotIn('priority', ['', 'null'])
                ->where('status', false)
                ->count();

        $tasksDueDate = Tasks::where('user_id', '=', $uid)
                ->whereNotNull('end_date')
                ->where('status', false)
                ->count();

        $tasksRecentlyAdded = Tasks::where('user_id', '=', $uid)
                ->where('status', false)
                ->where(DB::raw('created_at'), '!=', DB::raw('updated_at'))
                ->orderBy('updated_at', 'desc')              
                ->count();

        $tasksCompleted = Tasks::where('user_id', '=', $uid)
                ->where('status', true)
                ->count();
        
        
        return response()->json(['inbox' => $tasksInbox, 'starred' => $tasksStarred,
            'priorities' => $tasksPriorities, 'duedate' => $tasksDueDate]);
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
        $task = new Tasks();

        // Call fill on the task and pass in the data
        $task->fill($data);

        // Save to table
        $task->save();

        //find task item
        $taskOne = Tasks::find($task->id);

        //save data to usertasks
        $usertask = new UsersTasks();
        $usertask->user_id = tokenUserID();
        $usertask->type = 'creator'; //creator or member
        $usertask->task_id = $task->id;
        $usertask->save();

        // Now send event
        \Event::fire(new ServerUpdated($taskOne, '', 'tasks'));
        
        $arrData = array();
        return ['task' => $taskOne->toArray(), 
                'subtasks' => $arrData,
                'notes' => $arrData,
                'comments' => $arrData,
                'files' => $arrData,
                'assigned_users' => $arrData,
                'urlSlug' => $arrData];        
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
        $task = Tasks::find($id);

        // Call fill on the tasks and pass in the data
        $task->fill($data);

        $task->save();

        /*return $this->respond([
            'data' => $this->tasksTransformer->transform($task)
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
        $task = Tasks::find($id);

        if(!$task)
        {
            return $this->respondNotFound('Delete: Task does not exist.');
        }

        $task->delete();
        
        return $this->respondOK('success');
    }
}
