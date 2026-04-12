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
        Schema::create('model_annotations', function (Blueprint $table) {
            $table->id('annotation_id');
            $table->foreignId('museum_id')->constrained('virtual_museum', 'museum_id')->onDelete('cascade');
            $table->string('label', 100);
            $table->decimal('position_x', 10, 6)->default(0);
            $table->decimal('position_y', 10, 6)->default(0);
            $table->decimal('position_z', 10, 6)->default(0);
            $table->boolean('is_visible')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index('museum_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_annotations');
    }
};
