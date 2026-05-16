<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->unsignedBigInteger('student_id')->nullable()->change();
            $table->foreign('student_id')->references('id')->on('users')->nullOnDelete();
            $table->string('student_name')->nullable()->after('student_id');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('student_name');
            $table->dropForeign(['student_id']);
            $table->unsignedBigInteger('student_id')->nullable(false)->change();
            $table->foreign('student_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
