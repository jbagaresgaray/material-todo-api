<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\URLShortenerTransformer;
use App\Models\URLShortener;
use App\Models\Tasks;
use App\Models\Notes;
use App\Models\Comments;
use App\Models\Files;
use App\Models\UsersTasks;
use DB;

class URLShortenerController extends ApiController
{

    /**
     * @var Transformers\URLShortenerTransformer
     */
    protected $urlshortenerTransformer;

    public function __construct(URLShortenerTransformer $urlshortenerTransformer)
    {
        $this->urlshortenerTransformer = $urlshortenerTransformer;

        $this->middleware('jwt.auth', [ 'except' => ['getUrlSlugData']] );
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
        $urlShorts = URLShortener::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($urlShorts, [
            'data' => $this->urlshortenerTransformer->transformCollection($urlShorts->all())
        ]);*/
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
        $type = $data['type'];
        $aid = $data['associated_id'];

        //token user id.
        $uid = tokenUserID();

        //url slug code
        $urlSlug = generateUrlSlugCode();

        $checkUrlSlugTypeAndAID = URLShortener::where('type', '=', $type)
            ->where('associated_id', '=', $aid)
            ->first();

        if ($checkUrlSlugTypeAndAID) 
        {
            // Return response
            return $this->respond($checkUrlSlugTypeAndAID->slug);
            
        }
        else 
        {
            $checkUrlSlug = URLShortener::where('slug', '=', $urlSlug)->first();
            if ($checkUrlSlug === null)
            {
                // Grab all the input passed in
                $data = Request::all();

                // Create new instance
                $urlShort = new URLShortener();

                // Call fill on the urlShort and pass in the data
                $urlShort->fill($data);

                $urlShort->user_id = $uid;
                $urlShort->slug = $urlSlug;

                // Save to table
                $urlShort->save();

                // Return response
                return $this->respond($urlShort->slug);
            }
            else
            {
                // Return response
                return $this->respondWithError('Url slug already exist.');
            }
            
        }
    
    }

    //get data for public display
    public function getUrlSlugData($slug)
    {
        $checkUrlSlug = URLShortener::where('slug', '=', $slug)->first();
        
        if ($checkUrlSlug === null)
        {
            // Return response
            return $this->respond('No data.');
        }
        else
        {
            $type = $checkUrlSlug->type;
            $associatedID = $checkUrlSlug->associated_id;
            $slugID = $checkUrlSlug->id;

            switch ($type) 
            {
                case 'tasks':
                    //main task item
                    $task = Tasks::find($associatedID);

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
                            $join->on('fu.user_id', '=', 'ut.assigned_user_id')
                                 ->where('fu.type', '=', 'user_photo');
                        })
                        ->leftJoin('users as u', function ($join) {
                            $join->on('u.user_id', '=', 'ut.assigned_user_id');
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
                    break;

                case 'files':
                    $files = DB::table('files as ft')
                        ->where('ft.id', $associatedID)
                        ->select('ft.*')
                        ->get();

                    $urlSlugInfo = DB::table('url_shortener as us')
                        ->where('us.id', $slugID)
                        ->select('us.*')
                        ->get();

                    // response the collection
                    return ['file_info' => $files, 
                            'slug_info' => $urlSlugInfo]; 
                    break;


                default:
                    break;
            }

        }
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $urlShort = URLShortener::find($id);

        if(!$urlShort)
        {
            return $this->respondNotFound('urlShort does not exist.');
        }

        return $this->respond([
            'data' => $this->urlshortenerTransformer->transform($urlShort)
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
        $urlShort = URLShortener::find($id);

        // Call fill on the urlShort and pass in the data
        $urlShort->fill($data);

        $urlShort->save();

        return $this->respond([
            'data' => $this->urlshortenerTransformer->transform($urlShort)
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
        $urlShort = URLShortener::find($id);

        if(!$urlShort)
        {
            return $this->respondNotFound('Delete: urlShort does not exist.');
        }

        $urlShort->delete();
        
        return $this->respondCreated('urlShort successfully deleted.');
    }
}
