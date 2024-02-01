<?php

use App\Http\Controllers\AuthController; 
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskAssigneeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TakController;
use App\Http\Middleware\ValidateFields;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    
    // Authenication endpoint 
    Route::post('/logout', [AuthController::class, 'logout']);

    // TaskAssignee Controller
    Route::put('/task/{id}/assignee', [TaskAssigneeController::class, 'update']);

    Route::get('/assignee/{id}', [TaskController::class, 'test']);

    Route::get('/task/search/assignee', [TaskController::class,'searchTaskByAssignee']); 
    Route::put('/task/{id}/category', [TaskController::class,'addCategory']);

    // Task with search functionalities endpoints
    Route::get('/task/search/title', [TaskController::class,'searchTaskByTitle']);
    Route::get('/task/search/desc', [TaskController::class,'searchTaskByDesc']);
   

    // Task with filtering fucntionalities endpoints
    Route::get('/task/filter/date', [TaskController::class,'filterTaskByDate']);
    Route::get('/task/filter/priority', [TaskController::class,'filterTaskByPriority']);
    Route::get('/task/filter/status', [TaskController::class,'filterTaskByStatus']);

    // Task pagination endpoint
    Route::get('/task_lists', [TaskController::class,'paginateTask']);

    // Categories endpoints
    Route::get('/categories', [CategoryController::class, 'getTaskByCategoryId']); 

    // Comments endpoints
 
    Route::get('/comment/task/{taskId}', [CommentController::class,'getComment']);

});


Route::middleware(['auth:sanctum', 'validate.fields:title,description,due_date,priority,reporter_id,status'])->group(function () {
    Route::resource('tasks', TakController::class);

    Route::post('/comment', [CommentController::class,'addComment']);

});


// Authentication endpoints
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']); 

// Fallback endpoint: This will only work in get method 
Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found'], 404);
});

