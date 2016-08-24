<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\ChatsTransformer;
use App\Models\Chats;

class ChatsController extends ApiController
{

    /**
     * @var Transformers\ChatsTransformer
     */
    protected $chatsTransformer;

    function __construct(ChatsTransformer $chatsTransformer)
    {
        $this->chatsTransformer = $chatsTransformer;

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
        $chats = Chats::where('user_id','=',$uid)->paginate($limit);

        return $this->respondWithPagination($chats, [
            'data' => $this->chatsTransformer->transformCollection($chats->all())
        ]);
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
        $chat = new Chats();

        // Call fill on the chat and pass in the data
        $chat->fill($data);

        // Save to table
        $chat->save();

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
        $chat = Chats::find($id);

        if(!$chat)
        {
            return $this->respondNotFound('Chat does not exist.');
        }

        return $this->respond([
            'data' => $this->chatsTransformer->transform($chat)
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
        $chat = Chats::find($id);

        // Call fill on the gift and pass in the data
        $chat->fill($data);

        $chat->save();

        return $this->respond([
            'data' => $this->chatsTransformer->transform($chat)
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
        $chat = Chats::find($id);

        if(!$chat)
        {
            return $this->respondNotFound('Delete: Chat does not exist.');
        }

        $chat->delete();
        
        return $this->respondCreated('Chat successfully deleted.');
    }
}
