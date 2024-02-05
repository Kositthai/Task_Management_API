<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

  // getTaskByCategories 
    public function show(Request $request, $id) 
    {
      $taskId = $request->input('task_id');

      $task = Task::whereHas('categories', function ($query) use ($id) {
        $query->where('category_id', $id);
      })->where('id', $taskId)->get();

      return response()->json($task);
    }

    public function update(Request $request, $id) { 
      $categoryId = $id;
      $taskId = $request->input('task_id');
     
      $task = Task::find($taskId);
      
      if(!$task) {
          return response()->json(['error'=> 'Task not found'], 404);
      }

      $task->categories()->syncWithoutDetaching($categoryId);
      return response()->json(['task_id'=> $taskId,'category_id'=> $categoryId], 200); 
  }

}
