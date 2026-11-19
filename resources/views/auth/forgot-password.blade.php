@extends('layouts.app')

@section('title', 'Forgot Password - AchaarG')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-3xl p-8 border border-amber-100 shadow-xl space-y-6">
        
        {{-- Header --}}
        <div class="text-center space-y-1">
            <div class="w-12 h-12 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-xl mx-auto mb-2 shadow-md">
                <i class="fa-solid fa-key"></i>
            </div>
            <h1 class="font-serif-heading text-2xl font-bold text-slate-900">Forgot Password?</h1>
            <p class="text-xs text-slate-500">No worries! Reset your password securely using your registered mobile OTP or email.</p>
        </div>

        {{-- Alerts --}}
        @if(session('status'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl space-y-2 text-xs text-emerald-800">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                    <span class="font-semibold">{{ session('status') }}</span>
                </div>
                @if(session('reset_link'))
                    <div class="p-2.5 bg-white/80 rounded-lg border border-emerald-200 text-[11px] break-all">
                        <span class="text-slate-500 font-medium">Direct Reset Link (Local Dev):</span><br>
                        <a href="{{ session('reset_link') }}" class="font-bold text-amber-800 hover:underline">
                            Click here to reset your password →
                        </a>
                    </div>
                @endif
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-2.5 text-xs text-rose-800">
                <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Tabs: Mobile OTP vs Email --}}
        <div class="flex rounded-xl bg-slate-100 p-1 text-xs font-semibold">
            <button type="button" id="tabOtpBtn" onclick="switchTab('otp')"
                class="flex-1 py-2 rounded-lg text-center transition-all bg-white text-slate-900 shadow-sm">
                <i class="fa-solid fa-mobile-screen-button mr-1 text-amber-700"></i> Mobile OTP
            </button>
            <button type="button" id="tabEmailBtn" onclick="switchTab('email')"
                class="flex-1 py-2 rounded-lg text-center transition-all text-slate-500 hover:text-slate-800">
                <i class="fa-solid fa-envelope mr-1 text-slate-400"></i> Email Link
            </button>
        </div>

        {{-- Tab 1: Reset via Mobile OTP --}}
        <div id="tabOtpContent" class="space-y-4">
            <form action="{{ route('password.otp.reset') }}" method="POST" id="otpResetForm" class="space-y-4">
                @csrf

                {{-- Phone Input Row --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Registered Mobile Number *</label>
                    <div class="flex gap-2">
                        <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl px-3 text-xs font-semibold text-slate-600 shrink-0">
                            🇮🇳 +91
                        </div>
                        <input type="tel" name="phone" id="phoneInput"
                            value="{{ old('phone') }}"
                            placeholder="10-digit mobile number"
                            maxlength="10"
                            inputmode="numeric"
                            class="flex-1 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500"
                            oninput="this.value=this.value.replace(/\D/g,'').slice(0,10); onPhoneInput()">
                        <button type="button" id="sendOtpBtn"
                            onclick="sendOtp()"
                            class="shrink-0 px-4 py-2 rounded-xl bg-amber-700 hover:bg-amber-800 text-white text-xs font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            Send OTP
                        </button>
                    </div>
                    <p id="phoneHint" class="text-[11px] mt-1 text-slate-400">Enter your 10-digit mobile number registered with AchaarG</p>
                    <div id="recaptcha-container"></div>
                    @error('phone') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- OTP Panel (visible once OTP is requested) --}}
                <div id="otpPanel" class="hidden p-4 bg-amber-50 border border-amber-200 rounded-2xl space-y-3">
                    <p id="otpSentMsg" class="text-xs text-amber-900 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-mobile-screen-button text-amber-700"></i>
                        <span>OTP sent! Enter the 6-digit code below.</span>
                    </p>

                    <div class="flex gap-2">
                        <input type="text" id="otpInput"
                            placeholder="6-digit OTP"
                            maxlength="6"
                            inputmode="numeric"
                            class="flex-1 bg-white border border-amber-300 rounded-xl p-2.5 text-xs font-mono font-bold text-slate-900 tracking-[0.3em] focus:outline-none focus:ring-2 focus:ring-amber-500 text-center"
                            oninput="this.value=this.value.replace(/\D/g,'').slice(0,6)">
                        <button type="button" id="verifyOtpBtn"
                            onclick="verifyOtp()"
                            class="shrink-0 px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-all disabled:opacity-50"
                            disabled>
                            Verify
                        </button>
                    </div>

                    <div id="otpFeedback" class="hidden text-[11px] font-semibold flex items-center gap-1.5 px-3 py-2 rounded-lg"></div>

                    <div class="flex items-center justify-between text-[11px] text-amber-800">
                        <span id="otpExpiry" class="font-medium"></span>
                        <button type="button" id="resendBtn"
                            onclick="resendOtp()"
                            class="font-bold underline disabled:opacity-40 disabled:cursor-not-allowed disabled:no-underline"
                            disabled>
                            Resend OTP
                        </button>
                    </div>
                </div>

                {{-- New Password Fields (Unlocked only after OTP verification) --}}
                <div id="newPasswordSection" class="opacity-50 pointer-events-none space-y-4 pt-2 border-t border-slate-100 transition-opacity">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">New Password *</label>
                        <div class="relative">
                            <input type="password" name="password" id="newPassword" required placeholder="At least 6 characters"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pr-10 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <button type="button" onclick="togglePwdVisibility('newPassword', 'newPwdIcon')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-amber-700">
                                <i id="newPwdIcon" class="fa-solid fa-eye text-xs"></i>
                            </button>
                        </div>
                        @error('password') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Confirm New Password *</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="confirmPassword" required placeholder="Repeat new password"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pr-10 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <button type="button" onclick="togglePwdVisibility('confirmPassword', 'confirmPwdIcon')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-amber-700">
                                <i id="confirmPwdIcon" class="fa-solid fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="submitResetBtn"
                        class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs shadow-md transition-all">
                        Reset Password & Sign In
                    </button>
                </div>
            </form>
        </div>

        {{-- Tab 2: Reset via Email Link --}}
        <div id="tabEmailContent" class="hidden space-y-4">
            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Your Email Address</label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="Enter your registered email"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('email') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <p class="text-[11px] text-slate-500 leading-relaxed">
                    We will send a password reset link to your email. Click the link to choose a new password.
                </p>

                <button type="submit"
                    class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs shadow-md transition-all">
                    Send Password Reset Link
                </button>
            </form>
        </div>

        {{-- Footer links --}}
        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            <span>Remembered your password?</span>
            <a href="{{ route('login') }}" class="font-bold text-amber-800 hover:underline ml-1">Sign in here</a>
        </div>
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

    if (typeof firebase !== 'undefined' && !firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    }

    let firebaseConfirmationResult = null;
    let recaptchaVerifier = null;

    function getRecaptchaVerifier() {
        if (!window.recaptchaVerifier && typeof firebase !== 'undefined' && firebase.auth) {
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                'size': 'invisible',
                'callback': function(response) {
                    // reCAPTCHA solved
                }
            });
        }
        return window.recaptchaVerifier;
    }

    // Tab Switching
    function switchTab(tab) {
        const tabOtpBtn = document.getElementById('tabOtpBtn');
        const tabEmailBtn = document.getElementById('tabEmailBtn');
        const tabOtpContent = document.getElementById('tabOtpContent');
        const tabEmailContent = document.getElementById('tabEmailContent');

        if (tab === 'otp') {
            tabOtpBtn.className = 'flex-1 py-2 rounded-lg text-center transition-all bg-white text-slate-900 shadow-sm';
            tabEmailBtn.className = 'flex-1 py-2 rounded-lg text-center transition-all text-slate-500 hover:text-slate-800';
            tabOtpContent.classList.remove('hidden');
            tabEmailContent.classList.add('hidden');
        } else {
            tabEmailBtn.className = 'flex-1 py-2 rounded-lg text-center transition-all bg-white text-slate-900 shadow-sm';
            tabOtpBtn.className = 'flex-1 py-2 rounded-lg text-center transition-all text-slate-500 hover:text-slate-800';
            tabEmailContent.classList.remove('hidden');
            tabOtpContent.classList.add('hidden');
        }
    }

    // Password Visibility Toggle
    function togglePwdVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.classList.toggle('fa-eye', !isHidden);
        icon.classList.toggle('fa-eye-slash', isHidden);
    }

    // Mobile OTP Flow
    let phoneVerified = false;
    let resendInterval = null;
    let expiryInterval = null;
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function isValidIndianPhone(num) {
        return /^[6-9]\d{9}$/.test(num);
    }

    function onPhoneInput() {
        if (phoneVerified) return;
        const phone = document.getElementById('phoneInput').value.trim();
        const sendBtn = document.getElementById('sendOtpBtn');
        const hint = document.getElementById('phoneHint');

        if (phone.length === 10) {
            if (isValidIndianPhone(phone)) {
                sendBtn.disabled = false;
                hint.textContent = 'Valid 10-digit number. Click "Send OTP".';
                hint.className = 'text-[11px] mt-1 text-emerald-600 font-medium';
            } else {
                sendBtn.disabled = true;
                hint.textContent = 'Must start with 6, 7, 8, or 9.';
                hint.className = 'text-[11px] mt-1 text-rose-500 font-medium';
            }
        } else {
            sendBtn.disabled = true;
            hint.textContent = `Enter 10-digit number (${phone.length}/10 digits)`;
            hint.className = 'text-[11px] mt-1 text-slate-400';
        }
    }

    document.getElementById('otpInput').addEventListener('input', function() {
        document.getElementById('verifyOtpBtn').disabled = (this.value.trim().length !== 6);
    });

    async function sendOtp() {
        const phone = document.getElementById('phoneInput').value.trim();
        if (!isValidIndianPhone(phone)) return;

        setSendBtnLoading(true);
        hideFeedback();

        // 1. Try Firebase Phone Authentication first (supports Firebase Console test numbers & live SMS)
        if (typeof firebase !== 'undefined' && firebase.auth) {
            try {
                const appVerifier = getRecaptchaVerifier();
                const fullPhone = '+91' + phone;

                const confirmation = await firebase.auth().signInWithPhoneNumber(fullPhone, appVerifier);
                window.firebaseConfirmationResult = confirmation;

                document.getElementById('otpPanel').classList.remove('hidden');
                document.getElementById('otpInput').value = '';
                document.getElementById('otpSentMsg').innerHTML =
                    '<i class="fa-solid fa-mobile-screen-button text-amber-700 mr-1"></i> OTP sent via Firebase! Enter your code below.';
                document.getElementById('otpInput').focus();

                startCooldownTimer(60);
                startExpiryTimer(new Date(Date.now() + 10 * 60 * 1000).toISOString());
                setSendBtnLoading(false);
                return;
            } catch (fbErr) {
                console.warn('Firebase Phone Auth attempt:', fbErr);
                if (fbErr.code === 'auth/operation-not-allowed') {
                    showFeedback('warning', 'Notice: Phone Provider is not enabled in Firebase Console. Using local system.');
                }
            }
        }

        // 2. Fallback to local Laravel OTP endpoint
        try {
            const res = await fetch("{{ route('otp.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone: phone, purpose: 'password_reset' })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                document.getElementById('otpPanel').classList.remove('hidden');
                document.getElementById('otpSentMsg').innerHTML = `<i class="fa-solid fa-mobile-screen-button text-amber-700"></i> ${data.message}` + (data.dev_otp ? ` <span class="bg-amber-200 text-amber-900 px-1.5 py-0.5 rounded text-[10px] font-mono font-bold">Demo OTP: ${data.dev_otp}</span>` : '');
                document.getElementById('otpInput').focus();

                startCooldownTimer(data.cooldown || 60);
                startExpiryTimer(data.expires_at);
            } else {
                showFeedback('error', data.message || 'Failed to send OTP.');
            }
        } catch (err) {
            showFeedback('error', 'Network error. Please try again.');
        } finally {
            setSendBtnLoading(false);
        }
    }

    async function resendOtp() {
        const phone = document.getElementById('phoneInput').value.trim();
        const resendBtn = document.getElementById('resendBtn');
        resendBtn.disabled = true;
        window.firebaseConfirmationResult = null;
        await sendOtp();
    }

    async function verifyOtp() {
        const phone = document.getElementById('phoneInput').value.trim();
        const otp = document.getElementById('otpInput').value.trim();
        const verifyBtn = document.getElementById('verifyOtpBtn');

        if (otp.length !== 6) return;

        verifyBtn.disabled = true;
        verifyBtn.textContent = 'Verifying…';
        hideFeedback();

        // 1. If sent via Firebase Phone Auth, confirm with Firebase first:
        if (window.firebaseConfirmationResult) {
            try {
                const userCredential = await window.firebaseConfirmationResult.confirm(otp);
                const idToken = await userCredential.user.getIdToken();

                // Notify backend that phone is verified via Firebase
                const res = await fetch("{{ route('otp.verify') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        phone: phone,
                        otp: otp,
                        firebase_verified: true,
                        purpose: 'password_reset'
                    })
                });

                const data = await res.json();
                if (data.success) {
                    phoneVerified = true;
                    clearInterval(resendInterval);
                    clearInterval(expiryInterval);

                    document.getElementById('phoneInput').readOnly = true;
                    document.getElementById('sendOtpBtn').classList.add('hidden');
                    document.getElementById('phoneHint').innerHTML = '<span class="text-emerald-700 font-bold"><i class="fa-solid fa-circle-check"></i> Mobile number verified with Firebase! Please choose your new password.</span>';
                    document.getElementById('otpPanel').classList.add('hidden');

                    const pwdSection = document.getElementById('newPasswordSection');
                    pwdSection.classList.remove('opacity-50', 'pointer-events-none');
                    document.getElementById('newPassword').focus();
                    return;
                }
            } catch (fbConfirmError) {
                console.error('Firebase OTP verify error:', fbConfirmError);
                if (fbConfirmError.code === 'auth/invalid-verification-code') {
                    showFeedback('error', 'Incorrect verification code. Please check the code configured in Firebase.');
                    verifyBtn.disabled = false;
                    verifyBtn.textContent = 'Verify';
                    return;
                }
            }
        }

        // 2. Standard backend verification
        try {
            const res = await fetch("{{ route('otp.verify') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone: phone, otp: otp, purpose: 'password_reset' })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                phoneVerified = true;
                clearInterval(resendInterval);
                clearInterval(expiryInterval);

                // Lock phone input & show verified status
                document.getElementById('phoneInput').readOnly = true;
                document.getElementById('sendOtpBtn').classList.add('hidden');
                document.getElementById('phoneHint').innerHTML = '<span class="text-emerald-700 font-bold"><i class="fa-solid fa-circle-check"></i> Mobile number verified! Please choose your new password.</span>';

                // Hide OTP panel
                document.getElementById('otpPanel').classList.add('hidden');

                // Unlock password inputs
                const pwdSection = document.getElementById('newPasswordSection');
                pwdSection.classList.remove('opacity-50', 'pointer-events-none');
                document.getElementById('newPassword').focus();
            } else {
                showFeedback('error', data.message || 'Incorrect OTP.');
                verifyBtn.disabled = false;
                verifyBtn.textContent = 'Verify';
            }
        } catch (err) {
            showFeedback('error', 'Network error while verifying OTP.');
            verifyBtn.disabled = false;
            verifyBtn.textContent = 'Verify';
        }
    }

    function startCooldownTimer(seconds) {
        clearInterval(resendInterval);
        const resendBtn = document.getElementById('resendBtn');
        let remaining = seconds;

        function tick() {
            if (remaining <= 0) {
                clearInterval(resendInterval);
                resendBtn.disabled = false;
                resendBtn.textContent = 'Resend OTP';
            } else {
                resendBtn.disabled = true;
                resendBtn.textContent = `Resend in ${remaining}s`;
            }
            remaining--;
        }
        tick();
        resendInterval = setInterval(tick, 1000);
    }

    function startExpiryTimer(expiresAtIso) {
        clearInterval(expiryInterval);
        const expiryEl = document.getElementById('otpExpiry');
        const expiresAt = new Date(expiresAtIso).getTime();

        function tick() {
            const diff = Math.max(0, Math.floor((expiresAt - Date.now()) / 1000));
            const m = Math.floor(diff / 60);
            const s = diff % 60;
            expiryEl.textContent = `Expires in ${m}:${s.toString().padStart(2, '0')}`;
            if (diff <= 0) {
                clearInterval(expiryInterval);
                expiryEl.textContent = 'OTP has expired.';
            }
        }
        tick();
        expiryInterval = setInterval(tick, 1000);
    }

    function setSendBtnLoading(loading) {
        const btn = document.getElementById('sendOtpBtn');
        btn.disabled = loading;
        btn.textContent = loading ? 'Sending…' : 'Send OTP';
    }

    function showFeedback(type, msg) {
        const el = document.getElementById('otpFeedback');
        if (!msg) { el.classList.add('hidden'); return; }
        el.className = type === 'error'
            ? 'text-[11px] font-semibold flex items-center gap-1.5 px-3 py-2 rounded-lg bg-rose-50 text-rose-700 border border-rose-200'
            : 'text-[11px] font-semibold flex items-center gap-1.5 px-3 py-2 rounded-lg bg-amber-50 text-amber-700 border border-amber-200';
        el.innerHTML = `<i class="fa-solid ${type === 'error' ? 'fa-circle-xmark' : 'fa-triangle-exclamation'}"></i> ${msg}`;
        el.classList.remove('hidden');
    }

    function hideFeedback() {
        document.getElementById('otpFeedback').classList.add('hidden');
    }
</script>
@endpush

