<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException; 
use Illuminate\Validation\Rule;

class TaskController extends Controller
{


    public function addCategory(Request $request) { 

        $taskId = $request->input('task_id');
        $categoryId = $request->input('category_id', []);
      

        $task = Task::find($taskId);
        
        if(!$task) {
            return response()->json(['error'=> 'Task not found'], 400);
        }

        $task->categories()->sync($categoryId); 
        return response()->json(['task_id'=> $taskId,'category_id'=> $categoryId]); 
    }

    public function searchTaskByTitle(Request $request) 
    {
        $query = $request->input('query');
        $task = Task::where('title','like','%'. $query .'%')->get();

        if($task->isEmpty()){
            return response()->json(['error'=> 'this title is not exist here'], 404);
        }

        return response()->json([$task], 200); 
    }

    public function searchTaskByDesc(Request $request) 
    {
        $query = $request->input('query');
        $task = Task::where('description','like','%'. $query .'%')->get();

        if($task->isEmpty()){
            return response()->json(['error'=> 'this title is not exist here'], 404);
        }

        return response()->json([$task], 200); 
    }

    public function searchTaskByAssignee(Request $request) 
    {
        $userId = $request->input('user_id');
    
        $tasks = Task::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with(['users' => function($query) use ($userId) {
            $query->where('user_id', $userId)->select('name');
        }])->get();
    
        return response()->json($tasks, 200); 
    }
    
    public function test($id) 
    {
        $userId = $id;
    
        $tasks = Task::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with(['users' => function($query) use ($userId) {
            $query->where('user_id', $userId)->select('name');
        }])->get();
    
        return response()->json($tasks, 200); 
    }
    

    public function filterTaskByDate(Request $request) 
    {

        $tasks = Task::where('due_date', $request->input('due_date'))->get();

        return response()->json($tasks, 200); 
    }

    public function filterTaskByPriority(Request $request) 
    {

        $tasks = Task::where('priority', $request->input('priority'))->get();

        return response()->json($tasks, 200); 
    }

    public function filterTaskByStatus(Request $request) 
    {

        $tasks = Task::where('status', $request->input('status'))->get();

        return response()->json($tasks, 200); 
    }

  
    public function paginateTask() 
    {
        $tasks = Task::paginate(5); 
       
        return response()->json($tasks, 200);
    }
}
