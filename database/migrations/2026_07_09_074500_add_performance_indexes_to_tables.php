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
        Schema::table('interns', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('mentor_id');
            $table->index('team_id');
            $table->index('pengajuan_detail_id');
            $table->index(['is_active', 'mentor_id']);
        });

        Schema::table('mentors', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('team_id');
        });

        Schema::table('logbooks', function (Blueprint $table) {
            $table->index('intern_id');
            $table->index('approved_by');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->index('intern_id');
            $table->index('date');
        });
        
        Schema::table('final_reports', function (Blueprint $table) {
            $table->index('intern_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['mentor_id']);
            $table->dropIndex(['team_id']);
            $table->dropIndex(['pengajuan_detail_id']);
            $table->dropIndex(['is_active', 'mentor_id']);
        });

        Schema::table('mentors', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['team_id']);
        });

        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropIndex(['intern_id']);
            $table->dropIndex(['approved_by']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['intern_id']);
            $table->dropIndex(['date']);
        });
        
        Schema::table('final_reports', function (Blueprint $table) {
            $table->dropIndex(['intern_id']);
        });
    }
};
