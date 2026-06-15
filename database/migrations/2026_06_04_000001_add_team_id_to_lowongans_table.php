<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (!Schema::hasColumn('lowongans', 'team_id')) {

        Schema::table('lowongans', function (Blueprint $table) {

            $table->unsignedBigInteger('team_id')
                ->nullable()
                ->after('industri_id');

        });

    }
}

   public function down(): void
{
    if (Schema::hasColumn('lowongans', 'team_id')) {

        Schema::table('lowongans', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });

    }
}
};