@extends('layouts.app')

@section('title', 'Sign In - AchaarG')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-3xl p-8 border border-amber-100 shadow-xl space-y-6">
        <div class="text-center space-y-1">
            <div class="w-12 h-12 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-xl mx-auto mb-2 shadow-md">
                <i class="fa-solid fa-user"></i>
            </div>
            <h1 class="font-serif-heading text-2xl font-bold text-slate-900">Welcome Back</h1>
            <p class="text-xs text-slate-500">Sign in to track orders, manage addresses and reorder your favourite jars.</p>
        </div>

        @if(session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2.5 text-xs text-emerald-800">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-2.5 text-xs text-amber-800">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5 shrink-0"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-2.5 text-xs text-rose-800">
                <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Dynamic Google Auth Alert --}}
        <div id="googleAuthAlert" class="hidden"></div>

        {{-- Google Sign-In Option --}}
        <div>
            <button type="button" id="googleLoginBtn" onclick="handleGoogleSignIn()"
                class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 text-slate-700 font-semibold text-xs shadow-sm hover:shadow transition-all duration-200 active:scale-[0.99] disabled:opacity-60 disabled:cursor-not-allowed group">
                <svg id="googleLoginBtnIcon" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                    <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.16 0 9.97 0 12c0 2.03.45 3.84 1.25 5.42l4.03-3.15z"/>
                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                </svg>
                <i id="googleLoginSpinner" class="fa-solid fa-spinner fa-spin text-amber-700 hidden text-sm"></i>
                <span id="googleLoginBtnText">Continue with Google</span>
            </button>

            {{-- Divider --}}
            <div class="relative flex items-center justify-center my-5">
                <div class="w-full border-t border-slate-200"></div>
                <span class="bg-white px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider absolute">or sign in with email</span>
            </div>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="Enter your email"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                @error('email') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="customerPassword" required placeholder="Enter your password"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pr-10 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <button type="button" id="toggleCustomerPassword"
                        onclick="toggleCustomerPwd()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-amber-700 transition-colors focus:outline-none"
                        aria-label="Toggle password visibility">
                        <i id="toggleCustomerPwdIcon" class="fa-solid fa-eye text-xs"></i>
                    </button>
                </div>
                @error('password') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                    <input type="checkbox" name="remember" class="rounded text-amber-700 focus:ring-amber-500">
                    <span>Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="font-semibold text-amber-800 hover:text-amber-950 hover:underline">
                    Forgot password?
                </a>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs shadow-md transition-all">
                Sign In
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            <span>Don't have an account yet?</span>
            <a href="{{ route('register') }}" class="font-bold text-amber-800 hover:underline ml-1">Create one here</a>
        </div>

        {{-- <div class="p-3 bg-amber-50 rounded-xl border border-amber-200/80 text-[11px] text-amber-900 text-center">
            <span class="font-bold">Demo Customer:</span> customer@pickles.com | Password: password
        </div> --}}
    </div>
</div>
@endsection

@push('scripts')
{{-- Firebase App & Auth SDK (Compat) --}}
<script src="https://www.gstatic.com/firebasejs/10.13.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.13.0/firebase-auth-compat.js"></script>
<script>
    // Firebase Configuration
    const firebaseConfig = {
        apiKey: "{{ config('services.firebase.api_key') }}",
        authDomain: "{{ config('services.firebase.auth_domain') }}",
        projectId: "{{ config('services.firebase.project_id') }}",
        storageBucket: "{{ config('services.firebase.storage_bucket') }}",
        messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
        appId: "{{ config('services.firebase.app_id') }}",
        measurementId: "{{ config('services.firebase.measurement_id') }}"
    };

    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    }

    const googleAuthProvider = new firebase.auth.GoogleAuthProvider();
    googleAuthProvider.setCustomParameters({ prompt: 'select_account' });

    function showGoogleAlert(message, type = 'error') {
        const container = document.getElementById('googleAuthAlert');
        if (!container) return;

        const isError = type === 'error';
        container.className = isError
            ? 'p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-2.5 text-xs text-rose-800'
            : 'p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-2.5 text-xs text-amber-800';

        container.innerHTML = `
            <i class="fa-solid ${isError ? 'fa-circle-exclamation text-rose-600' : 'fa-triangle-exclamation text-amber-600'} mt-0.5 shrink-0"></i>
            <div class="flex-1">${message}</div>
        `;
        container.classList.remove('hidden');
    }

    async function handleGoogleSignIn() {
        const btn = document.getElementById('googleLoginBtn');
        const btnText = document.getElementById('googleLoginBtnText');
        const icon = document.getElementById('googleLoginBtnIcon');
        const spinner = document.getElementById('googleLoginSpinner');
        const originalText = 'Continue with Google';

        try {
            if (btn) btn.disabled = true;
            if (icon) icon.classList.add('hidden');
            if (spinner) spinner.classList.remove('hidden');
            if (btnText) btnText.innerText = 'Connecting to Google...';

            const alertBox = document.getElementById('googleAuthAlert');
            if (alertBox) alertBox.classList.add('hidden');

            const result = await firebase.auth().signInWithPopup(googleAuthProvider);
            const user = result.user;

            if (btnText) btnText.innerText = 'Signing into account...';

            const idToken = await user.getIdToken();

            const response = await fetch("{{ route('auth.google.firebase') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    name: user.displayName,
                    email: user.email,
                    uid: user.uid,
                    avatar: user.photoURL,
                    id_token: idToken
                })
            });

            const data = await response.json();

            if (data.success && data.redirect_url) {
                if (btnText) btnText.innerText = 'Success! Redirecting...';
                window.location.href = data.redirect_url;
            } else {
                showGoogleAlert(data.message || 'Failed to authenticate with server. Please try again.');
                if (btn) btn.disabled = false;
                if (icon) icon.classList.remove('hidden');
                if (spinner) spinner.classList.add('hidden');
                if (btnText) btnText.innerText = originalText;
            }
        } catch (error) {
            console.error('Google Sign-In Error:', error);
            if (btn) btn.disabled = false;
            if (icon) icon.classList.remove('hidden');
            if (spinner) spinner.classList.add('hidden');
            if (btnText) btnText.innerText = originalText;

            if (error.code === 'auth/popup-closed-by-user' || error.code === 'auth/cancelled-popup-request') {
                // User closed popup, do not show an aggressive error
                return;
            } else if (error.code === 'auth/operation-not-allowed') {
                showGoogleAlert('Google Sign-in is not enabled in Firebase Console. Please enable Google provider under Authentication &gt; Sign-in method in Firebase Console.');
            } else if (error.code === 'auth/unauthorized-domain') {
                showGoogleAlert('Current domain (' + window.location.hostname + ') is not authorized in Firebase. Add it to Firebase Console &gt; Authentication &gt; Settings &gt; Authorized domains.');
            } else {
                showGoogleAlert(error.message || 'Google sign-in could not be completed.');
            }
        }
    }

    function toggleCustomerPwd() {
        const input = document.getElementById('customerPassword');
        const icon  = document.getElementById('toggleCustomerPwdIcon');
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.classList.toggle('fa-eye',       !isHidden);
        icon.classList.toggle('fa-eye-slash',  isHidden);
    }
</script>
@endpush
