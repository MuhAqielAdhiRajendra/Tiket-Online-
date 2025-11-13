<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TiketKu - Konser & Event Seru</title>
    
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

    <nav class="bg-primary text-white fixed w-full z-50 shadow-md transition-all duration-300">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold tracking-tighter flex items-center gap-2">
                🎫 TIKET<span class="text-secondary">KU.</span>
            </a>

            <div class="hidden md:flex space-x-8 text-sm font-semibold">
                <a href="#" class="hover:text-secondary transition">HOME</a>
                <a href="#event-section" class="hover:text-secondary transition">EVENT TERBARU</a>
            </div>

            <div class="space-x-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-slate-700 border border-slate-600 rounded text-sm font-semibold hover:bg-slate-600 transition">
                            Dashboard ({{ Auth::user()->name }})
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 border border-slate-500 rounded text-sm hover:bg-slate-800 hover:border-white transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-secondary text-white rounded text-sm font-bold hover:bg-yellow-600 transition shadow-lg shadow-yellow-500/30">
                            Daftar
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <header class="relative bg-primary pt-32 pb-24 text-center px-4 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

        <div class="relative z-10 container mx-auto max-w-4xl">
            <span class="text-secondary font-bold tracking-widest text-xs uppercase mb-3 block animate-pulse">Platform Tiket Termudah</span>
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                Amankan Kursimu di <br> Event Paling Hits!
            </h1>
            <p class="text-slate-400 text-lg mb-10 max-w-2xl mx-auto">
                Jangan sampai kehabisan. Pesan tiket konser, seminar, dan festival favoritmu sekarang juga.
            </p>
            
            <form action="{{ route('home') }}" method="GET">
                <div class="bg-white p-2 rounded-full shadow-2xl flex flex-col md:flex-row items-center max-w-2xl mx-auto border-4 border-primary/50">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama konser atau lokasi..." class="w-full px-6 py-3 rounded-full outline-none text-slate-700 placeholder-slate-400">
                    <button type="submit" class="w-full md:w-auto bg-secondary text-white font-bold px-8 py-3 rounded-full hover:bg-yellow-600 transition transform hover:scale-105">
                        CARI
                    </button>
                </div>
            </form>

        </div>
    </header>

    <section id="event-section" class="container mx-auto px-6 py-16">
        <div class="flex justify-between items-end mb-10 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-3xl font-bold text-primary">Event Pilihan 🔥</h2>
                @if(request('search'))
                    <p class="text-secondary mt-1 font-bold">Menampilkan hasil pencarian: "{{ request('search') }}"</p>
                @else
                    <p class="text-slate-500 mt-1">Daftar acara yang sedang trending minggu ini.</p>
                @endif
            </div>
            @if(request('search'))
                <a href="{{ route('home') }}" class="text-red-500 text-sm hover:underline">Reset Pencarian</a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            @foreach($events as $event)
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 overflow-hidden border border-slate-100 group flex flex-col h-full">
                
                <div class="h-48 bg-slate-800 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=800&q=80&sig={{ $event->id }}" 
                         alt="{{ $event->name }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500 grayscale group-hover:grayscale-0">
                    
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-lg text-xs font-bold shadow text-primary">
                        TIKET TERSEDIA
                    </div>
                </div>
                
                <div class="p-6 flex-1 flex flex-col">
                    <div class="text-xs text-slate-500 mb-3 flex items-center gap-2 uppercase tracking-wide font-semibold">
                        📅 {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }}
                    </div>
                    
                    <h3 class="text-xl font-bold text-primary mb-2 leading-snug group-hover:text-secondary transition">
                        {{ $event->name }}
                    </h3>
                    
                    <p class="text-slate-500 text-sm mb-4 line-clamp-2 flex-1">
                        📍 {{ $event->venue }} <br>
                        <span class="italic text-xs">{{ $event->description }}</span>
                    </p>
                    
                    <div class="flex justify-between items-center border-t border-slate-100 pt-4 mt-auto">
                        <div>
                            <p class="text-xs text-slate-400">Harga Mulai</p>
                            <p class="text-secondary font-bold text-lg">
                                Rp {{ number_format($event->price, 0, ',', '.') }}
                            </p>
                        </div>

                        @auth
                            <a href="{{ route('checkout', $event->id) }}" class="bg-primary text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-slate-800 transition shadow-lg shadow-primary/30">
                                Beli Tiket
                            </a>
                        @else
                            <a href="{{ route('checkout', $event->id) }}" class="bg-slate-200 text-slate-600 px-6 py-2 rounded-xl text-sm font-bold hover:bg-secondary hover:text-white transition">
                                Beli
                            </a>
                        @endauth

                    </div>
                </div>
            </div>
            @endforeach

        </div>
        
        @if($events->isEmpty())
            <div class="text-center py-20 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                <p class="text-slate-400 text-lg mb-2">Yah, event yang kamu cari tidak ditemukan.</p>
                <a href="/" class="text-secondary font-bold hover:underline">Lihat Semua Event</a>
            </div>
        @endif

    </section>

    <footer class="bg-primary text-slate-400 py-10 text-center text-sm border-t border-slate-800">
        <div class="container mx-auto px-4">
            <p class="mb-2">&copy; {{ date('Y') }} TiketKu Indonesia. All rights reserved.</p>
            <p>Dibuat dengan ❤️ menggunakan Laravel & Tailwind.</p>
        </div>
    </footer>

</body>
</html>