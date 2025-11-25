<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id('id_po_item');

            $table->unsignedBigInteger('id_po');
            $table->unsignedBigInteger('id_produk');
            $table->integer('qty');
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
            $table->foreign('id_po')
                ->references('id')
                ->on('purchase_orders')
                ->onDelete('cascade');

            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
