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
        // Schema::table('maintenance_records', function (Blueprint $table) {
        //     $table->unsignedBigInteger('cash_transactions_id')
        //         ->nullable()
        //         ->after('id');

        //     // If you want foreign key
        //     $table->foreign('cash_transactions_id')
        //         ->references('id')
        //         ->on('cash_transactions')
        //         ->onDelete('set null');
        // });
    }

    public function down()
    {
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->dropForeign(['cash_transactions_id']);
            $table->dropColumn('cash_transactions_id');
        });
    }
};
