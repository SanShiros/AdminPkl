<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
      protected $fillable = ['nama', 'keterangan'];

       public function products()
    {
        // FK di products = id_kategori, PK local = id
        return $this->hasMany(Product::class, 'id_kategori', 'id');
    }
      
}
