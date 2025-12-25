<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('brco_report_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brco_report_id')->constrained()->onDelete('cascade');
            $table->string('path'); // location ng photo sa storage
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('brco_report_photos');
    }
};

