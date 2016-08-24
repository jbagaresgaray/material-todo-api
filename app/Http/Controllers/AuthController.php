<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Users;
use App\Libraries\Helpers;
use Auth;
use Input;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends ApiController
{
	public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');

            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                //return response()->json(['error' => 'invalid_credentials'], 401);
                return $this->respondUnauthorizedError('Invalid credentials.');
            }
        } catch (JWTException $e) {
            // something went wrong
            //return response()->json(['error' => 'could_not_create_token'], 500);
            return $this->respondInternalError('Could not create token.');
        }

        // if no errors are encountered we can return a JWT
        //return response()->json(compact('token')); 
        return $this->respond(compact('token'));    
    }

}

/*
//test try catch for memcache
        try {

            $this->validate($request, [
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials, $request->has('remember'))) {
                // Return response success
                //return $this->respondOK('success');
                return $this->respondOK(array(Auth::user()));


            }

            

        /*try
        {
            // attempt to verify the credentials and create a token for the user
            if ( ! $token = JWTAuth::attempt($credentials))
            {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        }
        catch (JWTException $e)
        {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }*/

        // all good so return the token
        //return response()->json(compact('token'));
        //return $this->respondOK(array(compact('token')));
        
/*
            // Return response fail
            return $this->respondWithError('failed');
        
        } catch (Exception $e) {
            return $e;
        }
*/