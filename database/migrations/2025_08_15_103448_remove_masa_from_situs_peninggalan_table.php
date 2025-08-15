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
        Schema::table('situs_peninggalan', function (Blueprint $table) {
            $table->dropColumn('masa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('situs_peninggalan', function (Blueprint $table) {
            $table->string('masa')->after('lng')->nullable();
        });
    }
};
