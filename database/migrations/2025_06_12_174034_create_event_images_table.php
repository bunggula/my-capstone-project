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
        Schema::create('event_images', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('event_id') // Foreign key to events table
                  ->constrained()        // Assumes there's an 'events' table
                  ->onDelete('cascade'); // Delete images when event is deleted
                  $table->string('path')->nullable();
                  // Path to the stored image file
            $table->timestamps(); // created_at and updated_at
        });
    }
    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_images');
    }
};
