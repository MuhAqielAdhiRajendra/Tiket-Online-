<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk / Daftar - TiketKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: '#0F172A', secondary: '#F59E0B', } } } }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } .hidden { display: none; } </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center px-4">
<input type="hidden" id="active-tab-status" value="{{ (old('name') || $errors->has('name')) ? 'register' : 'login' }}">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200">
        
        <div class="bg-primary p-6 text-center relative">
            <h2 class="text-2xl font-bold text-white tracking-tighter z-10 relative">
                TIKET<span class="text-secondary">KU.</span>
            </h2>
            <p class="text-slate-400 text-xs mt-1 z-10 relative">Gerbang menuju keseruan!</p>
        </div>

        <div class="flex border-b border-slate-200">
            <button onclick="switchTab('login')" id="tabLogin" class="w-1/2 py-4 font-bold text-sm transition text-primary border-b-2 border-primary bg-slate-50">
                MASUK
            </button>
            <button onclick="switchTab('register')" id="tabRegister" class="w-1/2 py-4 font-bold text-sm text-slate-400 transition hover:bg-slate-50">
                DAFTAR BARU
            </button>
        </div>

        <div class="p-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-3 text-xs rounded">
                    <strong>Oops!</strong> {{ $errors->first() }}
                </div>
            @endif

            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-slate-600 text-xs font-bold uppercase mb-2">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-primary outline-none text-sm" placeholder="nama@email.com">
                </div>
                <div class="mb-6">
                    <label class="block text-slate-600 text-xs font-bold uppercase mb-2">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-primary outline-none text-sm" placeholder="••••••••">
                </div>
                <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-slate-800 transition shadow-lg">
                    LOGIN
                </button>
            </form>

            <form id="registerForm" method="POST" action="{{ route('register') }}" class="hidden">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-slate-600 text-xs font-bold uppercase mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-primary outline-none text-sm" placeholder="Budi Santoso">
                </div>

                <div class="mb-4">
                    <label class="block text-slate-600 text-xs font-bold uppercase mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-primary outline-none text-sm" placeholder="nama@email.com">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-slate-600 text-xs font-bold uppercase mb-2">No. HP (WA)</label>
                        <input type="text" name="phone_number" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-primary outline-none text-sm" placeholder="0812...">
                    </div>
                    <div>
                        <label class="block text-slate-600 text-xs font-bold uppercase mb-2">Tanggal Lahir</label>
                        <input type="date" name="birth_date" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-primary outline-none text-sm text-slate-600">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-slate-600 text-xs font-bold uppercase mb-2">Buat Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-primary outline-none text-sm" placeholder="Minimal 6 karakter">
                </div>

                <button type="submit" class="w-full bg-secondary text-white font-bold py-3 rounded-xl hover:bg-yellow-600 transition shadow-lg">
                    DAFTAR 
                </button>
            </form>

        </div>
    </div>

    <script>
        function switchTab(tab) {
            const loginForm = document.getElementById('loginForm');
            const regForm = document.getElementById('registerForm');
            const tabLogin = document.getElementById('tabLogin');
            const tabRegister = document.getElementById('tabRegister');

            if (tab === 'login') {
                // Tampilkan Login
                loginForm.classList.remove('hidden');
                regForm.classList.add('hidden');
                
                tabLogin.classList.add('text-primary', 'border-b-2', 'border-primary', 'bg-slate-50');
                tabLogin.classList.remove('text-slate-400');
                
                tabRegister.classList.remove('text-primary', 'border-b-2', 'border-primary', 'bg-slate-50');
                tabRegister.classList.add('text-slate-400');
            } else {
                // Tampilkan Register
                loginForm.classList.add('hidden');
                regForm.classList.remove('hidden');

                tabRegister.classList.add('text-primary', 'border-b-2', 'border-primary', 'bg-slate-50');
                tabRegister.classList.remove('text-slate-400');

                tabLogin.classList.remove('text-primary', 'border-b-2', 'border-primary', 'bg-slate-50');
                tabLogin.classList.add('text-slate-400');
            }
        }

        // --- LOGIKA BARU (ANTI ERROR DECORATOR) ---
        // 1. Ambil nilai dari input tersembunyi diatas
        const status = document.getElementById('active-tab-status').value;

        // 2. Jika nilainya 'register', pindah tab otomatis
        if (status === 'register') {
            switchTab('register');
        }
    </script>

</body>
</html>