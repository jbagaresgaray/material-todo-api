<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'logs';

	//set fillable fields
    protected $fillable = [
	    'description', 
	    'type',
	    'associated_id',
	    'user_id'
    ];
}
