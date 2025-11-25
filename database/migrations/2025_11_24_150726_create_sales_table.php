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
        Schema::create('sales', function (Blueprint $table) {
            $table->id('id_sale'); // PK

            $table->string('kode_nota');
            $table->dateTime('tanggal');

            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('bayar', 15, 2)->default(0);
            $table->decimal('kembalian', 15, 2)->default(0);

            $table->string('metode_bayar');

            // FK ke user
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('sales_products', function (Blueprint $table) {
            $table->foreignId('id_sale')->constrained('sales','id_sale')->onDelete('cascade');
            $table->foreignId('id_product')->constrained('products','id_produk')->onDelete('cascade');
            $table->decimal('subtotal');
            $table->decimal('harga_jual');
            $table->integer('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_products');
        Schema::dropIfExists('sales');
    }
};
