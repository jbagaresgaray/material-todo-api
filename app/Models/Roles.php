<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'roles';

	//set fillable fields
    protected $fillable = [
	    'name', 
	    'description'
    ];
}
