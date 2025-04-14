<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggans')->cascadeOnDelete();
            $table->date('tanggal');
            $table->decimal('total_harga', 12, 2)->default(0.00);
            $table->string('status_pembayaran');
            $table->string('metode_pembayaran');
            $table->string('kode_invoice')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};