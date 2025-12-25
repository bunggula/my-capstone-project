<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vawc_report_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vawc_report_id')
                  ->constrained('vawc_reports')
                  ->onDelete('cascade');
        
            $table->string('nature_of_case')->nullable(); // optional
            $table->string('subcategory')->nullable();    // optional
        
            // Victims & cases
            $table->integer('num_victims')->nullable();
            $table->integer('num_cases')->nullable();
        
            // Referrals
            $table->integer('ref_cmswdo')->nullable();
            $table->integer('ref_pnp')->nullable();
            $table->integer('ref_court')->nullable();
            $table->integer('ref_hospital')->nullable();
            $table->integer('ref_others')->nullable();
        
            // BPO
            $table->integer('num_applied_bpo')->nullable();
            $table->integer('num_bpo_issued')->nullable();
        
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('vawc_report_cases');
    }
};
