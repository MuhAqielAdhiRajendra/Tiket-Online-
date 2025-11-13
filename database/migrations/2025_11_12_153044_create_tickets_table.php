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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            // 1. RELASI KE INDUK (TRANSAKSI)
            // Menghubungkan tiket fisik ini ke nota pembeliannya.
            // Jika transaksi dihapus, tiket ini ikut hilang (cascade).
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');

            // 2. FITUR ANTI TUKAR / ANTI CALO
            // Kode Unik (Cikal bakal QR Code). Wajib unique!
            $table->string('ticket_code')->unique(); 
            
            // Nama & KTP orang yang MEMEGANG tiket (bisa beda dengan pembeli)
            $table->string('visitor_name');          
            $table->string('visitor_identity');      

            // 3. STATUS PINTU MASUK
            // false (0) = Belum masuk venue.
            // true (1)  = Sudah discan petugas.
            $table->boolean('is_checked_in')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};