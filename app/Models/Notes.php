<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'notes';

	//set fillable fields
    protected $fillable = [
	    'note',
	    'type', 
	    'associated_id',
	    'user_id'
    ];
}
