<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class TaskController extends Controller
{
  
/**
    * @OA\Get(
    *     path="/api/tasks",
    *     summary="Get a list of tasks",
    *     tags={"Tasks"},
    *     @OA\Response(response=200, description="Successful operation"),
    *     @OA\Response(response=500, description="Invalid request")
    * )
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

    public function show(string $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        return response()->json($task);
    }

    
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


    public function destroy(string $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json(['message' => 'Delete task successfully'], 204);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'error' => 'Entry for ' . str_replace('App\\', '', $exception->getModel()) . ' not found'
            ], 404);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }   
    }
}
