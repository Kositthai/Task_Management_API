<?php

use App\Http\Controllers\AuthController; 
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication endpoints
Route::post('/login', [AuthController::class, 'login']); 
Route::post('/register', [AuthController::class,'register']);
Route::post('/logout', [AuthController::class,'logout']);

// Use POST to create a new task 
Route::post('/task', [TaskController::class,'addATask']);

// Use GET to fetch all task or specific task by ID
Route::get('/tasks', [TaskController::class,'getTasks']);
Route::get('/task/{id}', [TaskController::class,'getTaskById']);

// Use PUT/PATCH to update a task by ID
Route::put('/task', [TaskController::class,'updateATask']);

// Use DELETE to delete a task by ID
Route::delete('/task', [TaskController::class,'deleteTask']);

// Task_User endpoints 
Route::put('/task/{id}/assignee', [TaskController::class, 'addAssignee']); 
Route::put('/task/{id}/category', [TaskController::class,'addCategory']);

// Additional routes for specific actions
Route::get('/task/search/title', [TaskController::class,'searchTaskByTitle']);
Route::get('/task/search/desc', [TaskController::class,'searchTaskByDesc']);
Route::get('/task/search/assignee', [TaskController::class,'searchTaskByAssignee']);

// Categories endpoints
Route::get('/categories', [CategoryController::class, 'getTaskByCategoryId']); 

// Comments endpoints
Route::post('/comment', [CommentController::class,'addComment']);
Route::get('/comment/task/{taskId}', [CommentController::class,'getComment']);

// Fallback endpoint: This will only work in get method 
Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found'], 404);
});

