<?php

use App\Http\Controllers\AuthController; 
use App\Http\Controllers\CategoryController;
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


// Task endpoints
Route::post('/task', [TaskController::class,'addATask']);
Route::get('/task/{id}', [TaskController::class,'getTaskById']);
Route::get('/task', [TaskController::class,'getTask']);
Route::put('/task', [TaskController::class,'updateATask']);
Route::delete('/task', [TaskController::class,'deleteTask']);

// Task_User endpoints 
Route::put('/task/assignee', [TaskController::class, 'addAssignee']); 
Route::put('/task/category', [TaskController::class,'addCategory']);


// Categories endpoints
Route::get('/categories', [CategoryController::class, 'addCategory']); 

// Fallback endpoint 
// This will only work in get method 
Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found'], 404);
});

