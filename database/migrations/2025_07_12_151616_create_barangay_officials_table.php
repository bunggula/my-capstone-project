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
        Schema::create('barangay_officials', function (Blueprint $table) {
            $table->id();
    
            $table->unsignedBigInteger('barangay_id');
    
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
    
            $table->enum('gender', ['male', 'female']);
            $table->date('birthday');
    
            $table->string('civil_status')->nullable();
    
            // Checkbox categories
            $table->boolean('is_pwd')->default(false);
            $table->boolean('is_senior')->default(false);
            $table->boolean('is_ip')->default(false); // indigenous people
            $table->boolean('is_solo_parent')->default(false);
    
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
    
            $table->string('position');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
    
            $table->timestamps();
    
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('cascade');
        });
    }
    
    
    public function down()
    {
        Schema::dropIfExists('barangay_officials');
    }
    
};
