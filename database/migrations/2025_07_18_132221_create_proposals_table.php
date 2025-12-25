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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('captain_id')->constrained('users')->onDelete('cascade');
            $table->string('barangay');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();  // store file path
            $table->string('file')->nullable();   // store file path
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
