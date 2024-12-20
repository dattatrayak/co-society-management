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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('society_id');
            $table->string(column: 'name')->default(0);
            $table->integer(column: 'flat_count')->default(0)->nullable();
            $table->integer(column: 'floor')->default(0)->nullable();
            $table->integer(column: 'flat_no_start')->default(0)->nullable();
            $table->integer(column: 'flat_per_floor')->default(0)->nullable();
            $table->integer('cctv')->default(0)->nullable();
            $table->integer('lift')->default(0)->nullable();
            $table->integer('water_tank')->default(0)->nullable();
            $table->string('building_img')->nullable();
            $table->string('floor_plan')->nullable();
            $table->timestamps();
            $table->unique(['society_id', 'name'], 'unique_society_building');
            $table->foreign('society_id')->references('id')->on('society_users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
