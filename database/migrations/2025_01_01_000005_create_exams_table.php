<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('type')->default('quiz');
            $table->integer('questions')->default(30);
            $table->string('duration')->nullable();
            $table->integer('attempts')->default(1);
            $table->string('status')->default('active');
            $table->string('avg_score')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
