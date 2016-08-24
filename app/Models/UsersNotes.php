<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersNotes extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'users_notes';

	//set fillable fields
    protected $fillable = [
	    'user_id', 
	    'note_id',
	    'permissions'
    ];
}
