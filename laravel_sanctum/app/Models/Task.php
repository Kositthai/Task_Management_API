<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date', 'priority', 'status', 'reporter_id']; 

    protected $hidden = ['pivot'];

    public function users()
    {
        return $this->belongsToMany(User::class); 
    }
    
    public function categories() 
    {
        return $this->belongsToMany(Category::class); // Resolved through task_user
    }

    public function comments() 
    {
        return $this->hasMany(Comment::class);
    }
}
