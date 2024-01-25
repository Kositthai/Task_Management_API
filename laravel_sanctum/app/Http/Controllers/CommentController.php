<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Task;

class CommentController extends Controller
{
    public function addComment(Request $request) 
    {
        try {

        $taskId = $request->input("task_id");
        $task = Task::find($taskId);

        if(!$task)
        {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $comment = new Comment();
        $comment->body= $request->body;
        $comment->task_id = $taskId;

        $comment->save();   

        return response()->json([$comment], 201);
        }catch (\Exception $e){
            return response()->json(['Bad request'], 400); 
        }
    }

    public function getComment($taskId) 
    {
        try {
            $comments = Comment::where('task_id', $taskId)->with('task:id,title')->get();

            if($comments->isEmpty()) 
            {
                return response()->json(['error'=> 'Task not found or has no comment'], 404);
            }

            $comments = Comment::where('task_id', $taskId)->with('task:id,title')->get(); 

            return response()->json($comments, 200);

        }catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
