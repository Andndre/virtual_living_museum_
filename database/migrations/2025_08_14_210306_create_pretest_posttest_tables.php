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
        // Tabel pretest
        Schema::create('pretest', function (Blueprint $table) {
            $table->id('pretest_id');
            $table->unsignedBigInteger('materi_id');
            $table->text('pertanyaan');
            $table->string('pilihan_a', 255);
            $table->string('pilihan_b', 255);
            $table->string('pilihan_c', 255);
            $table->string('pilihan_d', 255);
            $table->char('jawaban_benar', 1)->comment('a,b,c,d');

            // Foreign key constraint
            $table->foreign('materi_id')->references('materi_id')->on('materi')->onDelete('cascade');
            
            // Index
            $table->index('materi_id');
        });

        // Tabel posttest
        Schema::create('posttest', function (Blueprint $table) {
            $table->id('posttest_id');
            $table->unsignedBigInteger('materi_id');
            $table->text('pertanyaan');
            $table->string('pilihan_a', 255);
            $table->string('pilihan_b', 255);
            $table->string('pilihan_c', 255);
            $table->string('pilihan_d', 255);
            $table->char('jawaban_benar', 1)->comment('a,b,c,d');

            // Foreign key constraint
            $table->foreign('materi_id')->references('materi_id')->on('materi')->onDelete('cascade');
            
            // Index
            $table->index('materi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posttest');
        Schema::dropIfExists('pretest');
    }
};
