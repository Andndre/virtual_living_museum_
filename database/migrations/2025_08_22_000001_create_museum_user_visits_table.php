<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('museum_user_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('museum_id');
            $table->timestamp('visited_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'museum_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('museum_id')->references('museum_id')->on('virtual_museum')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('museum_user_visits');
    }
};
