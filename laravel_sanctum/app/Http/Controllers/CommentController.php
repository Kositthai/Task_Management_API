<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Task;

class CommentController extends Controller
{
    public function store(Request $request, Task $task) 
    {
        try {
        // task->id will return task.id, if you only declare $task, it will return the whole $task object 
        $tasks = Task::find($task->id);

        if(!$tasks)
        {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $comment = new Comment();
        // $comment represents an instance of the Comment model, and body is a property within the Comment model.
        // We assign the value received from the $request with the key 'body' to the body property of the $comment object.
        $comment->body = $request->body;
        $comment->author_id = $request->author_id;
        $comment->task_id = $task->id;

        $comment->save();   

        return response()->json([$comment], 201);
        }catch (\Exception $e){
            return response()->json(['error'=> $e->getMessage()],500);
        }

     
    }

    public function index(Task $task) 
    {
        try {
            $comments = Comment::where('task_id', $task->id)->with('task:id,title','user:id,name')->get();

            if($comments->isEmpty()) 
            {
                return response()->json(['error'=> 'Task not found or has no comment'], 404);
            }

            return response()->json($comments, 200);

        }catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
