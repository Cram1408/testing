@extends('layouts.admin')

@section('title', 'Admin Staff Management - Super Admin')
@section('header_title', 'Admin Staff Management')
@section('header_subtitle', 'Manage administrator credentials, assigned roles, and module access permissions')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-900 font-extrabold text-[10px] uppercase tracking-wider border border-amber-300">
                    <i class="fa-solid fa-shield-halved mr-1 text-amber-700"></i> Super Admin Privileges
                </span>
                <span class="text-xs text-amber-800/70 font-medium">• {{ $admins->count() }} active staff members</span>
            </div>
            <h2 class="font-serif-heading text-xl font-bold text-amber-950 mt-1">Administrator Accounts</h2>
        </div>

        <a href="{{ route('admin.admins.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>Create New Admin</span>
        </a>
    </div>

    {{-- Admins Directory Table --}}
    <div class="bg-white rounded-3xl border border-amber-200/70 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-amber-100 flex items-center justify-between">
            <h3 class="font-serif-heading font-bold text-sm text-amber-950">Admin Staff Accounts Directory</h3>
            <span class="text-xs text-amber-800/60 font-medium">Granular role-based access control</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FFFDF9] border-b border-amber-200/60 text-amber-900/60 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="p-4 pl-6">Staff Member</th>
                        <th class="p-4">Contact</th>
                        <th class="p-4">Assigned Role</th>
                        <th class="p-4">Accessible Modules</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100/60">
                    @foreach($admins as $admin)
                        @php
                            $userModules = \App\Services\AdminRoleService::getPermissionsForUser($admin->id);
                            $isPrimary = ($admin->email === 'adminram14@achaarg.com');
                        @endphp
                        <tr class="hover:bg-amber-50/30 transition-colors">
                            <td class="p-4 pl-6 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-600 to-amber-800 text-white font-extrabold flex items-center justify-center text-xs shrink-0 shadow-sm border border-amber-400/30">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <h4 class="font-bold text-slate-900 text-xs">{{ $admin->name }}</h4>
                                        @if($isPrimary)
                                            <span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-900 border border-amber-300 text-[9px] font-extrabold uppercase">Primary</span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-slate-400">ID: #{{ $admin->id }}</span>
                                </div>
                            </td>

                            <td class="p-4">
                                <p class="font-semibold text-slate-800">{{ $admin->email }}</p>
                                <p class="text-[11px] text-slate-400">{{ $admin->phone ?? 'No phone' }}</p>
                            </td>

                            <td class="p-4">
                                @if($admin->isSuperAdmin())
                                    <span class="px-2.5 py-1 rounded-full bg-gradient-to-r from-amber-600 to-amber-800 text-white font-extrabold text-[10px] shadow-xs flex items-center gap-1 w-max">
                                        <i class="fa-solid fa-crown text-[9px] text-amber-200"></i>
                                        <span>Super Administrator</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-900 border border-amber-200 font-bold text-[10px] flex items-center gap-1 w-max">
                                        <i class="fa-solid fa-shield text-[9px] text-amber-700"></i>
                                        <span>{{ $admin->role_title }}</span>
                                    </span>
                                @endif
                            </td>

                            <td class="p-4">
                                @if($admin->isSuperAdmin())
                                    <span class="text-xs font-semibold text-emerald-700 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-[11px]"></i>
                                        <span>All Modules (Full Access)</span>
                                    </span>
                                @else
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach($userModules as $mod)
                                            <span class="px-2 py-0.5 rounded-full bg-amber-100/70 text-amber-900 border border-amber-200/60 text-[10px] font-semibold">
                                                {{ ucfirst($mod) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>

                            <td class="p-4 text-center">
                                @if($admin->is_deactivated)
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700 border border-rose-200 font-bold text-[10px]">Deactivated</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 font-bold text-[10px]">Active</span>
                                @endif
                            </td>

                            <td class="p-4 pr-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.admins.edit', $admin->id) }}" class="p-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200/70 transition-colors" title="Edit Admin & Permissions">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>

                                    @if(!$isPrimary && $admin->id !== auth()->id())
                                        <form action="{{ route('admin.admins.toggle', $admin->id) }}" method="POST" onsubmit="return confirm('Change status for {{ $admin->name }}?');">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-xl {{ $admin->is_deactivated ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' : 'bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200' }} transition-colors" title="{{ $admin->is_deactivated ? 'Reactivate' : 'Deactivate' }}">
                                                <i class="fa-solid {{ $admin->is_deactivated ? 'fa-circle-check' : 'fa-ban' }} text-xs"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Permanently delete {{ $admin->name }}? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition-colors" title="Delete Admin">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Role Templates Reference Cards --}}
    <div class="space-y-4">
        <h3 class="font-serif-heading text-lg font-bold text-amber-950">Configured Role Templates</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($roleTemplates as $key => $tmpl)
                <div class="p-5 bg-white rounded-2xl border border-amber-200/80 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-xs text-slate-900">{{ $tmpl['title'] }}</h4>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $tmpl['badge'] }}">{{ $key }}</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">{{ $tmpl['description'] }}</p>
                    <div class="pt-2 border-t border-amber-100">
                        <span class="text-[10px] uppercase font-bold text-amber-800/60 block mb-1">Permitted Modules:</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach($tmpl['modules'] as $m)
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-900 border border-amber-200/60 rounded-full text-[9px] font-semibold">
                                    {{ ucfirst($m) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

