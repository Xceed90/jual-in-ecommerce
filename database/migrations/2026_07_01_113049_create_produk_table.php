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
    Schema::create('produk', function (Blueprint $table) {
        $table->id('id_produk'); // Primary Key
        
        // Foreign Key ke Vendor & Kategori
        $table->foreignId('id_vendor')->constrained('vendors', 'id_vendor')->onDelete('cascade');
        $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori')->onDelete('cascade');
        
        $table->string('nama_produk');
        $table->integer('harga');
        $table->integer('stok');
        $table->text('deskripsi')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
