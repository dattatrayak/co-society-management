<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('flats', function (Blueprint $table) {
            // Add the column
            $table->unsignedBigInteger('society_member_id')->nullable();

            // Add the foreign key constraint
            $table->foreign('society_member_id')->references('id')->on('society_members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
