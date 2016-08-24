<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use YouProductive\Transformers\URLTransformer;
use App\Models\URL;

class URLController extends ApiController
{

    /**
     * @var Transformers\URLTransformer
     */
    protected $urlTransformer;

    public function __construct(URLTransformer $urlTransformer)
    {
        $this->urlTransformer = $urlTransformer;

        $this->middleware('jwt.auth', [ 'except' => []] );
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urls = URL::all();
        
        return $this->respond($urls);
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
        $urlData = $data['urldata'];

        //generate hash
        $urlHash = md5($urlData);

        $checkURL = URL::where('hash', '=', $urlHash)->first();
        if ($checkURL === null)
        {
            // Create new instance
            $url = new URL();

            // Call fill on the url and pass in the data
            $url->fill($data);

            $url->url = $urlData;
            $url->hash = $urlHash;
            $url->counter = 0;

            // Save to table
            $url->save();

            // Return response
            return $this->respond($url->id);
        }
        else
        {
            // Return response
            return $this->respond($checkURL->id);
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
        $url = URL::find($id);

        if(!$url)
        {
            return $this->respondNotFound('Url does not exist.');
        }

        return $this->respond([
            'data' => $this->urlTransformer->transform($url)
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
        $url = URL::find($id);

        // Call fill on the url and pass in the data
        $url->fill($data);

        $url->save();

        return $this->respond([
            'data' => $this->urlTransformer->transform($url)
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
        $url = URL::find($id);

        if(!$url)
        {
            return $this->respondNotFound('Delete: Url does not exist.');
        }

        $url->delete();
        
        return $this->respondCreated('Url successfully deleted.');
    }
}
