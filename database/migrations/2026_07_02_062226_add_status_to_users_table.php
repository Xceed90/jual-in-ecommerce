<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Status default 'approved' (agar Budi & Admin langsung aktif)
            // Tapi nanti saat vendor baru daftar, statusnya diset 'pending'
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};