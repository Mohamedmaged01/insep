<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->integer('progress')->default(0);
            $table->string('grade')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('enrolled_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
