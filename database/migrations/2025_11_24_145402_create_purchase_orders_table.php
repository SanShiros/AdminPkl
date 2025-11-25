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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();             // PK
            $table->string('kode_po')->unique();
            $table->unsignedBigInteger('id_supplier'); // FK ke suppliers.id
            $table->date('tanggal');
            $table->decimal('total', 15, 2)->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->foreign('id_supplier')
                ->references('id')
                ->on('suppliers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
