<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            $table->string('document_type');
            $table->string('purpose')->nullable();
            $table->date('pickup_date')->nullable();
            $table->foreignId('barangay_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->decimal('price', 8, 2)->nullable();
            $table->string('ctc_number')->nullable();
            $table->string('receipt_number')->nullable();
            $table->integer('years_of_residency')->nullable();
            $table->integer('months_of_residency')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
