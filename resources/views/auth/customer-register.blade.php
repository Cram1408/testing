@extends('layouts.app')

@section('title', 'Create Account - AchaarG')

@section('content')
<div class="max-w-lg mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl p-8 border border-amber-100 shadow-xl space-y-6">
        <div class="text-center space-y-1">
            <div class="w-12 h-12 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-xl mx-auto mb-2 shadow-md">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h1 class="font-serif-heading text-2xl font-bold text-slate-900">Create Your Account</h1>
            <p class="text-xs text-slate-500">Join our family of pickle lovers for express checkout and exclusive discounts.</p>
        </div>

        {{-- Dynamic Google Auth Alert --}}
        <div id="googleAuthAlert" class="hidden"></div>

        {{-- Google Sign-Up / Login Option --}}
        <div>
            <button type="button" id="googleRegisterBtn" onclick="handleGoogleSignIn()"
                class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 text-slate-700 font-semibold text-xs shadow-sm hover:shadow transition-all duration-200 active:scale-[0.99] disabled:opacity-60 disabled:cursor-not-allowed group">
                <svg id="googleRegisterBtnIcon" class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                    <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.16 0 9.97 0 12c0 2.03.45 3.84 1.25 5.42l4.03-3.15z"/>
                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                </svg>
                <i id="googleRegisterSpinner" class="fa-solid fa-spinner fa-spin text-amber-700 hidden text-sm"></i>
                <span id="googleRegisterBtnText">Continue with Google</span>
            </button>

            {{-- Divider --}}
            <div class="relative flex items-center justify-center my-5">
                <div class="w-full border-t border-slate-200"></div>
                <span class="bg-white px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider absolute">or register with mobile & email</span>
            </div>
        </div>

        <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="registerForm">
            @csrf

            {{-- Profile Picture Upload with Preview & Format/Size Validation --}}
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Profile Picture <span class="text-slate-400 font-normal lowercase">(optional)</span>
                </label>
                
                <div class="flex items-center gap-4 p-3.5 bg-slate-50 border border-slate-200 rounded-2xl">
                    {{-- Circular Preview Container --}}
                    <div class="relative shrink-0 w-20 h-20 rounded-full border-2 border-amber-300 shadow-sm overflow-hidden bg-amber-50 flex items-center justify-center group">
                        <img id="avatarPreview"
                             src="{{ asset('images/default-avatar.svg') }}"
                             data-default="{{ asset('images/default-avatar.svg') }}"
                             alt="Profile Preview"
                             class="w-full h-full object-cover">
                        
                        {{-- Quick tap overlay to trigger file chooser --}}
                        <button type="button" onclick="document.getElementById('avatarInput').click()" 
                            title="Choose photo"
                            class="absolute inset-0 bg-black/40 text-white flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-[10px] font-semibold">
                            <i class="fa-solid fa-camera text-sm mb-0.5"></i>
                            <span>Choose</span>
                        </button>
                    </div>

                    {{-- Upload & Status Controls --}}
                    <div class="flex-1 space-y-1.5">
                        <input type="file" name="avatar" id="avatarInput"
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               class="hidden"
                               onchange="handleAvatarSelect(this)">
                        
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button" onclick="document.getElementById('avatarInput').click()"
                                class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[10px]"></i>
                                <span id="avatarBtnText">Upload Photo</span>
                            </button>
                            <button type="button" id="removeAvatarBtn" onclick="removeAvatarSelection()"
                                class="hidden px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-semibold transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-xmark text-[10px]"></i>
                                <span>Remove</span>
                            </button>
                        </div>

                        <p class="text-[11px] text-slate-400">
                            JPG, PNG, or WEBP (Max 2MB)
                        </p>
                    </div>
                </div>

                {{-- Avatar Error Alert (Client-side & Server-side) --}}
                <div id="avatarClientError" class="hidden p-2.5 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 flex items-center gap-2 font-medium">
                    <i class="fa-solid fa-circle-exclamation shrink-0"></i>
                    <span id="avatarClientErrorMsg"></span>
                </div>
                @error('avatar')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Full Name --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name *</label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="Enter your full name"
                    pattern="^[A-Za-z\s]+$"
                    title="Only alphabetic characters and spaces allowed"
                    oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                @error('name') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address *</label>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="Enter your email"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                @error('email') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Mobile Number + OTP section --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Number *</label>

                {{-- Phone Input Row --}}
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

                {{-- Phone validation hint --}}
                <p id="phoneHint" class="text-[11px] mt-1 text-slate-400">Enter 10-digit number starting with 6, 7, 8, or 9</p>
                <div id="recaptcha-container"></div>
                @error('phone') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror

                {{-- OTP Entry Panel (hidden until OTP sent) --}}
                <div id="otpPanel" class="hidden mt-3 p-4 bg-amber-50 border border-amber-200 rounded-2xl space-y-3">
                    <p id="otpSentMsg" class="text-xs text-amber-800 font-semibold">
                        <i class="fa-solid fa-mobile-screen-button mr-1"></i>
                        OTP sent! Enter the 6-digit code below.
                    </p>

                    {{-- OTP input + verify --}}
                    <div class="flex gap-2">
                        <input type="text" id="otpInput"
                            placeholder="Enter 6-digit OTP"
                            maxlength="6"
                            inputmode="numeric"
                            class="flex-1 bg-white border border-amber-300 rounded-xl p-3 text-xs font-mono font-bold text-slate-900 tracking-[0.3em] focus:outline-none focus:ring-2 focus:ring-amber-500 text-center"
                            oninput="this.value=this.value.replace(/\D/g,'').slice(0,6)">
                        <button type="button" id="verifyOtpBtn"
                            onclick="verifyOtp()"
                            class="shrink-0 px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-all disabled:opacity-50"
                            disabled>
                            Verify
                        </button>
                    </div>

                    {{-- Feedback messages --}}
                    <div id="otpFeedback" class="hidden text-[11px] font-semibold flex items-center gap-1.5 px-3 py-2 rounded-lg"></div>

                    {{-- Expiry countdown + Resend --}}
                    <div class="flex items-center justify-between text-[11px] text-amber-700">
                        <span id="otpExpiry" class="font-medium"></span>
                        <button type="button" id="resendBtn"
                            onclick="resendOtp()"
                            class="font-bold underline disabled:opacity-40 disabled:cursor-not-allowed disabled:no-underline"
                            disabled>
                            Resend OTP
                        </button>
                    </div>
                    <p id="resendCountdown" class="text-[11px] text-slate-500 hidden text-right"></p>
                </div>

                {{-- Verified badge --}}
                <div id="verifiedBadge" class="hidden mt-2 flex items-center gap-1.5 text-emerald-700 text-xs font-bold">
                    <i class="fa-solid fa-circle-check"></i>
                    Mobile number verified!
                </div>

                {{-- Hidden field passed to server --}}
                <input type="hidden" name="phone_verified" id="phoneVerifiedFlag" value="0">
            </div>

            {{-- Passwords --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password *</label>
                    <input type="password" name="password" required placeholder="Min 6 characters"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('password') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required placeholder="Re-enter password"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <button type="submit" id="createAccountBtn"
                class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                Create My Account
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            <span>Already registered?</span>
            <a href="{{ route('login') }}" class="font-bold text-amber-800 hover:underline ml-1">Sign in here</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* ========================================================
   AchaarG — Registration OTP Flow
   ======================================================== */

const PHONE_REGEX   = /^[6-9]\d{9}$/;
const CSRF_TOKEN    = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

let otpExpiresAt    = null;   // Date object when current OTP expires
let expiryInterval  = null;   // setInterval handle for countdown
let resendInterval  = null;   // setInterval handle for resend cooldown
let phoneVerified   = false;  // true after successful OTP verification

// ── Phone Input Handler ────────────────────────────────
function onPhoneInput() {
    const phone = document.getElementById('phoneInput').value;
    const btn   = document.getElementById('sendOtpBtn');
    const hint  = document.getElementById('phoneHint');

    if (phone.length === 0) {
        hint.textContent = 'Enter 10-digit number starting with 6, 7, 8, or 9';
        hint.className   = 'text-[11px] mt-1 text-slate-400';
        btn.disabled     = true;
    } else if (phone.length < 10) {
        hint.textContent = `${10 - phone.length} more digit(s) needed`;
        hint.className   = 'text-[11px] mt-1 text-amber-600';
        btn.disabled     = true;
    } else if (!PHONE_REGEX.test(phone)) {
        hint.textContent = '❌ Invalid number. Must start with 6, 7, 8, or 9.';
        hint.className   = 'text-[11px] mt-1 text-rose-600 font-semibold';
        btn.disabled     = true;
    } else {
        hint.textContent = '✓ Valid Indian mobile number';
        hint.className   = 'text-[11px] mt-1 text-emerald-600 font-semibold';
        btn.disabled     = false;
    }

    // Reset verification if phone changes
    if (phoneVerified) {
        phoneVerified = false;
        document.getElementById('phoneVerifiedFlag').value = '0';
        document.getElementById('verifiedBadge').classList.add('hidden');
        document.getElementById('otpPanel').classList.add('hidden');
    }
}

// ── Firebase Recaptcha & Confirmation Setup ───────────────
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

// ── Send OTP ───────────────────────────────────────────
async function sendOtp() {
    const phone = document.getElementById('phoneInput').value;
    if (!PHONE_REGEX.test(phone)) return;

    setSendBtnLoading(true);
    hideFeedback();

    // 1. Try Firebase Phone Authentication first (supports Firebase Console test numbers & live SMS)
    if (typeof firebase !== 'undefined' && firebase.auth) {
        try {
            const appVerifier = getRecaptchaVerifier();
            const fullPhone = '+91' + phone;

            const confirmation = await firebase.auth().signInWithPhoneNumber(fullPhone, appVerifier);
            window.firebaseConfirmationResult = confirmation;

            // Show OTP panel
            document.getElementById('otpPanel').classList.remove('hidden');
            document.getElementById('otpInput').value = '';
            document.getElementById('otpSentMsg').innerHTML =
                '<i class="fa-solid fa-mobile-screen-button text-amber-700 mr-1"></i> OTP sent via Firebase! Enter your code below.';

            startExpiryCountdown();
            startResendCooldown(60);
            document.getElementById('otpInput').focus();
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
        const res = await fetch('{{ route("otp.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: JSON.stringify({ phone, purpose: 'registration' }),
        });

        const data = await res.json();

        if (!res.ok && res.status === 429) {
            showFeedback('warning', data.message);
            startResendCooldown(data.cooldown ?? 60);
            setSendBtnLoading(false);
            return;
        }

        if (!data.success) {
            showFeedback('error', data.message);
            setSendBtnLoading(false);
            return;
        }

        // Show OTP panel
        otpExpiresAt = new Date(data.expires_at);
        document.getElementById('otpPanel').classList.remove('hidden');
        document.getElementById('otpInput').value = '';
        document.getElementById('otpSentMsg').innerHTML =
            `<i class="fa-solid fa-mobile-screen-button text-amber-700 mr-1"></i> ${data.message}` +
            (data.dev_otp ? ` <span class="bg-amber-200 text-amber-900 px-1.5 py-0.5 rounded text-[10px] font-mono font-bold">Demo OTP: ${data.dev_otp}</span>` : '');

        startExpiryCountdown();
        startResendCooldown(data.cooldown ?? 60);
        document.getElementById('otpInput').focus();

    } catch (err) {
        showFeedback('error', 'Network error. Please try again.');
    }

    setSendBtnLoading(false);
}

async function resendOtp() {
    document.getElementById('resendBtn').disabled = true;
    window.firebaseConfirmationResult = null;
    await sendOtp();
}

// ── Verify OTP ────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const otpInput = document.getElementById('otpInput');
    if (otpInput) {
        otpInput.addEventListener('input', function () {
            document.getElementById('verifyOtpBtn').disabled = (this.value.length !== 6);
        });
    }
});

async function verifyOtp() {
    const phone = document.getElementById('phoneInput').value;
    const otp   = document.getElementById('otpInput').value;

    if (otp.length !== 6) return;

    document.getElementById('verifyOtpBtn').disabled = true;
    document.getElementById('verifyOtpBtn').textContent = '...';
    hideFeedback();

    // 1. If sent via Firebase Phone Auth, confirm with Firebase first:
    if (window.firebaseConfirmationResult) {
        try {
            const userCredential = await window.firebaseConfirmationResult.confirm(otp);
            const idToken = await userCredential.user.getIdToken();

            // Notify backend that phone is verified via Firebase
            const res = await fetch('{{ route("otp.verify") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
                body: JSON.stringify({
                    phone,
                    otp,
                    firebase_verified: true,
                    purpose: 'registration'
                }),
            });

            const data = await res.json();
            if (data.success) {
                phoneVerified = true;
                document.getElementById('phoneVerifiedFlag').value = '1';

                clearInterval(expiryInterval);
                document.getElementById('otpPanel').classList.add('hidden');
                document.getElementById('verifiedBadge').classList.remove('hidden');
                document.getElementById('verifiedBadge').innerHTML =
                    '<i class="fa-solid fa-circle-check"></i> Mobile number verified with Firebase!';
                return;
            }
        } catch (fbConfirmError) {
            console.error('Firebase OTP verify error:', fbConfirmError);
            if (fbConfirmError.code === 'auth/invalid-verification-code') {
                showFeedback('error', 'Incorrect verification code. Please check the code configured in Firebase.');
                document.getElementById('verifyOtpBtn').disabled = false;
                document.getElementById('verifyOtpBtn').textContent = 'Verify';
                return;
            }
        }
    }

    // 2. Standard backend verification
    try {
        const res = await fetch('{{ route("otp.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: JSON.stringify({ phone, otp, purpose: 'registration' }),
        });

        const data = await res.json();

        if (data.success) {
            phoneVerified = true;
            document.getElementById('phoneVerifiedFlag').value = '1';

            // Hide OTP panel, show verified badge
            clearInterval(expiryInterval);
            document.getElementById('otpPanel').classList.add('hidden');
            document.getElementById('verifiedBadge').classList.remove('hidden');
            document.getElementById('verifiedBadge').innerHTML =
                '<i class="fa-solid fa-circle-check"></i> Mobile number verified!';
        } else {
            showFeedback('error', data.message);
            document.getElementById('verifyOtpBtn').disabled = false;
            document.getElementById('verifyOtpBtn').textContent = 'Verify';
        }

    } catch (err) {
        showFeedback('error', 'Network error. Please try again.');
        document.getElementById('verifyOtpBtn').disabled = false;
        document.getElementById('verifyOtpBtn').textContent = 'Verify';
    }
}

// ── Expiry Countdown ─────────────────────────────────
function startExpiryCountdown() {
    clearInterval(expiryInterval);
    updateExpiryDisplay();
    expiryInterval = setInterval(updateExpiryDisplay, 1000);
}

function updateExpiryDisplay() {
    const el      = document.getElementById('otpExpiry');
    const now     = new Date();
    const diffSec = Math.max(0, Math.round((otpExpiresAt - now) / 1000));
    const mm      = String(Math.floor(diffSec / 60)).padStart(2, '0');
    const ss      = String(diffSec % 60).padStart(2, '0');

    if (diffSec === 0) {
        el.textContent = '⏱ OTP expired';
        el.className   = 'font-semibold text-rose-600';
        clearInterval(expiryInterval);
        document.getElementById('verifyOtpBtn').disabled = true;
        showFeedback('error', 'OTP has expired. Please resend.');
    } else {
        el.textContent = `⏱ Expires in ${mm}:${ss}`;
        el.className   = 'font-medium text-amber-700';
    }
}

// ── Resend Cooldown ───────────────────────────────────
function startResendCooldown(seconds) {
    const resendBtn      = document.getElementById('resendBtn');
    const resendCountdown = document.getElementById('resendCountdown');
    resendBtn.disabled   = true;
    resendCountdown.classList.remove('hidden');

    clearInterval(resendInterval);
    let remaining = seconds;

    function tick() {
        resendCountdown.textContent = `Resend available in ${remaining}s`;
        if (remaining <= 0) {
            clearInterval(resendInterval);
            resendBtn.disabled = false;
            resendCountdown.classList.add('hidden');
        }
        remaining--;
    }

    tick();
    resendInterval = setInterval(tick, 1000);
}

// ── UI Helpers ────────────────────────────────────────
function setSendBtnLoading(loading) {
    const btn = document.getElementById('sendOtpBtn');
    btn.disabled     = loading;
    btn.textContent  = loading ? 'Sending…' : 'Send OTP';
}

function showFeedback(type, msg) {
    const el = document.getElementById('otpFeedback');
    if (!msg) { el.classList.add('hidden'); return; }
    el.classList.remove('hidden',
        'bg-rose-50', 'text-rose-700', 'border', 'border-rose-200',
        'bg-amber-50', 'text-amber-700', 'border-amber-200',
        'bg-emerald-50', 'text-emerald-700', 'border-emerald-200');

    if (type === 'error') {
        el.className = 'text-[11px] font-semibold flex items-center gap-1.5 px-3 py-2 rounded-lg bg-rose-50 text-rose-700 border border-rose-200';
        el.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> ${msg}`;
    } else if (type === 'warning') {
        el.className = 'text-[11px] font-semibold flex items-center gap-1.5 px-3 py-2 rounded-lg bg-amber-50 text-amber-700 border border-amber-200';
        el.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> ${msg}`;
    }
    el.classList.remove('hidden');
}

function hideFeedback() {
    document.getElementById('otpFeedback').classList.add('hidden');
}
</script>

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
            ? 'p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-2.5 text-xs text-rose-800 mb-4'
            : 'p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-2.5 text-xs text-amber-800 mb-4';

        container.innerHTML = `
            <i class="fa-solid ${isError ? 'fa-circle-exclamation text-rose-600' : 'fa-triangle-exclamation text-amber-600'} mt-0.5 shrink-0"></i>
            <div class="flex-1">${message}</div>
        `;
        container.classList.remove('hidden');
    }

    async function handleGoogleSignIn() {
        const btn = document.getElementById('googleRegisterBtn');
        const btnText = document.getElementById('googleRegisterBtnText');
        const icon = document.getElementById('googleRegisterBtnIcon');
        const spinner = document.getElementById('googleRegisterSpinner');
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

    // ── Profile Picture Preview & Client-side Validation ─────
    function handleAvatarSelect(input) {
        const errorBox   = document.getElementById('avatarClientError');
        const errorMsg   = document.getElementById('avatarClientErrorMsg');
        const preview    = document.getElementById('avatarPreview');
        const removeBtn  = document.getElementById('removeAvatarBtn');
        const uploadText = document.getElementById('avatarBtnText');

        if (!errorBox || !preview) return;

        errorBox.classList.add('hidden');
        errorMsg.textContent = '';

        const file = input.files && input.files[0];
        if (!file) return;

        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        const maxSizeBytes = 2 * 1024 * 1024; // 2MB

        // 1. Format validation
        if (!allowedTypes.includes(file.type.toLowerCase())) {
            input.value = '';
            preview.src = preview.dataset.default;
            if (removeBtn) removeBtn.classList.add('hidden');
            if (uploadText) uploadText.textContent = 'Upload Photo';
            errorMsg.textContent = 'Invalid file format. Please select an image in JPG, PNG, or WEBP format.';
            errorBox.classList.remove('hidden');
            return;
        }

        // 2. Size validation
        if (file.size > maxSizeBytes) {
            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
            input.value = '';
            preview.src = preview.dataset.default;
            if (removeBtn) removeBtn.classList.add('hidden');
            if (uploadText) uploadText.textContent = 'Upload Photo';
            errorMsg.textContent = `File size too large (${fileSizeMB} MB). Maximum allowed size is 2MB.`;
            errorBox.classList.remove('hidden');
            return;
        }

        // 3. Show live preview
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            if (removeBtn) removeBtn.classList.remove('hidden');
            if (uploadText) uploadText.textContent = 'Change Photo';
        };
        reader.readAsDataURL(file);
    }

    function removeAvatarSelection() {
        const input      = document.getElementById('avatarInput');
        const preview    = document.getElementById('avatarPreview');
        const removeBtn  = document.getElementById('removeAvatarBtn');
        const uploadText = document.getElementById('avatarBtnText');
        const errorBox   = document.getElementById('avatarClientError');

        if (input) input.value = '';
        if (preview) preview.src = preview.dataset.default;
        if (removeBtn) removeBtn.classList.add('hidden');
        if (uploadText) uploadText.textContent = 'Upload Photo';
        if (errorBox) errorBox.classList.add('hidden');
    }
</script>
@endpush
