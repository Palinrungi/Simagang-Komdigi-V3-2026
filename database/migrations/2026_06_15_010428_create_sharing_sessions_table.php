<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('sharing_sessions', function (Blueprint $table) {
    $table->id();

    // admin yang membuat jadwal
    $table->foreignId('created_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    // intern yang dipilih sebagai narasumber
    $table->foreignId('speaker_user_id')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    // intern yang dipilih sebagai moderator
    $table->foreignId('moderator_user_id')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    // diisi admin
    $table->date('session_date');
    $table->time('start_time')->nullable();
    $table->string('location')->nullable();

    // diisi narasumber
    $table->string('title')->nullable();
    $table->text('description')->nullable();
    $table->string('evaluation_form_link')->nullable();

    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('sharing_sessions');
    }
};