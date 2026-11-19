<!DOCTYPE html>
<html lang="en" class="h-full bg-[#FDFBF7]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - AchaarG Control Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FDFBF7;
        }
        .font-serif-heading {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 font-sans bg-[#FDFBF7] text-slate-800 antialiased selection:bg-amber-500 selection:text-white">
    <div class="max-w-md w-full bg-white border border-amber-200/80 rounded-3xl p-8 shadow-xl space-y-6">
        
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-600 to-amber-800 text-white flex items-center justify-center text-2xl mx-auto shadow-md shadow-amber-800/20 font-bold border border-amber-400/40">
                <i class="fa-solid fa-jar"></i>
            </div>
            <h1 class="font-serif-heading text-2xl font-bold tracking-tight text-amber-950">Admin Control Portal</h1>
            <p class="text-xs text-amber-800/70 font-medium">AchaarG Pickles & Mukhwas Operations</p>
        </div>

        @if(session('error'))
            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Admin Email</label>
                <div class="relative">
                    <input type="email" name="email" required
                    value="{{ old('email') }}"
                    placeholder="Enter your registered staff email"
                    class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl py-3 pl-10 pr-4 text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600 transition-all">
                    <i class="fa-solid fa-envelope absolute left-3.5 top-3.5 text-amber-700/60 text-xs"></i>
                </div>
                @error('email') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password</label>
                <div class="relative">
                   <input type="password" name="password" id="adminPassword" required
                   placeholder="Enter your security password"
                   autocomplete="current-password"
                   class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl py-3 pl-10 pr-10 text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600 transition-all">
                   <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-amber-700/60 text-xs"></i>
                   <button type="button" id="togglePassword"
                       onclick="toggleAdminPassword()"
                       class="absolute right-3.5 top-3 text-slate-400 hover:text-amber-800 transition-colors focus:outline-none"
                       aria-label="Toggle password visibility">
                       <i id="togglePasswordIcon" class="fa-solid fa-eye text-xs"></i>
                   </button>
                </div>
                @error('password') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" checked class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                    <span>Keep me logged in</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-amber-700 hover:text-amber-900 font-bold hover:underline">
                    Forgot password?
                </a>
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs rounded-xl shadow-md shadow-amber-900/20 active:scale-95 transition-all">
                Authenticate & Enter Portal
            </button>
        </form>

        <div class="text-center pt-2 border-t border-slate-100">
            <a href="{{ route('home') }}" class="text-xs font-semibold text-amber-800 hover:text-amber-950 hover:underline transition-colors flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Return to Customer Storefront</span>
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
