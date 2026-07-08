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
        Schema::table('activity_posts', function (Blueprint $table) {
            $table->string('editor_name')->nullable()->after('author_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_posts', function (Blueprint $table) {
            $table->dropColumn('editor_name');
        });
    }
};
