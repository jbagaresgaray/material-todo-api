<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class URL extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'url';

	//set fillable fields
    protected $fillable = [
	    'url', 
	    'hash',
	    'counter'
    ];
}
