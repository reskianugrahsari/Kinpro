<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-[#0F2747] dark:text-slate-100 tracking-tight">
                {{ __('Profil Pengguna') }}
            </h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Kelola informasi akun dan pengaturan profil Anda</p>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-6">

        <!-- Header Banner Profil (Sesuai Desain Gradient & Plus Grid) -->
        <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gradient-to-r from-[#5B73E8] via-[#6355C8] to-[#7048A0] text-white p-6 sm:p-7 shadow-sm">
            <!-- Background Faint Plus Pattern on Right Half -->
            <div class="absolute inset-y-0 right-0 w-1/2 pointer-events-none opacity-20 flex justify-end">
                <svg class="h-full w-full object-cover" width="300" height="160" fill="none" viewBox="0 0 300 160">
                    <defs>
                        <pattern id="profile-plus-grid" width="22" height="22" patternUnits="userSpaceOnUse">
                            <path d="M11 6v10M6 11h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#profile-plus-grid)" />
                </svg>
            </div>

            <!-- Content Banner -->
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center gap-5">
                @php
                    $uPhoto = $pegawai?->foto_url ?? $user->pegawai?->foto_url;
                    $initials = '';
                    $parts = preg_split('/[\s,\.]+/', trim($user->name));
                    foreach ($parts as $p) {
                        if (!empty($p) && ctype_alpha($p[0])) {
                            $initials .= strtoupper($p[0]);
                            if (strlen($initials) >= 2) break;
                        }
                    }
                    if (empty($initials)) $initials = strtoupper(substr($user->name, 0, 2));
                @endphp

                <!-- Avatar -->
                @if($uPhoto)
                    <img src="{{ $uPhoto }}" alt="{{ $user->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border-2 border-white/40 shadow-md shrink-0" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/20 backdrop-blur-md text-white font-extrabold text-2xl border-2 border-white/40 shadow-md hidden items-center justify-center shrink-0 tracking-wider">
                        {{ $initials }}
                    </div>
                @else
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/20 backdrop-blur-md text-white font-extrabold text-2xl border-2 border-white/40 shadow-md flex items-center justify-center shrink-0 tracking-wider">
                        {{ $initials }}
                    </div>
                @endif

                <!-- User Details -->
                <div class="space-y-1.5">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight leading-tight">
                        {{ $user->name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-white/90 font-medium">
                        {{ $pegawai?->jabatan ?? ($user->role === 'admin' ? 'Administrator Sistem' : 'Pegawai Balai') }}
                    </p>

                    <!-- Two Pill Badges -->
                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        <!-- ID / NIP Badge -->
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/25 text-white text-[11px] font-semibold">
                            <span class="material-symbols-outlined text-sm">badge</span>
                            <span class="font-mono">{{ $pegawai?->nip ?? '-' }}</span>
                        </div>

                        <!-- Role Badge -->
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/25 text-white text-[11px] font-semibold">
                            <span class="material-symbols-outlined text-sm">person</span>
                            <span>{{ $user->role === 'admin' ? 'Administrator' : 'Pegawai' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid 2 Kolom Sejajar: Kolom Kiri (Info & Password) & Kolom Kanan (Edit Profil & Foto) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Kolom Kiri: Informasi Akun + Perbarui Kata Sandi (5 Kolom) -->
            <div class="lg:col-span-5 space-y-6">

                <!-- Kartu 1: Informasi Akun -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs p-6">
                    <!-- Header -->
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="w-9 h-9 rounded-full bg-[#5850EC] text-white flex items-center justify-center shrink-0 shadow-xs">
                            <span class="material-symbols-outlined text-lg">person</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Informasi Akun</h3>
                    </div>

                    <!-- Field List -->
                    <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        <div class="py-3 flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">NIP</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100 font-mono">{{ $pegawai?->nip ?? '-' }}</span>
                        </div>
                        <div class="py-3 flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Username</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ $pegawai?->username ?? $user->email }}</span>
                        </div>
                        <div class="py-3 flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Jabatan</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100 text-right max-w-[60%]">{{ $pegawai?->jabatan ?? '-' }}</span>
                        </div>
                        <div class="py-3 flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Email</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ $user->email }}</span>
                        </div>
                        <div class="py-3 flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">No. HP</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ $pegawai?->no_hp ?? '-' }}</span>
                        </div>
                        <div class="py-3 flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Bergabung</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100">
                                {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kartu 2: Perbarui Kata Sandi (Compact & Sejajar dengan Foto Profil) -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs p-6">
                    <!-- Header -->
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="w-9 h-9 rounded-full bg-[#5850EC] text-white flex items-center justify-center shrink-0 shadow-xs">
                            <span class="material-symbols-outlined text-lg">lock</span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Perbarui Kata Sandi</h3>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500">Kelola keamanan sandi akun</p>
                        </div>
                    </div>

                    <!-- Form Password -->
                    <form method="post" action="{{ route('password.update') }}" class="mt-4 space-y-3">
                        @csrf
                        @method('put')

                        <div>
                            <label for="current_password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi Saat Ini</label>
                            <input id="current_password" name="current_password" type="password" class="w-full border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-xl px-3.5 py-2 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#5850EC] focus:border-[#5850EC] outline-none shadow-xs transition" placeholder="••••••••" autocomplete="current-password" />
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi Baru</label>
                            <input id="password" name="password" type="password" class="w-full border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-xl px-3.5 py-2 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#5850EC] focus:border-[#5850EC] outline-none shadow-xs transition" placeholder="••••••••" autocomplete="new-password" />
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Kata Sandi</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-xl px-3.5 py-2 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#5850EC] focus:border-[#5850EC] outline-none shadow-xs transition" placeholder="••••••••" autocomplete="new-password" />
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
                        </div>

                        <div class="pt-1.5">
                            <button type="submit" class="w-full py-2.5 bg-[#5850EC] hover:bg-[#4338CA] text-white text-xs font-bold rounded-xl flex items-center justify-center gap-2 shadow-xs transition cursor-pointer">
                                <span class="material-symbols-outlined text-base">lock_reset</span>
                                <span>Perbarui Password</span>
                            </button>
                        </div>

                        @if (session('status') === 'password-updated')
                            <div
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 3000)"
                                class="text-xs font-bold text-emerald-700 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-2 rounded-xl border border-emerald-200 dark:border-emerald-800 text-center"
                            >
                                Password berhasil diperbarui!
                            </div>
                        @endif
                    </form>
                </div>

            </div>

            <!-- Kolom Kanan: Edit Profil (7 Kolom) -->
            <div class="lg:col-span-7 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs p-6">
                <!-- Header -->
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-9 h-9 rounded-full bg-[#5850EC] text-white flex items-center justify-center shrink-0 shadow-xs">
                        <span class="material-symbols-outlined text-lg">edit</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Edit Profil</h3>
                </div>

                <!-- Form -->
                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                    @csrf
                    @method('patch')

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap</label>
                        <input id="name" name="name" type="text" class="w-full border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#5850EC] focus:border-[#5850EC] outline-none shadow-xs transition" value="{{ old('name', $user->name) }}" required autocomplete="name" />
                        <x-input-error class="mt-1" :messages="$errors->get('name')" />
                    </div>

                    <!-- Email & No. HP (2 Kolom) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Email</label>
                            <input id="email" name="email" type="email" class="w-full border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#5850EC] focus:border-[#5850EC] outline-none shadow-xs transition" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                            <x-input-error class="mt-1" :messages="$errors->get('email')" />
                        </div>
                        <div>
                            <label for="no_hp" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">No. HP</label>
                            <input id="no_hp" name="no_hp" type="text" class="w-full border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#5850EC] focus:border-[#5850EC] outline-none shadow-xs transition" value="{{ old('no_hp', $pegawai?->no_hp) }}" placeholder="08xxxxxxxxxx" />
                            <x-input-error class="mt-1" :messages="$errors->get('no_hp')" />
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label for="alamat" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3" class="w-full border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-[#5850EC] focus:border-[#5850EC] outline-none shadow-xs resize-y transition" placeholder="Alamat lengkap...">{{ old('alamat', $pegawai?->alamat) }}</textarea>
                        <x-input-error class="mt-1" :messages="$errors->get('alamat')" />
                    </div>

                    <!-- Foto Profil (Dropzone) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Foto Profil</label>
                        <div x-data="{ fileName: '', previewUrl: '{{ $pegawai?->foto_url ?? '' }}' }" class="relative border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-[#5850EC] dark:hover:border-[#5850EC] rounded-xl p-5 text-center cursor-pointer transition bg-slate-50/50 dark:bg-slate-800/40 group">
                            <input type="file" name="foto_profil" id="foto_profil" accept="image/*"
                                @change="
                                    const file = $event.target.files[0];
                                    if (file) {
                                        fileName = file.name;
                                        previewUrl = URL.createObjectURL(file);
                                    }
                                "
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                                <template x-if="previewUrl">
                                    <img :src="previewUrl" class="w-16 h-16 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-xs mb-1">
                                </template>
                                <template x-if="!previewUrl">
                                    <span class="material-symbols-outlined text-3xl text-slate-400 group-hover:text-[#5850EC] transition-colors">cloud_upload</span>
                                </template>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium" x-text="fileName ? fileName : 'Klik untuk upload foto baru'"></p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500">PNG, JPG atau WEBP (Maks. 5MB)</p>
                            </div>
                        </div>
                        <x-input-error class="mt-1" :messages="$errors->get('foto_profil')" />
                    </div>

                    <!-- Tombol Simpan Perubahan -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-[#5850EC] hover:bg-[#4338CA] text-white text-xs font-bold rounded-xl flex items-center justify-center gap-2 shadow-xs transition cursor-pointer">
                            <span class="material-symbols-outlined text-base">save</span>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>

                    @if (session('status') === 'profile-updated')
                        <div
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 3000)"
                            class="text-xs font-bold text-emerald-700 bg-emerald-50 dark:bg-emerald-950/40 px-3.5 py-2.5 rounded-xl border border-emerald-200 dark:border-emerald-800 text-center"
                        >
                            Profil berhasil diperbarui!
                        </div>
                    @endif
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
