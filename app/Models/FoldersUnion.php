<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoldersUnion extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'folders_union';

	//set fillable fields
    protected $fillable = [
	    'folder_id',
	    'associated_id',
	    'department',
	    'user_id'
    ];
}
