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
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('society_id')->nullable();
            $table->unsignedBigInteger('building_id')->nullable();
            $table->foreignId('flat_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->year('year');
            $table->tinyInteger('month');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_mode', ['online', 'cash', 'cheque', 'dd'])->default('online');
            $table->string('check_no')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->date('paid_on')->nullable();
            $table->string('note')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('society_id')->references('id')->on('society_users')->onDelete('cascade');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            $table->unique(['flat_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
    }
};
