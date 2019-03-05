<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Routing\Controller as BaseController;
use Firebase\JWT\JWT;

class UserController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $header = getallheaders();
        $userParams = JWT::decode($header['Authorization'], $this->key, array('HS256'));
        if ($userParams->id == 1) {
            return User::where('role_id', 2)->get();
        }
        else
        {
            return response()->json([
                'MESSAGE' => 403, 'Dont have enough permission'
            ], 403);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    
	public function store(Request $request)
	{
	
		$key = $this->key;
        $user = new User();

        if (empty($request->name) or empty($request->email) or empty($request->password) or empty($request->confirm_password)) 
        {
            return response()->json([
                            'MESSAGE' => 'Some fields are empty', 406
                        ], 406);
        }

        $user->name = str_replace(' ', '', $request->name);
        $user->email = $request->email;

        $users = User::where('email', $request->email)->get();

        foreach ($users as $key => $value) 
        {
            if ($request->email == $value->email) 
            {
                return response()->json([
                    'MESSAGE' => 'The email is in use'
                ], 406);
            }
        }

        $user->name = str_replace(' ', '', $request->name);

        if (!strpos($request->email, "@") || !strpos($request->email, ".")) 
        {       
            return response()->json([
                            'MESSAGE' => 'The email has not been written correctly', 406
                        ], 406);
            
        } else
        {
            $user->email = $request->email;
        }

        if (strlen($request->password) > 7)
        {
            $user->password = encrypt($request->password);
        } else
        {
            return response()->json([
                            'MESSAGE' => 'The password must have at least eight characters', 411
                        ], 411);
        }

        $user->role_id = 2;

        if ($request->confirm_password == $request->password) 
        {   
            $user->save();

            $userSave = User::where('email', $request->email)->first();
            $userParams = [
                'id' => $userSave->id,
                'password' => $request->password,
            ];
            $registerToken = JWT::encode($userParams, $key);

            return $this->success('The user has been correctly created', $user);
        } else
        {
            return response()->json([
                            'MESSAGE' => 'The password must have at least eight characters', 406
                        ], 406);
        }
    }

        /**
     * Display the specified resource.
     *
     * @param  \App\GPASS  $gPASS
     * @return \Illuminate\Http\Response
     */
    public function show(UserController $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GPASS  $gPASS
     * @return \Illuminate\Http\Response
     */
    public function edit(GPASS $gPASS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GPASS  $gPASS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $header = getallheaders();

        if ($header['Authorization'] != null) 
        {
            try {
                $userLogged = JWT::decode($header['Authorization'], $this->key, array('HS256'));
                if ($userLogged->id == 1) 
                {
                    if (empty($request->name) || empty($request->email) || empty($request->password))
                    {
                        return response()->json([
                            'MESSAGE' => 'You have to fill the fields'
                        ], 400);
                    }

        $users = User::where('email', $request->email)->get();

        foreach ($users as $key => $value) 
        {
            if ($request->email == $value->email) 
            {
                return response()->json([
                    'MESSAGE' => 'The email is in use'
                ], 406);
            }
        }

        $user = User::where('id',$id)->first();
        $user->name = str_replace(' ', '', $request->name);

        if (!strpos($request->email, "@") || !strpos($request->email, ".")) 
        {       
            return response()->json([
                            'MESSAGE' => 'The email has not been written correctly', 406
                        ], 406);
            
        } else
        {
            $user->email = $request->email;
        }

        if (strlen($request->password) > 7)
        {
            $user->password = encrypt($request->password);
        } else
        {
            return response()->json([
                            'MESSAGE' => 'The password must have at least eight characters', 411
                        ], 411);
        }

                    
                     $user->name = $request->name;
                     $user->email = $request->email;
                     $user->password = encrypt($request->password);
                     $user->update();

                    return response()->json([
                        'MESSAGE' => 'The user has been updated correctly', 200
                    ], 200);
                } else {
                    return response()->json([
                        'MESSAGE' => 'Dont have enough permission', 403
                    ], 403);
                }
            } 
            catch (Exception $e) 
            {
                return response()->json([
                    'MESSAGE' => 'Dont have enough permission', 403
                ], 403);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GPASS  $gPASS
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)     
    {
        $header = getallheaders();

        if ($header['Authorization'] != null) 
        {
            try {
                $userLogged = JWT::decode($header['Authorization'], $this->key, array('HS256'));
                if ($userLogged->id == 1) 
                {
                    $userID = $id;
                    $userSaved = User::where('id',$userID)->first();
                    $userSaved->delete();
                    return response()->json([
                        'MESSAGE' => 'The user has been deleted correctly', 200
                    ], 200);
                } else {
                    return response()->json([
                        'MESSAGE' => 'Dont have enough permission', 403
                    ], 403);
                }
            } 
            catch (Exception $e) 
            {
                return response()->json([
                    'MESSAGE' => 'Dont have enough permission', 403
                ], 403);
            }
        }
    }
}

