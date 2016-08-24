<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folders extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'folders';

	//set fillable fields
    protected $fillable = [
	    'name', 
	    'description',
	    'organise', 
	    'parent_folder_id',
	    'user_id'
    ];

    protected $casts = [
        'folder_id' => 'int'
    ];
}
