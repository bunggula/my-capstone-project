<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('blotters', function (Blueprint $table) {
            $table->string('signed_file')->nullable(); // path ng uploaded file
            $table->boolean('is_signed')->default(false); // indicator kung napermahan na
            $table->timestamp('signed_at')->nullable(); // timestamp kung kelan na-upload
        });
    }

    public function down()
    {
        Schema::table('blotters', function (Blueprint $table) {
            $table->dropColumn(['signed_file', 'is_signed', 'signed_at']);
        });
    }
};
