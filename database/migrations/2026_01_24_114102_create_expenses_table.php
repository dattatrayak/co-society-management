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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('society_id')->nullable();
            $table->unsignedBigInteger('cash_category_id');
            $table->unsignedBigInteger('cash_transactions_id')->nullable();
            $table->unsignedBigInteger('society_members_id')->nullable();
            $table->tinyInteger('frequency')
                ->nullable()
                ->default(null);
            $table->date('expense_date');

            $table->decimal('amount', 10, 2);
            $table->enum('payment_mode', [
                'cash',
                'bank',
                'upi',
                'cheque'
            ])->default('cash');
            $table->string('check_no')->nullable();
            $table->string('attachment')->nullable();
            $table->string('reference_no')->nullable();
            $table->unsignedBigInteger('parent_expense_id')->nullable();

            $table->enum('status', ['paid', 'pending'])->default('paid');
            $table->enum('paid_to', ['member', 'other'])->default('member');
            $table->date('paid_on')->nullable();
            $table->string('paid_to_name')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('society_id')->references('id')->on('society_users')->onDelete('cascade');
            $table->foreign('society_members_id')->references('id')->on('society_members')->onDelete('cascade');
            $table->foreign('cash_category_id')
                ->references('id')
                ->on('cash_categories');
            $table->foreign('cash_transactions_id')
                ->references('id')
                ->on('cash_transactions')
                ->onDelete('set null');
            $table->foreign('parent_expense_id')
                ->references('id')
                ->on('expenses')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
