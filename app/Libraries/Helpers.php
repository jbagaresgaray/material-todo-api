<?php

use App\Libraries;

//use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

//generate 30 digit confirmation code.
function generateConfimationCode()
{
	return $confirmation_code = str_random(30);
}

//generate 24 digit url slug code.
function generateUrlSlugCode()
{
	return $urlSlug_code = str_random(24);
}

//get username from email.
function getUsernameFromEmail($email)
{
	$find = '@';
	$pos = strpos($email, $find);
	 
	$username = substr($email, 0, $pos);
	 
	return $username;
}

//get user id from token payload.
function tokenUserID()
{
	$token = JWTAuth::getToken();
    $user = JWTAuth::toUser($token);
    $uid = $user->user_id;

    return $uid;
}

//format file size to human readable.
function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 
