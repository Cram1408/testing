@extends('layouts.app')

@section('title', 'Sign In - Swad Desi')

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

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="aarav@example.com"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                @error('email') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                @error('password') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                    <input type="checkbox" name="remember" class="rounded text-amber-700 focus:ring-amber-500">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs shadow-md transition-all">
                Sign In
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            <span>Don't have an account yet?</span>
            <a href="{{ route('register') }}" class="font-bold text-amber-800 hover:underline ml-1">Create one here</a>
        </div>

        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200/80 text-[11px] text-amber-900 text-center">
            <span class="font-bold">Demo Customer:</span> customer@pickles.com | Password: password
        </div>
    </div>
</div>
@endsection
