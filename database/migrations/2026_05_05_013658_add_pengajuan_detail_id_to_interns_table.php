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
        Schema::table('interns', function (Blueprint $table) {
            $table->foreignId('pengajuan_detail_id')
                  ->nullable()
                  ->after('user_id') // opsional posisi kolom
                  ->constrained('pengajuan_details')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_detail_id']);
            $table->dropColumn('pengajuan_detail_id');
        });
    }
};
