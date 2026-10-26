<x-guest-layout>
    <style>
        @keyframes subtleFloat {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-5px);
            }
        }
        @keyframes twinkle {
            0%, 100% {
                opacity: 0.2;
                transform: scale(0.8);
            }
            50% {
                opacity: 0.95;
                transform: scale(1.3);
            }
        }
        .anim-float-1 {
            animation: subtleFloat 3.8s ease-in-out infinite;
        }
        .anim-float-2 {
            animation: subtleFloat 3.8s ease-in-out infinite 1.2s;
        }
        .anim-float-3 {
            animation: subtleFloat 3.8s ease-in-out infinite 2.4s;
        }
    </style>
    <div class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-12 bg-brand-beige antialiased font-sans" x-data="{ showPassword: false }">
        <!-- LEFT HERO SECTION (Primary Navy #2F4156 with grid) -->
        <div class="lg:col-span-6 xl:col-span-6 bg-brand-navy text-white p-8 sm:p-12 lg:p-14 flex flex-col justify-between relative overflow-hidden">
            <!-- Subtle Grid Background -->
            <div class="absolute inset-0 opacity-[0.07] pointer-events-none" style="background-image: linear-gradient(to right, #ffffff 1px, transparent 1px), linear-gradient(to bottom, #ffffff 1px, transparent 1px); background-size: 36px 36px;"></div>

            <!-- Soft ambient glows -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-teal/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-brand-sky/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-10 -right-20 w-96 h-96 bg-brand-teal/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Center Stage Hero: Logo Center Horizontal & Vertikal, Title, Description -->
            <div class="relative z-10 my-auto py-6 flex flex-col items-center text-center">
                <!-- Main Centered Enlarged Logo (Clean Transparent without Wrapper Box) -->
                <div class="relative mb-6 group flex items-center justify-center">
                    <div class="absolute -inset-6 bg-gradient-to-r from-teal-500/25 via-sky-400/30 to-cyan-500/25 rounded-full blur-3xl pointer-events-none group-hover:blur-3xl transition duration-500"></div>
                    <img src="{{ asset('images/logo-kinpro-auth.png') }}?v={{ file_exists(public_path('images/logo-kinpro-auth.png')) ? filemtime(public_path('images/logo-kinpro-auth.png')) : time() }}" alt="KinPro Logo" class="relative z-10 w-40 sm:w-48 md:w-52 max-h-48 sm:max-h-52 object-contain filter drop-shadow-[0_12px_24px_rgba(0,0,0,0.45)] transition-transform duration-300 group-hover:scale-105">
                </div>

                <!-- Dynamic & Vivid Main Title with Gradient -->
                <h1 class="text-3xl sm:text-4xl lg:text-[42px] font-extrabold leading-tight tracking-tight mb-4 max-w-lg">
                    <span class="text-white">Kelola Kinerja Lebih </span>
                    <span class="bg-gradient-to-r from-teal-300 via-sky-300 to-cyan-200 bg-clip-text text-transparent drop-shadow-sm block sm:inline">
                        Efisien & Terukur.
                    </span>
                </h1>

                <!-- Body description -->
                <p class="text-slate-200 text-xs sm:text-sm leading-relaxed max-w-lg font-normal mb-8">
                    Platform cerdas pemantauan, evaluasi berkala, dan penilaian kinerja ASN Balai Pengembangan Kompetensi PUPR Wilayah VIII Makassar yang transparan, akurat, dan berbasis data realtime.
                </p>

                <!-- Stats 3 Cards Centered with Animated Float & Glow Hover -->
                <div class="grid grid-cols-3 gap-3 sm:gap-4 w-full max-w-md">
                    <!-- Card 1 -->
                    <div class="anim-float-1 group/stat relative bg-[#202C3B]/90 border border-brand-teal/40 rounded-2xl p-3.5 text-center backdrop-blur-sm shadow-md transition-all duration-300 ease-out hover:-translate-y-2 hover:shadow-[0_12px_24px_rgba(20,184,166,0.25)] hover:border-cyan-300 cursor-default">
                        <div class="text-xl sm:text-2xl font-black bg-gradient-to-r from-teal-300 to-sky-300 bg-clip-text text-transparent group-hover/stat:scale-110 transition-transform duration-300 inline-block">32+</div>
                        <div class="text-[9px] sm:text-[10px] uppercase font-bold text-slate-300 tracking-wider mt-1 group-hover/stat:text-cyan-200 transition-colors">PEGAWAI AKTIF</div>
                    </div>
                    <!-- Card 2 -->
                    <div class="anim-float-2 group/stat relative bg-[#202C3B]/90 border border-brand-teal/40 rounded-2xl p-3.5 text-center backdrop-blur-sm shadow-md transition-all duration-300 ease-out hover:-translate-y-2 hover:shadow-[0_12px_24px_rgba(56,189,248,0.25)] hover:border-sky-300 cursor-default">
                        <div class="text-xl sm:text-2xl font-black bg-gradient-to-r from-white via-sky-100 to-teal-200 bg-clip-text text-transparent group-hover/stat:scale-110 transition-transform duration-300 inline-block">96.8%</div>
                        <div class="text-[9px] sm:text-[10px] uppercase font-bold text-slate-300 tracking-wider mt-1 group-hover/stat:text-sky-200 transition-colors">AKURASI SKP</div>
                    </div>
                    <!-- Card 3 -->
                    <div class="anim-float-3 group/stat relative bg-[#202C3B]/90 border border-brand-teal/40 rounded-2xl p-3.5 text-center backdrop-blur-sm shadow-md transition-all duration-300 ease-out hover:-translate-y-2 hover:shadow-[0_12px_24px_rgba(45,212,191,0.25)] hover:border-teal-300 cursor-default">
                        <div class="text-xl sm:text-2xl font-black bg-gradient-to-r from-sky-300 to-teal-300 bg-clip-text text-transparent group-hover/stat:scale-110 transition-transform duration-300 inline-block">24/7</div>
                        <div class="text-[9px] sm:text-[10px] uppercase font-bold text-slate-300 tracking-wider mt-1 group-hover/stat:text-teal-200 transition-colors">REALTIME SYNC</div>
                    </div>
                </div>
            </div>

            <!-- Bottom Badges Row (4) -->
            <div class="relative z-10 flex flex-wrap sm:flex-nowrap justify-center items-center gap-2 pt-6 border-t border-brand-teal/30 text-[11px] text-slate-200 font-medium">
                <span class="inline-flex items-center gap-1.5 bg-[#202C3B]/80 px-2.5 py-1.5 rounded-full border border-teal-500/30 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span> Real-time Sync
                </span>
                <span class="inline-flex items-center gap-1.5 bg-[#202C3B]/80 px-2.5 py-1.5 rounded-full border border-sky-500/30 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span> Terstruktur
                </span>
                <span class="inline-flex items-center gap-1.5 bg-[#202C3B]/80 px-2.5 py-1.5 rounded-full border border-cyan-500/30 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span> Otomatisasi SKP
                </span>
                <span class="inline-flex items-center gap-1.5 bg-[#202C3B]/80 px-2.5 py-1.5 rounded-full border border-teal-500/30 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span> Keamanan Berlapis
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
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan NIP atau Email Resmi"
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
