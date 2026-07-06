<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Sapu bersih view lama jika ada
        DB::unprepared('DROP VIEW IF EXISTS laporan_pendapatan_vendor;');

        // Membuat SQL View dengan nama kolom yang SESUAI SCREENSHOT
        DB::unprepared("
            CREATE VIEW laporan_pendapatan_vendor AS
            SELECT 
                v.nama_toko,
                p.nama_produk,
                p.harga,
                SUM(io.jumlah_beli) AS total_barang_terjual,
                SUM(io.jumlah_beli * p.harga) AS total_pendapatan
            FROM vendors v
            JOIN produk p ON v.id_vendor = p.id_vendor
            JOIN item_order io ON p.id_produk = io.id_produk
            GROUP BY v.nama_toko, p.nama_produk, p.harga
            ORDER BY total_pendapatan DESC;
        ");
    }

    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS laporan_pendapatan_vendor;');
    }
};