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
    Schema::table('materi', function (Blueprint $table) {
      $table->unsignedBigInteger('era_id')->nullable()->after('materi_id');
      $table->integer('bab')->nullable()->after('era_id');

      $table->foreign('era_id')->references('era_id')->on('era')->onDelete('cascade');
      $table->index(['era_id', 'bab']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('materi', function (Blueprint $table) {
      $table->dropForeign(['era_id']);
      $table->dropIndex(['era_id', 'bab']);
      $table->dropColumn(['era_id', 'bab']);
    });
  }
};
