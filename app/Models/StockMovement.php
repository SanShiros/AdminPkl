<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
   protected $table = 'stock_movements';
    protected $primaryKey = 'id_movement';

    protected $fillable = [
        'id_produk',
        'tanggal',
        'tipe',
        'qty',
        'sumber',
        'id_ref',
        'keterangan',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id_produk');
    }
}
