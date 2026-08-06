<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('hero_sliders', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Judul Slide
        $table->text('subtitle')->nullable(); // Deskripsi / Teks kecil
        $table->string('image_path')->nullable(); // Background gambar / poster
        $table->string('button_text')->nullable5; // Teks Tombol (misal: "Daftar Sekarang")
        $table->string('button_url')->nullable(); // Link Tombol
        $table->string('youtube_url')->nullable(); // Khusus jika ingin menyematkan video YouTube
        $table->enum('type', ['general', 'youtube', 'sharing'])->default('general'); // Tipe slide
        $table->integer('order')->default(0); // Urutan slide
        $table->boolean('is_active')->default(true); // Status aktif/tidak
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('hero_sliders');
}
};
