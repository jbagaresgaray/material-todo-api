<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'files';

	//set fillable fields
    protected $fillable = [
	    'file_name', 
	    'hash',
	    'mime',
	    'type',
	    'file_size',
	    'project_id',
	    'organization_id', 
	    'task_id',
	    'user_id',
	    'comment_id',
	    's3_file_uri'
    ];
}
