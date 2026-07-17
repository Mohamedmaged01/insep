<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Adds Arabic/English variants (`X_ar`, `X_en`) for every translatable text
 * field across the content tables, and backfills the existing single-column
 * value into `X_ar` (existing content treated as Arabic). The legacy `X`
 * column is kept as a final display fallback.
 */
return new class extends Migration
{
    /** table => [translatable fields] */
    private array $map = [
        'courses'          => ['title', 'description', 'content', 'features', 'accreditation', 'job_opportunities', 'duration'],
        'news'             => ['title', 'description'],
        'committee_members'=> ['name', 'title', 'specialization', 'bio'],
        'batches'          => ['name'],
        'exams'            => ['title'],
        'resources'        => ['title'],
        'live_sessions'    => ['title'],
        'sections'         => ['description'], // name_ar/name_en already exist
    ];

    public function up(): void
    {
        foreach ($this->map as $table => $fields) {
            if (!Schema::hasTable($table)) continue;

            Schema::table($table, function (Blueprint $t) use ($table, $fields) {
                foreach ($fields as $f) {
                    if (Schema::hasColumn($table, $f) && !Schema::hasColumn($table, "{$f}_ar")) {
                        $t->text("{$f}_ar")->nullable();
                    }
                    if (Schema::hasColumn($table, $f) && !Schema::hasColumn($table, "{$f}_en")) {
                        $t->text("{$f}_en")->nullable();
                    }
                }
            });

            // Backfill: copy the existing value into the Arabic variant.
            foreach ($fields as $f) {
                if (Schema::hasColumn($table, $f) && Schema::hasColumn($table, "{$f}_ar")) {
                    DB::statement("UPDATE `{$table}` SET `{$f}_ar` = `{$f}` WHERE `{$f}_ar` IS NULL AND `{$f}` IS NOT NULL");
                }
            }
        }
    }

    public function down(): void
    {
        foreach ($this->map as $table => $fields) {
            if (!Schema::hasTable($table)) continue;
            Schema::table($table, function (Blueprint $t) use ($table, $fields) {
                foreach ($fields as $f) {
                    foreach (["{$f}_ar", "{$f}_en"] as $col) {
                        if (Schema::hasColumn($table, $col)) $t->dropColumn($col);
                    }
                }
            });
        }
    }
};
