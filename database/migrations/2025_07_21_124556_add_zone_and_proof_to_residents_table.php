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
        Schema::table('residents', function (Blueprint $table) {
            $table->string('zone')->nullable()->after('barangay_id');
            $table->string('proof_of_residency')->nullable()->after('zone');
        });
    }
    
    public function down()
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['zone', 'proof_of_residency']);
        });
    }
    
};
