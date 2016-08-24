<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\RolesTransformer;
use App\Models\Roles;

class RolesController extends ApiController
{

    /**
     * @var Transformers\RolesTransformer
     */
    protected $rolesTransformer;

    function __construct(RolesTransformer $rolesTransformer)
    {
        $this->rolesTransformer = $rolesTransformer;

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
        $roles = Roles::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($roles, [
            'data' => $this->rolesTransformer->transformCollection($roles->all())
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
        $role = new Roles();

        // Call fill on the role and pass in the data
        $role->fill($data);

        // Save to table
        $role->save();

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
        $role = Roles::find($id);

        if(!$role)
        {
            return $this->respondNotFound('Roles does not exist.');
        }

        return $this->respond([
            'data' => $this->rolesTransformer->transform($role)
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
        $role = Roles::find($id);

        // Call fill on the role and pass in the data
        $role->fill($data);

        $role->save();

        return $this->respond([
            'data' => $this->rolesTransformer->transform($role)
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
        $role = Roles::find($id);

        if(!$role)
        {
            return $this->respondNotFound('Delete: Role does not exist.');
        }

        $role->delete();
        
        return $this->respondCreated('Role successfully deleted.');
    }
}
