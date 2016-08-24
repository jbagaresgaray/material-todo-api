<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\UsersRolesTransformer;
use App\Models\UsersRoles;

class UsersRolesController extends ApiController
{

    /**
     * @var Transformers\UsersRolesTransformer
     */
    protected $usersrolesTransformer;

    function __construct(UsersRolesTransformer $usersrolesTransformer)
    {
        $this->usersrolesTransformer = $usersrolesTransformer;

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
        $usersRoles = UsersRoles::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($usersRoles, [
            'data' => $this->usersrolesTransformer->transformCollection($usersRoles->all())
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
        $usersRole = new UsersRoles();

        // Call fill on the usersRole and pass in the data
        $usersRole->fill($data);

        // Save to table
        $usersRole->save();

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
        $usersRole = UsersRoles::find($id);

        if(!$usersRole)
        {
            return $this->respondNotFound('usersRole does not exist.');
        }

        return $this->respond([
            'data' => $this->usersrolesTransformer->transform($usersRole)
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
        $usersRole = UsersRoles::find($id);

        // Call fill on the usersRole and pass in the data
        $usersRole->fill($data);

        $usersRole->save();

         return $this->respond([
            'data' => $this->usersrolesTransformer->transform($usersRole)
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
        $usersRole = UsersRoles::find($id);

        if(!$usersRole)
        {
            return $this->respondNotFound('Delete: usersRole does not exist.');
        }

        $usersRole->delete();
        
        return $this->respondCreated('usersRole successfully deleted.');
    }
}
