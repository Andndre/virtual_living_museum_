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
        Schema::create('akses_situs_user', function (Blueprint $table) {
            $table->id('akses_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('situs_id');
            $table->enum('status', ['terbuka', 'terkunci'])->default('terkunci');
            $table->timestamp('unlocked_at')->nullable();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('situs_id')->references('situs_id')->on('situs_peninggalan')->onDelete('cascade');
            
            // Unique constraint untuk kombinasi user_id dan situs_id
            $table->unique(['user_id', 'situs_id']);
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index('unlocked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akses_situs_user');
    }
};
