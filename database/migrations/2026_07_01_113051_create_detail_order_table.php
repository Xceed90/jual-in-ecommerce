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
    Schema::create('detail_order', function (Blueprint $table) {
        $table->id('id_detail_order'); // Primary Key
        
        // Hubungkan ke Order Induk dan Vendor bersangkutan
        $table->foreignId('id_order')->constrained('orders', 'id_order')->onDelete('cascade');
        $table->foreignId('id_vendor')->constrained('vendors', 'id_vendor')->onDelete('cascade');
        
        $table->string('kurir_pengiriman');
        $table->integer('ongkir_per_vendor');
        $table->enum('status_order', ['menunggu_pembayaran', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('menunggu_pembayaran');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_order');
    }
};
