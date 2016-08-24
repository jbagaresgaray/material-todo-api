<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationsUsers extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'organizations_users';

	//set fillable fields
    protected $fillable = [
	    'user_id', 
	    'role_id',
	    'organization_id'
    ];
}
