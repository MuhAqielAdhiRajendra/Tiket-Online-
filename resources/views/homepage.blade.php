<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage - TiketKu</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                primary: '#0F172A',  // Biru Gelap
                secondary: '#F59E0B', // Emas
              }
            }
          }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            
            <a href="/dashboard" class="text-2xl font-bold tracking-tighter text-primary">
                🎫 TIKET<span class="text-secondary">KU.</span>
            </a>

            <div class="flex items-center gap-6">
                
                <a href="{{ route('my.tickets') }}" class="hidden md:flex items-center gap-2 text-sm font-bold text-primary hover:text-secondary bg-slate-100 px-3 py-2 rounded-lg transition">
                    🎟️ Tiket Saya
                </a>

                <div class="hidden md:block text-right">
                    <p class="text-sm font-bold text-primary">{{ Auth::user()->name }}</p>
                    
                    @if(Auth::user()->role === 'admin')
                        <p class="text-[10px] font-bold text-secondary uppercase tracking-wider border border-secondary px-1 rounded inline-block mt-1">
                            ADMINISTRATOR
                        </p>
                    @else
                        <p class="text-xs text-slate-500">Member Club</p>
                    @endif
                </div>
                
                <div class="h-10 w-10 bg-primary rounded-full flex items-center justify-center text-white font-bold border-2 border-secondary">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-semibold border border-red-200 px-3 py-1 rounded hover:bg-red-50 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-10">
        
        <div class="bg-gradient-to-r from-primary to-slate-800 rounded-2xl p-8 text-white shadow-lg mb-10 flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold mb-2">Mau nonton apa hari ini, {{ explode(' ', Auth::user()->name)[0] }}? 👋</h1>
                <p class="text-slate-300">Jangan lewatkan event seru minggu ini. Stok tiket terbatas!</p>
            </div>
            <div class="hidden md:block text-6xl opacity-20">🎉</div>
        </div>

        <div class="flex items-center gap-2 mb-6">
            <div class="h-8 w-1 bg-secondary rounded-full"></div>
            <h2 class="text-2xl font-bold text-primary">Katalog Event Terbaru</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            @foreach($events as $event)
            <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden border border-slate-100 group flex flex-col h-full">
                
                <div class="h-48 bg-slate-800 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=800&q=80&sig={{ $event->id }}" 
                         alt="{{ $event->name }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2 py-1 rounded text-[10px] font-bold shadow text-primary border border-slate-200">
                        QUOTA: {{ $event->quota }}
                    </div>
                </div>
                
                <div class="p-5 flex-1 flex flex-col">
                    <div class="text-xs text-slate-500 mb-2 flex items-center gap-2 font-semibold">
                        📅 {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }}
                    </div>
                    
                    <h3 class="text-lg font-bold text-primary mb-2 leading-snug">
                        {{ $event->name }}
                    </h3>
                    
                    <p class="text-slate-500 text-sm mb-4 flex-1 line-clamp-2">
                        📍 {{ $event->venue }}
                    </p>
                    
                    <div class="flex justify-between items-center border-t border-slate-100 pt-4 mt-auto">
                        <div>
                            <p class="text-xs text-slate-400">Harga Tiket</p>
                            <p class="text-secondary font-bold text-lg">
                                Rp {{ number_format($event->price, 0, ',', '.') }}
                            </p>
                        </div>
                        
                        <a href="{{ route('checkout', $event->id) }}" class="bg-primary text-white px-5 py-2 rounded-xl text-sm font-bold hover:bg-slate-800 transition shadow-lg shadow-primary/30 flex items-center gap-2">
                            Beli
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

        </div>

        @if($events->isEmpty())
            <div class="text-center py-20 bg-white rounded-xl border border-dashed border-slate-300 mt-4">
                <p class="text-slate-400 text-lg">Belum ada event yang tersedia saat ini.</p>
            </div>
        @endif

    </div>

</body>
</html>