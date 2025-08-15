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
        Schema::create('ebook', function (Blueprint $table) {
            $table->id('ebook_id');
            $table->unsignedBigInteger('materi_id');
            $table->string('judul', 255);
            $table->string('path_file', 255);

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
        Schema::dropIfExists('ebook');
    }
};
