<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brco_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barangay_id'); // para ma-filter by barangay
            $table->unsignedBigInteger('user_id');     // para sa gumawa ng report (secretary)
            $table->string('location');
            $table->string('length');
            $table->date('date');
            $table->string('action_taken');
            $table->text('remarks')->nullable();
            $table->boolean('conducted')->default(0); // yes/no
            $table->string('photo')->nullable();      // upload photo
            $table->timestamps();

            // Foreign keys (optional pero recommended)
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brco_reports');
    }
};
