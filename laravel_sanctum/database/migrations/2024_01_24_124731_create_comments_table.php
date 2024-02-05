<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('body'); 
            $table->unsignedBigInteger('author_id');
            $table->foreignIdFor(\App\Models\Task::class)->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
