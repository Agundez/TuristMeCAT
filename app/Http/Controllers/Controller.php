<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $key = 'crPTj7hQpfZcDqZ23mMBjzMsECxZ4jes';

    protected function error($code, $message)
    {
    	$json = ['message' => $message];
    	$json = json_encode($json);
    	return response($json, $code);
    }

    protected function success($message, $data = [])
    {
    	$json = ['message' => $message, 'data' => $data];
    	$json = json_encode($json);
    	return response($json, 200);
    }

    protected function checkLogin($email, $password)
    {
        $userSave = User::where('email', $email)->first();
        if ($userSave == null)
        {
            return $this->error(401, 'This user does not exist');
        }
        $emailSave = $userSave->email;
        $passwordSave = $userSave->password;
        $passwordSave = decrypt($passwordSave);
        if ($emailSave == $email and $passwordSave == $password)
        {
            return true;
        }
        return false;
    }
}
