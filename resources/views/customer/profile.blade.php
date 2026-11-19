@extends('layouts.app')

@section('title', 'My Profile - AchaarG')

@section('content')
{{-- Top Banner --}}
<div class="bg-amber-50/50 py-6 border-b border-amber-200/40">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif-heading text-2xl sm:text-3xl font-extrabold text-slate-900">My Profile</h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">Manage your personal information, profile photo, and default shipping address.</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 rounded-xl bg-white hover:bg-rose-50 text-rose-600 border border-slate-200 text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    {{-- Navigation Tabs --}}
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('customer.profile') }}" 
           class="px-4 py-2 rounded-xl bg-amber-700 text-white text-xs font-bold shadow-sm flex items-center gap-2 transition-all">
            <i class="fa-solid fa-user"></i>
            <span>My Profile</span>
        </a>
        <a href="{{ route('customer.orders') }}" 
           class="px-4 py-2 rounded-xl bg-white hover:bg-amber-50 text-slate-700 border border-slate-200 text-xs font-semibold hover:text-amber-800 flex items-center gap-2 transition-all">
            <i class="fa-solid fa-box-open text-slate-400"></i>
            <span>My Orders</span>
            @if(isset($ordersCount) && $ordersCount > 0)
                <span class="px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">{{ $ordersCount }}</span>
            @endif
        </a>
    </div>

    {{-- Profile Details Only Card --}}
    <div class="max-w-2xl mx-auto bg-white rounded-3xl p-6 sm:p-8 border border-amber-100 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h2 class="font-serif-heading text-lg font-bold text-slate-900">Personal & Delivery Details</h2>
            <p class="text-xs text-slate-500 mt-0.5">Keep your details up to date for fast, seamless deliveries.</p>
        </div>

        <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5" id="customerProfileForm">
            @csrf
            @method('PUT')

            {{-- Profile Photo Upload Section --}}
            <div class="flex items-center gap-4 p-4 bg-amber-50/40 border border-amber-200/60 rounded-2xl">
                {{-- Circular Avatar with Hover Overlay --}}
                <div class="relative shrink-0 w-20 h-20 rounded-full border-2 border-amber-300 shadow-sm overflow-hidden bg-amber-50 flex items-center justify-center group">
                    <img id="profileAvatarPreview"
                         src="{{ $user->avatar_url }}"
                         data-original="{{ $user->avatar_url }}"
                         data-default="{{ asset('images/default-avatar.svg') }}"
                         alt="{{ $user->name }}"
                         class="w-full h-full object-cover">

                    <button type="button" onclick="document.getElementById('profileAvatarInput').click()"
                        title="Change photo"
                        class="absolute inset-0 bg-black/40 text-white flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-[10px] font-semibold">
                        <i class="fa-solid fa-camera text-sm mb-0.5"></i>
                        <span>Edit</span>
                    </button>
                </div>

                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-sm text-slate-900 truncate">{{ $user->name }}</h3>
                    <p class="text-xs text-slate-500 truncate mb-2">{{ $user->email }}</p>

                    <div class="flex flex-wrap items-center gap-2">
                        <input type="file" name="avatar" id="profileAvatarInput"
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               class="hidden"
                               onchange="handleProfileAvatarSelect(this)">
                        
                        <input type="hidden" name="remove_avatar" id="removeAvatarFlag" value="0">

                        <button type="button" onclick="document.getElementById('profileAvatarInput').click()"
                            class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs">
                            <i class="fa-solid fa-camera text-[11px]"></i>
                            <span id="changePhotoBtnText">Change Photo</span>
                        </button>

                        <button type="button" id="cancelAvatarBtn" onclick="cancelProfileAvatarSelect()"
                            class="hidden px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-colors">
                            Cancel
                        </button>

                        @if($user->avatar)
                            <button type="button" id="removeProfileAvatarBtn" onclick="markAvatarForRemoval()"
                                class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-semibold transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                <span>Remove</span>
                            </button>
                        @endif
                    </div>

                    <p class="text-[11px] text-slate-400 mt-1.5">
                        JPG, PNG, or WEBP (Max 2MB)
                    </p>
                </div>
            </div>

            {{-- Client-side Avatar Validation Warning/Error --}}
            <div id="profileAvatarError" class="hidden p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 flex items-center gap-2 font-medium">
                <i class="fa-solid fa-circle-exclamation shrink-0"></i>
                <span id="profileAvatarErrorMsg"></span>
            </div>

            <div id="profileAvatarPending" class="hidden p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 flex items-center gap-2 font-medium">
                <i class="fa-solid fa-circle-info shrink-0 text-amber-600"></i>
                <span id="profileAvatarPendingMsg">Photo selected. Click "Save Changes" below to update your profile picture.</span>
            </div>

            @error('avatar')
                <p class="text-rose-600 text-xs">{{ $message }}</p>
            @enderror

            @if(session('success'))
                <div class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2 text-xs text-emerald-800 font-semibold">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Full Name --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Name *</label>
                <input type="text" name="name" required value="{{ old('name', $user->name) }}"
                    pattern="^[A-Za-z\s]+$"
                    title="Only alphabetic characters and spaces are allowed"
                    placeholder="e.g. Aarav Sharma"
                    oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')"
                    class="w-full bg-slate-50 border @error('name') border-rose-400 ring-1 ring-rose-400 @else border-slate-200 @enderror rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                @error('name') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email (Read-only) --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                <input type="email" value="{{ $user->email }}" disabled
                    class="w-full bg-slate-100 border border-slate-200 rounded-xl p-3 text-xs font-semibold text-slate-500 cursor-not-allowed">
                <p class="text-[10px] text-slate-400 mt-1">Your email address is linked to your account credentials.</p>
            </div>

            {{-- Phone Number --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Phone Number (10 digits)</label>
                <div class="flex gap-2">
                    <div class="flex items-center bg-slate-100 border border-slate-200 rounded-xl px-3 text-xs font-semibold text-slate-600 shrink-0">
                        🇮🇳 +91
                    </div>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="10-digit mobile number"
                        maxlength="10"
                        inputmode="numeric"
                        pattern="^\d{10}$"
                        title="Must be exactly 10 digits"
                        oninput="this.value = this.value.replace(/\D/g, '').slice(0, 10)"
                        class="flex-1 bg-slate-50 border @error('phone') border-rose-400 ring-1 ring-rose-400 @else border-slate-200 @enderror rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                @error('phone') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Default Address --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Default Delivery Address</label>
                <textarea name="address" rows="2" placeholder="House/Flat No., Building, Street address" 
                    class="w-full bg-slate-50 border @error('address') border-rose-400 ring-1 ring-rose-400 @else border-slate-200 @enderror rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('address', $user->address) }}</textarea>
                @error('address') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- City and PIN Code --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">City</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}"
                        pattern="^[A-Za-z\s]+$"
                        title="Only alphabetic characters and spaces are allowed"
                        placeholder="e.g. Mumbai"
                        oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')"
                        class="w-full bg-slate-50 border @error('city') border-rose-400 ring-1 ring-rose-400 @else border-slate-200 @enderror rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('city') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PIN Code (6 digits)</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="^\d{6}$"
                        placeholder="6-digit PIN"
                        title="Must contain exactly 6 digits"
                        oninput="this.value = this.value.replace(/\D/g, '').slice(0, 6)"
                        class="w-full bg-slate-50 border @error('postal_code') border-rose-400 ring-1 ring-rose-400 @else border-slate-200 @enderror rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('postal_code') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- State --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">State</label>
                <input type="text" name="state" value="{{ old('state', $user->state) }}"
                    pattern="^[A-Za-z\s]+$"
                    title="Only alphabetic characters and spaces are allowed"
                    placeholder="e.g. Maharashtra"
                    oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')"
                    class="w-full bg-slate-50 border @error('state') border-rose-400 ring-1 ring-rose-400 @else border-slate-200 @enderror rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                @error('state') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Profile Changes</span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function handleProfileAvatarSelect(input) {
        const errorBox   = document.getElementById('profileAvatarError');
        const errorMsg   = document.getElementById('profileAvatarErrorMsg');
        const pendingBox = document.getElementById('profileAvatarPending');
        const pendingMsg = document.getElementById('profileAvatarPendingMsg');
        const preview    = document.getElementById('profileAvatarPreview');
        const cancelBtn  = document.getElementById('cancelAvatarBtn');
        const removeFlag = document.getElementById('removeAvatarFlag');
        const btnText    = document.getElementById('changePhotoBtnText');

        if (!preview) return;

        if (errorBox) errorBox.classList.add('hidden');
        if (removeFlag) removeFlag.value = '0';

        const file = input.files && input.files[0];
        if (!file) return;

        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        const maxSizeBytes = 2 * 1024 * 1024; // 2MB

        // 1. Format validation
        if (!allowedTypes.includes(file.type.toLowerCase())) {
            input.value = '';
            preview.src = preview.dataset.original;
            if (cancelBtn) cancelBtn.classList.add('hidden');
            if (pendingBox) pendingBox.classList.add('hidden');
            if (btnText) btnText.textContent = 'Change Photo';
            if (errorBox && errorMsg) {
                errorMsg.textContent = 'Invalid file format. Please select an image in JPG, PNG, or WEBP format.';
                errorBox.classList.remove('hidden');
            }
            return;
        }

        // 2. Size validation
        if (file.size > maxSizeBytes) {
            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
            input.value = '';
            preview.src = preview.dataset.original;
            if (cancelBtn) cancelBtn.classList.add('hidden');
            if (pendingBox) pendingBox.classList.add('hidden');
            if (btnText) btnText.textContent = 'Change Photo';
            if (errorBox && errorMsg) {
                errorMsg.textContent = `File size too large (${fileSizeMB} MB). Maximum allowed size is 2MB.`;
                errorBox.classList.remove('hidden');
            }
            return;
        }

        // 3. Show live preview
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            if (cancelBtn) cancelBtn.classList.remove('hidden');
            if (pendingBox) {
                if (pendingMsg) pendingMsg.textContent = 'New photo selected. Click "Save Profile Changes" to update.';
                pendingBox.classList.remove('hidden');
            }
            if (btnText) btnText.textContent = 'Choose Different';
        };
        reader.readAsDataURL(file);
    }

    function cancelProfileAvatarSelect() {
        const input      = document.getElementById('profileAvatarInput');
        const preview    = document.getElementById('profileAvatarPreview');
        const cancelBtn  = document.getElementById('cancelAvatarBtn');
        const pendingBox = document.getElementById('profileAvatarPending');
        const errorBox   = document.getElementById('profileAvatarError');
        const removeFlag = document.getElementById('removeAvatarFlag');
        const btnText    = document.getElementById('changePhotoBtnText');

        if (input) input.value = '';
        if (preview) preview.src = preview.dataset.original;
        if (cancelBtn) cancelBtn.classList.add('hidden');
        if (pendingBox) pendingBox.classList.add('hidden');
        if (errorBox) errorBox.classList.add('hidden');
        if (removeFlag) removeFlag.value = '0';
        if (btnText) btnText.textContent = 'Change Photo';
    }

    function markAvatarForRemoval() {
        const input      = document.getElementById('profileAvatarInput');
        const preview    = document.getElementById('profileAvatarPreview');
        const cancelBtn  = document.getElementById('cancelAvatarBtn');
        const pendingBox = document.getElementById('profileAvatarPending');
        const pendingMsg = document.getElementById('profileAvatarPendingMsg');
        const removeFlag = document.getElementById('removeAvatarFlag');
        const btnText    = document.getElementById('changePhotoBtnText');

        if (input) input.value = '';
        if (preview) preview.src = preview.dataset.default;
        if (removeFlag) removeFlag.value = '1';
        if (cancelBtn) cancelBtn.classList.remove('hidden');
        if (pendingBox) {
            if (pendingMsg) pendingMsg.textContent = 'Photo will be removed. Click "Save Profile Changes" to confirm.';
            pendingBox.classList.remove('hidden');
        }
        if (btnText) btnText.textContent = 'Upload New';
    }
</script>
@endpush

