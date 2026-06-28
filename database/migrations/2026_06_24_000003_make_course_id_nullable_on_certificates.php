<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Allow certificates without a course (bulk metadata imports). The FK
        // permits NULL automatically; we only need to drop the NOT NULL flag.
        DB::statement('ALTER TABLE `certificates` MODIFY `course_id` BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `certificates` MODIFY `course_id` BIGINT UNSIGNED NOT NULL');
    }
};
