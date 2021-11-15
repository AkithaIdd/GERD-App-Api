<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Auth;
use Arr;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;
class LoginController extends Controller
{
      /**
     * Login User
     * @param Request $request
     * @return User $user with token
     * @return Id $id with token
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
               'message' => "Missing Email Or Password"
            ]);
        }

        if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                'status' => 401,
                'message' => "Invalid Email Or Password"
            ]);

        }
        $credentials = $request->only('email','password');
        $credentials['isActive'] = 1 ;

        if(!Auth::attempt($credentials)){
            return response()->json([
                'status' => 403,
                'message' => "User Has Not Been Activated"
            ]);
        }
        $user = User::where("email",$request->email)->select('id','name','email','phoneNumber')->first();//first means first user that = to email
        $token = $user->createToken('token_name')->plainTextToken;
        Arr::add($user,'token',$token);
        return response()->json([
            'status' => 200,
            'message' => "User Logged",
            'user'=>$user['name'],
            'token'=>$token,
            'email'=>$user['email'],
            'phoneNumber'=>$user['phoneNumber'],
            'id'=>$user->id,
            ]);

    }


    public function change_password(Request $request)
    {
    $input = $request->all();
    // $userid = Auth::attempt($request)->user()->id;
    $userid = $request->user()->id;
    error_log("id".$userid);
    $rules = array(
        'old_password' => 'required',
        'new_password' => 'required|min:8',
        'confirm_password' => 'required|same:new_password',
    );
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
        $arr = array("status" => 400, "message" => $validator->errors()->first());
    } else {
        try {
            if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                $arr = array("status" => 400, "message" => "Check your old password.");
            } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
            } else {
                User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                $arr = array("status" => 200, "message" => "Password updated successfully.");
            }
        } catch (\Exception $ex) {
            if (isset($ex->errorInfo[2])) {
                $msg = $ex->errorInfo[2];
            } else {
                $msg = $ex->getMessage();
            }
            $arr = array("status" => 400, "message" => $msg);
        }
    }
    return \Response::json($arr);
    }
    public function updateProfile(Request $request,$id)
    { 
        $email = $request->get('email');

        // $items = Patient::select('phoneNumber')
        //      ->where('id', $id)
        //      ->first();
        error_log("id".$id);
        $emailOnDb = User::where('id', $id)->
        pluck('email')
        ->first();

        error_log($email);
        error_log("dd".$emailOnDb);
       

        $validator = Validator::make($request->all(),[
          
            "email" => "required|unique:users|email"
        ]);
        if($email==$emailOnDb){
            // return response()->json([
            //     'status' => 400,
            //     'message' => "success",
            //  ]);
        }else if($validator->fails()){
            return response()->json([
               'status' => 400,
               'message' => $validator->messages()->first(),
            ]);
        }

        $user = User::find($id);
        if($user)
        {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phoneNumber = $request->phoneNumber;
            $user->update();

            return response()->json([
                'status' => 200,
                'message' => "User Updated",
             ]);
        }

    }
}
