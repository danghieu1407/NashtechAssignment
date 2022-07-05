<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        //mat khau : $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
        $user = User::where('email', $request->email)->first();
        if(!$user) {
            return response()->json(['message' => 'User was not exist ! '], 404);
        }
        if((!Hash::check($request->password, $user->password))){
            return response()->json(['message' => 'Password was wrong ! '], 404);
        }
        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json(
            ['message' => 'Login Success ! ',
            'token' => $token,
            'token_type' => 'Bearer',
            ], 200);
    }
    public function register (Request $request)
    {
        $message = [
            'email.email '=> 'Email is not valid',
            'email.unique' => 'Email is already exist',
            'password.min' => 'Password must be at least 6 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'email.required' => 'Please input email',
            'password.required' => 'Please input password',
            'password_confirmation.required' => 'Please input password confirmation',
            'first_name.required' => 'Please input first name',
            'last_name.required' => 'Please input last name',

        ]; 
        $validate = Validator::make($request->all(), 
            [
                'email' => 'required|email|unique:user,email',
                'password' => 'required|min:6|confirmed',
                'first_name' => 'required',
                'last_name' => 'required',
            ], $message);
        if($validate->fails()) {
            return response()->json(['message' => $validate->errors()], 400);
        }
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_confirmation' => $request->password_confirmation,
        ]);
        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json(
            ['message' => 'Register Success ! ',
            'token' => $token,
            'token_type' => 'Bearer',
            ], 200);
      
    }
    public function user(Request $request)
    {   
        return request()->user();
    }

    
    public function logout(){
        $user = request()->user();
        $user->tokens()->delete();
        return response()->json(['message' => 'Logout Success ! '], 200);
    }
}
