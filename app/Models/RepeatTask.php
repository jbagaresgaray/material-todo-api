<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepeatTask extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'repeat_task';

	//set fillable fields
    protected $fillable = [
	    'task_id', 
	    'is_group',
	    'repeat',
	    'repeat_from'
    ];
}
