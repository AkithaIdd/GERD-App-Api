<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;

class NewPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $user_email = $request->get('email');

        error_log("id".$user_email);
        $users = User::where('email', '=', $request->input('email'))->first();
        // error_log("id".$user);
        $validator = Validator::make($request->all(),[
        
            "email" => "required|email",
          
        ]);

        if($validator->fails()){
            return response()->json([
               'status' => 400,
               'message' => $validator->messages()->first(),
            //    'errors' => $validator->messages()->first(),
            ]);
        }else if ($users === null) {
            return response()->json([
                'status' => 400,
                'message' => "No users from this email",
             ]);
        }

        // $request->validate([
        //     'email' => 'required|email',
        // ]);
       
        
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => 200,
                'message' => __($status)
            ];
        }

        // throw ValidationException::withMessages([
        //     'email' => [trans($status)],
        // ]);
    }

    public function reset(Request $request)
    {
        $input = $request->all();

        // $rules = array(
        //     'token'=> 'required',
        //     'email' => 'required',
        //     'password' => 'required|min:8',
        //     'password_confirmation' => 'required|same:password',
        // );
        $validator = Validator::make($input, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(["status" => 400, "message" => $validator->errors()->first()]);
        }
        // $validator = $request->validate([
        //     'token' => 'required',
        //     'email' => 'required|email',
        //     'password' => 'required|min:8',
        //     'password_confirmation' => 'required|same:password',
        // ]);

        // if ($validator->fails()) {
        //         $arr = array("status" => 400, "message" => $validator->errors()->first());
        //     }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'status' => 200,
                'message'=> 'Password reset successfully'
            ]);
           
        }

        // return view('forgot_password',["message"=> __($status)]);
        return Redirect::to('forgot_password')->with('message', 'Login Failed');

        // return response([
        //     'status' => 500,
        //     'message'=> __($status)
        // ]);

    }
}
