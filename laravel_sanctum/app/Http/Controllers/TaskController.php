<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException; 
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    // Database Connection Issue:
    // If there's a problem connecting to the database, Laravel might throw a PDOException or a similar exception.

   public function getTasks(Request $request) {
        $task = Task::all();
        return response()->json($task);
   }  

   public function getTaskById($id) {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

   public function addATask(Request $request) {
        try {
                $this->validate($request, [
                    'title' => ['required', 'string'],
                    'description' => ['nullable', 'string'],
                    'due_date' => ['nullable', 'date'],
                    'priority' => ['nullable', 'integer'],
                    'reporter_id' => ['required', 'integer']  
                ]);

                $task = new Task();
                $task->title= $request->title;
                $task->description= $request->description;   
                $task->due_date= $request->due_date;
                $task->priority= $request->priority;
                $task->reporter_id= $request->reporter_id;
        
                $task->save();   
            return response()->json(['Add task successfully', $task], 201);

        }catch(ValidationException $exception){
            return  response()->json(['error' => $exception -> errors()], 422); 
        } 
    } 


   public function updateATask(Request $request) {
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
        }catch(ValidationException $exception){ 
            return response()->json(['error'=> $exception -> errors()], 422);
        }   
    }

   public function deleteTask(Request $request) {
        try {
            $task = Task::findOrFail($request->id);
            $task->delete();

            return response()->json(['message' => 'Delete task successfully'], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'error' => 'Entry for ' . str_replace('App\\', '', $exception->getModel()) . ' not found'
            ], 404);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }   
    }

    public function addAssignee(Request $request, $id) {
        $taskId = $id;
        $userId = $request->input('user_id', []);
        
        $task = Task::find($taskId); 
        
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
    
        $task->users()->sync($userId);
    
        return response()->json(['user_id' => $userId, 'task_id' => $taskId], 200); 
        
    }

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
