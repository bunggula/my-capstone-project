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
        Schema::table('residents', function (Blueprint $table) {
            $table->string('password')->nullable(); // nullable para di masira existing rows
            $table->string('status')->default('pending'); // pending, approved, rejected
        });
    }
    
    public function down()
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['password', 'status']);
        });
    }
    
};
