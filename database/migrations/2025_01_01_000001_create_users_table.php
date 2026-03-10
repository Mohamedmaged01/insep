<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('role')->default('student'); // admin, student, instructor
            $table->string('birth_date')->nullable();
            $table->string('status')->default('active');
            $table->string('avatar')->nullable();
            $table->string('specialty')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->string('salary')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
