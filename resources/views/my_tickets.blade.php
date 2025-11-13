<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Saya - TiketKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: '#0F172A', secondary: '#F59E0B', } } } }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-100 text-slate-800 pb-20">

    <nav class="bg-white border-b border-slate-200 px-6 py-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/dashboard" class="font-bold text-xl text-primary flex items-center gap-2 hover:text-secondary transition">
                ⬅ Kembali ke Home
            </a>
            <span class="font-bold text-slate-400">Dompet Tiket</span>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-2xl font-bold text-primary mb-6 flex items-center gap-2">
            🎟️ Tiket Saya ({{ $tickets->count() }})
        </h1>

        <div class="space-y-6">
            @forelse($tickets as $ticket)
            <div class="bg-white rounded-2xl shadow-md overflow-hidden flex flex-col md:flex-row border border-slate-200 relative group hover:shadow-xl transition">
                <div class="p-6 flex-1 flex flex-col justify-between bg-white z-10">
                    
                    <div class="flex justify-between items-start mb-4">
                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider border border-green-200">
                            LUNAS
                        </span>
                        
                        @if($ticket->is_checked_in)
                            <span class="bg-blue-600/20 text-blue-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase border border-blue-500/50">
                                SUDAH CHECK-IN ✅
                            </span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase border border-yellow-500/50 animate-pulse">
                                BELUM DIGUNAKAN 🟡
                            </span>
                        @endif
                    </div>
                    <h3 class="text-2xl font-bold text-primary leading-tight">{{ $ticket->transaction->event->name }}</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        📅 {{ \Carbon\Carbon::parse($ticket->transaction->event->event_date)->translatedFormat('d F Y') }} <br>
                        📍 {{ $ticket->transaction->event->venue }}
                    </p>

                    <div class="mt-6 pt-4 border-t border-slate-300">
                        <div class="mb-2 border-b border-slate-100 pb-2">
                            <p class="text-[10px] text-slate-400 uppercase font-bold">Akun Pembeli</p>
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <p class="text-sm font-bold text-slate-600">{{ Auth::user()->name }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold">Nama Pengunjung</p>
                                <p class="text-sm font-bold text-slate-800">{{ $ticket->visitor_name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold">No Identitas</p>
                                <p class="text-sm font-bold text-slate-800">{{ $ticket->visitor_identity }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-48 bg-slate-50 p-6 flex flex-col items-center justify-center text-center border-l border-slate-100">
                    <div class="bg-white p-2 rounded-lg shadow-sm mb-3 border border-slate-200">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $ticket->ticket_code }}" 
                             alt="QR" class="w-24 h-24">
                    </div>
                    <p class="text-[10px] text-slate-400">Kode Tiket</p>
                    <p class="text-xs font-bold font-mono text-secondary mt-1">{{ $ticket->ticket_code }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-20 bg-white rounded-xl shadow-lg border border-dashed border-slate-300">
                <p class="text-slate-500 text-lg">Kamu belum memiliki tiket aktif, Wak. Ayo beli!</p>
                <a href="/dashboard" class="text-primary font-bold hover:underline mt-2 inline-block">Lihat Event Sekarang</a>
            </div>
            @endforelse
        </div>
    </div>
</body>
</html>