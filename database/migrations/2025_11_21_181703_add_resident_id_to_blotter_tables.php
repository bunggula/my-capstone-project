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
        // Add resident_id sa blotter_complainants
        Schema::table('blotter_complainants', function (Blueprint $table) {
            $table->unsignedBigInteger('resident_id')->nullable()->after('id');
            $table->foreign('resident_id')->references('id')->on('residents')->onDelete('set null');
        });

        // Add resident_id sa blotter_respondents
        Schema::table('blotter_respondents', function (Blueprint $table) {
            $table->unsignedBigInteger('resident_id')->nullable()->after('id');
            $table->foreign('resident_id')->references('id')->on('residents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blotter_complainants', function (Blueprint $table) {
            $table->dropForeign(['resident_id']);
            $table->dropColumn('resident_id');
        });

        Schema::table('blotter_respondents', function (Blueprint $table) {
            $table->dropForeign(['resident_id']);
            $table->dropColumn('resident_id');
        });
    }
};
