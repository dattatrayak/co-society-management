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
        Schema::create('society_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Menu item name
            $table->string('url')->nullable(); // Menu item URL
            $table->string('icon')->nullable(); // For icons
            $table->string('page_heading')->nullable();
            $table->string('sub_heading')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // Parent ID for submenus
            $table->integer('order')->default(0); // Menu item order
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('society_menus');
    }
};
