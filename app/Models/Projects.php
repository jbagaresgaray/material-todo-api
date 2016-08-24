<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'projects';

	//set fillable fields
    protected $fillable = [
	    'name', 
	    'description',
	    'public_key', 
	    'status',
	    'start_date', 
	    'end_date',
	    'organization_id'
    ];

    //hide fields
    protected $hidden = array('status', 'public_key');
}
