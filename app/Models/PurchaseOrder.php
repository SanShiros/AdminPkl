<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
      protected $table = 'purchase_orders';
    // primaryKey default = 'id', jadi nggak perlu di-set

    protected $fillable = [
        'kode_po',
        'id_supplier',
        'tanggal',
        'total',
        'status',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id');
    }
}
