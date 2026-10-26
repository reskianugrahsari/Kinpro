<x-app-layout>
    <div class="space-y-6">
        <!-- 1. HERO IDENTITY & WELCOME CARD (Primary Navy #2F4156) -->
        <section class="bg-brand-navy border border-[#202C3B] rounded-xl p-6 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-md">
            <!-- Left Info -->
            <div class="space-y-3 max-w-3xl">
                <div class="flex flex-wrap items-center gap-2">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-[#202C3B] border border-brand-teal/40 text-white text-xs font-bold">
                        <span class="material-symbols-outlined text-[16px] text-brand-sky">verified</span>
                        <span>Portal ASN Bapekom Wil. VIII</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-brand-teal/40 text-brand-sky border border-brand-teal/60 font-mono">
                        {{ $pegawai->status_kepegawaian ?? 'PNS / PPPK' }}
                    </span>
                    @php
                        $isAktif = $pegawai ? ($pegawai->is_aktif ?? true) : true;
                    @endphp
                    @if($isAktif)
                        <span class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-emerald-950/60 text-emerald-300 border border-emerald-500/50">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span>Aktif Bekerja</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-rose-950/60 text-rose-300 border border-rose-500/50">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                            </span>
                            <span>Tidak Aktif</span>
                        </span>
                    @endif
                </div>

                <div class="flex items-center space-x-3.5">
                    @php
                        $pegawaiFotoUrl = $pegawai->foto_url ?? Auth::user()->pegawai?->foto_url;
                    @endphp
                    @if($pegawaiFotoUrl)
                        <img src="{{ $pegawaiFotoUrl }}" alt="{{ $pegawai->nama_lengkap ?? Auth::user()->name }}" class="w-12 h-12 rounded-xl object-cover object-center border border-brand-sky/40 shadow-sm shrink-0" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-12 h-12 rounded-xl bg-brand-teal border border-brand-sky/30 text-white hidden items-center justify-center font-bold text-lg shrink-0">
                            {{ strtoupper(substr($pegawai->nama_lengkap ?? ($pegawai->nama ?? Auth::user()->name), 0, 2)) }}
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-xl bg-brand-teal border border-brand-sky/30 text-white flex items-center justify-center font-bold text-lg shrink-0">
                            {{ strtoupper(substr($pegawai->nama_lengkap ?? ($pegawai->nama ?? Auth::user()->name), 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                            Halo, {{ $pegawai->nama_lengkap ?? ($pegawai->nama ?? Auth::user()->name) }}
                        </h1>
                        <p class="text-xs text-slate-200 mt-0.5">
                            Selamat datang di Sistem Manajemen Kinerja & Layanan Mandiri Terpadu Bapekom PUPR Wilayah VIII Makassar.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Digital Clock WITA (Solid Flat Container) -->
            <div class="shrink-0 bg-[#202C3B] border border-brand-teal/40 p-4 rounded-xl text-center w-full md:w-60"
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
                <div class="text-2xl font-mono font-bold text-white tracking-tight my-0.5 tabular-nums" x-text="timeStr">
                    {{ now()->timezone('Asia/Makassar')->format('H:i:s') }}
                </div>
                <div class="inline-block px-2 py-0.5 bg-[#19222E] border border-brand-teal/40 text-[10px] font-bold text-brand-sky rounded font-mono">
                    WITA (UTC+8)
                </div>
            </div>
        </section>

        <!-- 2. QUICK ACTION SHORTCUTS (4 Action Tiles) -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Laporan Pajokka (Teal/Cyan theme) -->
            <a href="{{ route('pajokka.create') }}" class="group bg-brand-white dark:bg-slate-800 border border-teal-200 dark:border-teal-900/60 rounded-xl p-4 flex items-center justify-between hover:bg-teal-50/70 dark:hover:bg-teal-950/40 hover:border-teal-400 dark:hover:border-teal-600 hover:shadow-md transition duration-150 border-l-4 border-l-teal-600 dark:border-l-teal-400">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 group-hover:scale-105 group-hover:bg-teal-600 group-hover:text-white dark:group-hover:bg-teal-500 transition-all shadow-xs">
                        <svg class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2.5 19h19v2h-19v-2zm19.57-9.36c-.21-.8-1.04-1.28-1.84-1.06L14.92 10 8.91 3.84c-.4-.41-1.07-.46-1.54-.11l-.83.62c-.44.33-.55.93-.25 1.39l3.87 5.92-4.04 1.08-2.22-1.74c-.33-.26-.79-.31-1.17-.13l-.68.32c-.44.21-.63.73-.42 1.18l1.45 3.09c.27.58.85.95 1.49.95h.19l15.11-4.04c.8-.21 1.28-1.04 1.06-1.84z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900 dark:text-slate-100 group-hover:text-teal-700 dark:group-hover:text-teal-300 transition-colors">Laporan Pajokka</div>
                        <div class="text-[11px] text-teal-700 dark:text-teal-300 mt-0.5 font-semibold">Buat SPPD & Kuitansi</div>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-full bg-teal-100 dark:bg-teal-900/50 flex items-center justify-center text-teal-700 dark:text-teal-300 group-hover:bg-teal-600 dark:group-hover:bg-teal-500 group-hover:text-white transition-all shadow-2xs">
                    <span class="material-symbols-outlined group-hover:translate-x-0.5 transition-transform text-[16px]">arrow_forward</span>
                </div>
            </a>

            <!-- 2. Pengajuan Izin (Blue/Sky theme) -->
            <a href="{{ route('izin.create') }}" class="group bg-brand-white dark:bg-slate-800 border border-blue-200 dark:border-blue-900/60 rounded-xl p-4 flex items-center justify-between hover:bg-blue-50/70 dark:hover:bg-blue-950/40 hover:border-blue-400 dark:hover:border-blue-600 hover:shadow-md transition duration-150 border-l-4 border-l-blue-600 dark:border-l-blue-400">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 group-hover:scale-105 group-hover:bg-blue-600 group-hover:text-white dark:group-hover:bg-blue-500 transition-all shadow-xs">
                        <svg class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7v-5z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900 dark:text-slate-100 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors">Pengajuan Izin</div>
                        <div class="text-[11px] text-blue-700 dark:text-blue-300 mt-0.5 font-semibold">Izin, Sakit, & Cuti</div>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-700 dark:text-blue-300 group-hover:bg-blue-600 dark:group-hover:bg-blue-500 group-hover:text-white transition-all shadow-2xs">
                    <span class="material-symbols-outlined group-hover:translate-x-0.5 transition-transform text-[16px]">arrow_forward</span>
                </div>
            </a>

            <!-- 3. Lupa Presensi (Amber/Orange theme) -->
            <a href="{{ route('absen.create') }}" class="group bg-brand-white dark:bg-slate-800 border border-amber-200 dark:border-amber-900/60 rounded-xl p-4 flex items-center justify-between hover:bg-amber-50/70 dark:hover:bg-amber-950/40 hover:border-amber-400 dark:hover:border-amber-600 hover:shadow-md transition duration-150 border-l-4 border-l-amber-500 dark:border-l-amber-400">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-105 group-hover:bg-amber-600 group-hover:text-white dark:group-hover:bg-amber-500 transition-all shadow-xs">
                        <svg class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm4.2 14.2L11 13V7h1.5v5.2l4.5 2.7-.8 1.3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900 dark:text-slate-100 group-hover:text-amber-800 dark:group-hover:text-amber-300 transition-colors">Lupa Presensi</div>
                        <div class="text-[11px] text-amber-800 dark:text-amber-300 mt-0.5 font-semibold">Klaim Absensi Kerja</div>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-800 dark:text-amber-300 group-hover:bg-amber-600 dark:group-hover:bg-amber-500 group-hover:text-white transition-all shadow-2xs">
                    <span class="material-symbols-outlined group-hover:translate-x-0.5 transition-transform text-[16px]">arrow_forward</span>
                </div>
            </a>

            <!-- 4. Aspirasi & Lapor (Rose/Red theme) -->
            <a href="{{ route('pengaduan.create') }}" class="group bg-brand-white dark:bg-slate-800 border border-rose-200 dark:border-rose-900/60 rounded-xl p-4 flex items-center justify-between hover:bg-rose-50/70 dark:hover:bg-rose-950/40 hover:border-rose-400 dark:hover:border-rose-600 hover:shadow-md transition duration-150 border-l-4 border-l-rose-500 dark:border-l-rose-400">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 group-hover:scale-105 group-hover:bg-rose-600 group-hover:text-white dark:group-hover:bg-rose-500 transition-all shadow-xs">
                        <svg class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 11v2h4v-2h-4zm-2 6.61c.96.71 2.21 1.65 3.2 2.39.4-.53.8-1.07 1.2-1.6-.99-.74-2.24-1.68-3.2-2.4-.4.54-.8 1.08-1.2 1.61zM20.4 4.6c-.4-.53-.8-1.07-1.2-1.6-.99.74-2.24 1.68-3.2 2.4.4.53.8 1.07 1.2 1.6.96-.72 2.21-1.65 3.2-2.4zM4 9c-1.1 0-2 .9-2 2v2c0 1.1.9 2 2 2h1v4h2v-4h1l5 3V6L8 9H4zm11.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900 dark:text-slate-100 group-hover:text-rose-800 dark:group-hover:text-rose-300 transition-colors">Aspirasi & Lapor</div>
                        <div class="text-[11px] text-rose-800 dark:text-rose-300 mt-0.5 font-semibold">Pengaduan Pegawai</div>
                    </div>
                </div>
                <div class="w-7 h-7 rounded-full bg-rose-100 dark:bg-rose-900/50 flex items-center justify-center text-rose-800 dark:text-rose-300 group-hover:bg-rose-600 dark:group-hover:bg-rose-500 group-hover:text-white transition-all shadow-2xs">
                    <span class="material-symbols-outlined group-hover:translate-x-0.5 transition-transform text-[16px]">arrow_forward</span>
                </div>
            </a>
        </section>

        <!-- 3. 4 SOLID KPI METRIC CARDS (Exact match to Code.html style) -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Metric 1: Skor Kinerja -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200/90 dark:border-slate-700 rounded-xl p-5 border-l-4 border-l-[#10B981] flex flex-col justify-between shadow-xs">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">SKOR KINERJA PEGAWAI</span>
                        <div class="text-3xl font-extrabold text-slate-800 dark:text-white mt-1 tabular-nums">
                            {{ number_format((float)($skorKinerja ?? 91.2), 1) }}
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-[#ECFDF5] text-[#059669] flex items-center justify-center border border-[#A7F3D0]">
                        <span class="material-symbols-outlined text-[22px]">verified</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded bg-[#ECFDF5] text-[#065F46]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                        {{ $predikatKinerja ?? 'Sangat Baik' }}
                    </span>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Evaluasi SKP 2026</span>
                </div>
            </div>

            <!-- Metric 2: Tugas Dinas (Pajokka) -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200/90 dark:border-slate-700 rounded-xl p-5 border-l-4 border-l-[#7C3AED] flex flex-col justify-between shadow-xs">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">TUGAS DINAS (PAJOKKA)</span>
                        <div class="text-3xl font-extrabold text-slate-800 dark:text-white mt-1 tabular-nums">
                            {{ $totalDinas ?? 0 }}
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-[#F5F3FF] text-[#7C3AED] flex items-center justify-center border border-[#DDD6FE]">
                        <span class="material-symbols-outlined text-[22px]">flight_takeoff</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded bg-[#F5F3FF] text-[#6D28D9]">
                        Laporan Selesai
                    </span>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Riwayat SPPD Aktif</span>
                </div>
            </div>

            <!-- Metric 3: Pengajuan Izin / Cuti -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200/90 dark:border-slate-700 rounded-xl p-5 border-l-4 border-l-[#F59E0B] flex flex-col justify-between shadow-xs">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">PENGAJUAN IZIN / CUTI</span>
                        <div class="text-3xl font-extrabold text-slate-800 dark:text-white mt-1 tabular-nums">
                            {{ $totalIzin ?? 0 }}
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-[#FFFBEB] text-[#D97706] flex items-center justify-center border border-[#FDE68A]">
                        <span class="material-symbols-outlined text-[22px]">pending_actions</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded bg-[#FFFBEB] text-[#B45309]">
                        Total Diajukan
                    </span>
                    <span class="text-xs text-amber-600 font-semibold">Cuti & Izin Pegawai</span>
                </div>
            </div>

            <!-- Metric 4: Lupa Presensi -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200/90 dark:border-slate-700 rounded-xl p-5 border-l-4 border-l-[#3B82F6] flex flex-col justify-between shadow-xs">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">LUPA PRESENSI</span>
                        <div class="text-3xl font-extrabold text-slate-800 dark:text-white mt-1 tabular-nums">
                            {{ $totalAbsen ?? 0 }}
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200">
                        <span class="material-symbols-outlined text-[22px]">alarm_off</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded bg-blue-50 text-blue-700">
                        Total Diajukan
                    </span>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Bulan Berjalan</span>
                </div>
            </div>
        </section>

        <!-- 4. ANALYTICS & MONITORING CHARTS (Bento Section - Exact Matching Style) -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Card: Capaian Kinerja ASN (7 cols) -->
            <div class="lg:col-span-7 bg-white dark:bg-slate-800 border border-slate-200/90 dark:border-slate-700 rounded-xl p-6 flex flex-col justify-between shadow-xs">
                <div>
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700">
                        <div>
                            <h2 class="text-base font-extrabold text-slate-800 dark:text-white">
                                Capaian &amp; Distribusi Evaluasi Kinerja (SKP 2026)
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Klasifikasi matriks hasil evaluasi kinerja Triwulan III Balai Wilayah VIII</p>
                        </div>
                        <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-900 pl-3 pr-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300">
                            <span>Filter: Semua Unit</span>
                            <span class="material-symbols-outlined text-[14px] text-slate-500 dark:text-slate-400">expand_more</span>
                        </div>
                    </div>

                    <!-- Donut Visual + Detail Breakdown Row -->
                    <div class="py-6 flex flex-col sm:flex-row items-center justify-around gap-6">
                        <!-- Modern SVG Donut Chart (Exact Arc Segments) -->
                        <div class="relative w-52 h-52 shrink-0 flex items-center justify-center">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" fill="none" r="38" stroke="#F1F5F9" stroke-width="14" class="dark:stroke-slate-700"></circle>
                                <circle cx="50" cy="50" fill="none" r="38" stroke="#10B981" stroke-dasharray="114.60 238.76" stroke-dashoffset="0" stroke-width="14"></circle>
                                <circle cx="50" cy="50" fill="none" r="38" stroke="#1D4ED8" stroke-dasharray="85.95 238.76" stroke-dashoffset="-114.60" stroke-width="14"></circle>
                                <circle cx="50" cy="50" fill="none" r="38" stroke="#F59E0B" stroke-dasharray="28.65 238.76" stroke-dashoffset="-200.55" stroke-width="14"></circle>
                                <circle cx="50" cy="50" fill="none" r="38" stroke="#EF4444" stroke-dasharray="9.55 238.76" stroke-dashoffset="-229.20" stroke-width="14"></circle>
                            </svg>
                            <!-- Center Cutout Metric Legend -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center select-none pointer-events-none">
                                <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">TOTAL ASN</span>
                                <span class="text-3xl font-extrabold text-slate-800 dark:text-white tabular-nums">33</span>
                                <span class="text-[10px] font-semibold text-[#10B981] bg-[#ECFDF5] px-2 py-0.5 rounded-full mt-0.5">Pegawai</span>
                            </div>
                        </div>

                        <!-- Structured Legend Table (Exact Card Rows) -->
                        <div class="flex-1 w-full space-y-2.5">
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full bg-[#10B981] shrink-0"></span>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Sangat Baik</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold font-mono text-slate-800 dark:text-white tabular-nums">16 Pegawai</span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#ECFDF5] text-[#065F46] tabular-nums">48%</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full bg-[#1D4ED8] shrink-0"></span>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Baik (Sesuai Ekspektasi)</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold font-mono text-slate-800 dark:text-white tabular-nums">12 Pegawai</span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-100 text-blue-700 tabular-nums">36%</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full bg-[#F59E0B] shrink-0"></span>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Butuh Perbaikan</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold font-mono text-slate-800 dark:text-white tabular-nums">4 Pegawai</span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#FFFBEB] text-[#B45309] tabular-nums">12%</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full bg-[#EF4444] shrink-0"></span>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Belum Lengkap</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold font-mono text-slate-800 dark:text-white tabular-nums">1 Pegawai</span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#FEF2F2] text-[#B91C1C] tabular-nums">4%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Sync Status -->
                <div class="pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
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
            <div class="lg:col-span-5 bg-white dark:bg-slate-800 border border-slate-200/90 dark:border-slate-700 rounded-xl p-6 flex flex-col justify-between shadow-xs">
                <div>
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700">
                        <div>
                            <h2 class="text-base font-extrabold text-slate-800 dark:text-white">Presensi &amp; Disiplin Hari Ini</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Status log harian presensi elektronik</p>
                        </div>
                        <span class="px-2.5 py-0.5 bg-[#ECFDF5] text-[#065F46] font-bold text-[11px] rounded-full border border-[#A7F3D0]">
                            93.9% Hadir
                        </span>
                    </div>

                    <!-- Secondary Donut Chart & Presence Stats -->
                    <div class="py-5 flex items-center gap-6">
                        <!-- Compact Solid Donut -->
                        <div class="relative w-40 h-40 shrink-0 flex items-center justify-center">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" fill="none" r="36" stroke="#F1F5F9" stroke-width="12" class="dark:stroke-slate-700"></circle>
                                <circle cx="50" cy="50" fill="none" r="36" stroke="#10B981" stroke-dasharray="191.8 226.19" stroke-dashoffset="0" stroke-width="12"></circle>
                                <circle cx="50" cy="50" fill="none" r="36" stroke="#1D4ED8" stroke-dasharray="20.58 226.19" stroke-dashoffset="-191.8" stroke-width="12"></circle>
                                <circle cx="50" cy="50" fill="none" r="36" stroke="#F59E0B" stroke-dasharray="13.8 226.19" stroke-dashoffset="-212.38" stroke-width="12"></circle>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                <span class="text-2xl font-black text-slate-800 dark:text-white tabular-nums">28</span>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Di Kantor</span>
                            </div>
                        </div>

                        <!-- Mini Indicators -->
                        <div class="flex-1 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#10B981]"></span>
                                    <span>Hadir Tepat Waktu</span>
                                </span>
                                <span class="font-bold text-slate-800 dark:text-white tabular-nums">28 Hari</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#1D4ED8]"></span>
                                    <span>Perjalanan Dinas (DL)</span>
                                </span>
                                <span class="font-bold text-slate-800 dark:text-white tabular-nums">{{ $totalDinas ?? 0 }} Kegiatan</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#F59E0B]"></span>
                                    <span>Izin / Cuti Tahunan</span>
                                </span>
                                <span class="font-bold text-slate-800 dark:text-white tabular-nums">{{ $totalIzin ?? 0 }} Hari</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#EF4444]"></span>
                                    <span>Lupa Presensi</span>
                                </span>
                                <span class="font-bold text-slate-800 dark:text-white tabular-nums">{{ $totalAbsen ?? 0 }} Kali</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Direct Operational Shortcuts -->
                <div class="pt-3 border-t border-slate-100 dark:border-slate-700 space-y-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Tindakan Mandiri Pegawai:</span>
                    <div class="grid grid-cols-2 gap-2.5">
                        <a href="{{ route('absen.create') }}" class="flex items-center justify-center gap-2 py-2 px-3 bg-brand-navy hover:bg-[#202C3B] text-white rounded-xl text-xs font-bold transition shadow-xs">
                            <span class="material-symbols-outlined text-[16px]">alarm_off</span>
                            <span>Klaim Presensi</span>
                        </a>
                        <a href="{{ route('izin.create') }}" class="flex items-center justify-center gap-2 py-2 px-3 bg-brand-white dark:bg-slate-700 border border-brand-sky dark:border-slate-600 text-brand-navy dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-brand-beige dark:hover:bg-slate-600 transition">
                            <span class="material-symbols-outlined text-[16px]">event_note</span>
                            <span>Ajukan Cuti</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. BOTTOM TABLE (MONITORING PENGAJUAN & DOKUMEN SAYA) -->
        <section class="bg-brand-white dark:bg-slate-800 rounded-xl border border-brand-sky dark:border-slate-700 shadow-xs overflow-hidden">
            <!-- Table Header Toolbar -->
            <div class="p-4 border-b border-brand-sky/60 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h2 class="font-bold text-sm text-brand-navy dark:text-white">
                        Riwayat & Status Pengajuan Saya
                    </h2>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Monitoring permohonan dinas, izin cuti, dan presensi terkini</p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('pajokka.create') }}" class="px-3.5 py-1.5 bg-brand-navy hover:bg-[#202C3B] text-white text-xs font-bold rounded-lg transition shadow-xs flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        <span>Buat Permohonan Baru</span>
                    </a>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-brand-navy dark:text-slate-300">
                    <thead class="bg-brand-beige dark:bg-slate-900/80 text-[11px] font-bold text-brand-navy uppercase tracking-wider border-b border-brand-sky dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-3.5">JENIS LAYANAN</th>
                            <th class="px-6 py-3.5">KETERANGAN / TANGGAL</th>
                            <th class="px-6 py-3.5">STATUS VERIFIKASI</th>
                            <th class="px-6 py-3.5">DOKUMEN</th>
                            <th class="px-6 py-3.5 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/80 font-medium">
                        @php
                            $allSubmissions = collect();

                            foreach ($myReports as $r) {
                                $allSubmissions->push([
                                    'type' => 'pajokka',
                                    'label' => 'Perjalanan Dinas (Pajokka)',
                                    'dot' => 'bg-blue-600',
                                    'badge' => 'Surat Tugas No. ' . ($r->nomor_st ?? '042/ST/BKP/2026'),
                                    'desc' => $r->maksud_tujuan ?? $r->uraian ?? 'Perjalanan dinas pelatihan',
                                    'date' => ($r->tgl_mulai ? \Carbon\Carbon::parse($r->tgl_mulai)->format('d M Y') : '12 Okt 2026') . ' - ' . ($r->tgl_selesai ? \Carbon\Carbon::parse($r->tgl_selesai)->format('d M Y') : '15 Okt 2026'),
                                    'status' => $r->status ?? 'submitted',
                                    'doc' => 'Laporan-SPPD.pdf',
                                    'icon' => 'picture_as_pdf',
                                    'icon_color' => 'text-rose-500',
                                    'url' => route('pajokka.show', $r->id),
                                    'created_at' => $r->created_at,
                                ]);
                            }

                            foreach ($myIzin as $iz) {
                                $allSubmissions->push([
                                    'type' => 'izin',
                                    'label' => 'Izin / Cuti ' . ucfirst($iz->jenis_izin ?? 'Tahunan'),
                                    'dot' => 'bg-amber-500',
                                    'badge' => 'Pengajuan Izin Mandiri',
                                    'desc' => $iz->keterangan ?? 'Keperluan keluarga',
                                    'date' => ($iz->tanggal_mulai ? \Carbon\Carbon::parse($iz->tanggal_mulai)->format('d M Y') : '20 Okt 2026') . ' - ' . ($iz->tanggal_selesai ? \Carbon\Carbon::parse($iz->tanggal_selesai)->format('d M Y') : '22 Okt 2026'),
                                    'status' => $iz->status ?? 'pending',
                                    'doc' => $iz->dokumen ? basename($iz->dokumen) : 'Form-Izin.pdf',
                                    'icon' => 'description',
                                    'icon_color' => 'text-blue-600',
                                    'url' => route('izin.index'),
                                    'created_at' => $iz->created_at,
                                ]);
                            }

                            foreach ($myAbsen as $ab) {
                                $allSubmissions->push([
                                    'type' => 'absen',
                                    'label' => 'Klaim Presensi Elektronik',
                                    'dot' => 'bg-emerald-500',
                                    'badge' => ucfirst($ab->jenis_absen ?? 'Lupa Absen'),
                                    'desc' => $ab->alasan ?? 'Kendala jaringan perangkat',
                                    'date' => $ab->tanggal_lupa ? \Carbon\Carbon::parse($ab->tanggal_lupa)->format('d M Y') : '05 Okt 2026',
                                    'status' => $ab->status ?? 'approved',
                                    'doc' => $ab->bukti_foto ? basename($ab->bukti_foto) : 'Bukti-Presensi.png',
                                    'icon' => 'task_alt',
                                    'icon_color' => 'text-emerald-600',
                                    'url' => route('absen.show', $ab->id),
                                    'created_at' => $ab->created_at,
                                ]);
                            }

                            foreach ($myPengaduan as $pg) {
                                $allSubmissions->push([
                                    'type' => 'pengaduan',
                                    'label' => 'Aspirasi & Lapor',
                                    'dot' => 'bg-purple-500',
                                    'badge' => ucfirst($pg->kategori ?? 'Pengaduan Sarpras'),
                                    'desc' => $pg->isi_laporan ?? 'Pengaduan fasilitas',
                                    'date' => $pg->created_at ? $pg->created_at->format('d M Y') : '10 Okt 2026',
                                    'status' => $pg->status ?? 'pending',
                                    'doc' => $pg->lampiran ? basename($pg->lampiran) : 'Lampiran.pdf',
                                    'icon' => 'report_problem',
                                    'icon_color' => 'text-purple-600',
                                    'url' => route('pengaduan.index'),
                                    'created_at' => $pg->created_at,
                                ]);
                            }

                            $sortedSubmissions = $allSubmissions->sortByDesc('created_at')->take(5);
                        @endphp

                        @forelse($sortedSubmissions as $item)
                            @php
                                $statusClass = match(strtolower($item['status'])) {
                                    'approved', 'disetujui' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/50',
                                    'rejected', 'ditolak' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/50',
                                    'revisi', 'revision' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/50',
                                    'submitted', 'selesai' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/50',
                                    default => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/50',
                                };
                                $statusLabel = match(strtolower($item['status'])) {
                                    'approved', 'disetujui' => 'Disetujui',
                                    'rejected', 'ditolak' => 'Ditolak',
                                    'revisi', 'revision' => 'Perlu Revisi',
                                    'submitted' => 'Menunggu Review',
                                    'pending' => 'Pending',
                                    default => ucfirst($item['status']),
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/40 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full {{ $item['dot'] }}"></span>
                                        <span class="font-bold text-slate-800 dark:text-white">{{ $item['label'] }}</span>
                                    </div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $item['badge'] }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-700 dark:text-slate-200 font-semibold">{{ $item['date'] }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 truncate max-w-xs">{{ $item['desc'] }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 text-slate-600 dark:text-slate-300 text-xs">
                                        <span class="material-symbols-outlined text-[16px] {{ $item['icon_color'] }}">{{ $item['icon'] }}</span>
                                        <span class="truncate max-w-[120px]">{{ $item['doc'] }}</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ $item['url'] }}" class="inline-flex items-center space-x-1 px-3 py-1 bg-white dark:bg-slate-700 hover:bg-slate-100 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-600 font-bold rounded-lg text-xs transition">
                                        <span>Lihat Status</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                    Belum ada log aktivitas tugas / presensi tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                <div>
                    Menampilkan 3 pengajuan layanan mandiri aktif terakhir
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('pajokka.index') }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline">Semua Perjalanan Dinas &rarr;</a>
                    <a href="{{ route('izin.index') }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline">Semua Izin &rarr;</a>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
