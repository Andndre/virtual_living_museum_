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
        Schema::create('virtual_museum', function (Blueprint $table) {
            $table->id('museum_id');
            $table->unsignedBigInteger('situs_id');
            $table->string('nama');
            $table->string('path_obj');
            $table->timestamps();

            $table->foreign('situs_id')->references('situs_id')->on('situs_peninggalan')->onDelete('cascade');
            $table->index('situs_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_museum');
    }
};
