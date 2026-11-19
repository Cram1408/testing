@extends('layouts.admin')

@section('title', 'Edit Administrator #' . $admin->id . ' - Super Admin')
@section('header_title', 'Edit Administrator')
@section('header_subtitle', 'Update administrator profile, job title, and granular module permissions')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.admins.index') }}" class="text-xs font-bold text-amber-800 hover:text-amber-950 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>Back to Admin Directory</span>
        </a>
    </div>

    <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-200/80 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        {{-- Basic Information --}}
        <div class="space-y-4">
            <h3 class="font-serif-heading font-bold text-base text-amber-950 border-b border-amber-100 pb-3">Account Information</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Name *</label>
                    <input type="text" name="name" required value="{{ old('name', $admin->name) }}"
                        class="w-full bg-amber-50/40 border @error('name') border-rose-400 @else border-amber-200/80 @enderror rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                    @error('name') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address *</label>
                    <input type="email" name="email" required value="{{ old('email', $admin->email) }}"
                        class="w-full bg-amber-50/40 border @error('email') border-rose-400 @else border-amber-200/80 @enderror rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                    @error('email') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reset Password <span class="text-amber-800/60 font-normal lowercase">(leave blank to keep current)</span></label>
                    <input type="password" name="password" placeholder="New password"
                        class="w-full bg-amber-50/40 border @error('password') border-rose-400 @else border-amber-200/80 @enderror rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                    @error('password') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirm new password"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone', $admin->phone) }}" placeholder="+91 98765 00000"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                </div>
            </div>
        </div>

        {{-- Role & Permission Settings --}}
        <div class="space-y-4 pt-4 border-t border-amber-100">
            <h3 class="font-serif-heading font-bold text-base text-amber-950 border-b border-amber-100 pb-3">Role & Access Control</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Account Authority Level *</label>
                    <select name="role_type" id="roleTypeSelect" onchange="toggleRoleSettings(this.value)" class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-bold text-slate-900 focus:ring-2 focus:ring-amber-500">
                        <option value="admin" {{ $admin->role === 'admin' ? 'selected' : '' }}>Administrator (Controlled by Permissions)</option>
                        <option value="super_admin" {{ $admin->isSuperAdmin() ? 'selected' : '' }}>Super Administrator (Full Unrestricted Access)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Job Title / Department *</label>
                    <input type="text" name="role_title" id="roleTitleInput" required value="{{ old('role_title', $roleTitle) }}"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                </div>
            </div>

            {{-- Preset Role Template Quick Buttons --}}
            <div id="rolePresetsContainer" class="p-4 bg-amber-50/40 border border-amber-200/70 rounded-2xl space-y-2 {{ $admin->isSuperAdmin() ? 'hidden' : '' }}">
                <span class="text-[10px] uppercase font-bold text-amber-800/60 block">Apply Role Preset:</span>
                <div class="flex flex-wrap gap-2">
                    @foreach($roleTemplates as $key => $tmpl)
                        @if($key !== 'super_admin')
                            <button type="button" onclick="applyPreset('{{ $tmpl['title'] }}', {{ json_encode($tmpl['modules']) }})"
                                class="px-3 py-1.5 bg-white hover:bg-amber-100 text-amber-950 hover:text-amber-900 border border-amber-200 rounded-xl text-xs font-semibold shadow-xs transition-all">
                                {{ $tmpl['title'] }}
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Granular Permissions Checkboxes --}}
            <div id="permissionsBox" class="space-y-3 pt-2 {{ $admin->isSuperAdmin() ? 'hidden' : '' }}">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Assigned Module Permissions</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($modules as $key => $mod)
                        <label class="p-3 bg-amber-50/30 hover:bg-amber-50/60 border border-amber-200/70 rounded-xl flex items-start gap-3 cursor-pointer transition-colors">
                            <input type="checkbox" name="modules[]" value="{{ $key }}" class="module-check mt-1 rounded border-amber-300 text-amber-600 focus:ring-amber-500"
                                {{ in_array($key, old('modules', $userModules)) ? 'checked' : '' }}>
                            <div class="text-xs">
                                <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                    <i class="fa-solid {{ $mod['icon'] }} text-amber-700 text-[11px]"></i>
                                    <span>{{ $mod['name'] }}</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $mod['description'] }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-amber-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.admins.index') }}" class="px-5 py-2.5 rounded-xl border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-900 text-xs font-bold transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white text-xs font-bold shadow-md shadow-amber-900/20 transition-all">
                Update Administrator
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function toggleRoleSettings(val) {
        const presets = document.getElementById('rolePresetsContainer');
        const box = document.getElementById('permissionsBox');
        const titleInput = document.getElementById('roleTitleInput');

        if (val === 'super_admin') {
            presets.classList.add('hidden');
            box.classList.add('hidden');
            if (titleInput) titleInput.value = 'Super Administrator';
        } else {
            presets.classList.remove('hidden');
            box.classList.remove('hidden');
            if (titleInput && titleInput.value === 'Super Administrator') {
                titleInput.value = 'Store Manager';
            }
        }
    }

    function applyPreset(title, modules) {
        document.getElementById('roleTitleInput').value = title;
        document.querySelectorAll('.module-check').forEach(cb => {
            cb.checked = modules.includes(cb.value);
        });
    }
</script>
@endpush

