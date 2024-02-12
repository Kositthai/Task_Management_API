<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
class AssigneeTaskController extends Controller
{
    public function show(string $id)
    {
        try {
           
            $user = User::findOrFail($id);
            $userId = $user->id;
        
            $tasks = Task::whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->with(['users' => function($query) use ($userId) {
                $query->where('user_id', $userId)->select('name');
            }])->get();

            if($tasks->isEmpty()){
                return response()->json(["Message" => "There is no task assign by this user"], 200); 
            }

            return response()->json($tasks, 200); 
        } catch(ModelNotFoundException $exception){
            return response()->json(["Message" => "User not found", "Error" => $exception->getMessage()], 404); 
        }
    }


    public function update(Request $request, string $id)
    {
        $userId = $id;
        $taskId = $request->input('task_id');
       
        $task = Task::find($taskId); 
        
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
    
        $task->users()->sync($userId);
    
        return response()->json(['user_id' => $id, 'task_id' => $taskId], 200); 
       
    }
}
