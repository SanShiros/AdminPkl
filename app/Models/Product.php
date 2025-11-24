<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   protected $table = 'products';
    protected $primaryKey = 'id_produk';
    public $timestamps = true;

    protected $fillable = [
        'nama_produk',
        'sku',
        'id_kategori',
        'stok',
        'harga_beli_terakhir',
        'harga_jual',
        'id_supplier_default',
    ];

    public function category()
    {
        // FK di products: id_kategori, PK di categories: id
        return $this->belongsTo(Category::class, 'id_kategori', 'id');
    }

    public function supplier()
    {
        // FK di products: id_supplier_default, PK di suppliers: id
        return $this->belongsTo(Supplier::class, 'id_supplier_default', 'id');
    }
}
