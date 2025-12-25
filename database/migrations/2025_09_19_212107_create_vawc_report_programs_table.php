<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vawc_report_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vawc_report_id')
                  ->constrained('vawc_reports')
                  ->onDelete('cascade');
        
            $table->string('ppa_type')->nullable();  // optional
            $table->string('title')->nullable();     // optional
            $table->string('remarks')->nullable();   // optional
        
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('vawc_report_programs');
    }
};
