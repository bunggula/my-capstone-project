<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vawc_report_cases', function (Blueprint $table) {
            $table->string('nature_of_case')->nullable()->change();
            $table->string('subcategory')->nullable()->change();
            
            $table->integer('num_victims')->nullable()->change();
            $table->integer('num_cases')->nullable()->change();

            $table->integer('ref_cmswdo')->nullable()->change();
            $table->integer('ref_pnp')->nullable()->change();
            $table->integer('ref_court')->nullable()->change();
            $table->integer('ref_hospital')->nullable()->change();
            $table->integer('ref_others')->nullable()->change();

            $table->integer('num_applied_bpo')->nullable()->change();
            $table->integer('num_bpo_issued')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('vawc_report_cases', function (Blueprint $table) {
            $table->string('nature_of_case')->nullable(false)->change();
            $table->string('subcategory')->nullable(false)->change();
            
            $table->integer('num_victims')->nullable(false)->change();
            $table->integer('num_cases')->nullable(false)->change();

            $table->integer('ref_cmswdo')->nullable(false)->change();
            $table->integer('ref_pnp')->nullable(false)->change();
            $table->integer('ref_court')->nullable(false)->change();
            $table->integer('ref_hospital')->nullable(false)->change();
            $table->integer('ref_others')->nullable(false)->change();

            $table->integer('num_applied_bpo')->nullable(false)->change();
            $table->integer('num_bpo_issued')->nullable(false)->change();
        });
    }
};