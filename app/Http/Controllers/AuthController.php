<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // login user function and regsiter  and logout
    public function login(Request $request)
    {
        $Get_Request =  $request->validate([
            'email' =>'required',
            'password' =>'required',
        ]);

       //create user instance and login
       $user = User::where('email', $request->email)->first();
       if ($user && Hash::check($request->password, $user->password)) {
        $token = $user->createToken($user->name);
        return [
             'user' => $user,
             'token' => $token->plainTextToken,
           ];
       }
       return "invalid credentials";
    }

    public function register(Request $request)
    {
        $Get_Request =  $request->validate([
            'name' =>'required',
            'email' =>'required',
            'password' =>'required|confirmed',
        ]);
        // Logic for login user and create session
       $User = User::create([
         'name' => $Get_Request['name'],
         'email' => $Get_Request['email'],
         'password' => Hash::make($Get_Request['password']),
       ]);

       $token = $User->createToken($request->name);


       return [
         'user' => $User,
         'token' => $token->plainTextToken,
       ];

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return "Logged out successfully";

    }
}
