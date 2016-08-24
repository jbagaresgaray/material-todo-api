<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagsUnion extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'tags_union';

	//set fillable fields
    protected $fillable = [
	    'tag_id',
	    'associated_id',
	    'department',
	    'user_id'
    ];
}
