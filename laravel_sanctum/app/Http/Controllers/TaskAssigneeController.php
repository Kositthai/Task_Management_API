<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task; 

class TaskAssigneeController extends Controller
{
    public function update(Request $request, $id) {
        $taskId = $id;
        $userId = $request->input('user_id', []);
        
        $task = Task::find($taskId); 
        
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
    
        $task->users()->sync($userId);
    
        return response()->json(['user_id' => $userId, 'task_id' => $taskId], 200); 
        
    }
}
