<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
                $tasks = Task::all();
                if(count($tasks) === 0) {
                    return response()->json(['Message' => 'No data.'], 200);
                }

                return response()->json($tasks);

        } catch (ValidationException $e) {

            return response()->json(['Message' => 'Something went wrong, please try again later.'],500);

        }       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string'],
                'description' => ['nullable', 'string'],
                'due_date' => ['nullable', 'date'],
                'priority' => ['nullable', 'integer'],
                'reporter_id' => ['required', 'integer']  
            ]);

            if( $validator->fails() ) {
                return response()->json($validator->errors(),422);
            }

            # this code used to check if user trying to add unexpected fields when submit the form? 
            # the code used array_keys to get the keys of request body and using contains method to check if requestField is contains field (allowedFields) 
            # if found unexpected field inside requestField return true otherwise false 
            $allowedFields = ['title','description','due_date','priority','reporter_id','status'];
            $requestFields = collect(array_keys($request->all()));
            $containsUnexpectedFields = $requestFields->contains(function ($field) use ($allowedFields) {
                return !in_array($field, $allowedFields);
            });
            
            if ($containsUnexpectedFields) {
                return response()->json(['error' => 'Unexpected fields detected.'], 422);
            }

            $task = new Task();
            $task->title= $request->title;
            $task->description= $request->description;   
            $task->due_date= $request->due_date;
            $task->priority= $request->priority;
            $task->reporter_id= $request->reporter_id;    
            $task->save();   

            return response()->json(['Message' => 'Add a task successfully', $task], 201);

        } catch(ValidationException $exception){
            return  response()->json(['error' => $exception -> errors()], 422); 
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            if(count($request->except('id')) === 0){
                return response()->json(['error'=> 'You did not pass any update information'], 400);
            }

            $this->validate($request, [
                'id' => ['required', 'integer'],
                'title' => ['nullable', 'string'],
                'description' => ['nullable', 'string'],
                'due_date' => ['nullable', 'date'],
                'priority' => ['nullable', 'integer'],
                'status' => ['nullable', 'string', Rule::in(['To Do', 'In Progress', 'Done'])],         
            ]);

            $task = Task::findorfail($request->id);
        
            $task->fill($request->only(['title', 'description', 'due_date', 'priority', 'status']));
            $task->save(); 
        
            return response()->json(['Update task successfully', $task], 200);
        } catch(ValidationException $exception){ 
            return response()->json(['error'=> $exception -> errors()], 422);
        }   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
