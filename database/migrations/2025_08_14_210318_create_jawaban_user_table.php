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
        Schema::create('jawaban_user', function (Blueprint $table) {
            $table->id('jawaban_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('materi_id');
            $table->enum('jenis', ['pretest', 'posttest']);
            $table->unsignedBigInteger('soal_id');
            $table->char('jawaban_user', 1);
            $table->boolean('benar');
            $table->integer('poin');
            $table->timestamp('answered_at');

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('materi_id')->references('materi_id')->on('materi')->onDelete('cascade');
            
            // Indexes
            $table->index(['user_id', 'materi_id', 'jenis']);
            $table->index('answered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_user');
    }
};
