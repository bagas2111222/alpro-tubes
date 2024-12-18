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
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id'); // Tambahkan definisi kolom barang_id
            $table->unsignedBigInteger('penjualan_id'); // Tambahkan definisi kolom barang_id
            $table->foreign('penjualan_id')->references('id')->on('penjualans')->onDelete('cascade');;
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');;
            $table->integer('harga');
            $table->integer('jumlah_barang');
            $table->integer('total_harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details');
    }
};
