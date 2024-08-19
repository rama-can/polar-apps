<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usage_logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->date('date');
            $table->string('name');
            $table->enum('status', ['MAHASISWA', 'PLP', 'DOSEN', 'PENELITI', 'LAINNYA']);
            $table->time('total_duration');
            $table->string('temperature');
            $table->string('rh');
            $table->text('note')->nullable();
            // $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usage_logbooks');
    }
};
