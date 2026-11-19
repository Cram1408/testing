@extends('layouts.admin')

@section('title', 'Role & Permission Matrix - Super Admin')
@section('header_title', 'Role & Permission Management')
@section('header_subtitle', 'Control granular module access rights for every administrative staff member')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-900 font-extrabold text-[10px] uppercase tracking-wider border border-amber-300">
                    <i class="fa-solid fa-user-lock mr-1 text-amber-700"></i> Access Control Policy
                </span>
                <span class="text-xs text-amber-800/70 font-medium">Live security authorization</span>
            </div>
            <h2 class="font-serif-heading text-xl font-bold text-amber-950 mt-1">Permission Matrix by Administrator</h2>
        </div>
    </div>

    {{-- Interactive Matrix Form --}}
    <form action="{{ route('admin.roles.update') }}" method="POST" class="bg-white rounded-3xl border border-amber-200/70 shadow-sm overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 border-b border-amber-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-serif-heading font-bold text-sm text-amber-950">Module Access Control Matrix</h3>
                <p class="text-xs text-amber-800/70 mt-0.5">Toggle checkmarks below to instantly grant or revoke administrative module access</p>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs shadow-md shadow-amber-900/20 transition-all flex items-center gap-2 self-start sm:self-auto">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Permission Matrix</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FFFDF9] border-b border-amber-200/60 text-amber-900/60 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="p-4 pl-6 min-w-[200px]">Administrator Staff</th>
                        @foreach($modules as $mKey => $mod)
                            <th class="p-3 text-center min-w-[110px]" title="{{ $mod['description'] }}">
                                <div class="flex flex-col items-center gap-1">
                                    <i class="fa-solid {{ $mod['icon'] }} text-amber-700 text-sm"></i>
                                    <span class="text-[9px] font-extrabold">{{ ucfirst($mKey) }}</span>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100/60">
                    @foreach($admins as $admin)
                        @php
                            $userModules = \App\Services\AdminRoleService::getPermissionsForUser($admin->id);
                            $isSuper = $admin->isSuperAdmin();
                        @endphp
                        <tr class="hover:bg-amber-50/30 transition-colors {{ $isSuper ? 'bg-amber-50/40' : '' }}">
                            <td class="p-4 pl-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-600 to-amber-800 text-white font-extrabold flex items-center justify-center text-xs shrink-0 shadow-sm border border-amber-400/30">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <h4 class="font-bold text-slate-900 text-xs truncate">{{ $admin->name }}</h4>
                                            @if($isSuper)
                                                <i class="fa-solid fa-crown text-amber-600 text-[10px]" title="Super Admin"></i>
                                            @endif
                                        </div>
                                        <p class="text-[10px] text-amber-800/70 truncate">{{ $admin->role_title }}</p>
                                    </div>
                                </div>
                            </td>

                            @foreach($modules as $mKey => $mod)
                                <td class="p-3 text-center">
                                    @if($isSuper)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-200" title="Super Admin has full access">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                    @else
                                        <input type="checkbox" name="permissions[{{ $admin->id }}][]" value="{{ $mKey }}"
                                            class="rounded border-amber-300 text-amber-600 focus:ring-amber-500 w-4 h-4 cursor-pointer"
                                            {{ in_array($mKey, $userModules) ? 'checked' : '' }}>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-[#FFFDF9] border-t border-amber-100 flex items-center justify-between text-xs text-amber-800/80">
            <span>* Super Administrators always retain full, unrevokable access across all modules.</span>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs shadow-md shadow-amber-900/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Permission Matrix</span>
            </button>
        </div>
    </form>
</div>
@endsection

