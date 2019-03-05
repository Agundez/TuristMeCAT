<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
        public static function getRole()
    {
    	$role = new Role();

    	$users = User::all();
    	foreach ($users as $key => $user) 
    	{
    		if ($user->role_id == 1)
    		{
    			return $role->name = 'administrator';
    		}else
    		{
    			return $role->name = 'guest';
    		}	
    	}
    }
}
