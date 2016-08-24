<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\ApiController;
use YouProductive\Transformers\UsersTransformer;
use App\Models\Users;
use App\Libraries\Helpers;
use Hash;
use Illuminate\Support\Facades\Mail;
use DB;

class UsersController extends ApiController
{

    /**
     * @var Transformers\UsersTransformer
     */
    protected $usersTransformer;

    public function __construct(UsersTransformer $usersTransformer)
    {
        $this->usersTransformer = $usersTransformer;

        $this->middleware('jwt.auth', [ 'except' => ['store', 'update', 'confirmUser']] );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //token user id.
        $uid = tokenUserID();

        $user = DB::table('users as us')
            ->where('us.user_id', $uid)
            ->leftJoin('files as fu', function ($join) {
                $join->on('fu.user_id', '=', 'us.user_id')
                     ->where('fu.type', '=', 'user_photo');
            })
            ->select('us.user_id', 
                    'us.email',
                    'us.username',
                    'us.first_name',
                    'us.last_name',
                    'us.public_key',
                    'us.timezone', 
                    'us.country', 
                    'fu.s3_file_uri as s3_file_uri_user_photo',
                    'fu.id')
            ->get();

        return $this->respond([
            'user_details' => $user
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
        //email verification code
        $evc = generateConfimationCode();

        // Grab all the input passed in
        $data = Request::all();

        //$email = $request->get('email');

        // Create new instance
        $user = new Users();

        // Call fill on the user and pass in the data
        $user->fill($data);

        // auto generated data for user
        $user->email_verification_code = $evc;
        $user->status = '1';

        // Save to table
        $user->save();

        $emailActivationCode = $evc;
        $sendToEmail = $data['email'];

        //send email
        $emailData = array('sendto' => $sendToEmail, 'emailactivationcode' => $emailActivationCode);
        Mail::send('emails.userverify', $emailData, function($msg) use ($emailData) {
            $msg->from('no-reply@youproductive.com', 'YP Staff');
            $msg->to($emailData['sendto'])->subject('YouProductive Email Activation');
        });

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
        $user = Users::find($id);

        if(!$user)
        {
            return $this->respondNotFound('User does not exist.');
        }

        return $this->respond([
            'data' => $this->usersTransformer->transform($user)
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
        $user = Users::find($id);

        // Call fill on the urlShort and pass in the data
        $user->fill($data);

        $user->save();
        
        /*return $this->respond([
            'data' => $this->usersTransformer->transform($user)
        ]);*/

        // Return response success
        return $this->respondOK('success');
        //return $this->respondOK($data);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Users::find($id);

        if(!$user)
        {
            return $this->respondNotFound('Delete: User does not exist.');
        }

        $user->delete();
        
        return $this->respondCreated('User successfully deleted.');
    
    }

    //user search by keyword
    public function userSearchByKeyword($keyword)
    {
        if ($keyword!='') {
            $user = DB::table('users as us')
            ->where("us.email", "LIKE", "%$keyword%")
            ->orWhere("us.first_name", "LIKE", "%$keyword%")
            ->orWhere("us.last_name", "LIKE", "%$keyword%")
            ->leftJoin('files as fu', function ($join) {
                $join->on('fu.user_id', '=', 'us.user_id')
                     ->where('fu.type', '=', 'user_photo');
            })
            ->select('us.user_id', 
                    'us.email',
                    'us.username',
                    'us.first_name',
                    'us.last_name',
                    'us.public_key',
                    'us.timezone', 
                    'us.country', 
                    'fu.s3_file_uri as s3_file_uri_user_photo')
            ->get();

            return $this->respond([
                'users' => $user
            ]);
        }
    }

    //user signup/registration confirm
    public function confirmUser($email_verification_code)
    {
        if(!$email_verification_code)
        {
            throw new InvalidConfirmationCodeException;
        }

        //email_verification_code = EmailVerificationCode (with where using eloquent).
        $user = Users::whereEmailVerificationCode($email_verification_code)->first();

        if (!$user)
        {
            //throw new InvalidConfirmationCodeException;
            return $this->respondCreated("Verification code not exist.");
        }

        //manage username
        $username = getUsernameFromEmail($user->email);

        $user->username = $username;
        $user->status = '2';
        $user->email_verification_code = null;

        $user->save();

        //return user id
        return $this->respondCreated($user->user_id);
    }
}
