<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Registration form link (e.g. a Google Form) attached to a news article,
 * rendered as a "Register Now" call-to-action on the article page.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'form_url')) {
                $table->string('form_url', 1000)->nullable()->after('video_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'form_url')) {
                $table->dropColumn('form_url');
            }
        });
    }
};
