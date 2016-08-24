<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersTasks extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'users_tasks';

	//set fillable fields
    protected $fillable = [
	    'user_id', 
	    'type',
	    'task_id'
    ];
}
