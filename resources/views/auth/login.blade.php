<x-guest-layout>
    <div class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-2 bg-[#0B132B]">
        <!-- Left Pane (Dark Navy #0B132B) -->
        <div class="bg-[#0B132B] text-white p-8 lg:p-16 flex flex-col justify-between relative overflow-hidden">
            <!-- Background glow effects -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#00B4D8]/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <!-- Circular Emblem & App Name -->
                <div class="flex items-center space-x-4 mb-8">
                    <div class="w-16 h-16 rounded-2xl bg-[#0F172A]/80 border border-[#00B4D8]/30 flex items-center justify-center p-2 shadow-xl shadow-[#00B4D8]/10 backdrop-blur-md">
                        <img src="{{ asset('images/logo-kinpro.png') }}" alt="KinPro Logo" class="w-full h-full object-contain filter drop-shadow">
                    </div>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-wide text-white">KinPro</h1>
                        <p class="text-xs text-slate-300 font-medium">Balai Pengembangan Kompetensi PU / Wilayah VIII Makassar</p>
                    </div>
                </div>

                <!-- Dark Pill Tag with Lightning Icon -->
                <div class="inline-flex items-center bg-[#1E293B] border border-[#00B4D8]/30 text-[#00B4D8] text-xs font-semibold px-4 py-2 rounded-full mb-8 shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-[#00B4D8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Sistem Kinerja Pegawai v2.0
                </div>

                <!-- Main Heading & Subtitle -->
                <h2 class="text-4xl lg:text-5xl font-extrabold text-white mb-4 leading-tight tracking-tight">
                    Kelola Kinerja Lebih Efisien
                </h2>
                <p class="text-slate-300 text-base lg:text-lg mb-10 font-normal">
                    Platform monitoring & penilaian kinerja terintegrasi
                </p>

                <!-- Metric Cards (3) -->
                <div class="grid grid-cols-3 gap-4 mb-10">
                    <div class="bg-[#111827] border border-slate-800 p-4 rounded-xl text-center shadow-md">
                        <div class="text-xl lg:text-2xl font-black text-[#00B4D8]">32</div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mt-1">PEGAWAI</div>
                    </div>
                    <div class="bg-[#111827] border border-slate-800 p-4 rounded-xl text-center shadow-md">
                        <div class="text-xl lg:text-2xl font-black text-purple-400">96%</div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mt-1">AKURASI</div>
                    </div>
                    <div class="bg-[#111827] border border-slate-800 p-4 rounded-xl text-center shadow-md">
                        <div class="text-xl lg:text-2xl font-black text-[#00B4D8]">24/7</div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mt-1">24 JAM / 7 HARI</div>
                    </div>
                </div>
            </div>

            <!-- Bottom Feature Badges (4) -->
            <div class="relative z-10 flex flex-wrap gap-3 pt-6 border-t border-slate-800 text-xs text-slate-300 font-medium">
                <span class="inline-flex items-center bg-[#111827] px-3 py-1.5 rounded-lg border border-slate-800">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2"></span> Real-time
                </span>
                <span class="inline-flex items-center bg-[#111827] px-3 py-1.5 rounded-lg border border-slate-800">
                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2"></span> Terstruktur
                </span>
                <span class="inline-flex items-center bg-[#111827] px-3 py-1.5 rounded-lg border border-slate-800">
                    <span class="w-2 h-2 rounded-full bg-purple-400 mr-2"></span> Otomatis
                </span>
                <span class="inline-flex items-center bg-[#111827] px-3 py-1.5 rounded-lg border border-slate-800">
                    <span class="w-2 h-2 rounded-full bg-amber-400 mr-2"></span> Aman
                </span>
            </div>
        </div>

        <!-- Right Pane (White Login Card) -->
        <div class="bg-white p-8 lg:p-16 flex flex-col justify-between shadow-2xl">
            <div>
                <!-- Top Badge & Header -->
                <div class="mb-8">
                    <div class="inline-flex items-center bg-slate-100 text-slate-700 px-3.5 py-1.5 rounded-full text-xs font-semibold mb-6">
                        <svg class="w-4 h-4 mr-2 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Selamat Malam
                    </div>

                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-14 h-14 bg-white border border-slate-200 rounded-2xl flex items-center justify-center p-1.5 shadow-md">
                            <img src="{{ asset('images/logo-kinpro.png') }}" alt="KinPro Logo" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-[#0F2747]">Selamat Datang!</h3>
                            <p class="text-sm text-slate-500">Masuk ke sistem untuk melanjutkan</p>
                        </div>
                    </div>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email / Username / NIP -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Email, Username, atau NIP</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <input id="email" class="block w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-[#0F2747] text-sm font-medium focus:outline-none focus:border-[#00B4D8] focus:ring-1 focus:ring-[#00B4D8]" type="text" name="email" value="{{ old('email', 'amiruddin01@pu.go.id') }}" required autofocus autocomplete="username" placeholder="amiruddin01@pu.go.id" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs text-[#00B4D8] hover:underline font-semibold" href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input id="password" class="block w-full pl-12 pr-12 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-[#0F2747] text-sm font-medium focus:outline-none focus:border-[#00B4D8] focus:ring-1 focus:ring-[#00B4D8]" type="password" name="password" required autocomplete="current-password" placeholder="••••••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4" name="remember">
                        <label for="remember_me" class="ms-2 text-sm text-slate-600 font-medium">Ingat saya</label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="w-full py-4 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 transition duration-200 flex items-center justify-center space-x-2 text-base">
                            <span>Masuk</span>
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="pt-8 text-center text-xs text-slate-400 border-t border-slate-100 mt-8">
                &copy; Bapekom PU Wilayah VIII Makassar 2026
            </div>
        </div>
    </div>
</x-guest-layout>
