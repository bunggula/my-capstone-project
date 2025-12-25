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
        Schema::create('barangay_document_purposes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_document_id')->constrained()->onDelete('cascade');
            $table->string('purpose'); // e.g. Employment, Business, Scholarship
            $table->decimal('price', 8, 2); // up to 999,999.99
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_document_purposes');
    }
};
