<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    DB::table('users')->update([
      'level_sekarang' => 0,
      'progress_level_sekarang' => 0,
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Intentionally left blank because previous progress values cannot be restored reliably.
  }
};
