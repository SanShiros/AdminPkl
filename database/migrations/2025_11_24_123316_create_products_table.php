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
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('nama_produk');
            $table->string('sku')->unique();
            $table->unsignedBigInteger('id_kategori');
            $table->integer('stok')->unsigned()->default(0);
            $table->decimal('harga_beli_terakhir', 15, 2)->nullable();
            $table->decimal('harga_jual', 15, 2);
            $table->unsignedBigInteger('id_supplier_default')->nullable();
            $table->timestamps();
            $table->foreign('id_kategori')
                ->references('id')        // PK categories = id
                ->on('categories')
                ->onDelete('cascade');

            $table->foreign('id_supplier_default')
                ->references('id')        // PK suppliers = id
                ->on('suppliers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
