<x-guest-layout>
    <style>
        /* Prevent browser autofill from overriding input background/text color */
        #loginForm input:-webkit-autofill,
        #loginForm input:-webkit-autofill:hover,
        #loginForm input:-webkit-autofill:focus,
        #loginForm input:-webkit-autofill:active,
        #loginForm input:autofill {
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
            box-shadow: 0 0 0 1000px #ffffff inset !important;
            -webkit-text-fill-color: #0B2A5B !important;
            caret-color: #0B2A5B !important;
            transition: background-color 99999s ease-in-out 0s;
        }
        html.dark #loginForm input {
            color-scheme: dark;
        }
        html.dark #loginForm input:-webkit-autofill,
        html.dark #loginForm input:-webkit-autofill:hover,
        html.dark #loginForm input:-webkit-autofill:focus,
        html.dark #loginForm input:-webkit-autofill:active,
        html.dark #loginForm input:autofill {
            -webkit-box-shadow: 0 0 0 1000px #091426 inset !important;
            box-shadow: 0 0 0 1000px #091426 inset !important;
            background-color: #091426 !important;
            -webkit-text-fill-color: #ffffff !important;
            caret-color: #ffffff !important;
            transition: background-color 99999s ease-in-out 0s;
        }
    </style>
    @php
        $statCardClass = 'bg-[#D5DEEF] hover:bg-[#C7D4EA] border border-[#BDC9E0] dark:bg-[#10233F] dark:hover:bg-[#162F54] dark:border-slate-700/80 rounded-2xl px-2.5 py-4 sm:py-5 text-center transition-all duration-200 hover:-translate-y-1 shadow-[0_6px_16px_rgba(11,42,91,0.06)] dark:shadow-[0_8px_22px_rgba(0,0,0,0.35)]';
        $statIconClass = 'flex justify-center mb-2 text-[#0B2A5B] dark:text-[#F5B700]';
        $statValueClass = 'text-xl sm:text-2xl font-extrabold text-[#0B2A5B] dark:text-white leading-tight';
        $statLabelClass = 'text-[11px] sm:text-xs font-semibold text-[#0B2A5B]/80 dark:text-slate-300 mt-1';
    @endphp

    <div
        class="min-h-screen w-full bg-[#F7F9FC] dark:bg-[#071121] relative overflow-hidden flex items-center justify-center px-4 sm:px-8 lg:px-14 py-8 sm:py-12 antialiased font-sans transition-colors duration-200"
        x-data="{ showPassword: false, showSupport: false }"
    >
        <!-- Subtle Background Dot Texture (Light & Dark) & Soft Ambient Glow -->
        <div
            class="block dark:hidden absolute inset-0 pointer-events-none opacity-45"
            style="background-image: radial-gradient(rgba(11, 42, 91, 0.08) 1px, transparent 1px); background-size: 24px 24px;"
        ></div>
        <div
            class="hidden dark:block absolute inset-0 pointer-events-none opacity-35"
            style="background-image: radial-gradient(rgba(148, 163, 184, 0.16) 1px, transparent 1px); background-size: 24px 24px;"
        ></div>
        <div class="absolute -top-32 -left-32 w-[420px] h-[420px] rounded-full bg-[#E8F0FE]/70 dark:bg-[#1D5FAD]/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-[420px] h-[420px] rounded-full bg-[#FEF6DF]/70 dark:bg-[#F5B700]/10 blur-3xl pointer-events-none"></div>

        <!-- Main Centered 2-Column Layout -->
        <div class="relative z-10 w-full max-w-[1100px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            <!-- LEFT HERO SECTION -->
            <div class="lg:col-span-6 flex flex-col justify-center">
                <!-- Brand Lockup: Official KINPRO Logo (Light & Dark Variants) -->
                <div class="mb-6 sm:mb-7">
                    <img
                        src="{{ asset('images/logo-kinpro-up.png') }}?v={{ file_exists(public_path('images/logo-kinpro-up.png')) ? filemtime(public_path('images/logo-kinpro-up.png')) : time() }}"
                        alt="KINPRO - Platform Monitoring & Penilaian Kinerja Pegawai Terintegrasi"
                        class="block dark:hidden w-full max-w-[340px] sm:max-w-[400px] h-auto object-contain select-none"
                    />
                    <img
                        src="{{ asset('images/logo-kinpro-up-dark.png') }}?v={{ file_exists(public_path('images/logo-kinpro-up-dark.png')) ? filemtime(public_path('images/logo-kinpro-up-dark.png')) : time() }}"
                        alt="KINPRO - Platform Monitoring & Penilaian Kinerja Pegawai Terintegrasi"
                        class="hidden dark:block w-full max-w-[340px] sm:max-w-[400px] h-auto object-contain select-none"
                    />
                </div>

                <!-- Hero Headline -->
                <h1 class="text-2xl sm:text-[34px] font-extrabold text-[#0B2A5B] dark:text-white leading-[1.2] tracking-tight max-w-[460px]">
                    Kelola Kinerja Lebih
                    <span class="block mt-0.5">Efisien &amp; Terukur.</span>
                </h1>

                <!-- Gold Accent Divider -->
                <div class="w-12 h-1 bg-[#F5B700] rounded-full mt-3.5 mb-4"></div>

                <!-- Hero Description -->
                <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-[15px] leading-relaxed max-w-[460px] mb-7">
                    Platform cerdas pemantauan, evaluasi berkala, dan penilaian kinerja ASN secara terintegrasi.
                </p>

                <!-- 3 Stat Cards -->
                <div class="grid grid-cols-3 gap-3 sm:gap-4 max-w-[460px]">
                    <!-- Card 1: Pegawai Aktif -->
                    <div class="{{ $statCardClass }}">
                        <div class="{{ $statIconClass }}">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                            </svg>
                        </div>
                        <div class="{{ $statValueClass }}">32+</div>
                        <div class="{{ $statLabelClass }}">Pegawai Aktif</div>
                    </div>

                    <!-- Card 2: Akurasi SKP -->
                    <div class="{{ $statCardClass }}">
                        <div class="{{ $statIconClass }}">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 17l6-6 4 4 7-7"/>
                                <path d="M15 8h5v5"/>
                                <path d="M5 21v-3M10 21v-6M15 21v-4M20 21v-7"/>
                            </svg>
                        </div>
                        <div class="{{ $statValueClass }}">96.8%</div>
                        <div class="{{ $statLabelClass }}">Akurasi SKP</div>
                    </div>

                    <!-- Card 3: Realtime Sync -->
                    <div class="{{ $statCardClass }}">
                        <div class="{{ $statIconClass }}">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"/>
                                <polyline points="12 7 12 12 15.5 14"/>
                            </svg>
                        </div>
                        <div class="{{ $statValueClass }}">24/7</div>
                        <div class="{{ $statLabelClass }}">Realtime Sync</div>
                    </div>
                </div>
            </div>

            <!-- RIGHT LOGIN CARD SECTION -->
            <div class="lg:col-span-6 flex justify-center lg:justify-end">
                <div class="relative w-full max-w-[470px] bg-white dark:bg-[#0E1C33] rounded-[24px] border border-slate-200/75 dark:border-slate-700/80 shadow-[0_20px_60px_-15px_rgba(11,42,91,0.12)] dark:shadow-[0_24px_60px_-15px_rgba(0,0,0,0.55)] px-6 py-8 sm:px-10 sm:py-10 overflow-hidden transition-colors duration-200">
                    <!-- Top-Right Curved Corner Ribbon (Navy + Gold) -->
                    <svg class="absolute top-0 right-0 w-36 h-36 pointer-events-none select-none" viewBox="0 0 144 144" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M24 0C78 16 128 66 144 120V144C122 80 64 22 0 0H24Z" fill="#F5B700"/>
                        <path d="M50 0C92 18 126 52 144 94V0H50Z" class="fill-[#0B2A5B] dark:fill-[#1A3B6E]"/>
                    </svg>

                    <!-- Card Header -->
                    <div class="relative z-10 mb-6 pr-8">
                        <h2 class="text-2xl sm:text-[30px] font-extrabold text-[#0B2A5B] dark:text-white tracking-tight mb-2">
                            Selamat Datang!
                        </h2>
                        <p class="text-xs sm:text-[13.5px] text-slate-500 dark:text-slate-300 leading-relaxed">
                            Masuk menggunakan kredensial NIP, email, atau username resmi Anda untuk melanjutkan.
                        </p>
                    </div>

                    <x-auth-session-status class="relative z-10 mb-4" :status="session('status')" />

                    <!-- Login Form -->
                    <form id="loginForm" method="POST" action="{{ route('login') }}" class="relative z-10 space-y-4 sm:space-y-5">
                        @csrf

                        <!-- Field 1: Email / Username / NIP -->
                        <div>
                            <label for="email" class="flex items-center gap-2 text-xs sm:text-[13px] font-bold text-[#0B2A5B] dark:text-slate-200 mb-1.5">
                                <svg class="w-4 h-4 text-[#0B2A5B] dark:text-[#F5B700] shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                                <span>Email, Username, atau NIP</span>
                            </label>
                            <input
                                id="email"
                                type="text"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan NIP atau Email Resmi"
                                class="w-full px-4 py-3 bg-white focus:bg-white dark:bg-[#091426] dark:focus:bg-[#091426] border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-[#0B2A5B] dark:text-white font-medium placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:border-[#0B2A5B] dark:focus:border-[#F5B700] focus:ring-2 focus:ring-[#0B2A5B]/15 dark:focus:ring-[#F5B700]/20 transition"
                            />
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                        </div>

                        <!-- Field 2: Password -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="flex items-center gap-2 text-xs sm:text-[13px] font-bold text-[#0B2A5B] dark:text-slate-200">
                                    <svg class="w-4 h-4 text-[#0B2A5B] dark:text-[#F5B700] shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2z"/>
                                    </svg>
                                    <span>Password</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#1D5FAD] hover:text-[#0B2A5B] dark:text-sky-400 dark:hover:text-sky-300 hover:underline transition">
                                        Lupa password?
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan password Anda"
                                    class="w-full pl-4 pr-11 py-3 bg-white focus:bg-white dark:bg-[#091426] dark:focus:bg-[#091426] border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-[#0B2A5B] dark:text-white font-medium placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:border-[#0B2A5B] dark:focus:border-[#F5B700] focus:ring-2 focus:ring-[#0B2A5B]/15 dark:focus:ring-[#F5B700]/20 transition"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-[#0B2A5B] dark:text-slate-400 dark:hover:text-white focus:outline-none transition cursor-pointer"
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
                        <div class="flex items-center gap-2.5 pt-0.5">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                checked
                                class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 dark:bg-[#091426] text-[#0B2A5B] dark:text-[#F5B700] focus:ring-[#0B2A5B] dark:focus:ring-[#F5B700] focus:ring-offset-0 cursor-pointer"
                            />
                            <label for="remember_me" class="text-xs sm:text-[13px] text-slate-600 dark:text-slate-300 font-medium select-none cursor-pointer">
                                Ingat sesi saya di browser ini
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-1">
                            <button
                                type="submit"
                                class="w-full py-3.5 px-5 bg-[#0B2A5B] hover:bg-[#081F44] active:bg-[#061835] dark:bg-[#1D5FAD] dark:hover:bg-[#246CC2] dark:active:bg-[#184E91] text-white font-bold rounded-xl shadow-[0_8px_20px_-6px_rgba(11,42,91,0.45)] dark:shadow-[0_8px_20px_-6px_rgba(29,95,173,0.45)] transition-all duration-200 flex items-center justify-center gap-2 text-sm sm:text-[15px] group cursor-pointer"
                            >
                                <span>Masuk ke Sistem KinPro</span>
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </button>
                        </div>
                    </form>

                    <!-- Help Callout Box -->
                    <div class="relative z-10 mt-5 p-3.5 bg-[#F2F7FD] dark:bg-[#132645] border border-[#DCE8F8] dark:border-slate-700/80 rounded-xl flex items-start gap-3 text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                        <svg class="w-5 h-5 text-[#1D5FAD] dark:text-sky-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" d="M12 16v-4m0-4h.01"/>
                        </svg>
                        <span>
                            Mengalami kendala saat login atau lupa NIP? Silakan hubungi
                            <button type="button" @click="showSupport = true" class="text-[#1D5FAD] dark:text-sky-400 font-semibold hover:underline text-left cursor-pointer">
                                Tim IT &amp; Kepegawaian Bapekom Wilayah VIII.
                            </button>
                        </span>
                    </div>

                    <!-- Divider -->
                    <div class="relative z-10 mt-5 flex items-center gap-3">
                        <div class="flex-1 h-px bg-slate-200/80 dark:bg-slate-700/80"></div>
                        <span class="text-xs text-slate-400 font-medium">atau</span>
                        <div class="flex-1 h-px bg-slate-200/80 dark:bg-slate-700/80"></div>
                    </div>

                    <!-- Hubungi Support Action -->
                    <div class="relative z-10 mt-3.5 text-center">
                        <button
                            type="button"
                            @click="showSupport = true"
                            class="inline-flex items-center justify-center gap-2 text-xs sm:text-[13px] font-semibold text-[#0B2A5B] hover:text-[#1D5FAD] dark:text-slate-200 dark:hover:text-sky-400 transition cursor-pointer"
                        >
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 1a9 9 0 0 0-9 9v7c0 1.66 1.34 3 3 3h3v-8H5v-2c0-3.87 3.13-7 7-7s7 3.13 7 7v2h-4v8h3c1.66 0 3-1.34 3-3v-7a9 9 0 0 0-9-9z"/>
                            </svg>
                            <span>Hubungi Support</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Info Modal -->
        <div
            x-show="showSupport"
            x-transition.opacity
            @keydown.escape.window="showSupport = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-[#0B2A5B]/40 dark:bg-black/65 backdrop-blur-xs p-4"
            style="display: none;"
        >
            <div @click.outside="showSupport = false" class="w-full max-w-md bg-white dark:bg-[#0E1C33] rounded-2xl border border-slate-200 dark:border-slate-700 shadow-2xl p-6">
                <div class="flex items-start justify-between gap-4 mb-3">
                    <div>
                        <h3 class="text-lg font-extrabold text-[#0B2A5B] dark:text-white">Bantuan Akses KinPro</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tim IT &amp; Kepegawaian Bapekom PU Wilayah VIII Makassar</p>
                    </div>
                    <button type="button" @click="showSupport = false" class="text-slate-400 hover:text-[#0B2A5B] dark:hover:text-white cursor-pointer" aria-label="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-3.5 rounded-xl bg-[#F2F7FD] dark:bg-[#132645] border border-[#DCE8F8] dark:border-slate-700 text-xs text-slate-600 dark:text-slate-300 leading-relaxed space-y-2">
                    <p>Jika Anda lupa NIP, akun belum terdaftar, atau terkendala masuk ke sistem, silakan hubungi <strong>Tim IT &amp; Subbagian Kepegawaian Bapekom Wilayah VIII Makassar</strong> secara langsung untuk verifikasi dan reset kredensial.</p>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" @click="showSupport = false" class="px-4 py-2 bg-[#0B2A5B] hover:bg-[#081F44] dark:bg-[#1D5FAD] dark:hover:bg-[#246CC2] text-white text-xs font-bold rounded-xl cursor-pointer transition">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
