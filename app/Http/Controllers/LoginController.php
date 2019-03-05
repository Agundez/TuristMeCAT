<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Login;
use \Firebase\JWT\JWT;


class LoginController extends Controller
{
    
	public function login()
    {
        if (empty($_POST['email']) or empty($_POST['password'])) 
        {
            return response()->json([
                'MESSAGE' => 'Some fields are empty', 411
            ], 411);
        } 
        else 
        {
            $user = User::where('email', $_POST['email'])->first();
            if (!empty($user)) 
            {
                if (decrypt($user->password) == $_POST['password']) 
                {
                    if ($user->role_id == 1) {
                            $tokenParams = [
                            'id' => $user->id,        
                            'password' => $_POST['password'],
                            'email' => $_POST['email']
                        ];

                        $token = JWT::encode($tokenParams, $this->key);
                        return response()->json([
                            'MESSAGE' => $token, 200
                        ]);
                    }else {
                        return response()->json([
                            'MESSAGE' => 'Dont have enough permission', 403
                        ], 403);
                    }
                    
                } else {
                    return response()->json([
                        'MESSAGE' => 'The specified password doesnt exist', 403
                    ], 403);
                }
            }else {
                return response([
                    'MESSAGE' => 'The specified email doesnt exist', 403
                ], 403);
            }
        }
    }
}
