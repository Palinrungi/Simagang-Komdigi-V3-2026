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
        Schema::table('sharing_sessions', function (Blueprint $table) {
            $table->string('documentation_photo')->nullable()->after('evaluation_form_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sharing_sessions', function (Blueprint $table) {
            $table->dropColumn('documentation_photo');
        });
    }
};
