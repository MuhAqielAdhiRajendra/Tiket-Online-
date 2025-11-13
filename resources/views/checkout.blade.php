<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - {{ $event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: '#0F172A', secondary: '#F59E0B', } } } }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans pb-20">

    <nav class="bg-white border-b border-slate-200 py-4 px-6">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/dashboard" class="font-bold text-xl text-primary flex items-center gap-2 hover:text-secondary transition">
                ⬅ Batal
            </a>
            <span class="font-bold text-slate-400">Secure Checkout 🔒</span>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-10 max-w-4xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6 sticky top-10">
                    <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=800&q=80&sig={{ $event->id }}" class="w-full h-32 object-cover rounded-lg mb-4">
                    
                    <h2 class="text-lg font-bold text-primary mb-2 leading-tight">{{ $event->name }}</h2>
                    <p class="text-sm text-slate-500 mb-4">
                        📍 {{ $event->venue }} <br>
                        📅 {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }}
                    </p>
                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs text-slate-400">Harga Satuan</p>
                        <p class="text-xl font-bold text-secondary" id="ticketPrice" data-price="{{ $event->price }}">
                            Rp {{ number_format($event->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
                    <h1 class="text-2xl font-bold text-primary mb-6">Isi Data Pemesanan</h1>

                    @if ($errors->any())
                        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm border-l-4 border-red-500">
                            <strong>Waduh!</strong> Ada data yang belum diisi:
                            <ul class="list-disc ml-4 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Mau beli berapa tiket?</label>
                            <select id="quantity" name="quantity" class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition bg-slate-50 font-bold" onchange="updateForm()">
                                <option value="1">1 Tiket</option>
                                <option value="2">2 Tiket</option>
                                <option value="3">3 Tiket</option>
                                <option value="4">4 Tiket</option>
                                <option value="5">5 Tiket</option>
                            </select>
                        </div>

                        <div id="visitorForms" class="space-y-6 mb-8">
                            </div>

                        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 flex justify-between items-center mb-8">
                            <span class="text-slate-600 font-semibold">Total Pembayaran</span>
                            <span class="text-3xl font-bold text-primary" id="totalDisplay">
                                Rp {{ number_format($event->price, 0, ',', '.') }}
                            </span>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-slate-800 transition shadow-lg transform active:scale-95 duration-200">
                            BAYAR SEKARANG 💳
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function updateForm() {
            const quantity = document.getElementById('quantity').value;
            const container = document.getElementById('visitorForms');
            const price = document.getElementById('ticketPrice').getAttribute('data-price');
            const totalDisplay = document.getElementById('totalDisplay');

            // 1. Update Total Harga
            const total = quantity * price;
            // Format Rupiah JS
            totalDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);

            // 2. Generate Form Input Sesuai Jumlah
            container.innerHTML = ''; // Kosongkan dulu
            
            for (let i = 1; i <= quantity; i++) {
                const html = `
                    <div class="border border-slate-200 rounded-lg p-5 bg-white relative shadow-sm">
                        <div class="absolute -top-3 -left-3 bg-secondary text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-sm border-2 border-white">
                            ${i}
                        </div>
                        <h3 class="font-bold text-slate-700 mb-4 ml-4 text-sm uppercase tracking-wide">Data Pengunjung ${i}</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Nama Lengkap (Sesuai KTP)</label>
                                <input type="text" name="visitor_names[]" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" placeholder="Cth: Budi Santoso">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Nomor Identitas (KTP/SIM/Kartu Pelajar)</label>
                                <input type="text" name="visitor_identities[]" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" placeholder="Cth: 337205...">
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            }
        }

        // Jalankan sekali saat halaman dimuat biar form no.1 muncul
        document.addEventListener('DOMContentLoaded', function() {
            updateForm();
        });
    </script>

</body>
</html>