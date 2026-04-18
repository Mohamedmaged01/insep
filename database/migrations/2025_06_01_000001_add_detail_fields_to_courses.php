<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'content')) {
                $table->text('content')->nullable()->after('description');
            }
            if (!Schema::hasColumn('courses', 'features')) {
                $table->text('features')->nullable()->after('content');
            }
            if (!Schema::hasColumn('courses', 'accreditation')) {
                $table->text('accreditation')->nullable()->after('features');
            }
            if (!Schema::hasColumn('courses', 'job_opportunities')) {
                $table->text('job_opportunities')->nullable()->after('accreditation');
            }
            if (!Schema::hasColumn('courses', 'promo_video')) {
                $table->string('promo_video')->nullable()->after('image');
            }
            if (!Schema::hasColumn('courses', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['content', 'features', 'accreditation', 'job_opportunities', 'promo_video', 'is_featured']);
        });
    }
};
