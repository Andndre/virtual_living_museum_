<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('pretest', function (Blueprint $table) {
      $table->string('pilihan_e')->nullable()->after('pilihan_d');
    });

    Schema::table('posttest', function (Blueprint $table) {
      $table->string('pilihan_e')->nullable()->after('pilihan_d');
    });
  }

  public function down(): void
  {
    Schema::table('pretest', function (Blueprint $table) {
      $table->dropColumn('pilihan_e');
    });

    Schema::table('posttest', function (Blueprint $table) {
      $table->dropColumn('pilihan_e');
    });
  }
};
