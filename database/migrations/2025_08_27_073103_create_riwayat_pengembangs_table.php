<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_pengembangs', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->date('tahun');
            $table->date('tahun_selesai')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_pengembangs');
    }
};
