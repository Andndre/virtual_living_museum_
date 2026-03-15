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
    Schema::create('era', function (Blueprint $table) {
      $table->id('era_id');
      $table->string('kode', 5);
      $table->string('nama', 255);
      $table->string('rentang_waktu', 255)->nullable();
      $table->integer('urutan')->default(1);
      $table->timestamps();

      $table->unique('kode');
      $table->index('urutan');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('era');
  }
};
