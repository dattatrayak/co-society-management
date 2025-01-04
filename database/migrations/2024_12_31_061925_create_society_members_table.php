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
        Schema::create('society_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('society_id');
            $table->unsignedBigInteger('building_id');
            $table->string('name');
            $table->date('date_of_birth')->nullable();
            $table->string('permanent_address');
            $table->string('pan')->nullable();
            $table->string('uid')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('mobile');
            $table->string('gender')->nullable();
            $table->unsignedBigInteger('flat_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('society_id')->references('id')->on('society_users')->onDelete('cascade');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            $table->foreign('flat_id')->references('id')->on('flats')->onDelete('cascade'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('society_members');
    }
};
