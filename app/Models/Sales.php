<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';
   protected $primaryKey = 'id_sale';

    protected $fillable = [
        'kode_nota',
        'tanggal',
        'total',
        'bayar',
        'kembalian',
        'metode_bayar',
        'id_user'
    ];

    protected $casts = [
        'tanggal' => 'datetime:Y-m-d H:i'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

  public function products()
{
    return $this->belongsToMany(Product::class, 'sales_products', 'id_sale', 'id_product')
        ->withPivot(['qty', 'subtotal', 'harga_jual'])
        ->withTimestamps();
}

}
