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
        Schema::create('society_flat_type_maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('society_id')->nullable();
            $table->unsignedBigInteger('society_flat_type_id');

            $table->decimal('maintenance_amount', 10, 2);

            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->unique(['society_id', 'society_flat_type_id'], 'unique_society_flat_maintainance');
            $table->foreign('society_id')->references('id')->on('society_users')->onDelete('cascade');
            $table->foreign('society_flat_type_id')
                ->references('id')
                ->on('society_flat_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('society_flat_type_maintenances');
    }
};
