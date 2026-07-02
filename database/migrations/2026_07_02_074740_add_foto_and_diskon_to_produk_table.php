<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->string('foto_produk')->nullable()->after('nama_produk'); // Simpan nama file foto
            $table->integer('diskon')->default(0)->after('harga'); // Simpan persentase diskon (0 - 100%)
        });
    }

    public function down()
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['foto_produk', 'diskon']);
        });
    }
};