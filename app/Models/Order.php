<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id_order';
    protected $guarded = [];

    // 1. Relasi ke User pembeli (Yang bikin error kalau hilang)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // 2. Relasi ke banyak Detail Order (Pecahan per Toko)
    public function details()
    {
        return $this->hasMany(DetailOrder::class, 'id_order', 'id_order');
    }
}