<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up()
    {
        // 0. SAPU BERSIH SISA-SISA ERROR SEBELUMNYA
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_catat_log_produk ON produk;');
        Schema::dropIfExists('log_aktivitas');

        // 1. Buat tabel log_aktivitas baru yang bersih
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id('id_log');
            $table->integer('id_produk');
            $table->string('aksi', 50); 
            $table->text('keterangan');
            $table->timestamp('created_at')->useCurrent();
        });

        // 2. Buat Function dan Trigger PostgreSQL
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fungsi_log_produk()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Jika ada aktivitas UPDATE (Ubah Harga)
                IF TG_OP = 'UPDATE' THEN
                    IF OLD.harga <> NEW.harga THEN
                        INSERT INTO log_aktivitas (id_produk, aksi, keterangan)
                        VALUES (OLD.id_produk, 'UPDATE HARGA', 'Harga berubah dari Rp' || OLD.harga || ' menjadi Rp' || NEW.harga);
                    END IF;
                    RETURN NEW;
                    
                -- Jika ada aktivitas DELETE (Hapus Produk)
                ELSIF TG_OP = 'DELETE' THEN
                    INSERT INTO log_aktivitas (id_produk, aksi, keterangan)
                    VALUES (OLD.id_produk, 'HAPUS PRODUK', 'Produk ' || OLD.nama_produk || ' dihapus oleh vendor');
                    RETURN OLD;
                END IF;
                
                RETURN NULL;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trigger_catat_log_produk
            AFTER UPDATE OR DELETE ON produk
            FOR EACH ROW
            EXECUTE FUNCTION fungsi_log_produk();
        ");
    }

    public function down()
    {
        DB::unprepared('
            DROP TRIGGER IF EXISTS trigger_catat_log_produk ON produk;
            DROP FUNCTION IF EXISTS fungsi_log_produk();
        ');
        Schema::dropIfExists('log_aktivitas');
    }
};