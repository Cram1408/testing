@extends('layouts.app')

@section('title', 'Reset Password - AchaarG')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-3xl p-8 border border-amber-100 shadow-xl space-y-6">
        
        {{-- Header --}}
        <div class="text-center space-y-1">
            <div class="w-12 h-12 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-xl mx-auto mb-2 shadow-md">
                <i class="fa-solid fa-lock-open"></i>
            </div>
            <h1 class="font-serif-heading text-2xl font-bold text-slate-900">Choose New Password</h1>
            <p class="text-xs text-slate-500">Please enter your email and your new password below.</p>
        </div>

        @if($errors->any())
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl space-y-1 text-xs text-rose-800">
                @foreach($errors->all() as $error)
                    <p class="flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                        <span>{{ $error }}</span>
                    </p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address *</label>
                <input type="email" name="email" required value="{{ old('email', $email) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">New Password *</label>
                <div class="relative">
                    <input type="password" name="password" id="resetPassword" required placeholder="At least 6 characters"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pr-10 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <button type="button" onclick="togglePwd('resetPassword', 'resetPwdIcon')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-amber-700">
                        <i id="resetPwdIcon" class="fa-solid fa-eye text-xs"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Confirm New Password *</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="resetConfirmPassword" required placeholder="Repeat new password"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pr-10 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <button type="button" onclick="togglePwd('resetConfirmPassword', 'resetConfirmPwdIcon')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-amber-700">
                        <i id="resetConfirmPwdIcon" class="fa-solid fa-eye text-xs"></i>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs shadow-md transition-all">
                Update Password & Sign In
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            <a href="{{ route('login') }}" class="font-bold text-amber-800 hover:underline">
                ← Back to Sign In
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePwd(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.classList.toggle('fa-eye', !isHidden);
        icon.classList.toggle('fa-eye-slash', isHidden);
    }
</script>
@endpush

