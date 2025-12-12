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
        Schema::create('member_flat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('flat_id');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('society_members')->onDelete('cascade');
            $table->foreign('flat_id')->references('id')->on('flats')->onDelete('cascade');

            $table->unique(['member_id', 'flat_id'], 'unique_member_flat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_flat');
    }
};
