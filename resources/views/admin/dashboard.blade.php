<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TiketKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } .content-tab { transition: opacity 0.2s; } </style>
    
    <script src="https://unpkg.com/html5-qrcode"></script> 
</head>
<body class="bg-slate-900 text-slate-100 font-sans">

    <div class="flex min-h-screen">
        
        <aside class="w-64 bg-slate-950 border-r border-slate-800 flex-shrink-0 hidden md:block">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-yellow-500 tracking-tighter">ADMIN<span class="text-white">PANEL</span></h1>
            </div>
            <nav class="px-4 space-y-2">
                <a href="#" onclick="switchContent('events', this)" id="link-events" class="nav-link block px-4 py-3 text-white font-bold border-l-4 border-yellow-500 bg-slate-800 rounded-lg transition">
                    📅 Event Manager
                </a>
                <a href="#" onclick="switchContent('transactions', this)" id="link-transactions" class="nav-link block px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 transition rounded-lg">
                    💰 Riwayat Transaksi
                </a>
                <a href="#" onclick="switchContent('scanner', this)" id="link-scanner" class="nav-link block px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 transition rounded-lg">
                    🎟️ Cek Tiket / Scan
                </a>
                
                <a href="{{ route('dashboard') }}" target="_blank" class="block px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 transition rounded-lg mt-4 border-t border-slate-800">Lihat Website Utama ↗</a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-4 py-2 text-red-400 hover:text-red-300 font-bold text-sm">Keluar / Logout</button>
                </form>
            </nav>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto">

            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                    ✅ {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="bg-red-500/10 border border-red-500 text-red-400 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                    ❌ {{ session('error') }}
                </div>
            @endif


            <div id="content-events" class="content-tab">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold">📅 Manajemen Event Aktif</h2>
                    <button onclick="toggleModal('modalAdd')" class="bg-yellow-500 hover:bg-yellow-600 text-slate-900 px-4 py-2 rounded-lg font-bold transition shadow-lg flex items-center gap-2">
                        + Tambah Event Baru
                    </button>
                </div>
                <div class="bg-slate-800 rounded-xl overflow-hidden shadow-xl border border-slate-700">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-slate-950 text-slate-500 uppercase text-xs font-bold">
                            <tr>
                                <th class="px-6 py-3">Nama Event</th>
                                <th class="px-6 py-3">Jadwal & Lokasi</th>
                                <th class="px-6 py-3">Harga</th>
                                <th class="px-6 py-3">Sisa Kuota</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($events as $event)
                            <tr class="hover:bg-slate-700/50 transition">
                                <td class="px-6 py-4 font-bold text-white">{{ $event->name }}</td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d M Y') }} <br>
                                    <span class="text-xs text-slate-500">📍 {{ $event->venue }}</span>
                                </td>
                                <td class="px-6 py-4 text-yellow-500">Rp {{ number_format($event->price) }}</td>
                                <td class="px-6 py-4">{{ $event->quota }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="toggleModal('editModal{{ $event->id }}')" class="text-blue-400 hover:text-blue-200 font-bold text-sm">Edit</button>
                                        <form action="{{ route('admin.event.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus event ini?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-400 hover:text-red-200 font-bold text-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <div id="editModal{{ $event->id }}" class="fixed inset-0 bg-black/80 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm text-left">
                                <div class="bg-slate-800 w-full max-w-lg rounded-2xl border border-slate-700 shadow-2xl overflow-hidden">
                                    <div class="p-5 border-b border-slate-700 flex justify-between items-center">
                                        <h3 class="text-lg font-bold text-white">Edit Event: {{ $event->name }}</h3>
                                        <button type="button" onclick="toggleModal('editModal{{ $event->id }}')" class="text-slate-400 hover:text-white">✖</button>
                                    </div>
                                    
                                    <form action="{{ route('admin.event.update', $event->id) }}" method="POST" class="p-6 space-y-4">
                                        @csrf @method('PUT')
                                        <div><label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nama Event</label><input type="text" name="name" value="{{ $event->name }}" required class="w-full bg-slate-900 border border-slate-700 rounded p-3 text-white focus:border-blue-500 outline-none"></div>
                                        <div><label class="block text-xs font-bold text-slate-400 uppercase mb-1">Deskripsi</label><textarea name="description" required rows="2" class="w-full bg-slate-900 border border-slate-700 rounded p-3 text-white focus:border-blue-500 outline-none">{{ $event->description }}</textarea></div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div><label class="block text-xs font-bold text-slate-400 uppercase mb-1">Tanggal</label><input type="date" name="event_date" value="{{ \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') }}" required class="w-full bg-slate-900 border border-slate-700 rounded p-3 text-white focus:border-blue-500 outline-none"></div>
                                            <div><label class="block text-xs font-bold text-slate-400 uppercase mb-1">Venue</label><input type="text" name="venue" value="{{ $event->venue }}" required class="w-full bg-slate-900 border border-slate-700 rounded p-3 text-white focus:border-blue-500 outline-none"></div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div><label class="block text-xs font-bold text-slate-400 uppercase mb-1">Harga (Rp)</label><input type="number" name="price" value="{{ $event->price }}" required class="w-full bg-slate-900 border border-slate-700 rounded p-3 text-white focus:border-blue-500 outline-none"></div>
                                            <div><label class="block text-xs font-bold text-slate-400 uppercase mb-1">Kuota</label><input type="number" name="quota" value="{{ $event->quota }}" required class="w-full bg-slate-900 border border-slate-700 rounded p-3 text-white focus:border-blue-500 outline-none"></div>
                                        </div>
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition mt-4">UPDATE EVENT 🛠️</button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            <div id="content-transactions" class="content-tab hidden">
                <h2 class="text-3xl font-bold mb-6">💰 Riwayat Transaksi Masuk</h2>
                <div class="bg-slate-800 rounded-xl overflow-hidden shadow-xl border border-slate-700">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-slate-950 text-slate-500 uppercase text-xs font-bold">
                            <tr>
                                <th class="px-6 py-3">ID TRX</th>
                                <th class="px-6 py-3">Pembeli</th>
                                <th class="px-6 py-3">Event</th>
                                <th class="px-6 py-3">Total Bayar</th>
                                <th class="px-6 py-3">Waktu</th>
                                <th class="px-6 py-3 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($transactions as $trx)
                            <tr class="hover:bg-slate-700/50 transition">
                                <td class="px-6 py-4 font-mono text-yellow-500">#{{ $trx->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white">{{ $trx->user->name }}</div>
                                </td>
                                <td class="px-6 py-4">{{ $trx->event->name }}</td>
                                <td class="px-6 py-4 font-bold text-green-400">Rp {{ number_format($trx->total_price) }}</td>
                                <td class="px-6 py-4 text-xs">{{ $trx->created_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="bg-green-500/20 text-green-400 border border-green-500/50 px-3 py-1 rounded-full text-xs font-bold">LUNAS</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($transactions->isEmpty())
                        <div class="p-6 text-center text-slate-500 italic">Belum ada transaksi masuk.</div>
                    @endif
                </div>
            </div>


            <div id="content-scanner" class="content-tab hidden">
                <div class="max-w-xl mx-auto">
                    
                    <h2 class="text-3xl font-bold mb-6 text-yellow-500">🎟️ Validasi Tiket (Live Scan)</h2>

                    <div class="bg-slate-800 rounded-xl p-6 shadow-xl border border-slate-700 mb-6 text-center">
                        <div id="qr-reader-container" class="w-full mb-4">
                            <div id="qr-reader" class="rounded-lg shadow-2xl border-4 border-slate-700"></div>
                        </div>
                        
                        <button onclick="startScanner()" id="btn-start" class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-lg flex items-center justify-center mx-auto gap-2">
                            AKTIFKAN SCANNER
                        </button>
                        <button onclick="stopScanner()" id="btn-stop" class="hidden bg-gray-600 hover:bg-gray-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-lg mt-2 mx-auto flex items-center justify-center gap-2">
                            TUTUP KAMERA
                        </button>

                        <p id="camera-status" class="mt-4 text-sm text-yellow-400 font-semibold">Status: Scanner Belum Aktif.</p>
                    </div>

                    <form action="{{ route('admin.ticket.check') }}" method="POST" id="scan-form">
                        @csrf
                        <label for="ticket_code" class="block text-xl font-bold text-slate-300 mb-3">Kode Tiket / Hasil Scan</label>
                        <div class="flex space-x-3">
                            <input type="text" name="ticket_code" id="ticket_code_input" required placeholder="TIK-ABC12345" 
                                class="flex-1 bg-slate-900 border-2 border-slate-700 rounded-xl p-4 text-white text-lg focus:border-yellow-500 outline-none placeholder-slate-500">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-lg">
                                CEK
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <div id="modalAdd" class="fixed inset-0 bg-black/80 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-slate-800 w-full max-w-lg rounded-2xl border border-slate-700 shadow-2xl overflow-hidden">
            <div class="p-5 border-b border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Tambah Event Baru</h3>
                <button onclick="toggleModal('modalAdd')" class="text-slate-400 hover:text-white">✖</button>
            </div>
            
            <form action="{{ route('admin.event.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <div><label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nama Event</label><input type="text" name="name" required class="w-full bg-slate-900 border border-slate-700 rounded p-3 text-white focus:border-yellow-500 outline-none"></div>
                <div><label class="block text-xs font-bold text-slate-400 uppercase mb-1">Deskripsi</label><textarea name="description" required rows="2" class="w-full bg-slate-900 border border-slate-700 rounded p-3 text-white focus:border-yellow-500 outline-none"></textarea></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold text-slate-400 uppercase mb-1">Tanggal Event</label><input type="date" name="event_date" required class="w-full bg-slate-900 border border-slate-700 rounded p-3 text-white focus:border-yellow-500 outline-none"></div>
                    <div><label class="block text-xs font-bold text-slate-400 uppercase mb-1">Lokasi (Venue)</label><input type="text" name="venue" required class="w-full bg-slate-900 border border-slate-700 rounded p-3 text-white focus:border-yellow-500 outline-none"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold text-slate-400 uppercase mb-1">Harga (Rp)</label><input type="number" name="price" required class="w-full bg-slate-900 border border-slate-700 rounded p-3 text-white focus:border-yellow-500 outline-none"></div>
                    <div><label class="block text-xs font-bold text-slate-400 uppercase mb-1">Kuota Tiket</label><input type="number" name="quota" required class="w-full bg-slate-900 border border-slate-700 rounded p-3 text-white focus:border-yellow-500 outline-none"></div>
                </div>

                <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-slate-900 font-bold py-3 rounded-xl transition mt-4">SIMPAN KE DATABASE 💾</button>
            </form>
        </div>
    </div>

    <script>
        // Global variable untuk menyimpan objek scanner
        let qrCodeScanner;
        
        // Fungsi untuk membuka/menutup modal
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }

        // Fungsi stop kamera (dipanggil saat pindah tab atau klik tombol stop)
        function stopScanner() {
            const status = document.getElementById('camera-status');
            const startBtn = document.getElementById('btn-start');
            const stopBtn = document.getElementById('btn-stop');
            
            if (qrCodeScanner) {
                qrCodeScanner.stop().then(() => {
                    status.innerText = 'Status: Scanner Dimatikan.';
                    startBtn.classList.remove('hidden');
                    stopBtn.classList.add('hidden');
                }).catch(err => {
                    console.error("Gagal mematikan scanner:", err);
                });
            }
        }

        // Fungsi start kamera (dipanggil saat klik tombol start)
        function startScanner() {
            const status = document.getElementById('camera-status');
            const startBtn = document.getElementById('btn-start');
            const stopBtn = document.getElementById('btn-stop');
            const qrReaderId = 'qr-reader';
            
            // Sembunyikan tombol start, tampilkan stop
            startBtn.classList.add('hidden');
            stopBtn.classList.remove('hidden');
            status.innerText = 'Status: Meminta Izin Kamera...';

            if (!window.Html5Qrcode) {
                status.innerText = '❌ Error: Library html5-qrcode belum termuat.';
                return;
            }

            if (!qrCodeScanner) {
                qrCodeScanner = new Html5Qrcode(qrReaderId);
            }

            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                // Ketika QR berhasil dideteksi:
                stopScanner(); // Matikan kamera agar tidak scan terus
                document.getElementById('ticket_code_input').value = decodedText; // Isi input field
                document.getElementById('scan-form').submit(); // Langsung submit form!
            };

            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            qrCodeScanner.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
                .then(() => {
                    status.innerText = '✅ Scanner Aktif! Arahkan ke Kode QR.';
                    status.classList.remove('text-yellow-400');
                    status.classList.add('text-green-400');
                })
                .catch((err) => {
                    status.innerText = '❌ Gagal Mengaktifkan Kamera. Pastikan izin diberikan.';
                    status.classList.remove('text-green-400');
                    status.classList.add('text-red-400');
                    startBtn.classList.remove('hidden'); // Tampilkan tombol start lagi
                    stopBtn.classList.add('hidden');
                    console.error("Gagal mengaktifkan scanner:", err);
                });
        }

        // Fungsi utama untuk pindah tab (Sidebar)
        function switchContent(targetId, clickedElement) {
            const contents = document.querySelectorAll('.content-tab');
            const links = document.querySelectorAll('.nav-link');
            
            // 1. Sembunyikan semua konten tab
            contents.forEach(content => {
                content.classList.add('hidden');
            });

            // 2. Jika pindah dari tab SCANNER, matikan kamera dulu
            if (document.getElementById('content-scanner').classList.contains('hidden') === false && targetId !== 'scanner') {
                stopScanner();
            }

            // 3. Tampilkan konten yang sesuai
            document.getElementById(`content-${targetId}`).classList.remove('hidden');

            // 4. Update styling link sidebar
            links.forEach(link => {
                link.classList.remove('bg-slate-800', 'border-l-4', 'border-yellow-500', 'text-white');
                link.classList.add('text-slate-400');
            });
            
            // 5. Aktifkan link yang diklik
            if (clickedElement) {
                 clickedElement.classList.add('bg-slate-800', 'border-l-4', 'border-yellow-500', 'text-white');
                 clickedElement.classList.remove('text-slate-400');
            }
            
            // 6. Jika pindah ke tab SCANNER, langsung nyalakan kamera (optional)
            if (targetId === 'scanner') {
                // startScanner(); // Biarkan user klik manual untuk menghindari loop izin
                document.getElementById('camera-status').innerText = 'Status: Klik AKTIFKAN SCANNER.';
            }
        }

        // Jalankan Tab Event Manager (Default) saat halaman pertama kali dimuat
        document.addEventListener('DOMContentLoaded', () => {
            switchContent('events', document.getElementById('link-events')); 
        });
    </script>
</body>
</html>