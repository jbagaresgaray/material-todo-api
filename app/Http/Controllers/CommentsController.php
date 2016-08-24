<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\CommentsTransformer;
use App\Models\Comments;
use Response;
use DB;
use App\Events\ServerUpdated;

class CommentsController extends ApiController
{

    /**
     * @var Transformers\CommentsTransformer
     */
    protected $commentsTransformer;

    public function __construct(CommentsTransformer $commentsTransformer)
    {
        $this->commentsTransformer = $commentsTransformer;

        $this->middleware('jwt.auth');
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
        $comments = Comments::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($comments, [
            'data' => $this->commentsTransformer->transformCollection($comments->all())
        ]);*/
         return $this->respond('comments here - index method');
    }

    public function commentsGroupById($group, $id)
    {
        switch ($group) {
            case 'tasks':
                $comments = Comments::where('task_id', '=', $id)
                ->where('type', '=', 'tasks')
                ->where(DB::raw('created_at'), '=', DB::raw('updated_at'))
                ->get();
                break;            

            default:
                $comments = Comments::where('user_id', '=', $id)
                ->get();
                break;
        }
        
        return $this->respond($comments);
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
        //token user id.
        $uid = tokenUserID();

        // Grab all the input passed in
        $data = Request::all();
        $taskID = $data['task_id'];

        //get the users involved in the tasks
        $userList = DB::table('tasks as t')
            ->where('t.id', '=', $taskID)
            ->leftJoin('users_tasks as ut', function ($join) {
                $join->on('ut.task_id', '=', 't.id');
            })
            ->select('t.user_id', 'ut.assigned_user_id')
            ->get();

        // Create new instance
        $comment = new Comments();

        // Call fill on the comment and pass in the data
        $comment->fill($data);

        $comment->user_id = $uid;

        // Save to table
        $comment->save();

        // Now send event
        \Event::fire(new ServerUpdated($comment, $userList, 'comments'));

        // Return response
        //return $this->respondCreated('Successfully created.');
        return $this->respond($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = Comments::find($id);

        if(!$comment)
        {
            return $this->respondNotFound('Comments does not exist.');
        }

        return $this->respond([
            'data' => $this->commentsTransformer->transform($comment)
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
        $comment = Comments::find($id);

        // Call fill on the comment and pass in the data
        $comment->fill($data);

        $comment->save();

        return $this->respond([
            'data' => $this->commentsTransformer->transform($comment)
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
        $comment = Comments::find($id);

        if(!$comment)
        {
            return $this->respondNotFound('Delete: Commment does not exist.');
        }

        $comment->delete();
        
        return $this->respondCreated('Comment successfully deleted.');
    }
}
