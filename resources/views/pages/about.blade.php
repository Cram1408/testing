@extends('layouts.app')

@section('title', 'Our Recipe Heritage - Swad Desi')

@section('content')
<div class="bg-amber-50/50 py-10 border-b border-amber-200/40 text-center">
    <div class="max-w-4xl mx-auto px-4">
        <span class="text-xs font-extrabold uppercase tracking-widest text-amber-700">Artisanal Tradition</span>
        <h1 class="font-serif-heading text-4xl font-extrabold text-slate-900 mt-2">The Swad Desi Story</h1>
        <p class="text-sm text-slate-600 mt-2 max-w-xl mx-auto">Reclaiming authentic Indian kitchen heritage, sun-matured pickles, and natural digestive mukhwas.</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="space-y-4">
            <span class="text-xs font-bold uppercase text-amber-800 tracking-wider">Rooted In Tradition</span>
            <h2 class="font-serif-heading text-3xl font-bold text-slate-900 leading-tight">Handcrafted Under The Indian Sun</h2>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                Generations of Indian grandmothers knew that real pickle cannot be rushed in industrial vats. It requires natural heat, patience, stone-ground fenugreek seeds, yellow mustard, and genuine cold-pressed mustard oil.
            </p>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                Swad Desi was born with a mission to preserve these heirloom recipes before they vanish. We never use artificial preservatives, chemical acetic acid, or synthetic food colors.
            </p>
        </div>
        <div class="rounded-3xl overflow-hidden shadow-xl border-4 border-white bg-amber-50">
            <img src="{{ asset('images/banners/hero_banner.png') }}" alt="Our heritage" class="w-full h-80 object-cover">
        </div>
    </div>

    <!-- Pillars -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="p-6 bg-white rounded-3xl border border-amber-100 shadow-sm text-center">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 mx-auto flex items-center justify-center text-xl mb-3">
                <i class="fa-solid fa-seedling"></i>
            </div>
            <h3 class="font-bold text-sm text-slate-900">Farm Fresh Sourcing</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">We source plump Rajapuri & Ramkela mangoes, Banarasi red chillies, and organic ginger directly from traditional farmers.</p>
        </div>

        <div class="p-6 bg-white rounded-3xl border border-amber-100 shadow-sm text-center">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 mx-auto flex items-center justify-center text-xl mb-3">
                <i class="fa-solid fa-oil-can"></i>
            </div>
            <h3 class="font-bold text-sm text-slate-900">Cold-Pressed Mustard Oil</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Using pure Kachi Ghani mustard oil that acts as nature's own antibacterial preservative and lends signature sharp pungent aroma.</p>
        </div>

        <div class="p-6 bg-white rounded-3xl border border-amber-100 shadow-sm text-center">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 mx-auto flex items-center justify-center text-xl mb-3">
                <i class="fa-solid fa-shield-heart"></i>
            </div>
            <h3 class="font-bold text-sm text-slate-900">Glass Jar Hygiene</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Every batch is packed in sterilized, premium food-grade glass jars with airtight tamper-evident induction seals.</p>
        </div>
    </div>
</div>
@endsection
