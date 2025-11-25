<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $table = 'purchase_order_items';
    protected $primaryKey = 'id_po_item';

    protected $fillable = ['id_po','id_produk','qty','harga_beli','subtotal'];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'id_po', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id_produk');
    }
}
