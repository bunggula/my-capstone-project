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
        $table->enum('voter', ['Yes','No'])->default('No');
        $table->string('proof_type', 50)->nullable();
    });
}

public function down()
{
    Schema::table('residents', function (Blueprint $table) {
        $table->dropColumn(['voter', 'proof_type']);
    });
}

};
