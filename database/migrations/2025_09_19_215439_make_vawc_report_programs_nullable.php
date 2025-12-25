<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vawc_report_programs', function (Blueprint $table) {
            $table->string('ppa_type')->nullable()->change();
            $table->string('title')->nullable()->change();
            $table->string('remarks')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('vawc_report_programs', function (Blueprint $table) {
            $table->string('ppa_type')->nullable(false)->change();
            $table->string('title')->nullable(false)->change();
            $table->string('remarks')->nullable(false)->change();
        });
    }
};

