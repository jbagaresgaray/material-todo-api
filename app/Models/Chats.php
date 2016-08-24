<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    //assign primary key field name.
	protected $primaryKey = 'id';

	//database table name.
	protected $table = 'chats';

	//set fillable fields
    protected $fillable = [
	    'group_chat_user_ids', 
	    'project_group_user_ids',
	    'is_one_to_chat', 
	    'is_project_group_chat',
	    'is_group_chat', 
	    'status_one_to_one',
	    'status_group_chat', 
	    'status_project_group',
	    'user_id_receiver', 
	    'user_id_sender',
	    'project_id'
    ];
}
