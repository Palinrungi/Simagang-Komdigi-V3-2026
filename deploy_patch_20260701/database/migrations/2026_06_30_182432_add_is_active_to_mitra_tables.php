<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('industris', 'is_active')) {
            Schema::table('industris', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('status');
            });
        }

        if (!Schema::hasColumn('institusis', 'is_active')) {
            Schema::table('institusis', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('no_hp');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('industris', 'is_active')) {
            Schema::table('industris', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        if (Schema::hasColumn('institusis', 'is_active')) {
            Schema::table('institusis', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};