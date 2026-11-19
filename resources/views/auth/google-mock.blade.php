@extends('layouts.app')

@section('title', 'Redirecting to Login - AchaarG')

@section('content')
<div class="max-w-md mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-3xl p-8 border border-amber-100 shadow-xl space-y-4">
        <i class="fa-solid fa-spinner fa-spin text-2xl text-amber-700"></i>
        <h2 class="text-sm font-bold text-slate-800">Redirecting to Sign In...</h2>
        <p class="text-xs text-slate-500">Please wait while we redirect you to the login page.</p>
    </div>
</div>
<script>
    window.location.href = "{{ route('login') }}";
</script>
@endsection
