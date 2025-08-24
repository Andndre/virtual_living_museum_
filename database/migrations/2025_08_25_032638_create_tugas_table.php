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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id('tugas_id');
            $table->unsignedBigInteger('materi_id');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('gambar')->nullable();
            $table->timestamps();

            $table->foreign('materi_id')->references('materi_id')->on('materi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
