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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // Primary Key Transaksi

            // 1. RELASI (Menghubungkan Pembeli & Acara)
            // User ID (Siapa yang beli)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Event ID (Acara apa yang dibeli)
            // WAJIB: Tabel 'events' harus sudah ada duluan!
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');

            // 2. DATA PEMBELIAN
            $table->integer('quantity');     // Jumlah tiket (misal: 2)
            $table->integer('total_price');  // Total bayar (misal: 300000)
            
            // 3. STATUS & BUKTI
            // Status default 'pending' (belum bayar)
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            
            // Bukti transfer (Boleh kosong dulu / nullable)
            $table->string('payment_proof')->nullable();

            $table->timestamps(); // Mencatat kapan transaksi dibuat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};