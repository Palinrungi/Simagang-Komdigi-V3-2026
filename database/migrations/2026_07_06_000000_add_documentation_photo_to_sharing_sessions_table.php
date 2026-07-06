<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentationPhotoToSharingSessionsTable extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sharing_sessions', 'documentation_photo')) {
            Schema::table('sharing_sessions', function (Blueprint $table) {
                $table->string('documentation_photo')->nullable()->after('evaluation_form_link');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sharing_sessions', 'documentation_photo')) {
            Schema::table('sharing_sessions', function (Blueprint $table) {
                $table->dropColumn('documentation_photo');
            });
        }
    }
}
