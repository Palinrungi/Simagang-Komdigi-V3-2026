<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('final_reports', function (Blueprint $table) {
            $table->boolean('project_handover_agreement')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('final_reports', function (Blueprint $table) {
            $table->dropColumn('project_handover_agreement');
        });
    }
};