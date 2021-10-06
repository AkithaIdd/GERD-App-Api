<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Auth;
use Arr;
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
class LoginController extends Controller
{
      /**
     * Login User
     * @param Request $request
     * @return User $user with token
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
    
            "email" => "required|email",
            "password" => "required"
        ]);

        if($validator->fails()){
            return response()->json([
               'status' => 400,
               'message' => "Bad Request"
            ]);
        }

        if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                'status' => 401,
                'message' => "Unauthorized"
            ]);

        }
        $credentials = $request->only('email','password');
        $credentials['isActive'] = 1 ;

        if(!Auth::attempt($credentials)){
            return response()->json([
                'status' => 403,
                'message' => "InActive User"
            ]);
        }
        $user = User::where("email",$request->email)->select('id','name','email')->first();//first means first user that = to email
        $token = $user->createToken('token_name')->plainTextToken;
        Arr::add($user,'token',$token);
        return response()->json([
            'status' => 200,
            'message' => "User Logged",
            $user]);

    }
}
