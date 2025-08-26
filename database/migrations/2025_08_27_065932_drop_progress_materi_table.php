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
//      drop progress_materi table
        Schema::dropIfExists('progress_materi');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is intentionally left empty because we don't want to recreate the table structure here.
        // If you need to recreate this table, create a new migration file for that purpose.
    }
};
