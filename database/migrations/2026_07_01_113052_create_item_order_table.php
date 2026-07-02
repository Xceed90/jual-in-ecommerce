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
    Schema::create('item_order', function (Blueprint $table) {
        $table->id('id_item_order'); // Primary Key
        
        // Hubungkan ke Detail Order dan Produknya
        $table->foreignId('id_detail_order')->constrained('detail_order', 'id_detail_order')->onDelete('cascade');
        $table->foreignId('id_produk')->constrained('produk', 'id_produk')->onDelete('cascade');
        
        $table->integer('jumlah_beli');
        $table->integer('harga_saat_beli'); // Mengunci harga saat transaksi terjadi
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_order');
    }
};
