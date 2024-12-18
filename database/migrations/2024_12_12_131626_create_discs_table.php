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
        Schema::create('discs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id'); // Tambahkan definisi kolom barang_id
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade'); // Foreign key dengan onDelete cascade
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->string('disc_rate');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discs');
    }
};
