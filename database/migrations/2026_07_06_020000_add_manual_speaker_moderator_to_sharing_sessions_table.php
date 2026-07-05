<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManualSpeakerModeratorToSharingSessionsTable extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sharing_sessions', 'speaker')) {
            Schema::table('sharing_sessions', function (Blueprint $table) {
                $table->string('speaker')->nullable()->after('moderator_user_id');
            });
        }

        if (!Schema::hasColumn('sharing_sessions', 'moderator')) {
            Schema::table('sharing_sessions', function (Blueprint $table) {
                $table->string('moderator')->nullable()->after('speaker');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sharing_sessions', 'moderator')) {
            Schema::table('sharing_sessions', function (Blueprint $table) {
                $table->dropColumn('moderator');
            });
        }

        if (Schema::hasColumn('sharing_sessions', 'speaker')) {
            Schema::table('sharing_sessions', function (Blueprint $table) {
                $table->dropColumn('speaker');
            });
        }
    }
}
