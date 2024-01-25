<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function getTaskByCategoryId(Request $request) { 

      $userId = $request->input('user_id');

      $task  = DB::table("tasks")
      ->join('category_task', 'tasks.id', '=', 'category_task.task_id')
      ->where('category_task.category_id', '=', $request->category_id)
      ->where('reporter_id','=', $userId)
      ->get(); 
    

       return response()->json([$task]);


    }
}
