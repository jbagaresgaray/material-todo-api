<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'notifications';

	//set fillable fields
    protected $fillable = [
	    'description', 
	    'status',
	    'user_id'
    ];

    //hide fields
    protected $hidden = array('status');
}
