<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blotters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barangay_id'); // para ma-filter per captain
            $table->date('date');
            $table->time('time');
            $table->text('description');
            $table->timestamps();

            $table->foreign('barangay_id')
                  ->references('id')
                  ->on('barangays')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blotters');
    }
};
