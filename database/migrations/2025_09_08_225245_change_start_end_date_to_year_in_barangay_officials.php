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
        Schema::table('barangay_officials', function (Blueprint $table) {
            $table->integer('start_year')->nullable()->after('position');
            $table->integer('end_year')->nullable()->after('start_year');
    
            // Optional: tanggalin na yung old date columns kung di na kailangan
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangay_officials', function (Blueprint $table) {
            //
        });
    }
};
