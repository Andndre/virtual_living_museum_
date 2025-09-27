<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('video_peninggalans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('deskripsi');
            $table->string('link');
            $table->string('thumbnail');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_peninggalans');
    }
};
