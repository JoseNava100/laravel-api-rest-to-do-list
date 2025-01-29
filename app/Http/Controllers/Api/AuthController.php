<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register (Request $request) {

        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|string|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]); 

        if ($validation->fails()) {
            
            $message = [
                'message' => 'Error in data validations',
                'errors' => $validation->errors(),
                'status' => 400,
            ];

            return response()->json($message, 400);

        } else {
            
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $response = [
                'token' => $user->createToken('')->plainTextToken,
                'username' => $user->username,
                'email' => $user->email,
            ];

            $message = [
                'message' => 'User created successfully',
                'data' => $response,
                'status' => 201,
            ];

            return response()->json($message, 201);
        }
        
    }
}
