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
        Schema::create('virtual_museum_object', function (Blueprint $table) {
            $table->id('object_id');
            $table->unsignedBigInteger('situs_id');
            $table->string('nama', 255);
            $table->string('gambar_real', 255)->comment('path foto asli');
            $table->string('path_obj', 255)->comment('path file obj');
            $table->string('path_gambar', 255)->comment('path gambar untuk marker');
            $table->string('path_patt', 255)->comment('path file pattern training AR marker');
            $table->text('deskripsi');

            // Foreign key constraint
            $table->foreign('situs_id')->references('situs_id')->on('situs_peninggalan')->onDelete('cascade');
            
            // Index
            $table->index('situs_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_museum_object');
    }
};
