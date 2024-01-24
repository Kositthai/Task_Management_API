<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date', 'priority', 'status', 'reporter_id']; 

    public function users()
    {
        return $this->belongsToMany(User::class); // Resolved through task_user
    }

    public function categories() 
    {
        return $this->belongsToMany(Category::class); // Resolved through task_user
    }
}
