<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\OrganizationsTransformer;
use App\Models\Organizations;

class OrganizationsController extends ApiController
{

    /**
     * @var Transformers\OrganizationsTransformer
     */
    protected $organizationsTransformer;

    function __construct(OrganizationsTransformer $organizationsTransformer)
    {
        $this->organizationsTransformer = $organizationsTransformer;

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
        $organizations = Organizations::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($organizations, [
            'data' => $this->organizationsTransformer->transformCollection($organizations->all())
        ]);*/
        
        $organizations = Organizations::all();
        return $this->respond($organizations);
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
        $organization = new Organizations();

        // Call fill on the chat and pass in the data
        $organization->fill($data);

        // Save to table
        $organization->save();

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
        $organization = Organizations::find($id);

        if(!$organization)
        {
            return $this->respondNotFound('Organization does not exist.');
        }

        return $this->respond([
            'data' => $this->organizationsTransformer->transform($organization)
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
        $organization = Organizations::find($id);

        // Call fill on the organization and pass in the data
        $organization->fill($data);

        $organization->save();

        /*return $this->respond([
            'data' => $this->organizationsTransformer->transform($organization)
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
        $organization = Organizations::find($id);

        if(!$organization)
        {
            return $this->respondNotFound('Delete: Organization does not exist.');
        }

        $organization->delete();
        
        //return $this->respondCreated('Organization successfully deleted.');
        return $this->respondOK('success');
    }
}
