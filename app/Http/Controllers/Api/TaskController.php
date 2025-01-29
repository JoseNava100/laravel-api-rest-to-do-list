<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller {

    public function index() {

        $task = Task::all();

        if ($task->isEmpty()) {

            $message = [
                'message' => 'No tasks found',
                'status' => 401,
            ];

            return response()->json($message, 401);

        } else {

            $message = [
                'message' => 'All tasks',
                'data' => $task,
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

    public function show(string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            
            $message = [
                'message' => 'Task not found',
                'status' => 401,
            ];

            return response()->json($message, 404);

        } else {

            $message = [
                'message' => 'Task found',
                'data' => $task,
                'status' => 201,
            ];
            
            return response()->json($message, 201);

        }
    }

    public function update(Request $request, string $id) {

        $task = Task::find($id);

        if (!$task) {
            
            $message = [
                'message' => 'Task not found',
                'status' => 401,
            ];

            return response()->json($message, 401);

        } else {

            $validation = Validator::make($request->all(), [
                'title' => 'nullable|string',
                'description' => 'nullable|string',
                'priority' => 'nullable',
                'due_date' => 'nullable|date',
                'completed' => 'nullable|boolean',
            ]);

            if ($validation->fails()) {
                
                $message = [
                    'message' => 'Error in data validations',
                    'errors' => $validation->errors(),
                    'status' => 406,
                ];

                return response()->json($message, 406);

            } else {

                $task->fill($request->only([
                    'title',
                    'description',
                    'priority',
                    'due_date',
                    'completed',
                ]))->save();
    
                $message = [
                    'message' => 'Update task',
                    'character' => $task,
                    'status' => 200
                ];
    
                 return response()->json($message, 200);

            }
        }
    }

    public function destroy(string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            
            $message = [
                'message' => 'Task not found',
                'status' => 401
            ];

            return response()->json($message, 401);

        } else {
            
            $task->delete();

            $message = [
                'message' => 'Delete car',
                'status' => 201
            ];

            return response()->json($message, 201);
        }
    }
}
