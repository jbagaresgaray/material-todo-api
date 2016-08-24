<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdministrativeRoles extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'administrative_roles';

	//set fillable fields
    protected $fillable = [
	    'name', 
	    'user_id'
    ];
}
