<?php

use App\Http\Controllers\AssigneeTaskController;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FilterSearchTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
    
    // Authenication endpoint 
    Route::post('/logout', [AuthController::class, 'logout']);

    // Task with search functionalities endpoints
    Route::get('/task/search/title', [FilterSearchTaskController::class,'searchTaskByTitle']);
    Route::get('/task/search/desc', [FilterSearchTaskController::class,'searchTaskByDesc']);
    Route::get('/task/search/assignee', [FilterSearchTaskController::class,'searchTaskByAssignee']); 
   
    // Task with filtering fucntionalities endpoints
    Route::get('/task/filter/date', [FilterSearchTaskController::class,'filterTaskByDate']);
    Route::get('/task/filter/priority', [FilterSearchTaskController::class,'filterTaskByPriority']);
    Route::get('/task/filter/status', [FilterSearchTaskController::class,'filterTaskByStatus']);

    // Task pagination endpoint
    Route::get('/task_lists', [FilterSearchTaskController::class,'paginateTask']);
});


Route::middleware(['auth:sanctum', 'validate.fields:title,description,due_date,priority,reporter_id,status'])->group(function () {
    Route::resource('tasks', TaskController::class);
});


Route::middleware(['auth:sanctum', 'validate.fields:body,author_id,task_id'])->group(function () {
    // Nested resource Comments 
    Route::resource('tasks.comments', CommentController::class);
});



Route::middleware(['auth:sanctum', 'validate.fields:task_id'])->group(function () {
    // Assignee endpoints
     Route::resource('assignees', AssigneeTaskController::class)->only(['show', 'update']);

    // Categories endpoints
    Route::resource('categories', CategoryController::class);
 });

// Authentication endpoints
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']); 

// Fallback endpoint: This will only work in get method 
Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found'], 404);
});

