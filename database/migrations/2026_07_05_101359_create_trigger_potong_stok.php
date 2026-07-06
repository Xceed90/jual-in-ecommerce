<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Mengeksekusi SQL mentah untuk PostgreSQL
        DB::unprepared('
            -- 1. Buat Function pendukungnya
            CREATE OR REPLACE FUNCTION fungsi_potong_stok()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Kurangi stok di tabel produk berdasarkan jumlah yang dibeli di item_order
                UPDATE produk
                SET stok = stok - NEW.jumlah
                WHERE id_produk = NEW.produk_id;
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            -- 2. Pasang Trigger ke tabel item_order
            CREATE TRIGGER trigger_otomatis_potong_stok
            AFTER INSERT ON item_order
            FOR EACH ROW
            EXECUTE FUNCTION fungsi_potong_stok();
        ');
    }

    public function down()
    {
        // Untuk menghapus trigger jika di-rollback
        DB::unprepared('
            DROP TRIGGER IF EXISTS trigger_otomatis_potong_stok ON item_order;
            DROP FUNCTION IF EXISTS fungsi_potong_stok();
        ');
    }
};