<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable()->after('id')->constrained('sections')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
        });
        Schema::dropIfExists('sections');
    }
};
