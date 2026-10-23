<x-guest-layout>
    <div class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-12 bg-brand-beige antialiased font-sans" x-data="{ showPassword: false }">
        <!-- LEFT HERO SECTION (Primary Navy #2F4156 with grid) -->
        <div class="lg:col-span-6 xl:col-span-6 bg-brand-navy text-white p-8 sm:p-12 lg:p-14 flex flex-col justify-between relative overflow-hidden">
            <!-- Subtle Grid Background -->
            <div class="absolute inset-0 opacity-[0.07] pointer-events-none" style="background-image: linear-gradient(to right, #ffffff 1px, transparent 1px), linear-gradient(to bottom, #ffffff 1px, transparent 1px); background-size: 36px 36px;"></div>

            <!-- Soft ambient glows -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-teal/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-10 -right-20 w-96 h-96 bg-brand-sky/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Content Area -->
            <div class="relative z-10">
                <!-- Top Brand Header -->
                <div class="flex items-center space-x-3.5 mb-8">
                    <!-- Clean Logo without wrapper -->
                    <div class="w-12 h-12 flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/logo-kinpro.png') }}?v={{ filemtime(public_path('images/logo-kinpro.png')) }}" alt="KinPro Logo" class="w-full h-full object-contain filter drop-shadow-[0_4px_12px_rgba(86,124,141,0.25)]">
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="text-2xl font-extrabold tracking-tight text-white">KinPro</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wider text-brand-sky border border-brand-teal/60 bg-[#202C3B]/60">ENTERPRISE</span>
                        </div>
                        <p class="text-xs text-brand-sky/80 font-normal">Balai Pengembangan Kompetensi PUPR Wilayah VIII Makassar</p>
                    </div>
                </div>

                <!-- Tagline Pill Badge -->
                <div class="inline-flex items-center gap-2 bg-[#202C3B] border border-brand-teal/40 text-brand-sky text-xs font-semibold px-3.5 py-1.5 rounded-full mb-7 shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-sky animate-pulse"></span>
                    <span>Sistem Kinerja Pegawai v2.0 Terintegrasi</span>
                </div>

                <!-- Large Headline -->
                <h1 class="text-3xl sm:text-4xl lg:text-[40px] font-extrabold leading-tight tracking-tight mb-4 text-white">
                    Kelola Kinerja Lebih <br>
                    <span class="text-brand-sky">Efisien & Terukur.</span>
                </h1>

                <!-- Body description -->
                <p class="text-slate-200/90 text-sm sm:text-base leading-relaxed mb-10 max-w-xl font-normal">
                    Platform cerdas pemantauan, evaluasi berkala, dan penilaian kinerja ASN Bapekom Wilayah VIII Makassar yang transparan, akurat, dan berbasis data realtime.
                </p>

                <!-- Stats 3 Cards -->
                <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-10 max-w-lg">
                    <div class="bg-[#202C3B]/90 border border-brand-teal/40 rounded-2xl p-4 text-center backdrop-blur-sm shadow-md">
                        <div class="text-2xl sm:text-3xl font-black text-brand-sky">32+</div>
                        <div class="text-[10px] sm:text-[11px] uppercase font-bold text-slate-300 tracking-wider mt-1">PEGAWAI AKTIF</div>
                    </div>
                    <div class="bg-[#202C3B]/90 border border-brand-teal/40 rounded-2xl p-4 text-center backdrop-blur-sm shadow-md">
                        <div class="text-2xl sm:text-3xl font-black text-white">96.8%</div>
                        <div class="text-[10px] sm:text-[11px] uppercase font-bold text-slate-300 tracking-wider mt-1">AKURASI SKP</div>
                    </div>
                    <div class="bg-[#202C3B]/90 border border-brand-teal/40 rounded-2xl p-4 text-center backdrop-blur-sm shadow-md">
                        <div class="text-2xl sm:text-3xl font-black text-brand-sky">24/7</div>
                        <div class="text-[10px] sm:text-[11px] uppercase font-bold text-slate-300 tracking-wider mt-1">LAYANAN REALTIME</div>
                    </div>
                </div>
            </div>

            <!-- Bottom Badges Row (4) -->
            <div class="relative z-10 flex flex-nowrap items-center gap-2 pt-6 border-t border-brand-teal/40 text-[11px] text-slate-200 font-medium overflow-x-auto">
                <span class="inline-flex items-center gap-1.5 bg-[#202C3B]/80 px-2.5 py-1.5 rounded-full border border-brand-teal/40 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-teal"></span> Real-time Sync
                </span>
                <span class="inline-flex items-center gap-1.5 bg-[#202C3B]/80 px-2.5 py-1.5 rounded-full border border-brand-teal/40 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-sky"></span> Terstruktur
                </span>
                <span class="inline-flex items-center gap-1.5 bg-[#202C3B]/80 px-2.5 py-1.5 rounded-full border border-brand-teal/40 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-teal"></span> Otomatisasi SKP
                </span>
                <span class="inline-flex items-center gap-1.5 bg-[#202C3B]/80 px-2.5 py-1.5 rounded-full border border-brand-teal/40 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-sky"></span> Keamanan Berlapis
                </span>
            </div>
        </div>

        <!-- RIGHT LOGIN FORM SECTION (Clean White Base Surface) -->
        <div class="lg:col-span-6 xl:col-span-6 bg-brand-white p-8 sm:p-12 lg:p-14 flex flex-col justify-between">
            <div class="max-w-md w-full mx-auto my-auto">
                <!-- Form Header -->
                <div class="mb-8">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-brand-navy tracking-tight mb-2">Selamat Datang!</h2>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Masuk menggunakan kredensial NIP, email, atau username resmi Anda untuk melanjutkan.
                    </p>
                </div>

                <x-auth-session-status class="mb-5" :status="session('status')" />

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Field 1: Email / Username / NIP -->
                    <div>
                        <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-brand-navy mb-1.5">
                            EMAIL, USERNAME, ATAU NIP
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input
                                id="email"
                                type="text"
                                name="email"
                                value="{{ old('email', 'amiruddin01@pu.go.id') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="amiruddin01@pu.go.id"
                                class="w-full pl-11 pr-4 py-3 bg-brand-white border border-brand-sky rounded-xl text-xs sm:text-sm text-brand-navy font-medium placeholder-slate-400 focus:outline-none focus:border-brand-teal focus:ring-2 focus:ring-brand-teal/20 transition shadow-xs"
                            />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                    </div>

                    <!-- Field 2: Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-brand-navy">
                                PASSWORD
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-xs text-brand-teal hover:text-brand-navy font-semibold hover:underline" href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••••••"
                                class="w-full pl-11 pr-11 py-3 bg-brand-white border border-brand-sky rounded-xl text-xs sm:text-sm text-brand-navy font-medium placeholder-slate-400 focus:outline-none focus:border-brand-teal focus:ring-2 focus:ring-brand-teal/20 transition shadow-xs"
                            />
                            <!-- Eye toggle icon -->
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                tabindex="-1"
                                aria-label="Toggle password visibility"
                            >
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-brand-sky text-brand-navy focus:ring-brand-teal focus:ring-offset-0"
                            checked
                        />
                        <label for="remember_me" class="ms-2 text-xs sm:text-sm text-slate-600 font-medium select-none cursor-pointer">
                            Ingat sesi saya di browser ini
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            class="w-full py-3.5 px-5 bg-brand-navy hover:bg-[#202C3B] text-brand-white font-bold rounded-xl shadow-md shadow-brand-navy/20 hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 text-sm sm:text-base group"
                        >
                            <span>Masuk ke Sistem KinPro</span>
                            <span class="group-hover:translate-x-1 transition-transform duration-200 font-bold">&rarr;</span>
                        </button>
                    </div>
                </form>

                <!-- Help Callout Box -->
                <div class="mt-6 p-3.5 bg-brand-beige border border-brand-sky rounded-xl flex items-start space-x-3 text-xs text-brand-navy leading-relaxed">
                    <svg class="w-4 h-4 text-brand-teal shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>
                        Mengalami kendala saat login atau lupa NIP? Silakan hubungi <a href="#" class="text-brand-teal font-semibold hover:underline">Tim IT & Kepegawaian Bapekom Wilayah VIII</a>.
                    </span>
                </div>
            </div>

            <!-- Footer -->
            <div class="max-w-md w-full mx-auto pt-8 border-t border-brand-sky/60 text-center text-[11px] text-slate-400 leading-normal">
                &copy; {{ date('Y') }} Balai Pengembangan Kompetensi Wilayah VIII Makassar. Seluruh hak cipta dilindungi undang-undang.
            </div>
        </div>
    </div>
</x-guest-layout>
