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

        <form action="{{ route('register') }}" method="POST" class="space-y-4" id="registerForm">
            @csrf

            {{-- Full Name --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name *</label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="Enter your name"
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

// ── Send OTP ───────────────────────────────────────────
async function sendOtp() {
    const phone = document.getElementById('phoneInput').value;
    if (!PHONE_REGEX.test(phone)) return;

    setSendBtnLoading(true);

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
            // Cooldown active – show countdown without opening panel again if not already open
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
        showFeedback('', ''); // clear previous feedback
        hideFeedback();

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
@endpush
