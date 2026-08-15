<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->integer('order')->default(0);
            $table->string('video_url')->nullable();
            $table->string('duration')->nullable();
            $table->boolean('is_preview')->default(false);
            $table->json('attachments')->nullable();
            // Self-contained knowledge-check quiz:
            // { pass_score, attempts, questions:[{ id, text, options:[], correct_answer }] }
            $table->json('quiz')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['course_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
