<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller {

    public function index() {

        $tasks = Task::all();

        if ($tasks->isEmpty()) {

            $message = [
                'message' => 'No tasks found',
                'status' => 401,
            ];

            return response()->json($message, 401);

        } else {

            $message = [
                'message' => 'All tasks',
                'data' => $tasks,
                'status' => 200,
            ];

            return response()->json($message, 200);
        }
    }

    public function store(Request $request) {

        if (!Auth::check()) {

            $message = [
                'message' => 'User not authenticated',
                'status' => 401,
            ];

            return response()->json($message, 401);
        }

        if ($request->has('completed')) {
            $request->merge([
                'completed' => filter_var($request->completed, FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        $validation = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'completed' => 'boolean',
        ]);

        if ($validation->fails()) {

            $message = [
                'message' => 'Error in data validation',
                'error' => $validation->errors(),
                'status' => 401,
            ];

            return response()->json($message, 401);
        }

        $taskData = $request->only([
            'title',
            'description',
            'priority',
            'due_date',
            'completed',
        ]);

        $taskData['user_id'] = Auth::id();

        $task = Task::create($taskData);

        if (!$task) {

            $message = [
                'message' => 'Error creating task',
                'status' => 401,
            ];

            return response()->json($message, 401);

        } else {

            $message = [
                'message' => 'Task created',
                'data' => $task,
                'status' => 200,
            ];

            return response()->json($message, 200);
            
        }
    }
}
