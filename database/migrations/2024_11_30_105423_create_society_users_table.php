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
        Schema::create('society_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('society_id')->nullable();
            $table->string('name'); // Name of the society
            $table->text('description')->nullable(); // Description
            $table->string('address'); // Address
            $table->string('email')->unique(); // Email
            $table->timestamp('email_verified_at')->nullable(); // Email verification timestamp
            $table->string('password'); // Password
            $table->integer('failed_attempts')->default(0); // Failed login attempts
            $table->timestamp('blocked_until')->nullable(); // Blocked until timestamp
            $table->string('title')->nullable(); // Optional title
            $table->integer('reg_no')->default(0);
            $table->integer('reg_year')->default(0);
            $table->integer('building_count')->default(0); // Building count
            $table->integer('lift_count')->default(0); // Lift count
            $table->integer('meter_count')->default(0); // Meter count
            $table->unsignedBigInteger('user_type_id')->nullable();
            $table->string('mobile_no', 15);
            $table->string('logo')->nullable();
            $table->string('society_image')->nullable();
            $table->boolean('status')->default(true);

            $table->timestamps();
            $table->foreign('user_type_id')->references('id')->on('society_user_types')->onDelete('cascade');
            $table->foreign('society_id')->references('id')->on('society_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('society_users');
    }
};
