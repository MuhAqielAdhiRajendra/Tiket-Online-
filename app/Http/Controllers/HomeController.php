<?php

namespace App\Http\Controllers;
use App\Models\Ticket; 
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{ 
    public function index(Request $request)
    {
        // 1. LOGIKA REDIRECT JIKA SUDAH LOGIN
        // ... (Biarkan logika redirect tetap ada) ...
        
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard'); // Redirect ke HomePage Member
        }

        // --- LOGIKA PENCARIAN & DISPLAY EVENT ---
        
        // FIX: Tambahkan argumen kolom 'created_at' dan urutan 'desc'
        $query = Event::orderBy('created_at', 'desc'); 

        // Jika ada input 'search' dari form
        if ($request->filled('search')) {
            $search = $request->search;
            
            // Tambahkan filter WHERE
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        // Ambil data dari query yang sudah difilter
        $events = $query->take(6)->get(); 
        
        return view('index', compact('events'));
    }
    
    // 2. LOGIKA HALAMAN TIKET SAYA (Ini yang tadi hilang!)
    public function myTickets()
    {
        // Ambil tiket milik user yang sedang login
        // Kita cari tiket yang transaction-nya punya user_id sama dengan Auth::id()
        $tickets = Ticket::whereHas('transaction', function($query) {
            $query->where('user_id', Auth::id());
        })->with('transaction.event')->latest()->get(); 

        return view('my_tickets', compact('tickets'));
    }
}