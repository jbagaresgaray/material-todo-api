<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\UsersProjectsTransformer;
use App\Models\UsersProjects;

class UsersProjectsController extends ApiController
{

    /**
     * @var Transformers\UsersProjectsTransformer
     */
    protected $usersprojectsTransformer;

    function __construct(UsersProjectsTransformer $usersprojectsTransformer)
    {
        $this->usersprojectsTransformer = $usersprojectsTransformer;

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
        $usersProjects = UsersProjects::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($usersProjects, [
            'data' => $this->usersprojectsTransformer->transformCollection($usersProjects->all())
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
        $usersProject = new UsersProjects();

        // Call fill on the usersProject and pass in the data
        $usersProject->fill($data);

        // Save to table
        $usersProject->save();

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
        $usersProject = UsersProjects::find($id);

        if(!$usersProject)
        {
            return $this->respondNotFound('usersProject does not exist.');
        }

        return $this->respond([
            'data' => $this->usersprojectsTransformer->transform($usersProject)
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
        $usersProject = UsersProjects::find($id);

        // Call fill on the usersProject and pass in the data
        $usersProject->fill($data);

        $usersProject->save();

         return $this->respond([
            'data' => $this->usersprojectsTransformer->transform($usersProject)
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
        $usersProject = UsersProjects::find($id);

        if(!$usersProject)
        {
            return $this->respondNotFound('Delete: usersProject does not exist.');
        }

        $usersProject->delete();
        
        return $this->respondCreated('usersProject successfully deleted.');
    }
}
