@extends('layouts.app')

@section('title', 'Create Account - Swad Desi')

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

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name *</label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="Aarav Sharma"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                @error('name') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address *</label>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="aarav@example.com"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                @error('email') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Phone</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                @error('phone') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

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

            <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs shadow-md transition-all">
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
