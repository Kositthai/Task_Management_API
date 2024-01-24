<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('category_task', function (Blueprint $table) {
            $table->timestamps();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('task_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_task');
    }
};
