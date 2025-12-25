<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();

            // Connect to residents table
            $table->foreignId('user_id')
                  ->constrained('residents')
                  ->onDelete('cascade');

            // Connect to barangays table
            $table->foreignId('barangay_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('title');
            $table->text('content');
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
