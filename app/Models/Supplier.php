<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'nama_supplier',
        'alamat',
        'telepon',
        'email'
    ];

     public function products()
    {
        // FK di products = id_supplier_default, PK local = id
        return $this->hasMany(Product::class, 'id_supplier_default', 'id');
    }   
}
