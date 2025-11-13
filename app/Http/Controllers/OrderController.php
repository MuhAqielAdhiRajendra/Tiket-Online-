<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Biar tau siapa yang beli
use Illuminate\Support\Str;          // Biar bisa bikin kode acak (TIK-XH8...)
use App\Models\Event;
use App\Models\Transaction;
use App\Models\Ticket;

class OrderController extends Controller
{
    // 1. TAMPILKAN HALAMAN CHECKOUT
    public function checkout($id)
    {
        $event = Event::findOrFail($id);
        return view('checkout', compact('event'));
    }

    // 2. PROSES BELI TIKET (LOGIKA UTAMA)
    public function store(Request $request)
    {
        // --- A. VALIDASI (Cek kelengkapan data) ---
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1',
            // Validasi Array (Karena nama & KTP bisa banyak)
            'visitor_names.*' => 'required|string',
            'visitor_identities.*' => 'required|string',
        ]);

        // --- B. PERSIAPAN DATA ---
        $event = Event::findOrFail($request->event_id);
        
        // Cek stok dulu, kalau habis tolak!
        if($event->quota < $request->quantity) {
            return back()->withErrors(['msg' => 'Yah, tiketnya habis Wak! Kalah cepat.']);
        }

        // Hitung Total Bayar
        $totalPrice = $event->price * $request->quantity;

        // --- C. SIMPAN KE DATABASE (TRANSAKSI) ---
        // Kita buat dulu induknya (Nota Pembelian)
        $transaction = Transaction::create([
            'user_id'     => Auth::id(), // ID user yang login
            'event_id'    => $event->id,
            'quantity'    => $request->quantity,
            'total_price' => $totalPrice,
            'status'      => 'paid', // Anggap langsung LUNAS
        ]);

        // --- D. SIMPAN KE DATABASE (TIKET FISIK) ---
        // Kita lakukan LOOPING sebanyak jumlah tiket yang dibeli
        for ($i = 0; $i < $request->quantity; $i++) {
            Ticket::create([
                'transaction_id' => $transaction->id, // Sambungkan ke Nota tadi
                
                // Generate Kode Unik (Gabungan TIK + 8 Huruf Acak)
                'ticket_code'    => 'TIK-' . strtoupper(Str::random(8)),
                
                // Ambil Nama & KTP dari input form (Array)
                'visitor_name'     => $request->visitor_names[$i],
                'visitor_identity' => $request->visitor_identities[$i],
            ]);
        }

        // --- E. KURANGI STOK EVENT ---
        $event->decrement('quota', $request->quantity);

        // --- F. SELESAI ---
        // Lempar ke halaman Tiket Saya
        return redirect()->route('my.tickets')->with('success', 'Pembelian Berhasil! Tiket sudah terbit.');
    }
}