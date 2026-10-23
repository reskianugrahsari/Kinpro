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

        <!-- ========================================================================= -->
        <!-- 1. BREADCRUMBS & TOP ACTION BAR                                           -->
        <!-- ========================================================================= -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="max-w-xl">
                <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1 font-label-md">
                    <span>Dashboard</span>
                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                    <span class="text-primary dark:text-sky-400 font-semibold">Rekap & Statistik</span>
                </nav>
                <h1 class="font-headline-md text-xl sm:text-2xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">
                    Rekap & Statistik Kepegawaian
                </h1>
                <p class="font-body-md text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Monitoring real-time distribusi personil, capaian kinerja, dan validasi berkas dinas Bapekom PU Makassar.
                </p>
            </div>
            
            <!-- Controls Group: Periode, Sync & Export (Sejajar Rapi Tanpa Scroll) -->
            <div class="flex items-center gap-2 shrink-0">
                <!-- Filter Periode Dropdown -->
                <div class="relative">
                    <select class="h-9 pl-2.5 pr-7 rounded-lg bg-surface-container-lowest dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 shadow-xs focus:border-primary focus:ring-1 focus:ring-primary appearance-none cursor-pointer">
                        <option>TA {{ $tahun ?? date('Y') }} (Semua Kuartal)</option>
                        <option>Kuartal IV - {{ $tahun ?? date('Y') }} (Aktif)</option>
                        <option>Kuartal III - {{ $tahun ?? date('Y') }}</option>
                        <option>Kuartal II - {{ $tahun ?? date('Y') }}</option>
                    </select>
                    <span class="material-symbols-outlined text-slate-400 dark:text-slate-500 text-[16px] absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                </div>

                <!-- Refresh / Sync Button -->
                <button type="button" onclick="window.location.reload()" class="h-9 px-2.5 rounded-lg bg-surface-container-lowest dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 shadow-xs flex items-center gap-1.5 transition-colors cursor-pointer active:scale-95 whitespace-nowrap">
                    <span class="material-symbols-outlined text-sm">sync</span>
                    <span>Sinkronisasi</span>
                </button>

                <!-- Export Report Split Button -->
                <div class="inline-flex rounded-lg shadow-xs" x-data="{ openExport: false }" @click.outside="openExport = false">
                    <a href="{{ route('rekap.export', 'excel') }}" class="h-9 px-3 bg-primary-container text-white text-xs font-bold rounded-l-lg hover:bg-primary transition-colors flex items-center gap-1.5 cursor-pointer active:scale-95 whitespace-nowrap">
                        <span class="material-symbols-outlined text-sm">download</span>
                        <span>Ekspor Rekap</span>
                    </a>
                    <div class="relative">
                        <button type="button" @click="openExport = !openExport" class="h-9 px-1.5 bg-primary-container border-l border-white/20 text-white rounded-r-lg hover:bg-primary transition-colors flex items-center justify-center cursor-pointer" title="Format Ekspor Lainnya">
                            <span class="material-symbols-outlined text-base">arrow_drop_down</span>
                        </button>
                        <div x-show="openExport" class="absolute right-0 top-full mt-1 w-36 bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/60 dark:border-slate-800 rounded-lg shadow-lg py-1 z-20" style="display: none;">
                            <a href="{{ route('rekap.export', 'excel') }}" class="block px-3 py-1.5 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Unduh Excel (.xlsx)</a>
                            <a href="{{ route('rekap.export', 'csv') }}" class="block px-3 py-1.5 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Unduh CSV (.csv)</a>
                            <a href="{{ route('rekap.export', 'pdf') }}" target="_blank" class="block px-3 py-1.5 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Cetak PDF (.pdf)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 2. ROW 1: 4 TOP KPI SUMMARY CARDS                                        -->
        <!-- ========================================================================= -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Card 1: TOTAL PEGAWAI -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between relative overflow-hidden group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-label-md text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold">TOTAL PEGAWAI</p>
                        <h3 class="font-headline-lg text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2 tracking-tight">{{ $totalPegawai }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100/80 dark:border-blue-900/60 group-hover:bg-blue-600 group-hover:text-white transition-all duration-200">
                        <span class="material-symbols-outlined text-2xl">group</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Terdaftar Aktif
                    </span>
                    <span class="text-[11px] text-slate-400 dark:text-slate-500 font-label-md">100% Valid BKN</span>
                </div>
            </div>

            <!-- Card 2: PENILAIAN KINERJA -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between relative overflow-hidden group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-label-md text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold">PENILAIAN KINERJA</p>
                        <div class="flex items-baseline gap-2 mt-2">
                            <h3 class="font-headline-lg text-3xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">{{ $totalPenilaian }}</h3>
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">dari {{ $totalPegawai }} SKP</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100/80 dark:border-indigo-900/60 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-200">
                        <span class="material-symbols-outlined text-2xl">monitoring</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                        Rata-rata: {{ $avgPenilaian }}
                    </span>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium font-label-md">Predikat Baik</span>
                </div>
            </div>

            <!-- Card 3: PERJALANAN DINAS -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between relative overflow-hidden group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-label-md text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold">PERJALANAN DINAS</p>
                        <h3 class="font-headline-lg text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2 tracking-tight">{{ $totalReports }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-100/80 dark:border-amber-900/60 group-hover:bg-amber-600 group-hover:text-white transition-all duration-200">
                        <span class="material-symbols-outlined text-2xl">description</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                        {{ $reportsApproved }} Disetujui
                    </span>
                    <span class="text-[11px] text-slate-400 dark:text-slate-500 font-label-md">Tahun Anggaran {{ $tahun ?? date('Y') }}</span>
                </div>
            </div>

            <!-- Card 4: TOTAL BIAYA DINAS -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between relative overflow-hidden group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-label-md text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold">TOTAL BIAYA DINAS</p>
                        <h3 class="font-headline-md text-2xl font-bold text-slate-900 dark:text-slate-100 mt-2 tracking-tight">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100/80 dark:border-emerald-900/60 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-200">
                        <span class="material-symbols-outlined text-2xl">payments</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-medium">Akumulasi Realisasi</span>
                    <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 font-label-md">Sesuai POK {{ $tahun ?? date('Y') }}</span>
                </div>
            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- 3. ROW 2: BENTO MAIN DATA & OPERATIONAL VERIFICATION PANELS               -->
        <!-- ========================================================================= -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- LEFT PANEL (6 cols): DISTRIBUSI PEGAWAI PER UNIT KERJA -->
            <div class="lg:col-span-6 bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">domain</span>
                            </div>
                            <div>
                                <h3 class="font-headline-sm text-base font-bold text-slate-900 dark:text-slate-100">Distribusi Pegawai per Unit Kerja</h3>
                                <p class="font-body-sm text-xs text-slate-500 dark:text-slate-400">Komposisi penempatan ASN dan PPPK Bapekom PU Makassar</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                            {{ count($pegawaiPerUnit) }} Unit Terdata
                        </span>
                    </div>

                    <!-- Table Header -->
                    <div class="grid grid-cols-12 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 py-3 border-b border-slate-100 dark:border-slate-800 mt-2">
                        <div class="col-span-8">UNIT KERJA</div>
                        <div class="col-span-4 text-right">JUMLAH PEGAWAI</div>
                    </div>

                    <!-- Unit Items List -->
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($pegawaiPerUnit as $unit)
                            @php
                                $pct = $totalPegawai > 0 ? round(($unit->pegawai_count / $totalPegawai) * 100, 1) : 0;
                                $barColor = match(true) {
                                    $pct > 50 => 'bg-primary-container',
                                    $pct > 10 => 'bg-indigo-500',
                                    default => 'bg-slate-300 dark:bg-slate-700'
                                };
                            @endphp
                            <div class="py-4 space-y-2 hover:bg-slate-50/50 dark:hover:bg-slate-800/40 rounded-lg px-2 -mx-2 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full {{ $barColor }}"></span>
                                        <span class="font-body-md text-xs font-bold text-slate-800 dark:text-slate-200">{{ $unit->nama }}</span>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-md bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 font-label-md text-xs font-bold border border-sky-100 dark:border-sky-900/60">
                                        {{ $unit->pegawai_count }} Pegawai
                                    </span>
                                </div>
                                <!-- Visual Bar -->
                                <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden flex">
                                    <div class="{{ $barColor }} h-full rounded-full transition-all duration-500" style="width: {{ $pct }}%;"></div>
                                </div>
                                <div class="flex justify-between items-center text-[11px] text-slate-500 dark:text-slate-400 pt-0.5">
                                    <span>Porsi Penugasan Aparatur</span>
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $pct }}% dari total aparatur</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-xs text-slate-400">Belum ada data distribusi unit.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Bottom Summary Box -->
                <div class="mt-6 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700 flex items-center justify-between text-xs">
                    <span class="text-slate-600 dark:text-slate-300 font-medium">Kapasitas Formasi Optimal: <strong>35 Pegawai</strong></span>
                    <span class="text-emerald-700 dark:text-emerald-300 font-bold bg-emerald-100/60 dark:bg-emerald-950/80 px-2.5 py-0.5 rounded border border-emerald-200 dark:border-emerald-800">
                        Terisi {{ $totalPegawai > 0 ? round(($totalPegawai / 35) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>

            <!-- RIGHT PANEL (6 cols): STATUS PENGAJUAN & LAYANAN KEPEGAWAIAN -->
            <div class="lg:col-span-6 bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">assignment_turned_in</span>
                            </div>
                            <div>
                                <h3 class="font-headline-sm text-base font-bold text-slate-900 dark:text-slate-100">Status Pengajuan & Layanan Kepegawaian</h3>
                                <p class="font-body-sm text-xs text-slate-500 dark:text-slate-400">Monitoring beban verifikasi administratif aktif</p>
                            </div>
                        </div>
                        @php
                            $totalMenunggu = ($izinPending ?? 0) + ($absenPending ?? 0) + ($pengaduanPending ?? 0);
                        @endphp
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                            {{ $totalMenunggu }} Menunggu Aksi
                        </span>
                    </div>

                    <!-- Service Verification Rows -->
                    <div class="mt-4 space-y-3.5">
                        <!-- Item 1: Izin & Cuti -->
                        @php
                            $pctIzinPending = $totalIzin > 0 ? round(($izinPending / $totalIzin) * 100, 1) : 0;
                            $pctIzinApproved = $totalIzin > 0 ? round(($izinApproved / $totalIzin) * 100, 1) : 0;
                        @endphp
                        <div class="p-4 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700 transition-all shadow-xs">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                <div>
                                    <h4 class="font-headline-sm text-[14px] font-bold text-slate-800 dark:text-slate-200">Izin & Cuti</h4>
                                    <p class="font-body-sm text-xs text-slate-500 dark:text-slate-400">Total Pengajuan: <strong class="text-slate-700 dark:text-slate-300">{{ $totalIzin }}</strong></p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-amber-50 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800 text-[11px] font-bold">
                                        {{ $izinPending }} Pending
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-[11px] font-bold">
                                        {{ $izinApproved }} Disetujui
                                    </span>
                                </div>
                            </div>
                            <!-- Micro Progress Bar -->
                            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden flex">
                                <div class="bg-amber-500 h-full" style="width: {{ $pctIzinPending }}%;"></div>
                                <div class="bg-emerald-500 h-full" style="width: {{ $pctIzinApproved }}%;"></div>
                            </div>
                        </div>

                        <!-- Item 2: Lupa Absen -->
                        @php
                            $absenSelesai = max(0, $totalAbsen - $absenPending);
                            $pctAbsenPending = $totalAbsen > 0 ? round(($absenPending / $totalAbsen) * 100, 1) : 0;
                            $pctAbsenSelesai = $totalAbsen > 0 ? round(($absenSelesai / $totalAbsen) * 100, 1) : 0;
                        @endphp
                        <div class="p-4 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700 transition-all shadow-xs">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                <div>
                                    <h4 class="font-headline-sm text-[14px] font-bold text-slate-800 dark:text-slate-200">Lupa Absen (Don't Forget)</h4>
                                    <p class="font-body-sm text-xs text-slate-500 dark:text-slate-400">Total Pengajuan: <strong class="text-slate-700 dark:text-slate-300">{{ $totalAbsen }}</strong></p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-amber-50 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800 text-[11px] font-bold">
                                        {{ $absenPending }} Menunggu Verifikasi
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 text-[11px] font-semibold">
                                        {{ $absenSelesai }} Selesai
                                    </span>
                                </div>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden flex">
                                <div class="bg-amber-500 h-full" style="width: {{ $pctAbsenPending }}%;"></div>
                                <div class="bg-slate-400 dark:bg-slate-600 h-full" style="width: {{ $pctAbsenSelesai }}%;"></div>
                            </div>
                        </div>

                        <!-- Item 3: Pengaduan Masyarakat / WBS -->
                        @php
                            $pengaduanSelesai = max(0, $totalPengaduan - $pengaduanPending);
                            $pctPengaduanPending = $totalPengaduan > 0 ? round(($pengaduanPending / $totalPengaduan) * 100, 1) : 0;
                            $pctPengaduanSelesai = $totalPengaduan > 0 ? round(($pengaduanSelesai / $totalPengaduan) * 100, 1) : 0;
                        @endphp
                        <div class="p-4 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700 transition-all shadow-xs">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                <div>
                                    <h4 class="font-headline-sm text-[14px] font-bold text-slate-800 dark:text-slate-200">Pengaduan Masyarakat / WBS</h4>
                                    <p class="font-body-sm text-xs text-slate-500 dark:text-slate-400">Total Laporan: <strong class="text-slate-700 dark:text-slate-300">{{ $totalPengaduan }}</strong></p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-rose-50 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800 text-[11px] font-bold">
                                        {{ $pengaduanPending }} Perlu Ditangani
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 text-[11px] font-semibold">
                                        {{ $pengaduanSelesai }} Arsip
                                    </span>
                                </div>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden flex">
                                <div class="bg-rose-500 h-full" style="width: {{ $pctPengaduanPending }}%;"></div>
                                <div class="bg-slate-400 dark:bg-slate-600 h-full" style="width: {{ $pctPengaduanSelesai }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Audit Timestamp Footer -->
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between text-slate-400 dark:text-slate-500 text-[11px] font-label-md gap-2">
                    <span>Siklus respon verifikasi standar: 24 Jam Kerja</span>
                    <span>Data sinkronisasi otomatis per {{ date('d F Y') }}</span>
                </div>
            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- 4. ROW 3: DETAILED DISCIPLINE & COMPLIANCE INSIGHTS                       -->
        <!-- ========================================================================= -->
        <section class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800 gap-4">
                <div>
                    <h3 class="font-headline-sm text-base font-bold text-slate-900 dark:text-slate-100">Kedisiplinan & Rekap Presensi Bulan Ini</h3>
                    <p class="font-body-sm text-xs text-slate-500 dark:text-slate-400">Ringkasan kepatuhan presensi biometrik & portal daring Bapekom PU Wil. VIII</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Tingkat Kehadiran: 96.4%
                    </span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-5">
                <div class="p-4 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700">
                    <div class="text-slate-500 dark:text-slate-400 text-xs font-semibold mb-1">Tepat Waktu (On-Time)</div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">92.1%</div>
                    <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium mt-1">↑ 1.4% dibanding bulan lalu</p>
                </div>
                <div class="p-4 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700">
                    <div class="text-slate-500 dark:text-slate-400 text-xs font-semibold mb-1">Terlambat Masuk (TL)</div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">4.3%</div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium mt-1">Total kejadian tercatat minim</p>
                </div>
                <div class="p-4 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700">
                    <div class="text-slate-500 dark:text-slate-400 text-xs font-semibold mb-1">Izin Resmi / Dinas Luar</div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">3.6%</div>
                    <p class="text-[11px] text-blue-600 dark:text-blue-400 font-medium mt-1">Disertai Surat Tugas lengkap</p>
                </div>
                <div class="p-4 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700">
                    <div class="text-slate-500 dark:text-slate-400 text-xs font-semibold mb-1">Tanpa Keterangan (Alpa)</div>
                    <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-400">0.0%</div>
                    <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium mt-1">Nol pelanggaran disiplin berat</p>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
