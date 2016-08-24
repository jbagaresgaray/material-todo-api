<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'status';

	//set fillable fields
    protected $fillable = [
	    'name'
    ];

    public function users()
    {
        return $this->belongsToMany('App\Users');
    }

}
