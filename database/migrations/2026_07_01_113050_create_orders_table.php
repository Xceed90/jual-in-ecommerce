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
    Schema::create('orders', function (Blueprint $table) {
        $table->id('id_order'); // Primary Key
        
        // Hubungkan ke tabel users bawaan Laravel (default Primary Key Laravel adalah 'id')
        $table->foreignId('id_user')->constrained('users', 'id')->onDelete('cascade');
        
        $table->timestamp('tanggal_order')->useCurrent();
        $table->integer('total_harga_produk');
        $table->integer('total_ongkir');
        $table->integer('grand_total');
        $table->text('alamat_pengiriman');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
