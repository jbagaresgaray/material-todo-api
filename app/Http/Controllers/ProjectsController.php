<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\ProjectsTransformer;
use App\Models\Projects;

class ProjectsController extends ApiController
{

    /**
     * @var Transformers\ProjectsTransformer
     */
    protected $projectsTransformer;

    function __construct(ProjectsTransformer $projectsTransformer)
    {
        $this->projectsTransformer = $projectsTransformer;

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
        $projects = Projects::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($projects, [
            'data' => $this->projectsTransformer->transformCollection($projects->all())
        ]);*/

        $projects = Projects::all();
        return $this->respond($projects);
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
        $project = new Projects();

        // Call fill on the chat and pass in the data
        $project->fill($data);

        // Save to table
        $project->save();

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
        $project = Projects::find($id);

        if(!$project)
        {
            return $this->respondNotFound('Project does not exist.');
        }

        return $this->respond([
            'data' => $this->projectsTransformer->transform($project)
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
        $project = Projects::find($id);

        // Call fill on the project and pass in the data
        $project->fill($data);

        $project->save();

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
        $project = Projects::find($id);

        if(!$project)
        {
            return $this->respondNotFound('Delete: Project does not exist.');
        }

        $project->delete();
        
        //return $this->respondCreated('Project successfully deleted.');
        return $this->respondOK('success');
    }
}
