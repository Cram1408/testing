<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - AchaarG</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex items-center justify-center p-4 font-sans bg-slate-950 text-slate-100 antialiased">
    <div class="max-w-md w-full bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl space-y-6">
        
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-2xl mx-auto shadow-lg shadow-amber-600/30 font-bold">
                <i class="fa-solid fa-jar"></i>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Admin Control Portal</h1>
            <p class="text-xs text-slate-400">AchaarG Pickles & Mukhwas Operations</p>
        </div>

        @if(session('error'))
            <div class="p-3 bg-rose-950/80 border border-rose-800 text-rose-300 rounded-xl text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('success'))
            <div class="p-3 bg-emerald-950/80 border border-emerald-800 text-emerald-300 rounded-xl text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Admin Email</label>
                <div class="relative">
                    <input type="email" name="email" required
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    class="w-full bg-slate-950 border border-slate-700 rounded-xl py-3 pl-10 pr-4 text-xs font-semibold text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <i class="fa-solid fa-envelope absolute left-3.5 top-3.5 text-slate-500 text-xs"></i>
                </div>
                @error('email') <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Password</label>
                <div class="relative">
                   <input type="password" name="password" id="adminPassword" required
                   placeholder="Enter your password"
                   autocomplete="current-password"
                   class="w-full bg-slate-950 border border-slate-700 rounded-xl py-3 pl-10 pr-10 text-xs font-semibold text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                   <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-500 text-xs"></i>
                   <button type="button" id="togglePassword"
                       onclick="toggleAdminPassword()"
                       class="absolute right-3.5 top-3 text-slate-500 hover:text-amber-400 transition-colors focus:outline-none"
                       aria-label="Toggle password visibility">
                       <i id="togglePasswordIcon" class="fa-solid fa-eye text-xs"></i>
                   </button>
                </div>
                @error('password') <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between text-xs text-slate-400">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" checked class="rounded bg-slate-800 border-slate-700 text-amber-600 focus:ring-amber-500">
                    <span>Keep me logged in</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-bold text-xs rounded-xl shadow-lg shadow-amber-700/20 active:scale-95 transition-all">
                Authenticate & Enter Portal
            </button>
        </form>

        {{-- <div class="p-3.5 bg-slate-950/80 rounded-xl border border-slate-800 text-center text-xs text-slate-400 space-y-1">
            <p class="font-bold text-slate-300">Default Superadmin Credentials:</p>
            <p class="font-mono text-amber-400 text-[11px]">admin@pickles.com / password</p>
        </div> --}}

        <div class="text-center">
            <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-400 hover:text-white transition-colors">
                ← Return to Customer Storefront
            </a>
        </div>
    </div>
<script>
    function toggleAdminPassword() {
        const input = document.getElementById('adminPassword');
        const icon  = document.getElementById('togglePasswordIcon');
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.classList.toggle('fa-eye',       !isHidden);
        icon.classList.toggle('fa-eye-slash',  isHidden);
    }
</script>
</body>
</html>
