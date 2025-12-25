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
            $table->string('type')->default('online'); // default to online
        });
    }
    
    public function down()
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
    
};
