<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersRoles extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'users_roles';

	//set fillable fields
    protected $fillable = [
	    'user_id', 
	    'role_id'
    ];
}
