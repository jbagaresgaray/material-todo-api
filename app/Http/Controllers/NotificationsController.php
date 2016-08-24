<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\NotificationsTransformer;
use App\Models\Notifications;

class NotificationsController extends ApiController
{

    /**
     * @var Transformers\NotificationsTransformer
     */
    protected $notificationsTransformer;

    function __construct(NotificationsTransformer $notificationsTransformer)
    {
        $this->notificationsTransformer = $notificationsTransformer;

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
        $notifications = Notifications::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($notifications, [
            'data' => $this->notificationsTransformer->transformCollection($notifications->all())
        ]);*/

        $notifications = Notifications::all();
        return $this->respond($notifications);
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
        $notification = new Notifications();

        // Call fill on the notification and pass in the data
        $notification->fill($data);

        // Save to table
        $notification->save();

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
        $notification = Notifications::find($id);

        if(!$user)
        {
            return $this->respondNotFound('Notification does not exist.');
        }

        return $this->respond([
            'data' => $this->notificationsTransformer->transform($notification)
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
        $notification = Notifications::find($id);

        // Call fill on the notification and pass in the data
        $notification->fill($data);

        $notification->save();

        return $this->respond([
            'data' => $this->notificationsTransformer->transform($notification)
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
        $notification = Notifications::find($id);

        if(!$notification)
        {
            return $this->respondNotFound('Delete: Notification does not exist.');
        }

        $notification->delete();
        
        //return $this->respondCreated('Notification successfully deleted.');
        return $this->respondOK('success');
    }
}
