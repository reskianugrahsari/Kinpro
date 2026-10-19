<x-app-layout>
    <x-slot name="header">
        <div>
            <!-- Header Title Dark Support -->
        <h2 class="font-extrabold text-2xl text-[#0F2747] dark:text-slate-100 tracking-tight">
                {{ __('Profil Pengguna') }}
            </h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Kelola informasi akun dan pengaturan keamanan kata sandi Anda</p>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Kartu Header Profil Utama -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs p-6 sm:p-7">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                <div class="flex items-center space-x-4">
                    @php
                        $uPhoto = $pegawai?->foto_url ?? $user->pegawai?->foto_url;
                    @endphp
                    @if($uPhoto)
                        <img src="{{ $uPhoto }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-slate-200 dark:border-slate-700 shadow-xs" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-16 h-16 rounded-2xl bg-[#0F2747] dark:bg-slate-800 text-white font-extrabold text-xl shadow-xs hidden items-center justify-center border-2 border-slate-300 dark:border-slate-600">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-[#0F2747] dark:bg-slate-800 text-white font-extrabold text-xl shadow-xs flex items-center justify-center border-2 border-slate-300 dark:border-slate-600">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif

                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="text-base font-extrabold text-[#0F2747] dark:text-slate-100">{{ $user->name }}</h3>
                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full {{ $user->role === 'admin' ? 'bg-amber-100 dark:bg-amber-950/70 text-amber-800 dark:text-amber-300' : 'bg-blue-100 dark:bg-blue-950/70 text-[#1769AA] dark:text-blue-300' }}">
                                {{ $user->role === 'admin' ? 'Administrator' : 'Pegawai' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">{{ $user->email }}</p>
                        @if($pegawai?->nip)
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 font-mono mt-0.5">NIP: {{ $pegawai->nip }}</p>
                        @endif
                    </div>
                </div>

                @if($pegawai)
                    <div class="bg-slate-50 dark:bg-slate-800/80 px-4 py-3 rounded-xl border border-slate-200/80 dark:border-slate-700 text-xs text-slate-600 dark:text-slate-300 space-y-1 w-full sm:w-auto">
                        <div><span class="text-slate-400 dark:text-slate-500">Jabatan:</span> <span class="font-bold text-slate-800 dark:text-slate-200">{{ $pegawai->jabatan ?? '-' }}</span></div>
                        <div><span class="text-slate-400 dark:text-slate-500">Unit Kerja:</span> <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $pegawai->unitKerja?->nama ?? 'Balai Diklat Wil. VIII' }}</span></div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kartu 1: Update Profil & Email -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs p-6 sm:p-7">
            <div class="max-w-2xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Kartu 2: Update Password -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs p-6 sm:p-7">
            <div class="max-w-2xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

    </div>
</x-app-layout>
