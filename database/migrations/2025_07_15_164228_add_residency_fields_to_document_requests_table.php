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
    Schema::table('document_requests', function (Blueprint $table) {
        $table->integer('years_of_residency')->nullable();
        $table->integer('months_of_residency')->nullable();
    });
}

public function down()
{
    Schema::table('document_requests', function (Blueprint $table) {
        $table->dropColumn('years_of_residency');
        $table->dropColumn('months_of_residency');
    });
}

};
