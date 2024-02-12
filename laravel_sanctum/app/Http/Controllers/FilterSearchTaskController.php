<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class FilterSearchTaskController extends Controller
{

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
