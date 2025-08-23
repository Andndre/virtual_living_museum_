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
        Schema::create('situs_peninggalan', function (Blueprint $table) {
            $table->id('situs_id');
            $table->string('nama', 255);
            $table->text('alamat');
            $table->text('deskripsi');
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->string('masa', 100)->comment('masa sejarah');
            $table->unsignedBigInteger('materi_id')->comment('relasi ke materi');
            $table->unsignedBigInteger('user_id')->comment('uploader atau pengelola');

            // Foreign key constraints
            $table->foreign('materi_id')->references('materi_id')->on('materi')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index(['lat', 'lng']);
            $table->index('materi_id');
            $table->index('user_id');
            $table->index('masa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('situs_peninggalan');
    }
};
