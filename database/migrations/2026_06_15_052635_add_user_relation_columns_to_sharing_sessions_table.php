<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sharing_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('sharing_sessions', 'created_by')) {
                $table->foreignId('created_by')
                    ->nullable()
                    ->after('id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('sharing_sessions', 'speaker_user_id')) {
                $table->foreignId('speaker_user_id')
                    ->nullable()
                    ->after('created_by')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('sharing_sessions', 'moderator_user_id')) {
                $table->foreignId('moderator_user_id')
                    ->nullable()
                    ->after('speaker_user_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('sharing_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('sharing_sessions', 'moderator_user_id')) {
                $table->dropConstrainedForeignId('moderator_user_id');
            }

            if (Schema::hasColumn('sharing_sessions', 'speaker_user_id')) {
                $table->dropConstrainedForeignId('speaker_user_id');
            }

            if (Schema::hasColumn('sharing_sessions', 'created_by')) {
                $table->dropConstrainedForeignId('created_by');
            }
        });
    }
};