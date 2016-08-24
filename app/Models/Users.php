<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use App\Models\Authorizable;
use Hash;

class Users extends Model implements AuthenticatableContract, 
                                     CanResetPasswordContract
{
	use Authenticatable, CanResetPassword;

    //assign primary key field name.
	protected $primaryKey = 'user_id';

	//database table name.
	protected $table = 'users';

	//set fillable fields
    protected $fillable = [
	    'email', 
	    'username',
	    'first_name', 
	    'last_name',
	    'password', 
	    'status',
	    'email_verification_code', 
	    'public_key',
	    'timezone', 
	    'country'
    ];

    //hide fields
    protected $hidden = array('password', 'email_verification_code', 'status');

    //hash password
    public function setPasswordAttribute($value)
	{
	    $this->attributes['password'] = Hash::make($value);
	}

}
