<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'comments';

	//set fillable fields
    protected $fillable = [
	    'comment',
	    'parent_comment_id', 
	    'status',
	    'project_id',
	    'type', 
	    'user_id',
	    'task_id',
	    'organization_id'
    ];

    //hide fields
    protected $hidden = array('status');
}
