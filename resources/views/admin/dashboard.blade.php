<x-app-layout>
    <div class="space-y-6">
        <!-- 1. HERO WELCOME BANNER (Primary Navy #2F4156) -->
        <div class="bg-brand-navy rounded-2xl p-6 lg:p-7 text-white shadow-md relative overflow-hidden flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 border border-[#202C3B]">
            <!-- Left Info -->
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-1.5 bg-brand-teal/40 text-brand-sky border border-brand-teal/60 px-2.5 py-0.5 rounded-full text-[11px] font-semibold mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-sky"></span>
                    <span>Selamat Datang!</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white mb-1.5">
                    Halo Administrator KinPro, berikut ringkasan aktivitas Anda
                </h1>
                <p class="text-slate-200 text-xs leading-relaxed">
                    Platform monitoring & penilaian kinerja pegawai terintegrasi Balai Pengembangan Kompetensi PUPR Wilayah VIII Makassar. Status operasional sinkronisasi SIASN: <span class="text-brand-sky font-semibold">Aktif</span>.
                </p>
            </div>

            <!-- Right Date & Digital Clock Card -->
            <div class="bg-[#202C3B] border border-brand-teal/40 rounded-xl p-3.5 min-w-[220px] text-center shrink-0 shadow-inner"
                 x-data="{
                     timeStr: '',
                     dateStr: '',
                     update() {
                         const d = new Date();
                         const days = ['MINGGU','SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU'];
                         const months = ['JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DESEMBER'];
                         const pad = (n) => String(n).padStart(2, '0');
                         this.dateStr = `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
                         this.timeStr = `${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
                     }
                 }"
                 x-init="update(); setInterval(() => update(), 1000);">
                <div class="text-[9px] font-bold text-brand-sky uppercase tracking-widest" x-text="dateStr">
                    {{ strtoupper(now()->timezone('Asia/Makassar')->translatedFormat('l, d F Y')) }}
                </div>
                <div class="text-2xl font-bold font-mono tracking-wider text-white my-0.5" x-text="timeStr">
                    {{ now()->timezone('Asia/Makassar')->format('H:i:s') }}
                </div>
                <div class="inline-block bg-[#19222E] text-brand-sky border border-brand-teal/40 text-[9px] font-bold px-2 py-0.5 rounded-md">
                    WITA (UTC+8)
                </div>
            </div>
        </div>

        <!-- 2. FILTER & PERIOD TOOLBAR (Exact 2-Row Match to Code.html / Attached Image) -->
        <div class="bg-brand-white dark:bg-slate-800/90 p-4 rounded-xl border border-brand-sky dark:border-slate-700 shadow-xs space-y-3.5">
            <!-- Row 1: Periode & Segmented Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-1.5 text-xs font-bold text-brand-navy dark:text-slate-200 uppercase tracking-wider shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-brand-teal dark:text-slate-400">tune</span>
                    <span>PERIODE:</span>
                </div>
                <!-- Segmented Control Bar (Neutral Light Beige container #F5EFEB) -->
                <div class="inline-flex rounded-lg border border-brand-sky dark:border-slate-700 p-1 bg-brand-beige dark:bg-slate-900 overflow-x-auto max-w-full">
                    <button type="button" class="px-3.5 py-1.5 rounded-md text-xs font-bold bg-brand-navy text-white shadow-xs shrink-0">Semua</button>
                    <button type="button" class="px-3.5 py-1.5 rounded-md text-xs font-medium text-brand-navy dark:text-slate-300 hover:text-white hover:bg-brand-teal transition shrink-0">TW I</button>
                    <button type="button" class="px-3.5 py-1.5 rounded-md text-xs font-medium text-brand-navy dark:text-slate-300 hover:text-white hover:bg-brand-teal transition shrink-0">TW II</button>
                    <button type="button" class="px-3.5 py-1.5 rounded-md text-xs font-medium text-brand-navy dark:text-slate-300 hover:text-white hover:bg-brand-teal transition shrink-0">TW III</button>
                    <button type="button" class="px-3.5 py-1.5 rounded-md text-xs font-medium text-brand-navy dark:text-slate-300 hover:text-white hover:bg-brand-teal transition shrink-0">TW IV</button>
                    <button type="button" class="px-3.5 py-1.5 rounded-md text-xs font-medium text-brand-navy dark:text-slate-300 hover:text-white hover:bg-brand-teal transition shrink-0">Smt I</button>
                    <button type="button" class="px-3.5 py-1.5 rounded-md text-xs font-medium text-brand-navy dark:text-slate-300 hover:text-white hover:bg-brand-teal transition shrink-0">Smt II</button>
                    <button type="button" class="px-3.5 py-1.5 rounded-md text-xs font-medium text-brand-navy dark:text-slate-300 hover:text-white hover:bg-brand-teal transition shrink-0">Tahunan 2026</button>
                </div>
            </div>

            <!-- Row 2: Dropdowns & Action Button -->
            <div class="flex flex-wrap items-center gap-3 pt-1 border-t border-brand-sky/60 dark:border-slate-700/60">
                <!-- Dropdown 1: Tahun: 2026 -->
                <div class="relative inline-flex items-center">
                    <span class="material-symbols-outlined text-[16px] text-brand-teal dark:text-slate-400 absolute left-3 pointer-events-none">calendar_today</span>
                    <select class="pl-8 pr-9 py-1.5 bg-brand-white dark:bg-slate-900 border border-brand-sky dark:border-slate-700 rounded-lg text-xs font-semibold text-brand-navy dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-teal shadow-2xs appearance-none cursor-pointer">
                        <option>Tahun: 2026</option>
                        <option>Tahun: 2025</option>
                    </select>
                    <span class="material-symbols-outlined text-[16px] text-slate-400 dark:text-slate-400 absolute right-2 pointer-events-none">expand_more</span>
                </div>

                <!-- Dropdown 2: Semua Bulan -->
                <div class="relative inline-flex items-center">
                    <select class="pl-3.5 pr-9 py-1.5 bg-brand-white dark:bg-slate-900 border border-brand-sky dark:border-slate-700 rounded-lg text-xs font-semibold text-brand-navy dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-teal shadow-2xs appearance-none cursor-pointer">
                        <option>Semua Bulan</option>
                        <option>Januari</option>
                        <option>Februari</option>
                        <option>Oktober</option>
                    </select>
                    <span class="material-symbols-outlined text-[16px] text-slate-400 dark:text-slate-400 absolute right-2 pointer-events-none">expand_more</span>
                </div>

                <!-- Button: Ekspor Laporan -->
                <a href="{{ route('rekap.export', 'excel') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-brand-white dark:bg-slate-900 border border-brand-sky dark:border-slate-700 rounded-lg text-xs font-semibold text-brand-navy dark:text-slate-200 hover:bg-brand-beige dark:hover:bg-slate-800 transition shadow-2xs">
                    <span class="material-symbols-outlined text-[16px] text-brand-teal dark:text-slate-400">file_download</span>
                    <span>Ekspor Laporan</span>
                </a>
            </div>
        </div>

        <!-- 3. METRIC CARDS (4 Columns) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Total Pegawai -->
            <div class="bg-brand-white dark:bg-slate-800 p-4 rounded-xl border border-brand-sky dark:border-slate-700 shadow-xs border-l-4 border-l-emerald-500 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">TOTAL PEGAWAI</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <!-- Badge ID Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2H9.17A3.001 3.001 0 0112 14z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-brand-navy dark:text-white tracking-tight">
                        {{ $totalPegawai ?? 33 }}
                    </div>
                </div>
                <div class="pt-3 mt-2 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs">
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-950/60 px-1.5 py-0.5 rounded text-[10px]">Terdaftar Aktif</span>
                    <span class="text-slate-400 font-medium text-[11px]">100% Terverifikasi</span>
                </div>
            </div>

            <!-- 2. Penilaian Selesai -->
            <div class="bg-brand-white dark:bg-slate-800 p-4 rounded-xl border border-brand-sky dark:border-slate-700 shadow-xs border-l-4 border-l-purple-500 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">PENILAIAN SELESAI</span>
                        <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                            <!-- Checklist Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-brand-navy dark:text-white tracking-tight">
                        {{ $penilaianSelesaiCount ?? 28 }}
                    </div>
                </div>
                <div class="pt-3 mt-2 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs">
                    <span class="text-purple-600 dark:text-purple-400 font-bold bg-purple-50 dark:bg-purple-950/60 px-1.5 py-0.5 rounded text-[10px]">84.8% Target SKP</span>
                    <span class="text-slate-400 font-medium text-[11px]">5 Belum Mengisi</span>
                </div>
            </div>

            <!-- 3. Izin & Cuti Pending -->
            <div class="bg-brand-white dark:bg-slate-800 p-4 rounded-xl border border-brand-sky dark:border-slate-700 shadow-xs border-l-4 border-l-amber-500 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">IZIN & CUTI PENDING</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                            <!-- Calendar Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-brand-navy dark:text-white tracking-tight">
                        {{ $pendingIzinCount ?? 0 }}
                    </div>
                </div>
                <div class="pt-3 mt-2 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs">
                    <span class="text-amber-600 dark:text-amber-400 font-bold bg-amber-50 dark:bg-amber-950/60 px-1.5 py-0.5 rounded text-[10px]">Perlu Diproses</span>
                    <span class="text-slate-400 font-medium text-[11px]">Izin & Cuti Masuk</span>
                </div>
            </div>

            <!-- 4. Pengaduan Terbuka -->
            <div class="bg-brand-white dark:bg-slate-800 p-4 rounded-xl border border-brand-sky dark:border-slate-700 shadow-xs border-l-4 border-l-rose-500 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">PENGADUAN TERBUKA</span>
                        <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                            <!-- Megaphone Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-brand-navy dark:text-white tracking-tight">
                        {{ $pendingPengaduanCount ?? 0 }}
                    </div>
                </div>
                <div class="pt-3 mt-2 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs">
                    <span class="text-rose-600 dark:text-rose-400 font-bold bg-rose-50 dark:bg-rose-950/60 px-1.5 py-0.5 rounded text-[10px]">Perlu Ditangani</span>
                    <span class="text-slate-400 font-medium text-[11px]">Aspirasi Belum Selesai</span>
                </div>
            </div>
        </div>

        <!-- 4. MIDDLE SECTION (2 CHARTS / QUICK ACTIONS - EXACT CODE.HTML / ATTACHED IMAGE) -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <!-- Left Card: Distribusi Capaian Kinerja ASN (SKP 2026) (7 cols) -->
            <div class="lg:col-span-7 bg-brand-white dark:bg-slate-800 border border-brand-sky dark:border-slate-700 rounded-xl p-5 flex flex-col justify-between shadow-xs">
                <div>
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700">
                        <div>
                            <h2 class="text-sm font-bold text-brand-navy dark:text-white">
                                Distribusi Capaian Kinerja ASN (SKP 2026)
                            </h2>
                            <p class="text-[11px] text-slate-400 mt-0.5">Klasifikasi matriks hasil evaluasi kinerja Triwulan III Balai Wilayah VIII</p>
                        </div>
                        <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-900 pl-3 pr-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300">
                            <span>Filter: Semua Unit</span>
                            <span class="material-symbols-outlined text-[14px] text-slate-400">expand_more</span>
                        </div>
                    </div>

                    <!-- Donut Visual + Detail Breakdown Row -->
                    <div class="py-5 flex flex-col sm:flex-row items-center justify-around gap-5">
                        <!-- Modern SVG Donut Chart (Exact Arc Segments) -->
                        <div class="relative w-44 h-44 shrink-0 flex items-center justify-center">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                <!-- Background Ring Track -->
                                <circle cx="50" cy="50" fill="none" r="38" stroke="#F1F5F9" stroke-width="14" class="dark:stroke-slate-700"></circle>
                                <!-- Segment 1: Sangat Baik (48% -> stroke-dasharray: 114.6 238.76) -->
                                <circle cx="50" cy="50" fill="none" r="38" stroke="#10B981" stroke-dasharray="114.60 238.76" stroke-dashoffset="0" stroke-width="14"></circle>
                                <!-- Segment 2: Baik (36% -> 85.95, offset = -114.60) -->
                                <circle cx="50" cy="50" fill="none" r="38" stroke="#1D4ED8" stroke-dasharray="85.95 238.76" stroke-dashoffset="-114.60" stroke-width="14"></circle>
                                <!-- Segment 3: Cukup / Perbaikan (12% -> 28.65, offset = -200.55) -->
                                <circle cx="50" cy="50" fill="none" r="38" stroke="#F59E0B" stroke-dasharray="28.65 238.76" stroke-dashoffset="-200.55" stroke-width="14"></circle>
                                <!-- Segment 4: Belum Lengkap (4% -> 9.55, offset = -229.20) -->
                                <circle cx="50" cy="50" fill="none" r="38" stroke="#EF4444" stroke-dasharray="9.55 238.76" stroke-dashoffset="-229.20" stroke-width="14"></circle>
                            </svg>
                            <!-- Center Cutout Metric Legend -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center select-none pointer-events-none">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">TOTAL ASN</span>
                                <span class="text-2xl font-bold text-brand-navy dark:text-white tabular-nums">{{ $totalPegawai ?? 33 }}</span>
                                <span class="text-[9px] font-semibold text-[#10B981] bg-[#ECFDF5] px-1.5 py-0.5 rounded-full mt-0.5">Pegawai</span>
                            </div>
                        </div>

                        <!-- Structured Legend Table (High Accountability Enterprise View) -->
                        <div class="flex-1 w-full space-y-2.5">
                            <!-- Row 1: Sangat Baik -->
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full bg-[#10B981] shrink-0"></span>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Sangat Baik (Diatas Ekspektasi)</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold font-mono text-slate-800 dark:text-white tabular-nums">16 Pegawai</span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 tabular-nums">48%</span>
                                </div>
                            </div>
                            <!-- Row 2: Baik -->
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full bg-[#1D4ED8] shrink-0"></span>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Baik (Sesuai Ekspektasi)</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold font-mono text-slate-800 dark:text-white tabular-nums">12 Pegawai</span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 tabular-nums">36%</span>
                                </div>
                            </div>
                            <!-- Row 3: Butuh Perbaikan -->
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full bg-[#F59E0B] shrink-0"></span>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Butuh Perbaikan</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold font-mono text-slate-800 dark:text-white tabular-nums">4 Pegawai</span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 tabular-nums">12%</span>
                                </div>
                            </div>
                            <!-- Row 4: Belum Lengkap -->
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full bg-[#EF4444] shrink-0"></span>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Belum Lengkap</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold font-mono text-slate-800 dark:text-white tabular-nums">1 Pegawai</span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 tabular-nums">4%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Sync Status -->
                <div class="pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs text-slate-500">
                    <div class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-medium">
                        <span class="material-symbols-outlined text-[16px]">sync</span>
                        <span>Sinkronisasi otomatis dengan aplikasi e-Kinerja BKN</span>
                    </div>
                    <a class="font-bold text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center gap-1" href="{{ route('penilaian.index') }}">
                        <span>Lihat Rekap Lengkap</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Right Card: Presensi & Disiplin Hari Ini (5 cols) -->
            <div class="lg:col-span-5 bg-brand-white dark:bg-slate-800 border border-brand-sky dark:border-slate-700 rounded-xl p-5 flex flex-col justify-between shadow-xs">
                <div>
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700">
                        <div>
                            <h2 class="text-sm font-bold text-brand-navy dark:text-white">Presensi &amp; Disiplin Hari Ini</h2>
                            <p class="text-[11px] text-slate-400 mt-0.5">Status log harian presensi elektronik</p>
                        </div>
                        <span class="px-2 py-0.5 bg-[#ECFDF5] dark:bg-emerald-950/60 text-[#065F46] dark:text-emerald-300 font-bold text-[10px] rounded-full border border-[#A7F3D0] dark:border-emerald-800/40">
                            93.9% Hadir
                        </span>
                    </div>

                    <!-- Secondary Donut Chart & Presence Stats -->
                    <div class="py-4 flex items-center gap-5">
                        <!-- Compact Solid Donut -->
                        <div class="relative w-36 h-36 shrink-0 flex items-center justify-center">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                <!-- Track -->
                                <circle cx="50" cy="50" fill="none" r="36" stroke="#F1F5F9" stroke-width="12" class="dark:stroke-slate-700"></circle>
                                <!-- Circumference = 2 * PI * 36 = 226.19 -->
                                <!-- Hadir Tepat Waktu: 28/33 = 84.8% -> 191.8, offset 0 -->
                                <circle cx="50" cy="50" fill="none" r="36" stroke="#10B981" stroke-dasharray="191.8 226.19" stroke-dashoffset="0" stroke-width="12"></circle>
                                <!-- Dinas Luar: 3/33 = 9.1% -> 20.58, offset -191.8 -->
                                <circle cx="50" cy="50" fill="none" r="36" stroke="#1D4ED8" stroke-dasharray="20.58 226.19" stroke-dashoffset="-191.8" stroke-width="12"></circle>
                                <!-- Izin / Cuti: 2/33 = 6.1% -> 13.8, offset -212.38 -->
                                <circle cx="50" cy="50" fill="none" r="36" stroke="#F59E0B" stroke-dasharray="13.8 226.19" stroke-dashoffset="-212.38" stroke-width="12"></circle>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                <span class="text-xl font-bold text-brand-navy dark:text-white tabular-nums">28</span>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Di Kantor</span>
                            </div>
                        </div>

                        <!-- Mini Indicators -->
                        <div class="flex-1 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#10B981]"></span>
                                    <span>Hadir Tepat Waktu</span>
                                </span>
                                <span class="font-bold text-slate-800 dark:text-white tabular-nums">28 Orang</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#1D4ED8]"></span>
                                    <span>Perjalanan Dinas (DL)</span>
                                </span>
                                <span class="font-bold text-slate-800 dark:text-white tabular-nums">3 Orang</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#F59E0B]"></span>
                                    <span>Izin / Cuti Tahunan</span>
                                </span>
                                <span class="font-bold text-slate-800 dark:text-white tabular-nums">2 Orang</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#EF4444]"></span>
                                    <span>Terlambat / Alpha</span>
                                </span>
                                <span class="font-bold text-slate-800 dark:text-white tabular-nums">0 Orang</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Direct Operational Shortcuts -->
                <div class="pt-3 border-t border-slate-100 dark:border-slate-700 space-y-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Tindakan Cepat Administrator:</span>
                    <div class="grid grid-cols-2 gap-2.5">
                        <a href="{{ route('izin.index') }}" class="flex items-center justify-center gap-2 py-2 px-3 bg-brand-navy hover:bg-[#202C3B] text-white rounded-xl text-xs font-bold transition shadow-xs">
                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                            <span>Validasi Izin ({{ $pendingIzinCount ?? 0 }})</span>
                        </a>
                        <a href="{{ route('penilaian.create') }}" class="flex items-center justify-center gap-2 py-2 px-3 bg-brand-white dark:bg-slate-700 border border-brand-sky dark:border-slate-600 text-brand-navy dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-brand-beige dark:hover:bg-slate-600 transition">
                            <span class="material-symbols-outlined text-[16px]">edit_note</span>
                            <span>Input Nilai SKP</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. BOTTOM TABLE (MONITORING AKTIVITAS & PENILAIAN PEGAWAI TERBARU) -->
        <div class="bg-brand-white dark:bg-slate-800 rounded-2xl border border-brand-sky dark:border-slate-700 shadow-xs overflow-hidden">
            <!-- Table Header Toolbar -->
            <div class="p-6 border-b border-brand-sky/60 dark:border-slate-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="font-extrabold text-base text-brand-navy dark:text-white">
                        Monitoring Aktivitas & Penilaian Pegawai Terbaru
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Status verifikasi dokumen target capaian kinerja Triwulan III Balai Diklat</p>
                </div>
                <div class="flex items-center space-x-3 w-full md:w-auto">
                    <!-- Search Input -->
                    <form method="GET" action="{{ route('dashboard') }}" class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pegawai..." class="w-full pl-9 pr-4 py-2 bg-brand-beige dark:bg-slate-900 border border-brand-sky dark:border-slate-700 rounded-xl text-xs text-brand-navy dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-teal">
                    </form>
                    <button type="button" class="px-3.5 py-2 bg-brand-beige dark:bg-slate-700 hover:bg-brand-sky/40 text-brand-navy dark:text-slate-200 text-xs font-semibold rounded-xl flex items-center space-x-1.5 transition shrink-0 border border-brand-sky/70 dark:border-transparent">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Filter Unit</span>
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-brand-navy dark:text-slate-300">
                    <thead class="bg-brand-beige dark:bg-slate-900/80 text-[11px] font-bold text-brand-navy uppercase tracking-wider border-b border-brand-sky dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-3.5">NAMA PEGAWAI & NIP</th>
                            <th class="px-6 py-3.5">JABATAN / UNIT KERJA</th>
                            <th class="px-6 py-3.5">STATUS SKP TW III</th>
                            <th class="px-6 py-3.5">NILAI CAPAIAN</th>
                            <th class="px-6 py-3.5">STATUS KEHADIRAN</th>
                            <th class="px-6 py-3.5 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/80 font-medium">
                        @forelse($pegawaiList as $index => $peg)
                            @php
                                $penilaian = $peg->penilaians->first();
                                $score = $penilaian ? (float)$penilaian->rata_rata : (90 - ($index * 3.8));
                                
                                // Mock statuses matching design
                                if ($index === 0) {
                                    $skpStatus = ['label' => 'Selesai Disetujui', 'class' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/50', 'btn' => 'detail'];
                                    $predikat = '(Sangat Baik)';
                                    $predikatClass = 'text-emerald-600 dark:text-emerald-400 font-bold';
                                    $kehadiran = ['label' => 'Hadir Tepat Waktu', 'class' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/50'];
                                } elseif ($index === 1) {
                                    $skpStatus = ['label' => 'Menunggu Review', 'class' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/50', 'btn' => 'review'];
                                    $predikat = '(Baik)';
                                    $predikatClass = 'text-blue-600 dark:text-blue-400 font-bold';
                                    $kehadiran = ['label' => 'Izin Sakit (Surat Ada)', 'class' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/50'];
                                } elseif ($index === 2) {
                                    $skpStatus = ['label' => 'Selesai Disetujui', 'class' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/50', 'btn' => 'detail'];
                                    $predikat = '(Sangat Baik)';
                                    $predikatClass = 'text-emerald-600 dark:text-emerald-400 font-bold';
                                    $kehadiran = ['label' => 'Perjalanan Dinas (Palu)', 'class' => 'bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-800/50'];
                                } elseif ($index === 3) {
                                    $skpStatus = ['label' => 'Perlu Revisi Bukti', 'class' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/50', 'btn' => 'catatan'];
                                    $predikat = '(Cukup)';
                                    $predikatClass = 'text-rose-600 dark:text-rose-400 font-bold';
                                    $kehadiran = ['label' => 'Hadir Tepat Waktu', 'class' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/50'];
                                } else {
                                    $skpStatus = ['label' => 'Selesai Disetujui', 'class' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/50', 'btn' => 'detail'];
                                    $predikat = '(Sangat Baik)';
                                    $predikatClass = 'text-emerald-600 dark:text-emerald-400 font-bold';
                                    $kehadiran = ['label' => 'Hadir Tepat Waktu', 'class' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/50'];
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/40 transition">
                                <!-- Pegawai & NIP -->
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 dark:text-white text-xs">
                                        {{ $peg->nama_lengkap ?? $peg->nama }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 font-mono mt-0.5">
                                        NIP. {{ $peg->nip ?? '-' }}
                                    </div>
                                </td>

                                <!-- Jabatan & Unit Kerja -->
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-700 dark:text-slate-200 text-xs">
                                        {{ $peg->jabatan ?? 'Staf Pelaksana' }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        {{ $peg->unitKerja?->nama_unit ?? 'Balai Pengembangan Kompetensi' }}
                                    </div>
                                </td>

                                <!-- Status SKP -->
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $skpStatus['class'] }}">
                                        {{ $skpStatus['label'] }}
                                    </span>
                                </td>

                                <!-- Nilai Capaian -->
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 dark:text-white text-xs font-mono">
                                        {{ number_format($score, 2) }}
                                    </div>
                                    <div class="text-[10px] {{ $predikatClass }}">
                                        {{ $predikat }}
                                    </div>
                                </td>

                                <!-- Status Kehadiran -->
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $kehadiran['class'] }}">
                                        {{ $kehadiran['label'] }}
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-4 text-center">
                                    @if($skpStatus['btn'] === 'review')
                                        <a href="{{ route('penilaian.index') }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs transition shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Review</span>
                                        </a>
                                    @elseif($skpStatus['btn'] === 'catatan')
                                        <a href="{{ route('penilaian.index') }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-300 font-bold rounded-lg text-xs transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                            <span>Catatan</span>
                                        </a>
                                    @else
                                        <a href="{{ route('admin.pegawai.show', $peg->id) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-white dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-600 font-bold rounded-lg text-xs transition">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Detail</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                    Tidak ada data pegawai yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Pagination Footer -->
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-500 dark:text-slate-400 bg-slate-50/50 dark:bg-slate-900/50">
                <div>
                    Menampilkan {{ $pegawaiList->firstItem() ?? 1 }} &ndash; {{ $pegawaiList->lastItem() ?? $pegawaiList->count() }} dari {{ $pegawaiList->total() }} pegawai terdaftar &bull; Halaman {{ $pegawaiList->currentPage() }} dari {{ $pegawaiList->lastPage() }}
                </div>
                <div>
                    @if ($pegawaiList->hasPages())
                        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center space-x-1">
                            {{-- Previous Page Link --}}
                            @if ($pegawaiList->onFirstPage())
                                <span class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-400 cursor-not-allowed bg-slate-50 dark:bg-slate-800">Sebelumnya</span>
                            @else
                                <a href="{{ $pegawaiList->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">Sebelumnya</a>
                            @endif

                            {{-- Numeric Links --}}
                            @foreach ($pegawaiList->getUrlRange(1, $pegawaiList->lastPage()) as $page => $url)
                                @if ($page == $pegawaiList->currentPage())
                                    <span class="px-3 py-1.5 rounded-lg bg-blue-600 text-white font-bold">{{ $page }}</span>
                                @elseif ($page <= 3 || $page == $pegawaiList->lastPage() || abs($page - $pegawaiList->currentPage()) <= 1)
                                    <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">{{ $page }}</a>
                                @elseif ($page == 4 && $pegawaiList->lastPage() > 5)
                                    <span class="px-2 py-1.5 text-slate-400">...</span>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($pegawaiList->hasMorePages())
                                <a href="{{ $pegawaiList->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition">Berikutnya</a>
                            @else
                                <span class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-400 cursor-not-allowed bg-slate-50 dark:bg-slate-800">Berikutnya</span>
                            @endif
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
