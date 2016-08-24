<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizations extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'organizations';

	//set fillable fields
    protected $fillable = [
	    'name', 
	    'description',
	    'user_id'
    ];
}
