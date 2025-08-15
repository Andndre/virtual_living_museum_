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
            $table->dropColumn('path_gambar');
            $table->string('path_gambar_marker', 255)->nullable()->comment('path gambar marker untuk AR')->after('path_obj');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('virtual_museum_object', function (Blueprint $table) {
            $table->dropColumn('path_gambar_marker');
            $table->string('path_gambar', 255)->comment('path gambar untuk marker')->after('path_obj');
        });
    }
};
