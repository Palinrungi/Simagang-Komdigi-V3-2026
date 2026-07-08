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
    if (!Schema::hasColumn('activity_posts', 'author_name')) {
        Schema::table('activity_posts', function (Blueprint $table) {
            $table->string('author_name')->nullable()->after('type');
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    if (Schema::hasColumn('activity_posts', 'author_name')) {
        Schema::table('activity_posts', function (Blueprint $table) {
            $table->dropColumn('author_name');
        });
    }
}
};
