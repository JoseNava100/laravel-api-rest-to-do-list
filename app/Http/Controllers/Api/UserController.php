<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index () {

        $user = User::find(auth()->id());

        $message = [
            'message' => 'User found',
            'data' => $user,
            'status' => 200,
        ];

        return response()->json($message, 200);
    }
}
