@extends('layouts.admin')

@section('title', 'Registered Customers - Admin')
@section('header_title', 'Customer Directory')
@section('header_subtitle', 'Registered buyers, order frequency, and total lifetime spend')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center gap-2">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search customer, email, city..." class="bg-amber-50/40 border border-amber-200/80 rounded-xl py-2 pl-9 pr-4 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600 text-slate-900 placeholder:text-slate-400 w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-amber-700/60 text-xs"></i>
            </div>
            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs rounded-xl shadow-md transition-all">Search</button>
            @if(request('q'))
                <a href="{{ route('admin.customers.index') }}" class="text-xs font-bold text-rose-600 hover:underline ml-2">Clear</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-amber-200/70 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FFFDF9] border-b border-amber-200/60 text-amber-900/60 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="p-4 pl-6">Customer</th>
                        <th class="p-4">Contact Info</th>
                        <th class="p-4">Location</th>
                        <th class="p-4">Total Orders</th>
                        <th class="p-4">Total Spend</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100/60">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-amber-50/30 transition-colors">
                            <td class="p-4 pl-6 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-900 font-extrabold flex items-center justify-center text-xs shrink-0 overflow-hidden border border-amber-200">
                                    <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs">{{ $customer->name }}</h4>
                                    <span class="text-[10px] text-slate-400">ID: #{{ $customer->id }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <p class="font-semibold text-slate-800">{{ $customer->email }}</p>
                                <p class="text-[11px] text-slate-400">{{ $customer->phone ?? 'No phone' }}</p>
                            </td>
                            <td class="p-4">
                                <span class="font-semibold text-slate-700">{{ $customer->city ?? 'N/A' }}</span>
                                @if($customer->state)
                                    <span class="text-slate-400 text-[10px]">, {{ $customer->state }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-100/70 text-amber-900 border border-amber-200/60 font-bold text-[10px]">
                                    {{ $customer->orders_count }} Orders
                                </span>
                            </td>
                            <td class="p-4 font-extrabold text-amber-950 text-sm">
                                ₹{{ number_format($customer->orders_sum_total_amount ?? 0, 2) }}
                            </td>
                            <td class="p-4 text-center">
                                @if($customer->is_deactivated)
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700 border border-rose-200 font-bold text-[10px]">Deactivated</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 font-bold text-[10px]">Active</span>
                                @endif
                            </td>
                            <td class="p-4 pr-6 text-right">
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200/80 font-bold text-[11px] transition-colors inline-flex items-center gap-1">
                                    <span>Profile</span>
                                    <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-amber-100">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
