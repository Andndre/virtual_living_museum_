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
        Schema::table('virtual_museum_object', function (Blueprint $table) {
            $table->unsignedBigInteger('museum_id')->nullable()->after('situs_id');
            $table->foreign('museum_id')->references('museum_id')->on('virtual_museum')->onDelete('cascade');
            $table->index('museum_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('virtual_museum_object', function (Blueprint $table) {
            $table->dropForeign(['museum_id']);
            $table->dropIndex(['museum_id']);
            $table->dropColumn('museum_id');
        });
    }
};
