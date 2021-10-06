<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
class ApiController extends Controller
{
    /**
     * Get user by token
     * @return User $user
     */

     public function getUser(Request $request)
     {
         return response()->json(['user'=>$request->user()]);

     }

     /**
     * Register User
     * @param Request $request
     * @return Bolean $result
     */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:8",
            "phoneNumber" => "required|min:10|max:10|unique:users"
        ]);

        if($validator->fails()){
            return response()->json([
               'status' => 400,
               'message' => $validator->messages()->first(),
            //    'errors' => $validator->messages()->first(),
            ]);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phoneNumber = $request->phoneNumber;
        $user->password = bcrypt($request->password);
        $user->isActive;
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => "User Registered"
         ]);

    }
}
