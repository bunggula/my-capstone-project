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
    if (!Schema::hasTable('residents')) {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();

            // Personal Info
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('gender');
            $table->date('birthdate');
            $table->integer('age');
            $table->string('civil_status');
            $table->string('category')->nullable();

            // Contact & Address
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('barangay_id');
            $table->text('address');

            $table->timestamps();

            // Foreign key
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('cascade');
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
    
};
