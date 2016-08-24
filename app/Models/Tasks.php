<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'tasks';

	//set fillable fields
    protected $fillable = [
	    'name', 
	    'description',
	    'is_complete',
	    'status', 
	    'start_date',
	    'completion_date',
	    'starred', 
	    'priority',
	    'estimate_time', 
	    'time_spent',
	    'parent_task_id', 
	    'original_task_id',
	    'project_id',
	    'folder_id'
    ];

    //show true or false string instead of 1/0
    protected $casts = [
        'is_complete' => 'boolean',
        'starred' => 'boolean',
    ];
}
