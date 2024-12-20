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
        Schema::create('flats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('society_id')->nullable();
            $table->unsignedBigInteger('building_id')->nullable();
            $table->string('flat_no');
            $table->unsignedBigInteger('society_flat_types_id')->nullable();
            $table->integer(column: 'floor_number')->nullable();
            $table->decimal('maintance_per_month', 10, 2)->nullable();
            $table->text('desc')->nullable();
            $table->timestamps();

            $table->foreign('society_id')->references('id')->on('society_users')->onDelete('cascade');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            $table->foreign('society_flat_types_id')->references('id')->on('society_flat_types')->onDelete('cascade');
            $table->unique(['society_id', 'building_id','flat_no'], 'unique_society_building_flat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flats');
    }
};
