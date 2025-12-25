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
        Schema::create('vawc_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')->constrained()->onDelete('cascade'); 
            
            // Covered period
            $table->date('period_start');
            $table->date('period_end');

            // Section I - Summary
            $table->integer('total_clients_served')->default(0);
            $table->integer('total_cases_received')->default(0);
            $table->integer('total_cases_acted')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vawc_reports');
    }
};
