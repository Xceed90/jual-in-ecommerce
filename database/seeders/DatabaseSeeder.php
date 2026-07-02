<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Akun Admin (Pemilik Platform)
        User::create([
            'name' => 'Si Bos Admin',
            'email' => 'admin@jual.in',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Akun Vendor (Penjual)
        User::create([
            'name' => 'Juragan Vendor',
            'email' => 'vendor@jual.in',
            'password' => Hash::make('password123'),
            'role' => 'vendor',
        ]);

        // 3. Akun User (Pembeli / Budi)
        User::create([
            'name' => 'Budi Si Pembeli',
            'email' => 'user@jual.in',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
        // 4. Akun Vendor Baru (Masih Pending, Menunggu Persetujuan Admin)
        User::create([
            'name' => 'Toko Sepatu Baru',
            'email' => 'tokobaru@jual.in',
            'password' => Hash::make('password123'),
            'role' => 'vendor',
            'status' => 'pending', // Ini kuncinya!
        ]);


        // 1. Masukkan Data Kategori Tiruan
        \Illuminate\Support\Facades\DB::table('kategori')->insert([
            ['nama_kategori' => 'Elektronik & Gadget'],
            ['nama_kategori' => 'Pakaian & Fashion'],
            ['nama_kategori' => 'Kebutuhan Rumah Tangga'],
            ['nama_kategori' => 'Makanan & Minuman'],
        ]);

        // 2. Masukkan Data Vendor (Toko) Tiruan
        \Illuminate\Support\Facades\DB::table('vendors')->insert([
            ['nama_toko' => 'Toko Elektronik Makmur'],
            ['nama_toko' => 'Fashion OOTD Terkini'],
            ['nama_toko' => 'Toko Kelontong Berkah'],
        ]);
    }
}