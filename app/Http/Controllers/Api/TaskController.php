<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $message = [
            'message' => 'Task list',
            'status' => 200,

        ];

        return response()->json($message, 200);
    }
}
