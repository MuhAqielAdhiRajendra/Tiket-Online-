<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Event;       // Model untuk tabel 'events'
use App\Models\Transaction; // Model untuk tabel 'transactions'

class AdminController extends Controller
{
    // --- 1. TAMPILKAN HALAMAN DASHBOARD ---
    public function index(Request $request) { // <--- Tambahkan Request $request di sini!
    
    // Logika Filter Transaksi Berdasarkan Event ID
    $transactionsQuery = Transaction::with(['user', 'event'])->latest();
    
    // Cek apakah ada filter 'event_id' dari dropdown
    if ($request->filled('event_id')) {
        $transactionsQuery->where('event_id', $request->event_id);
    }

    // 1. Ambil Data Event (untuk Tabel CRUD dan Dropdown)
    $events = Event::latest()->get();
    
    // 2. Ambil Data Transaksi (Setelah difilter)
    $transactions = $transactionsQuery->get();

    // Kirim data Event dan Transaksi ke view
    return view('admin.dashboard', compact('events', 'transactions'));
}

    // --- 2. PROSES NAMBAH EVENT BARU ---
    public function store(Request $request) {
        // Validasi biar data gak kosong
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'event_date' => 'required',
            'venue' => 'required',
            'price' => 'required|numeric',
            'quota' => 'required|numeric',
        ]);

        // Simpan ke tabel 'events'
        Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'event_date' => $request->event_date. ' 23:59:00',
            'venue' => $request->venue,
            'price' => $request->price,
            'quota' => $request->quota,
        ]);

        return back()->with('success', 'Event baru berhasil ditambahkan! ');
    }

    // --- 4. PROSES UPDATE EVENT ---
    public function update(Request $request, $id) {
        // 1. Validasi
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'event_date' => 'required|date',
            'venue' => 'required',
            'price' => 'required|numeric',
            'quota' => 'required|numeric',
        ]);

        // 2. Cari Event yang mau diedit
        $event = Event::findOrFail($id);

        // 3. Update Datanya
        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            // Gabungkan tanggal baru dengan jam default
            'event_date' => $request->event_date . ' 09:00:00',
            'venue' => $request->venue,
            'price' => $request->price,
            'quota' => $request->quota,
        ]);

        return back()->with('success', 'Event berhasil diperbarui! 🛠️');
    }

    // --- 3. PROSES HAPUS EVENT ---
    public function destroy($id) {
        Event::destroy($id);
        return back()->with('success', 'Event berhasil dihapus!');
    }

    // --- 5. LOGIKA VALIDASI / CHECK-IN TIKET ---
    public function checkTicket(Request $request)
    {
        // 1. Validasi Kode (Biar gak dikirim kosong)
        $request->validate([
            'ticket_code' => 'required|string|max:255',
        ]);

        // 2. Cari Tiket (dengan relasi Event)
        $ticket = Ticket::with('transaction.event')->where('ticket_code', $request->ticket_code)->first();

        // Cek 1: Apakah tiket ditemukan?
        if (!$ticket) {
            return back()->with('error', '❌ Tiket tidak ditemukan!');
        }

        // Cek 2: Apakah tiket sudah dipakai? (is_checked_in = 1)
        if ($ticket->is_checked_in) {
            return back()->with('error', '⚠️ Tiket sudah digunakan! (Oleh: ' . $ticket->visitor_name . ')');
        }

        // Cek 3: Tiket Valid, Lakukan Check-In (Update status)
        $ticket->is_checked_in = true;
        $ticket->save();

        // Redirect dengan pesan sukses
        return back()->with('success', '✅ Check-In Sukses! Pemegang: ' . $ticket->visitor_name . ' untuk event ' . $ticket->transaction->event->name . '.');
    }
}