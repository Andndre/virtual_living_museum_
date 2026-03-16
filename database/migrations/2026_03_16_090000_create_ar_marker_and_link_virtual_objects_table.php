<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('ar_marker', function (Blueprint $table) {
      $table->id('marker_id');
      $table->unsignedBigInteger('situs_id')->nullable();
      $table->unsignedBigInteger('museum_id')->nullable();
      $table->string('nama', 255)->nullable();
      $table->string('path_gambar_marker', 255)->nullable()->comment('path gambar marker untuk AR');
      $table->string('path_patt', 255)->nullable()->comment('path file pattern training AR marker');
      $table->timestamp('created_at')->useCurrent();

      $table->foreign('situs_id')->references('situs_id')->on('situs_peninggalan')->onDelete('cascade');
      $table->foreign('museum_id')->references('museum_id')->on('virtual_museum')->onDelete('cascade');
      $table->index('situs_id');
      $table->index('museum_id');
    });

    Schema::table('virtual_museum_object', function (Blueprint $table) {
      $table->unsignedBigInteger('marker_id')->nullable()->after('museum_id');
      $table->foreign('marker_id')->references('marker_id')->on('ar_marker')->onDelete('cascade');
      $table->index('marker_id');
    });

    $legacyObjects = DB::table('virtual_museum_object')
      ->select('object_id', 'situs_id', 'museum_id', 'nama', 'path_patt', 'path_gambar_marker')
      ->where(function ($query) {
        $query->whereNotNull('path_patt')
          ->orWhereNotNull('path_gambar_marker');
      })
      ->orderBy('object_id')
      ->get();

    $markerMap = [];

    foreach ($legacyObjects as $legacyObject) {
      $markerKey = implode('|', [
        $legacyObject->museum_id ?? 'null',
        $legacyObject->situs_id ?? 'null',
        $legacyObject->path_patt ?? 'null',
        $legacyObject->path_gambar_marker ?? 'null',
      ]);

      if (!isset($markerMap[$markerKey])) {
        $markerName = $legacyObject->nama ? ('Marker ' . $legacyObject->nama) : 'Marker AR';

        $markerMap[$markerKey] = DB::table('ar_marker')->insertGetId([
          'situs_id' => $legacyObject->situs_id,
          'museum_id' => $legacyObject->museum_id,
          'nama' => $markerName,
          'path_gambar_marker' => $legacyObject->path_gambar_marker,
          'path_patt' => $legacyObject->path_patt,
          'created_at' => now(),
        ]);
      }

      DB::table('virtual_museum_object')
        ->where('object_id', $legacyObject->object_id)
        ->update(['marker_id' => $markerMap[$markerKey]]);
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('virtual_museum_object', function (Blueprint $table) {
      $table->dropForeign(['marker_id']);
      $table->dropIndex(['marker_id']);
      $table->dropColumn('marker_id');
    });

    Schema::dropIfExists('ar_marker');
  }
};
