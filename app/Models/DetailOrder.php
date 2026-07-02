<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    use HasFactory;

    protected $table = 'detail_order';
    protected $primaryKey = 'id_detail_order';
    protected $guarded = [];

    // 1. Relasi ke Order Induk
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    // 2. Relasi ke Vendor / Toko (Yang barusan hilang karena tertimpa)
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor', 'id_vendor');
    }

    // 3. Relasi ke banyak Item Produk yang dibeli di toko ini
    public function items()
    {
        return $this->hasMany(ItemOrder::class, 'id_detail_order', 'id_detail_order');
    }
}