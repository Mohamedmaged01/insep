<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('status')->default('active');
            $table->integer('max_students')->default(30);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
