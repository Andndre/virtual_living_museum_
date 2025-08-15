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
        // Add timestamps to pretest table
        Schema::table('pretest', function (Blueprint $table) {
            $table->timestamps();
        });

        // Add timestamps to posttest table
        Schema::table('posttest', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove timestamps from pretest table
        Schema::table('pretest', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        // Remove timestamps from posttest table
        Schema::table('posttest', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
