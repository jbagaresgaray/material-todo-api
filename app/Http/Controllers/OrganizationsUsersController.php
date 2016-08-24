<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\OrganizationsUsersTransformer;
use App\Models\OrganizationsUsers;

class OrganizationsUsersController extends ApiController
{

    /**
     * @var Transformers\OrganizationsUsersTransformer
     */
    protected $organizationsusersTransformer;

    function __construct(OrganizationsUsersTransformer $organizationsusersTransformer)
    {
        $this->organizationsusersTransformer = $organizationsusersTransformer;

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
        $organizationUsers = OrganizationsUsers::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($organizationUsers, [
            'data' => $this->organizationsusersTransformer->transformCollection($organizationUsers->all())
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
        $organizationUser = new OrganizationsUsers();

        // Call fill on the chat and pass in the data
        $organizationUser->fill($data);

        // Save to table
        $organizationUser->save();

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
        $organizationUser = OrganizationsUsers::find($id);

        if(!$organizationUser)
        {
            return $this->respondNotFound('OrganizationUser does not exist.');
        }

        return $this->respond([
            'data' => $this->organizationsusersTransformer->transform($organizationUser)
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
        $organizationUser = OrganizationsUsers::find($id);

        // Call fill on the organizationUser and pass in the data
        $organizationUser->fill($data);

        $organizationUser->save();

        return $this->respond([
            'data' => $this->organizationsusersTransformer->transform($organizationUser)
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
        $organizationUser = OrganizationsUsers::find($id);

        if(!$organizationUser)
        {
            return $this->respondNotFound('Delete: OrganizationUser does not exist.');
        }

        $organizationUser->delete();
        
        return $this->respondCreated('OrganizationUser successfully deleted.');
    }
}
