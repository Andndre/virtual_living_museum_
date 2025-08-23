<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('progress_materi', function (Blueprint $table) {
            $table->id('progress_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('materi_id');
            $table->enum('status', ['belum_mulai', 'pretest_selesai', 'ebook_selesai', 'museum_selesai', 'posttest_selesai'])->default('belum_mulai');
            $table->datetime('last_updated')->default(DB::raw('CURRENT_TIMESTAMP'));

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('materi_id')->references('materi_id')->on('materi')->onDelete('cascade');

            // Unique constraint to prevent duplicate records
            $table->unique(['user_id', 'materi_id']);

            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['materi_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_materi');
    }
};
