@extends('layouts.admin')

@section('title', 'Registered Customers - Admin')
@section('header_title', 'Customer Directory')
@section('header_subtitle', 'Registered buyers, order frequency, and total lifetime spend')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center gap-2">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search customer, email, city..." class="bg-white border border-slate-200 rounded-xl py-2 pl-9 pr-4 text-xs font-semibold focus:ring-2 focus:ring-amber-500 w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl transition-colors">Search</button>
            @if(request('q'))
                <a href="{{ route('admin.customers.index') }}" class="text-xs font-bold text-rose-600 hover:underline ml-2">Clear</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="p-4 pl-6">Customer</th>
                        <th class="p-4">Contact Info</th>
                        <th class="p-4">Location</th>
                        <th class="p-4">Total Orders</th>
                        <th class="p-4">Total Spend</th>
                        <th class="p-4 pr-6 text-right">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="p-4 pl-6 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-900 font-extrabold flex items-center justify-center text-xs shrink-0">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
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
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-800 font-bold text-[10px]">
                                    {{ $customer->orders_count }} Orders
                                </span>
                            </td>
                            <td class="p-4 font-extrabold text-slate-900 text-sm">
                                ₹{{ number_format($customer->orders_sum_total_amount ?? 0, 2) }}
                            </td>
                            <td class="p-4 pr-6 text-right text-slate-400 text-[11px]">
                                {{ $customer->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
