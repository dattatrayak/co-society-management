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
        Schema::create('user_type_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->foreignId('user_type_id')->constrained('user_types')->onDelete('cascade');
            $table->boolean('view')->default(false);
            $table->boolean('add')->default(false);
            $table->boolean('delete')->default(false);
            $table->boolean('view_own')->default(false);
            $table->boolean('delete_own')->default(false);
            $table->boolean('delete_other')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_type_permissions');
    }
};
