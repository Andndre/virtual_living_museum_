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
        // Tabel kritik_saran
        Schema::create('kritik_saran', function (Blueprint $table) {
            $table->id('ks_id');
            $table->unsignedBigInteger('user_id');
            $table->text('pesan');
            $table->timestamp('created_at')->useCurrent();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Index
            $table->index(['user_id', 'created_at']);
        });

        // Tabel laporan_peninggalan
        Schema::create('laporan_peninggalan', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->unsignedBigInteger('user_id');
            $table->string('nama_peninggalan', 255);
            $table->text('alamat');
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->text('deskripsi');
            $table->timestamp('created_at')->useCurrent();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['lat', 'lng']);
            $table->index(['user_id', 'created_at']);
        });

        // Tabel laporan_gambar
        Schema::create('laporan_gambar', function (Blueprint $table) {
            $table->id('gambar_id');
            $table->unsignedBigInteger('laporan_id');
            $table->string('path_gambar', 255);

            // Foreign key constraint
            $table->foreign('laporan_id')->references('laporan_id')->on('laporan_peninggalan')->onDelete('cascade');
            
            // Index
            $table->index('laporan_id');
        });

        // Tabel laporan_komentar
        Schema::create('laporan_komentar', function (Blueprint $table) {
            $table->id('komentar_id');
            $table->unsignedBigInteger('laporan_id');
            $table->unsignedBigInteger('user_id');
            $table->text('komentar');
            $table->timestamp('created_at')->useCurrent();

            // Foreign key constraints
            $table->foreign('laporan_id')->references('laporan_id')->on('laporan_peninggalan')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Index
            $table->index(['laporan_id', 'created_at']);
            $table->index('user_id');
        });

        // Tabel laporan_suka
        Schema::create('laporan_suka', function (Blueprint $table) {
            $table->id('suka_id');
            $table->unsignedBigInteger('laporan_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('created_at')->useCurrent();

            // Foreign key constraints
            $table->foreign('laporan_id')->references('laporan_id')->on('laporan_peninggalan')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Unique constraint untuk mencegah double like
            $table->unique(['laporan_id', 'user_id']);
            
            // Index
            $table->index(['laporan_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_suka');
        Schema::dropIfExists('laporan_komentar');
        Schema::dropIfExists('laporan_gambar');
        Schema::dropIfExists('laporan_peninggalan');
        Schema::dropIfExists('kritik_saran');
    }
};
