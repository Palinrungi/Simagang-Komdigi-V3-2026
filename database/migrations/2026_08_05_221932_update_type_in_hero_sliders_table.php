<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hero_sliders', function (Blueprint $table) {
            $table->string('type')->default('general')->change(); // Mengubah type jadi string bebas/flexible
        });
    }

    public function down()
    {
        Schema::table('hero_sliders', function (Blueprint $table) {
            $table->string('type')->change();
        });
    }
};