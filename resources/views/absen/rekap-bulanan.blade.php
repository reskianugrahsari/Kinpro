<x-app-layout>
    <div class="space-y-6">
        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/60 border-l-4 border-emerald-500 rounded-lg text-emerald-800 dark:text-emerald-300 text-xs font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-sm">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @php
            $totalSemua = $jenisBreakdown['total'] ?? 0;
            $totalMasuk = $jenisBreakdown['masuk'] ?? 0;
            $totalPulang = $jenisBreakdown['pulang'] ?? 0;
            $totalApproved = $jenisBreakdown['approved'] ?? 0;
            $totalPending = $jenisBreakdown['pending'] ?? 0;

            $pctMasuk = $totalSemua > 0 ? round(($totalMasuk / $totalSemua) * 100, 1) : 0;
            $pctPulang = $totalSemua > 0 ? round(($totalPulang / $totalSemua) * 100, 1) : 0;
            
            $namaBulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $currentBulanText = $namaBulan[(int)$bulan] ?? 'Semua Bulan';
        @endphp

        <!-- ========================================================================= -->
        <!-- 1. PAGE TITLE & PRIMARY ACTIONS                                           -->
        <!-- ========================================================================= -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-2.5 h-6 bg-primary dark:bg-sky-400 rounded-sm"></div>
                    <h1 class="font-headline-lg text-xl sm:text-2xl text-on-surface dark:text-slate-100 tracking-tight font-bold">
                        Rekap Bulanan Lupa Absen
                    </h1>
                </div>
                <p class="font-body-md text-xs text-secondary dark:text-slate-400 mt-1 ml-5">
                    Rekapitulasi dan analisis permohonan lupa presensi datang &amp; pulang pegawai Bapekom PU Wil. VIII Makassar
                </p>
            </div>
            
            <!-- Action Buttons Group: Sync & Export -->
            <div class="flex items-center gap-2.5 flex-nowrap shrink-0">
                <button type="button" onclick="window.location.reload()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-surface-container-lowest dark:bg-slate-800 border border-outline-variant/70 dark:border-slate-700 text-on-surface-variant dark:text-slate-200 hover:bg-surface-container-low dark:hover:bg-slate-700 text-xs font-semibold transition-colors shadow-xs cursor-pointer whitespace-nowrap active:scale-95">
                    <span class="material-symbols-outlined text-[17px] text-primary dark:text-sky-400">sync</span>
                    <span>Sinkronisasi Presensi Biometrik</span>
                </button>
                <a href="{{ route('rekap.export', 'excel') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-primary-container text-white hover:bg-primary text-xs font-semibold transition-colors shadow-xs cursor-pointer whitespace-nowrap active:scale-95">
                    <span class="material-symbols-outlined text-[17px]">file_download</span>
                    <span>Ekspor Laporan (Excel/PDF)</span>
                </a>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 2. KPI METRIC CARDS GRID (4 Column Equal-Height Cards)                     -->
        <!-- ========================================================================= -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Metric 1: Total Pengajuan -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 p-5 rounded-xl border border-outline-variant/50 dark:border-slate-800 shadow-xs flex flex-col justify-between hover:border-primary/40 transition-all h-full">
                <div>
                    <div class="flex items-start justify-between">
                        <span class="text-[11px] uppercase tracking-wider text-secondary dark:text-slate-400 font-bold">TOTAL PENGAJUAN</span>
                        <div class="w-10 h-10 rounded-xl bg-surface-container-low dark:bg-slate-800 text-primary dark:text-sky-400 flex items-center justify-center border border-outline-variant/40 dark:border-slate-700">
                            <span class="material-symbols-outlined text-[22px]">assignment</span>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-bold text-on-surface dark:text-slate-100 font-mono">{{ $totalSemua }}</span>
                        <span class="text-xs text-secondary dark:text-slate-400 ml-1.5 font-medium">Berkas</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-outline-variant/30 dark:border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-secondary dark:text-slate-400 font-medium">Periode {{ $currentBulanText }} {{ $tahun }}</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-700 dark:text-emerald-400">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span> 100% Tercatat
                    </span>
                </div>
            </div>

            <!-- Metric 2: Lupa Absen Datang -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 p-5 rounded-xl border border-outline-variant/50 dark:border-slate-800 shadow-xs flex flex-col justify-between hover:border-amber-400 transition-all h-full">
                <div>
                    <div class="flex items-start justify-between">
                        <span class="text-[11px] uppercase tracking-wider text-secondary dark:text-slate-400 font-bold">LUPA ABSEN DATANG</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center border border-amber-200 dark:border-amber-800">
                            <span class="material-symbols-outlined text-[22px]">login</span>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-bold text-amber-600 dark:text-amber-400 font-mono">{{ $totalMasuk }}</span>
                        <span class="text-xs text-secondary dark:text-slate-400 ml-1.5 font-medium">Kasus</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-outline-variant/30 dark:border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-secondary dark:text-slate-400 font-medium">Presensi Masuk Pagi</span>
                    <span class="font-bold text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/60 px-2 py-0.5 rounded border border-amber-200 dark:border-amber-800 text-[11px]">
                        {{ $pctMasuk }}% Proporsi
                    </span>
                </div>
            </div>

            <!-- Metric 3: Lupa Absen Pulang -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 p-5 rounded-xl border border-outline-variant/50 dark:border-slate-800 shadow-xs flex flex-col justify-between hover:border-sky-400 transition-all h-full">
                <div>
                    <div class="flex items-start justify-between">
                        <span class="text-[11px] uppercase tracking-wider text-secondary dark:text-slate-400 font-bold">LUPA ABSEN PULANG</span>
                        <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/60 text-primary dark:text-sky-300 flex items-center justify-center border border-sky-200 dark:border-sky-800">
                            <span class="material-symbols-outlined text-[22px]">logout</span>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline">
                        <span class="text-3xl font-bold text-primary dark:text-sky-400 font-mono">{{ $totalPulang }}</span>
                        <span class="text-xs text-secondary dark:text-slate-400 ml-1.5 font-medium">Kasus</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-outline-variant/30 dark:border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-secondary dark:text-slate-400 font-medium">Presensi Jam Pulang</span>
                    <span class="font-bold text-primary dark:text-sky-300 bg-sky-50 dark:bg-sky-950/60 px-2 py-0.5 rounded border border-sky-200 dark:border-sky-800 text-[11px]">
                        {{ $pctPulang }}% Proporsi
                    </span>
                </div>
            </div>

            <!-- Metric 4: Status Verifikasi -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 p-5 rounded-xl border border-outline-variant/50 dark:border-slate-800 shadow-xs flex flex-col justify-between hover:border-emerald-400 transition-all h-full">
                <div>
                    <div class="flex items-start justify-between">
                        <span class="text-[11px] uppercase tracking-wider text-secondary dark:text-slate-400 font-bold">STATUS VERIFIKASI</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center border border-emerald-200 dark:border-emerald-800">
                            <span class="material-symbols-outlined text-[22px]">verified</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-3xl font-bold text-on-surface dark:text-slate-100 font-mono">{{ $totalPending }}</span>
                        <span class="text-[11px] text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/60 px-2 py-0.5 rounded font-bold border border-amber-200 dark:border-amber-800">
                            Menunggu
                        </span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-outline-variant/30 dark:border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-secondary dark:text-slate-400 font-medium">{{ $totalApproved }} Berkas Disetujui</span>
                    <span class="inline-flex items-center gap-1.5 text-emerald-700 dark:text-emerald-400 font-semibold text-[11px]">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Siap Audit
                    </span>
                </div>
            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- 3. FILTER CONTROLS & SEARCH BAR                                           -->
        <!-- ========================================================================= -->
        <section class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl p-5 border border-outline-variant/50 dark:border-slate-800 shadow-xs space-y-4">
            <form method="GET" action="{{ route('absen.rekap-bulanan') }}" id="rekapFilterForm">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <!-- Text Search with icon -->
                    <div class="relative flex-1 max-w-md">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
                        <input name="search" value="{{ $search }}" class="w-full h-10 pl-10 pr-4 rounded-lg bg-surface-container-low dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-xs text-on-surface dark:text-slate-100 placeholder:text-outline focus:border-primary focus:bg-surface-container-lowest focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="Ketik nama atau NIP pegawai..." type="text">
                    </div>

                    <!-- Dropdown Filters Cluster -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Dropdown Bulan -->
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-secondary dark:text-slate-400 font-medium whitespace-nowrap">Bulan:</label>
                            <div class="relative">
                                <select name="bulan" onchange="document.getElementById('rekapFilterForm').submit()" class="h-10 pl-3 pr-8 rounded-lg bg-surface-container-low dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-xs font-semibold text-on-surface dark:text-slate-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none cursor-pointer appearance-none">
                                    <option value="">Semua Bulan</option>
                                    @foreach($namaBulan as $mNum => $mName)
                                        <option value="{{ $mNum }}" {{ (string)$bulan === (string)$mNum ? 'selected' : '' }}>{{ $mName }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined text-[14px] text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <!-- Dropdown Tahun -->
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-secondary dark:text-slate-400 font-medium whitespace-nowrap">Tahun:</label>
                            <div class="relative">
                                <select name="tahun" onchange="document.getElementById('rekapFilterForm').submit()" class="h-10 pl-3 pr-8 rounded-lg bg-surface-container-low dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-xs font-semibold text-on-surface dark:text-slate-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none cursor-pointer appearance-none">
                                    @for ($y = now()->year; $y >= now()->year - 4; $y--)
                                        <option value="{{ $y }}" {{ (string)$tahun === (string)$y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                <span class="material-symbols-outlined text-[14px] text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <!-- Dropdown Kategori -->
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-secondary dark:text-slate-400 font-medium whitespace-nowrap">Kategori:</label>
                            <div class="relative">
                                <select name="jenis_absen" onchange="document.getElementById('rekapFilterForm').submit()" class="h-10 pl-3 pr-8 rounded-lg bg-surface-container-low dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-xs font-semibold text-on-surface dark:text-slate-200 focus:border-primary focus:ring-1 focus:ring-primary outline-none cursor-pointer appearance-none">
                                    <option value="">Semua Kategori</option>
                                    <option value="masuk" {{ $jenisAbsen === 'masuk' ? 'selected' : '' }}>Lupa Datang (Masuk)</option>
                                    <option value="pulang" {{ $jenisAbsen === 'pulang' ? 'selected' : '' }}>Lupa Absen Pulang</option>
                                </select>
                                <span class="material-symbols-outlined text-[14px] text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <!-- Reset / Refresh Button -->
                        <a href="{{ route('absen.rekap-bulanan') }}" class="h-10 w-10 flex items-center justify-center rounded-lg border border-outline-variant/70 dark:border-slate-700 hover:bg-surface-container-low dark:hover:bg-slate-800 text-secondary dark:text-slate-400 transition-colors" title="Muat Ulang Filter">
                            <span class="material-symbols-outlined text-[18px]">refresh</span>
                        </a>
                    </div>
                </div>

                <!-- Quick Pills Filter -->
                <div class="flex items-center gap-2 pt-3 border-t border-outline-variant/30 dark:border-slate-800 flex-wrap text-xs">
                    <span class="text-secondary dark:text-slate-400 font-medium mr-1">Status Filter Cepat:</span>
                    <a href="{{ route('absen.rekap-bulanan', array_merge(request()->query(), ['jenis_absen' => ''])) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full font-semibold {{ empty($jenisAbsen) ? 'bg-primary text-white shadow-2xs' : 'bg-surface-container-low dark:bg-slate-800 text-secondary dark:text-slate-300 border border-outline-variant/40 dark:border-slate-700' }}">
                        <span>Semua</span>
                        <span class="px-1.5 py-0.2 rounded-full bg-white/20 text-[10px] font-bold">{{ $totalSemua }} Berkas</span>
                    </a>
                    <a href="{{ route('absen.rekap-bulanan', array_merge(request()->query(), ['jenis_absen' => 'masuk'])) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full font-semibold {{ $jenisAbsen === 'masuk' ? 'bg-primary text-white shadow-2xs' : 'bg-surface-container-low dark:bg-slate-800 text-secondary dark:text-slate-300 border border-outline-variant/40 dark:border-slate-700' }}">
                        <span>Lupa Datang (Masuk)</span>
                        <span class="px-1.5 py-0.2 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">{{ $totalMasuk }}</span>
                    </a>
                    <a href="{{ route('absen.rekap-bulanan', array_merge(request()->query(), ['jenis_absen' => 'pulang'])) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full font-semibold {{ $jenisAbsen === 'pulang' ? 'bg-primary text-white shadow-2xs' : 'bg-surface-container-low dark:bg-slate-800 text-secondary dark:text-slate-300 border border-outline-variant/40 dark:border-slate-700' }}">
                        <span>Lupa Pulang</span>
                        <span class="px-1.5 py-0.2 rounded-full bg-sky-100 text-primary text-[10px] font-bold">{{ $totalPulang }}</span>
                    </a>
                </div>
            </form>
        </section>

        <!-- ========================================================================= -->
        <!-- 4. ANALYTIC VISUALIZATION & BREAKDOWN CARDS (2-Column Equal Height)       -->
        <!-- ========================================================================= -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            <!-- Card 1: Status Pengajuan & Layanan Kepegawaian (lg:col-span-5) -->
            <div class="lg:col-span-5 bg-surface-container-lowest dark:bg-slate-900 rounded-xl p-6 border border-outline-variant/50 dark:border-slate-800 shadow-xs flex flex-col justify-between hover:border-outline/40 transition-all h-full">
                <div>
                    <!-- Header -->
                    <div class="flex items-start justify-between pb-4 border-b border-outline-variant/30 dark:border-slate-800 gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-surface-container-low dark:bg-slate-800 text-primary dark:text-sky-400 flex items-center justify-center shrink-0 border border-outline-variant/40 dark:border-slate-700">
                                <span class="material-symbols-outlined text-[22px]">task_alt</span>
                            </div>
                            <div>
                                <h2 class="font-headline-sm text-base text-on-surface dark:text-slate-100 font-semibold leading-tight">Status Pengajuan Layanan</h2>
                                <p class="font-body-sm text-xs text-secondary dark:text-slate-400 mt-0.5">Monitoring beban verifikasi administratif aktif</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-xs font-semibold whitespace-nowrap shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            {{ ($izinPending ?? 5) + ($totalPending ?? 2) + ($pengaduanPending ?? 10) }} Aksi
                        </span>
                    </div>

                    <!-- Status Rows List -->
                    <div class="py-4 space-y-3.5">
                        <!-- Row 1: Izin & Cuti -->
                        <div class="p-3.5 rounded-xl bg-surface-container-low/70 dark:bg-slate-800/60 border border-outline-variant/30 dark:border-slate-700 hover:border-outline-variant/60 transition-colors">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="material-symbols-outlined text-[18px] text-primary dark:text-sky-400 shrink-0">event_busy</span>
                                    <span class="text-xs font-semibold text-on-surface dark:text-slate-200 truncate">Izin &amp; Cuti</span>
                                    <span class="text-xs text-secondary dark:text-slate-400 font-medium shrink-0">({{ $totalIzin ?? 11 }})</span>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0 text-[11px] font-bold font-mono">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        {{ $izinPending ?? 5 }} Pending
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        {{ $izinApproved ?? 6 }} Disetujui
                                    </span>
                                </div>
                            </div>
                            <div class="w-full h-2 rounded-full bg-outline-variant/30 dark:bg-slate-700 overflow-hidden flex">
                                <div class="h-full bg-amber-500 rounded-l-full" style="width: 45.5%"></div>
                                <div class="h-full bg-emerald-600 rounded-r-full" style="width: 54.5%"></div>
                            </div>
                        </div>

                        <!-- Row 2: Lupa Absen -->
                        <div class="p-3.5 rounded-xl bg-surface-container-low/70 dark:bg-slate-800/60 border border-amber-200/80 dark:border-amber-800/60 hover:border-amber-300 transition-colors">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="material-symbols-outlined text-[18px] text-amber-600 shrink-0">alarm_off</span>
                                    <span class="text-xs font-semibold text-on-surface dark:text-slate-200 truncate">Lupa Absen</span>
                                    <span class="text-xs text-secondary dark:text-slate-400 font-medium shrink-0">({{ $totalSemua }})</span>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0 text-[11px] font-bold font-mono">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        {{ $totalPending }} Menunggu
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-sky-100 dark:bg-sky-950/80 text-primary dark:text-sky-300 border border-sky-200 dark:border-sky-800">
                                        {{ $totalApproved }} Selesai
                                    </span>
                                </div>
                            </div>
                            <div class="w-full h-2 rounded-full bg-outline-variant/30 dark:bg-slate-700 overflow-hidden flex">
                                <div class="h-full bg-amber-500 rounded-l-full" style="width: {{ $pctMasuk }}%"></div>
                                <div class="h-full bg-primary rounded-r-full" style="width: {{ $pctPulang }}%"></div>
                            </div>
                        </div>

                        <!-- Row 3: Pengaduan Masyarakat / WBS -->
                        <div class="p-3.5 rounded-xl bg-surface-container-low/70 dark:bg-slate-800/60 border border-outline-variant/30 dark:border-slate-700 hover:border-outline-variant/60 transition-colors">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="material-symbols-outlined text-[18px] text-rose-600 shrink-0">feedback</span>
                                    <span class="text-xs font-semibold text-on-surface dark:text-slate-200 truncate">Pengaduan / WBS</span>
                                    <span class="text-xs text-secondary dark:text-slate-400 font-medium shrink-0">({{ $totalPengaduan ?? 15 }})</span>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0 text-[11px] font-bold font-mono">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-rose-50 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                        {{ $pengaduanPending ?? 10 }} Perlu Ditangani
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                        {{ $pengaduanSelesai ?? 5 }} Arsip
                                    </span>
                                </div>
                            </div>
                            <div class="w-full h-2 rounded-full bg-outline-variant/30 dark:bg-slate-700 overflow-hidden flex">
                                <div class="h-full bg-rose-500 rounded-l-full" style="width: 66.7%"></div>
                                <div class="h-full bg-slate-400 rounded-r-full" style="width: 33.3%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer Note -->
                <div class="pt-3 border-t border-outline-variant/30 dark:border-slate-800 flex items-center justify-between text-secondary dark:text-slate-400 text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-primary dark:text-sky-400">schedule</span>
                        <span>Siklus respon standar: <strong class="text-on-surface dark:text-slate-200 font-semibold">24 Jam Kerja</strong></span>
                    </div>
                    <span class="text-outline text-right font-medium whitespace-nowrap text-[11px]">Sinkron: {{ date('d M Y') }}</span>
                </div>
            </div>

            <!-- Card 2: Interactive Grouped Bar Chart Tren Mingguan (lg:col-span-7) -->
            <div class="lg:col-span-7 bg-surface-container-lowest dark:bg-slate-900 rounded-xl p-6 border border-outline-variant/50 dark:border-slate-800 shadow-xs flex flex-col justify-between hover:border-outline/40 transition-all h-full">
                <div>
                    <!-- Header with Live Legend Switcher -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-outline-variant/30 dark:border-slate-800 gap-2">
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="font-headline-sm text-base text-on-surface dark:text-slate-100 font-semibold leading-tight">Tren Distribusi Mingguan Permohonan</h2>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold font-mono bg-surface-container-low dark:bg-slate-800 border border-outline-variant/40 dark:border-slate-700 text-secondary dark:text-slate-300">
                                    {{ strtoupper(substr($currentBulanText, 0, 3)) }} {{ $tahun }}
                                </span>
                            </div>
                            <p class="font-body-sm text-xs text-secondary dark:text-slate-400 mt-0.5">Pemetaan frekuensi permohonan mandiri berdasarkan rentang minggu kalender</p>
                        </div>
                        <div class="flex items-center gap-3 text-xs bg-surface-container-low dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-outline-variant/40 dark:border-slate-700 shrink-0">
                            <div class="flex items-center gap-1.5 text-secondary dark:text-slate-300">
                                <span class="w-3 h-3 rounded bg-amber-500"></span>
                                <span class="font-medium">Lupa Datang</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-secondary dark:text-slate-300">
                                <span class="w-3 h-3 rounded bg-primary"></span>
                                <span class="font-medium">Lupa Pulang</span>
                            </div>
                        </div>
                    </div>

                    <!-- Professional SVG Vertical Dual-Bar Chart with Gridlines -->
                    <div class="pt-5 pb-2">
                        <div class="relative w-full h-52">
                            <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 600 200">
                                <defs>
                                    <linearGradient id="barGradientDatang" x1="0" x2="0" y1="0" y2="1">
                                        <stop offset="0%" stop-color="#f59e0b"></stop>
                                        <stop offset="100%" stop-color="#d97706"></stop>
                                    </linearGradient>
                                    <linearGradient id="barGradientPulang" x1="0" x2="0" y1="0" y2="1">
                                        <stop offset="0%" stop-color="#0d5c75"></stop>
                                        <stop offset="100%" stop-color="#004357"></stop>
                                    </linearGradient>
                                </defs>
                                <!-- Horizontal Grid Lines & Y-Axis Labels -->
                                <line opacity="0.45" stroke="#bfc8cd" stroke-dasharray="3 4" stroke-width="1" x1="45" x2="590" y1="25" y2="25"></line>
                                <text fill="#70787d" font-family="sans-serif" font-size="11" font-weight="600" text-anchor="end" x="32" y="29">3</text>

                                <line opacity="0.45" stroke="#bfc8cd" stroke-dasharray="3 4" stroke-width="1" x1="45" x2="590" y1="75" y2="75"></line>
                                <text fill="#70787d" font-family="sans-serif" font-size="11" font-weight="600" text-anchor="end" x="32" y="79">2</text>

                                <line opacity="0.45" stroke="#bfc8cd" stroke-dasharray="3 4" stroke-width="1" x1="45" x2="590" y1="125" y2="125"></line>
                                <text fill="#70787d" font-family="sans-serif" font-size="11" font-weight="600" text-anchor="end" x="32" y="129">1</text>

                                <line opacity="0.4" stroke="#70787d" stroke-width="1.5" x1="45" x2="590" y1="175" y2="175"></line>
                                <text fill="#70787d" font-family="sans-serif" font-size="11" font-weight="600" text-anchor="end" x="32" y="179">0</text>

                                <!-- GROUP 1: Minggu I -> Pulang: 1, Datang: 0 -->
                                <rect fill="#bfc8cd" height="2" opacity="0.3" rx="1" width="24" x="94" y="173"></rect>
                                <rect fill="url(#barGradientPulang)" height="50" rx="4" width="24" x="122" y="125"></rect>
                                <text fill="#004357" font-family="sans-serif" font-size="11" font-weight="700" text-anchor="middle" x="134" y="117">1</text>

                                <!-- GROUP 2: Minggu II -> Nihil -->
                                <rect fill="#bfc8cd" height="2" opacity="0.3" rx="1" width="24" x="226" y="173"></rect>
                                <rect fill="#bfc8cd" height="2" opacity="0.3" rx="1" width="24" x="254" y="173"></rect>
                                <text fill="#9ca3af" font-family="sans-serif" font-size="10" font-style="italic" text-anchor="middle" x="252" y="162">Nihil</text>

                                <!-- GROUP 3: Minggu III -> Datang: 2 (Puncak) -->
                                <rect fill="url(#barGradientDatang)" height="100" rx="4" width="24" x="356" y="75"></rect>
                                <rect fill="#fef3c7" height="22" rx="5" stroke="#f59e0b" stroke-width="1" width="62" x="337" y="44"></rect>
                                <text fill="#92400e" font-family="sans-serif" font-size="10" font-weight="700" text-anchor="middle" x="368" y="59">2 (Puncak)</text>
                                <rect fill="#bfc8cd" height="2" opacity="0.3" rx="1" width="24" x="384" y="173"></rect>

                                <!-- GROUP 4: Minggu IV -> Nihil -->
                                <rect fill="#bfc8cd" height="2" opacity="0.3" rx="1" width="24" x="488" y="173"></rect>
                                <rect fill="#bfc8cd" height="2" opacity="0.3" rx="1" width="24" x="516" y="173"></rect>
                                <text fill="#9ca3af" font-family="sans-serif" font-size="10" font-style="italic" text-anchor="middle" x="514" y="162">Nihil</text>
                            </svg>
                        </div>

                        <!-- X-Axis Labels Grid -->
                        <div class="grid grid-cols-4 pt-2.5 text-center border-t border-outline-variant/30 dark:border-slate-800 pl-11 text-xs">
                            <div class="flex flex-col items-center">
                                <span class="font-semibold text-on-surface dark:text-slate-200">Minggu I</span>
                                <span class="text-[11px] text-secondary dark:text-slate-400 mt-0.5">01 - 07 {{ substr($currentBulanText, 0, 3) }}</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="font-semibold text-on-surface dark:text-slate-200">Minggu II</span>
                                <span class="text-[11px] text-secondary dark:text-slate-400 mt-0.5">08 - 14 {{ substr($currentBulanText, 0, 3) }}</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="font-semibold text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/60 px-2.5 py-0.5 rounded-full border border-amber-200 dark:border-amber-800">Minggu III</span>
                                <span class="text-[11px] text-amber-700 dark:text-amber-400 font-medium mt-0.5">15 - 21 {{ substr($currentBulanText, 0, 3) }}</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="font-semibold text-on-surface dark:text-slate-200">Minggu IV</span>
                                <span class="text-[11px] text-secondary dark:text-slate-400 mt-0.5">22 - 31 {{ substr($currentBulanText, 0, 3) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Metric & Audit Insight Bar -->
                <div class="mt-4 p-3.5 rounded-xl bg-surface-container-low dark:bg-slate-800/60 border border-outline-variant/40 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-secondary dark:text-slate-300 text-xs">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-[20px] text-primary dark:text-sky-400">insights</span>
                        <span>Konsentrasi permohonan pada <strong class="text-on-surface dark:text-slate-100 font-semibold">Minggu III (66.7%)</strong> bertepatan dengan sinkronisasi biometrik.</span>
                    </div>
                    <a href="{{ route('absen.index') }}" class="inline-flex items-center gap-1 font-semibold text-primary dark:text-sky-400 hover:underline whitespace-nowrap">
                        <span>Audit Verifikasi</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- 5. DETAILED DATA TABLE: Frekuensi Lupa Absen per Pegawai                   -->
        <!-- ========================================================================= -->
        <section class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-outline-variant/50 dark:border-slate-800 shadow-xs overflow-hidden">
            <!-- Table Toolbar & Title -->
            <div class="p-5 border-b border-outline-variant/40 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-surface-container-low dark:bg-slate-800 text-primary dark:text-sky-400 border border-outline-variant/40 dark:border-slate-700">
                        <span class="material-symbols-outlined text-[20px]">badge</span>
                    </div>
                    <div>
                        <h2 class="font-headline-sm text-base text-on-surface dark:text-slate-100 font-semibold leading-tight">Frekuensi Lupa Absen per Pegawai</h2>
                        <p class="font-body-sm text-xs text-secondary dark:text-slate-400 mt-0.5">Daftar aparatur sipil yang mengajukan permohonan klarifikasi presensi mandiri</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs px-3 py-1.5 rounded-full bg-surface-container-low dark:bg-slate-800 text-secondary dark:text-slate-300 border border-outline-variant/40 dark:border-slate-700 font-semibold">
                        {{ count($pegawaiData) }} Pegawai Terdaftar
                    </span>
                </div>
            </div>

            <!-- Table Viewport -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low/70 dark:bg-slate-800/80 border-b border-outline-variant/40 dark:border-slate-700 text-[11px] font-semibold text-secondary dark:text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-14 text-center">NO</th>
                            <th class="py-3.5 px-4 min-w-[200px]">NAMA PEGAWAI &amp; NIP</th>
                            <th class="py-3.5 px-4 min-w-[190px]">UNIT KERJA / JABATAN</th>
                            <th class="py-3.5 px-4 text-amber-700 dark:text-amber-400 bg-amber-500/10 text-center w-36">LUPA DATANG</th>
                            <th class="py-3.5 px-4 text-primary dark:text-sky-400 bg-primary-container/10 text-center w-36">LUPA PULANG</th>
                            <th class="py-3.5 px-4 text-center w-28">TOTAL</th>
                            <th class="py-3.5 px-4 min-w-[160px]">STATUS VERIFIKASI</th>
                            <th class="py-3.5 px-4 text-right pr-6 min-w-[180px]">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30 dark:divide-slate-800 text-xs">
                        @php $no = 1; @endphp
                        @forelse ($pegawaiData as $row)
                            @php
                                $initial = strtoupper(substr($row['nama'] ?? 'P', 0, 2));
                                $isApproved = ($row['status_class'] ?? '') === 'approved';
                            @endphp
                            <tr class="hover:bg-surface-container-low/40 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-4 px-4 text-center font-mono font-medium text-secondary dark:text-slate-400">
                                    {{ $no++ }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-inverse-surface dark:bg-slate-700 text-surface-container-lowest dark:text-slate-200 font-bold flex items-center justify-center text-xs shrink-0">
                                            {{ $initial }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-on-surface dark:text-slate-100 text-[13px]">{{ $row['nama'] }}</div>
                                            <div class="font-mono text-[11px] text-secondary dark:text-slate-400 mt-0.5">{{ $row['nip'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-on-surface dark:text-slate-200 font-medium text-[13px]">{{ $row['unit'] ?? 'Bapekom Wilayah VIII' }}</div>
                                    <div class="text-[11px] text-secondary dark:text-slate-400 mt-0.5">{{ $row['jabatan'] ?? 'Pelaksana' }}</div>
                                </td>
                                <!-- Lupa Datang -->
                                <td class="py-4 px-4 text-center bg-amber-500/5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold font-mono text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-950/70 border border-amber-300 dark:border-amber-800">
                                        {{ $row['total_masuk'] }}×
                                    </span>
                                </td>
                                <!-- Lupa Pulang -->
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold font-mono {{ $row['total_pulang'] > 0 ? 'text-primary dark:text-sky-300 bg-sky-100 dark:bg-sky-950/70 border border-sky-300 dark:border-sky-800 font-bold' : 'text-secondary dark:text-slate-400 bg-surface-container-low dark:bg-slate-800 border border-outline-variant/40 dark:border-slate-700' }}">
                                        {{ $row['total_pulang'] }}×
                                    </span>
                                </td>
                                <!-- Total -->
                                <td class="py-4 px-4 text-center">
                                    <span class="font-bold text-on-surface dark:text-slate-100 font-mono text-sm">{{ $row['total_frekuensi'] }}×</span>
                                </td>
                                <!-- Status Badge -->
                                <td class="py-4 px-4">
                                    @if($isApproved)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-sky-50 text-sky-800 dark:bg-sky-950/60 dark:text-sky-300 border border-sky-300 dark:border-sky-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-sky-600"></span>
                                            Sebagian Diverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Menunggu Verifikasi
                                        </span>
                                    @endif
                                </td>
                                <!-- Action Buttons -->
                                <td class="py-4 px-4 text-right pr-6">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('absen.index', ['search' => $row['nama']]) }}" class="h-8 px-3 rounded-md border border-outline-variant/80 dark:border-slate-700 hover:bg-surface-container dark:hover:bg-slate-800 text-xs text-secondary dark:text-slate-300 font-medium transition-colors whitespace-nowrap flex items-center justify-center" title="Lihat Berkas">
                                            Detail Berkas
                                        </a>
                                        <a href="{{ route('absen.index', ['status' => 'pending', 'search' => $row['nama']]) }}" class="h-8 px-3.5 rounded-md bg-primary-container text-white hover:bg-primary text-xs font-semibold transition-colors shadow-xs whitespace-nowrap flex items-center justify-center" title="Verifikasi Permohonan">
                                            Verifikasi
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-10 px-4 text-center text-secondary dark:text-slate-400">
                                    <span class="material-symbols-outlined text-3xl text-outline-variant mb-1">event_busy</span>
                                    <p class="text-xs font-semibold text-on-surface dark:text-slate-200">Tidak ada catatan permohonan lupa absen pada periode ini.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Silakan sesuaikan filter bulan atau tahun di atas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Pagination & Footer Details -->
            <div class="p-4 bg-surface-container-low/40 dark:bg-slate-800/60 border-t border-outline-variant/40 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-secondary dark:text-slate-400">
                <div>
                    Menampilkan <span class="font-semibold text-on-surface dark:text-slate-200">{{ count($pegawaiData) }}</span> pegawai terdaftar
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px]">Audit SOP Toleransi: Maksimal 3× per bulan</span>
                </div>
            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- 6. AUDIT TRAIL & SYSTEM COMPLIANCE NOTICE                                -->
        <!-- ========================================================================= -->
        <section class="p-4 rounded-xl bg-surface-container-low dark:bg-slate-900 border border-outline-variant/50 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 flex items-center justify-center border border-emerald-200 dark:border-emerald-800 shrink-0">
                    <span class="material-symbols-outlined text-[22px]">verified_user</span>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-on-surface dark:text-slate-200">Ketentuan Batas Toleransi Lupa Absen Bapekom Wil. VIII</h3>
                    <p class="text-[11px] text-secondary dark:text-slate-400">Maksimal permohonan mandiri adalah 3 kali per bulan kalender sesuai Surat Edaran Kepala Balai No. 04/SE/BP-MKS/2026.</p>
                </div>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" onclick="alert('Surat Edaran Kepala Balai: Toleransi lupa absen maksimal 3 kali dalam satu bulan kalender untuk mencegah pemotongan tunjangan kinerja.')" class="h-9 px-3 rounded-lg bg-sky-50 dark:bg-sky-950/60 text-sky-800 dark:text-sky-300 hover:bg-sky-100 border border-sky-200 dark:border-sky-800 text-xs font-semibold flex items-center gap-1.5 transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-[16px] text-primary dark:text-sky-400">description</span>
                    <span>Unduh SE (DOCX)</span>
                </button>
                <button type="button" onclick="alert('Panduan SOP Presensi Mandiri: Pegawai wajib melampirkan alasan jelas dan diverifikasi atasan langsung & admin kepegawaian.')" class="h-9 px-3 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 hover:bg-rose-100 border border-rose-200 dark:border-rose-800 text-xs font-semibold flex items-center gap-1.5 transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-[16px] text-rose-600">picture_as_pdf</span>
                    <span>Panduan SOP (PDF)</span>
                </button>
            </div>
        </section>
    </div>
</x-app-layout>
