@extends('layouts.app')

@section('title', 'Contact Us - AchaarG')

@section('content')
<div class="bg-amber-50/50 py-10 border-b border-amber-200/40 text-center">
    <div class="max-w-4xl mx-auto px-4">
        <span class="text-xs font-extrabold uppercase tracking-widest text-amber-700">Get in Touch</span>
        <h1 class="font-serif-heading text-4xl font-extrabold text-slate-900 mt-2">We Would Love to Hear From You</h1>
        <p class="text-sm text-slate-600 mt-2 max-w-xl mx-auto">Have queries about bulk festive gifting, custom spice levels, or order tracking?</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
        
        <div class="bg-white p-8 rounded-3xl border border-amber-100 shadow-sm space-y-6">
            <h3 class="font-serif-heading font-bold text-xl text-slate-900">Send Us a Message</h3>
            
            <form onsubmit="event.preventDefault(); alert('Thank you for reaching out! Our team will respond shortly.'); this.reset();" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Your Name</label>
                    <input type="text" required placeholder="Aarav Sharma" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                    <input type="email" required placeholder="aarav@example.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Subject</label>
                    <input type="text" required placeholder="Custom Gifting / Inquiry" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Message</label>
                    <textarea rows="4" required placeholder="Tell us how we can help..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium focus:ring-2 focus:ring-amber-500"></textarea>
                </div>
                <button type="submit" class="w-full py-3.5 bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                    Send Inquiry
                </button>
            </form>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-amber-100 shadow-sm space-y-4">
                <h4 class="font-bold text-sm text-slate-900">Store Kitchen & Office</h4>
                <div class="space-y-3 text-xs text-slate-600">
                    <p class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot text-amber-700 mt-0.5"></i>
                        <span>Shop 12, Spice Heritage Market, Old Johari Quarter, Jaipur, Rajasthan 302001</span>
                    </p>
                    <p class="flex items-center gap-3">
                        <i class="fa-solid fa-phone text-amber-700"></i>
                        <span>+91 98765 43210 / +91 98111 22334</span>
                    </p>
                    <p class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope text-amber-700"></i>
                        <span>care@swaddesi.com / hello@pickles.com</span>
                    </p>
                    <p class="flex items-center gap-3">
                        <i class="fa-solid fa-clock text-amber-700"></i>
                        <span>Monday to Saturday: 9:30 AM – 7:30 PM IST</span>
                    </p>
                </div>
            </div>

            <div class="bg-amber-900 text-amber-100 p-6 rounded-3xl border border-amber-800 shadow-lg space-y-2">
                <h4 class="font-serif-heading font-bold text-lg text-white">Festive & Wedding Gifting</h4>
                <p class="text-xs text-amber-200/80 leading-relaxed">
                    Planning bespoke pickle jars with custom labels or royal mukhwas hampers for Diwali, Eid, or Indian wedding return gifts? Contact our team directly on WhatsApp for customized bulk pricing!
                </p>
                <div class="pt-2">
                    <a href="https://wa.me/919876543210" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-all">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>Chat on WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
