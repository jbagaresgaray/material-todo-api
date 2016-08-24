<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class URLShortener extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'url_shortener';

	//set fillable fields
    protected $fillable = [
	    'slug', 
	    'type',
	    'associated_id',
	    'user_id', 
	    'url_id'
    ];
}
