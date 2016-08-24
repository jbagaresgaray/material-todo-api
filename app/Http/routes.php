<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'v1'], function() {
	Route::post('auth/login', 'AuthController@login');
	Route::get('user/verify/{confirmationCode}', 'UsersController@confirmUser');
	Route::get('user/search/{keyword}', 'UsersController@userSearchByKeyword');
	Route::resource('users', 'UsersController');
	Route::get('tasks-group/{view}', 'TasksController@tasksByGroup'); 
	Route::get('tasks-counters', 'TasksController@allCounterTasks');
	Route::resource('tasks', 'TasksController'); 
	Route::resource('administrativeroles', 'AdministrativeRolesController'); 
	Route::resource('chats', 'ChatsController'); 
	Route::get('comments/{group}/{id}', 'CommentsController@commentsGroupById');
	Route::resource('comments', 'CommentsController'); 
	Route::get('files/{department}/{id}', 'FilesController@index'); 
	Route::resource('files', 'FilesController', ['except' => ['index']]); 
	Route::post('foldercheck/{name}', 'FoldersController@checkFolderNameIfExistIfNotCreated'); 
	Route::resource('folders', 'FoldersController'); 
	Route::get('foldersunions/{department}/{id}', 'FoldersUnionController@index');
	Route::get('foldersuser', 'FoldersUnionController@foldersByUser'); 
	Route::resource('foldersunions', 'FoldersUnionController', ['except' => ['index', 'foldersByUser']]); 
	Route::resource('logs', 'LogsController'); 
	Route::get('notes/{department}/{id}', 'NotesController@index'); 
	Route::resource('notes', 'NotesController', ['except' => ['index']]); 
	Route::resource('notifications', 'NotificationsController'); 
	Route::resource('organizations', 'OrganizationsController'); 
	Route::resource('organizationsusers', 'OrganizationsUsersController'); 
	Route::resource('projects', 'ProjectsController'); 
	Route::resource('roles', 'RolesController'); 
	Route::resource('status', 'StatusController'); 
	Route::post('tagcheck/{name}', 'TagsController@checkTagNameIfExistIfNotCreated'); 
	Route::resource('tags', 'TagsController'); 
	Route::get('tagsunions/{department}/{id}', 'TagsUnionController@index'); 
	Route::resource('tagsunions', 'TagsUnionController', ['except' => ['index', 'tagsByUser']]); 
	Route::resource('urls', 'URLController'); 
	Route::get('urlshorts/{slug}', 'URLShortenerController@getUrlSlugData'); 
	Route::resource('urlshorts', 'URLShortenerController', ['except' => ['getUrlSlugData']]); 
	Route::resource('usersnotes', 'UsersNotesController'); 
	Route::resource('usersprojects', 'UsersProjectsController'); 
	Route::resource('usersroles', 'UsersRolesController'); 
	Route::get('userstasks/{taskid}', 'UsersTasksController@index'); 
	Route::resource('userstasks', 'UsersTasksController', ['except' => ['index']]); 

	//for redis/nodejs/socket
	//Route::post('socket', 'SocketController@sendMessage');

	Route::get('socket', function () {
	    // this checks for the event
	    return view('welcome');
	});

	Route::get('fire', function () {
	    // this fires the event
	    event(new App\Events\ServerUpdated());
	    return "event fired";
	});

	//temporary routes
	//Route::get('tasks/{taskgroup}/{id}', 'TasksController@index');
	//Route::get('tasks/totals/{taskgroup}/{id}', 'TasksController@countTasks');
	//Route::get('tasks/all/totals/{id}', 'TasksController@allCounterTasks');
});
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
