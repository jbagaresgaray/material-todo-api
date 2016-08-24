<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\AdministrativeRolesTransformer;
use App\Models\AdministrativeRoles;

class AdministrativeRolesController extends ApiController
{

    /**
     * @var Transformers\AdministrativeRolesTransformer
     */
    protected $administrativerolesTransformer;

    function __construct(AdministrativeRolesTransformer $administrativerolesTransformer)
    {
        $this->administrativerolesTransformer = $administrativerolesTransformer;
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
        $adminRoles = AdministrativeRoles::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($adminRoles, [
            'data' => $this->administrativerolesTransformer->transformCollection($adminRoles->all())
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
        //version 1
        //this not done yet input field will grow depending on number of fields.
        /*if(!Input::get('email')) //email input field
        {
            return $this->respondUnprocessableEntity('Parameters failed validation for a lesson.');
        }

        AdministrativeRoles::create(Input::all());

        return $this->respondCreated('AdminRole successfully created.');
        */

        //version 2
        // Grab all the input passed in
        $data = Request::all();

        // Create new instance
        $adminRole = new AdministrativeRoles();

        // Call fill on the adminRole and pass in the data
        $adminRole->fill($data);

        // Save to table
        $adminRole->save();

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
        $adminRole = AdministrativeRoles::find($id);

        if(!$adminRole)
        {
            return $this->respondNotFound('AdminRole does not exist.');
        }

        return $this->respond([
            'data' => $this->administrativerolesTransformer->transform($adminRole)
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
        /*version 1
        //this is not final yet will grow the field number
        $user = Users::find($id);

        $user->title = Input::get('title'); //field
        $user->isDone = Input::get('isDone');
        $user->save();
        
        return $this->respond([
            'data' => $this->todoTransformer->transform($user)
        ]);*/

        //version2
        // Grab all the input passed in
        $data = Request::all();

        // Use Eloquent to grab the gift record that we want to update,
        // referenced by the ID passed to the REST endpoint
        $adminRole = AdministrativeRoles::find($id);

        // Call fill on the adminRole and pass in the data
        $adminRole->fill($data);

        $adminRole->save();

        return $this->respond([
            'data' => $this->administrativerolesTransformer->transform($adminRole)
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
        $adminRole = AdministrativeRoles::find($id);

        if(!$adminRole)
        {
            return $this->respondNotFound('Delete: AdminRole does not exist.');
        }

        $uadminRoleser->delete();
        
        return $this->respondCreated('AdminRole successfully deleted.');
    }
}
