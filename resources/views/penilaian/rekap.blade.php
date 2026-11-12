<x-app-layout>
    <!-- Google Font & Material Symbols for Executive Infographic Look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

    <!-- ========================================================================= -->
    <!-- INTERACTIVE TOOLBAR (HIDDEN ON PRINT)                                     -->
    <!-- ========================================================================= -->
    <div class="no-print max-w-5xl mx-auto mb-4">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-3 shadow-sm flex flex-wrap items-center justify-between gap-3">
            <!-- Left: Back Button & Context Info -->
            <div class="flex items-center gap-2">
                <a href="{{ route('penilaian.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    <span>Kembali ke Penilaian</span>
                </a>
                <span class="text-xs text-slate-400">|</span>
                <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                    Poster Rekapitulasi Predikat SKP (Fit 1 Halaman A4)
                </span>
            </div>

            <!-- Right: Filter & Print Buttons -->
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('penilaian.rekap') }}" class="flex items-center gap-2">
                    <select name="periode" onchange="this.form.submit()"
                            class="text-xs font-semibold rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 py-1.5 pl-3 pr-8 focus:ring-teal-500 focus:border-teal-500 cursor-pointer shadow-2xs">
                        <option value="tw1" {{ $periode === 'tw1' ? 'selected' : '' }}>Triwulan I (TW1)</option>
                        <option value="tw2" {{ $periode === 'tw2' ? 'selected' : '' }}>Triwulan II (TW2)</option>
                        <option value="tw3" {{ $periode === 'tw3' ? 'selected' : '' }}>Triwulan III (TW3)</option>
                        <option value="tw4" {{ $periode === 'tw4' ? 'selected' : '' }}>Triwulan IV (TW4)</option>
                        <option value="tahunan" {{ $periode === 'tahunan' ? 'selected' : '' }}>Tahunan (Final)</option>
                    </select>

                    <select name="tahun" onchange="this.form.submit()"
                            class="text-xs font-semibold rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 py-1.5 pl-3 pr-8 focus:ring-teal-500 focus:border-teal-500 cursor-pointer shadow-2xs">
                        @for($y = date('Y') + 1; $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ (int)$tahun === (int)$y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </form>

                <button type="button"
                        onclick="window.print()"
                        class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold rounded-lg bg-[#0A1931] hover:bg-slate-800 text-white shadow-xs transition cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">print</span>
                    <span>Cetak / Simpan PDF</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- REKAPITULASI DOCUMENT SHEET (FIT 1 HALAMAN A4, 100% IDENTIK DENGAN CETAK) -->
    <!-- ========================================================================= -->
    <div class="max-w-5xl mx-auto bg-white text-slate-900 rounded-2xl shadow-xl border border-slate-200 overflow-hidden relative print:shadow-none" id="infografisSheet">

        <div class="p-4 sm:p-6 space-y-3.5 print:p-3 print:space-y-2.5">

            <!-- ----------------------------------------------------------------- -->
            <!-- HEADER SECTION (BALANCED 3-COLUMN LOGO BAR + CLEAN TITLE)         -->
            <!-- ----------------------------------------------------------------- -->
            <header class="pb-3 border-b-2 border-slate-200 print-break-inside-avoid">
                <!-- Top Brand Logos & Building Photo (Strictly Aligned) -->
                <div class="flex items-center justify-between gap-3">
                    <!-- Left: Logo KemenPU -->
                    <div class="flex items-center h-12 shrink-0">
                        @if(file_exists(public_path('images/rekap-skp/logo-kemenpu.png')))
                            <img src="{{ asset('images/rekap-skp/logo-kemenpu.png') }}" alt="Logo KemenPU" class="h-10 sm:h-12 w-auto object-contain">
                        @else
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-lg bg-[#FFB800] flex items-center justify-center font-black text-[#0A1931] text-base shadow-xs">
                                    PU
                                </div>
                                <div class="leading-tight">
                                    <div class="font-black text-sm text-[#0A1931] tracking-tight">KEMENPU</div>
                                    <div class="text-[8px] font-bold text-slate-500 uppercase tracking-wider">Sigap Membangun Negeri</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Center: Logo BPSDM Wilayah VIII Makassar -->
                    <div class="flex items-center justify-center h-12 shrink-0">
                        @if(file_exists(public_path('images/rekap-skp/logo-bpsdm.png')))
                            <img src="{{ asset('images/rekap-skp/logo-bpsdm.png') }}" alt="Logo BPSDM" class="h-10 sm:h-12 w-auto object-contain">
                        @else
                            <div class="text-center">
                                <div class="text-[10px] font-extrabold text-[#0A1931] uppercase tracking-wider">BPSDM WILAYAH VIII</div>
                                <div class="text-[8px] font-bold text-slate-600 leading-tight">BALAI PENGEMBANGAN KOMPETENSI<br>MAKASSAR</div>
                            </div>
                        @endif
                    </div>

                    <!-- Right: Photo Gedung Bapekom Makassar (Clean Proportional Card) -->
                    <div class="shrink-0 flex items-center h-12">
                        @if(file_exists(public_path('images/rekap-skp/gedung-bapekom.jpg')))
                            <div class="relative rounded-lg overflow-hidden border border-slate-200 shadow-2xs w-32 sm:w-40 h-10 sm:h-12">
                                <img src="{{ asset('images/rekap-skp/gedung-bapekom.jpg') }}" alt="Gedung Bapekom VIII Makassar" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-[#0A1931]/40 flex items-end p-1">
                                    <span class="text-[7.5px] font-bold text-white tracking-wider uppercase leading-none drop-shadow-xs">BAPEKOM VIII MAKASSAR</span>
                                </div>
                            </div>
                        @else
                            <div class="w-32 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-[9px] text-slate-600 font-bold">
                                BAPEKOM VIII MAKASSAR
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Double Divider Line (Classic Formal PU Accent) -->
                <div class="mt-2.5 pt-0.5">
                    <div class="h-1 bg-[#0A1931] rounded-full"></div>
                    <div class="h-0.5 bg-[#FFB800] mt-0.5 rounded-full"></div>
                </div>

                <!-- Center Title Banner -->
                <div class="text-center mt-2.5">
                    <div class="inline-block bg-[#FFB800] text-[#0A1931] font-black text-[10px] px-3 py-0.5 rounded-full uppercase tracking-[0.2em] shadow-2xs mb-1">
                        REKAPITULASI
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-[#0A1931] tracking-tight leading-tight">
                        PREDIKAT SKP
                    </h2>
                    <div class="text-sm sm:text-base font-black text-slate-800 mt-0.5">
                        {{ $periodeTitle }} <span class="text-amber-600">{{ $tahun }}</span>
                    </div>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">
                        Penilaian Kinerja Pegawai di Lingkungan Balai Pengembangan Kompetensi PUPR Wilayah VIII Makassar
                    </p>
                </div>
            </header>

            <!-- ----------------------------------------------------------------- -->
            <!-- SECTION 01: RINGKASAN HASIL                                       -->
            <!-- ----------------------------------------------------------------- -->
            <section class="space-y-1.5 print-break-inside-avoid">
                <!-- Section Header Banner with Marker -->
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-[#FFB800] text-[#0A1931] font-black text-[11px] flex items-center justify-center shrink-0 shadow-2xs">
                        01
                    </div>
                    <div class="bg-[#0A1931] text-white font-extrabold text-[10.5px] uppercase tracking-wider px-2.5 py-0.5 rounded shadow-2xs">
                        RINGKASAN HASIL
                    </div>
                </div>

                <!-- Summary Box -->
                <div class="bg-slate-50/90 border border-slate-200 border-l-4 border-l-[#0A1931] rounded-xl p-2.5 flex items-center gap-3 relative overflow-hidden shadow-2xs">
                    <!-- Icon Circle -->
                    <div class="w-8 h-8 rounded-full bg-[#0A1931] text-[#FFB800] flex items-center justify-center shrink-0 shadow-xs">
                        <span class="material-symbols-outlined text-[18px]">fact_check</span>
                    </div>
                    <!-- Body Text -->
                    <div class="text-[11.5px] text-slate-700 leading-snug font-medium">
                        Rekapitulasi predikat SKP <strong>{{ $periodeTitle }} Tahun {{ $tahun }}</strong> disusun langsung dari hasil penilaian kinerja pegawai yang telah diinputkan. Hasil penilaian terbagi dalam kategori predikat <span class="inline-flex items-center px-1.5 py-0.2 rounded font-bold text-[#00828A] bg-teal-50 border border-teal-200">Sangat Baik ({{ $countSB }} pegawai)</span> dan <span class="inline-flex items-center px-1.5 py-0.2 rounded font-bold text-amber-700 bg-amber-50 border border-amber-200">Baik ({{ $countB }} pegawai)</span>@if(isset($countC) && $countC > 0), serta <span class="inline-flex items-center px-1.5 py-0.2 rounded font-bold text-slate-700 bg-slate-100 border border-slate-200">Cukup ({{ $countC }} pegawai)</span>@endif.
                    </div>
                </div>
            </section>

            <!-- ----------------------------------------------------------------- -->
            <!-- SECTION 02: PREDIKAT SKP (STATISTIK & DISTRIBUSI)                 -->
            <!-- ----------------------------------------------------------------- -->
            <section class="space-y-2 print-break-inside-avoid">
                <!-- Section Header Banner with Marker -->
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-[#FFB800] text-[#0A1931] font-black text-[11px] flex items-center justify-center shrink-0 shadow-2xs">
                        02
                    </div>
                    <div class="bg-[#0A1931] text-white font-extrabold text-[10.5px] uppercase tracking-wider px-2.5 py-0.5 rounded shadow-2xs">
                        PREDIKAT SKP {{ $periodeTitle }} {{ $tahun }}
                    </div>
                </div>

                <!-- 2 Comparison Cards (Sangat Baik & Baik) Side by Side -->
                <div class="grid grid-cols-2 gap-3.5">
                    <!-- Card 1: Sangat Baik (Teal) -->
                    <div class="bg-white border-2 border-teal-500/40 rounded-xl overflow-hidden shadow-xs flex flex-col justify-between">
                        <!-- Top Header Pill -->
                        <div class="bg-[#00828A] text-white px-3 py-1.5 flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-white">verified</span>
                                <span class="font-extrabold text-xs tracking-wide">Sangat Baik</span>
                            </div>
                            <span class="bg-white text-[#00828A] font-black text-[11px] px-2 py-0.2 rounded-full shadow-2xs">
                                {{ $pctSB }}%
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-2.5 flex flex-col justify-between space-y-2">
                            <div class="flex items-baseline justify-between">
                                <div class="text-2xl font-black text-[#00828A] font-mono tracking-tight leading-none">
                                    {{ $countSB }} <span class="text-[11px] font-bold text-slate-600 font-sans">pegawai</span>
                                </div>
                                <span class="text-[9px] font-bold text-teal-800 bg-teal-50 border border-teal-200 px-1.5 py-0.5 rounded uppercase tracking-wider">
                                    Kategori Utama &bull; SKP &ge; 85.0
                                </span>
                            </div>

                            <!-- Progress Distribution Bar -->
                            <div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden border border-slate-200">
                                    <div class="bg-[#00828A] h-1.5 rounded-full" style="width: {{ str_replace(',', '.', $pctSB) }}%"></div>
                                </div>
                            </div>

                            <!-- Visual Tokens (Compact, Symmetrical) -->
                            <div class="pt-1.5 border-t border-slate-100">
                                <div class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center justify-between">
                                    <span>Representasi Pegawai:</span>
                                    <span class="font-mono text-teal-800 font-bold">{{ $countSB }} / {{ $totalPegawaiDinilai }}</span>
                                </div>
                                <div class="flex flex-wrap gap-1 items-center">
                                    @for($i = 0; $i < $countSB; $i++)
                                        <div class="w-4.5 h-4.5 rounded bg-teal-50 border border-teal-200 text-[#00828A] flex items-center justify-center text-[8px] font-bold shadow-2xs" title="Pegawai {{ $i + 1 }}">
                                            <span class="material-symbols-outlined text-[11px]">person</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Baik (Gold/Amber) -->
                    <div class="bg-white border-2 border-amber-500/40 rounded-xl overflow-hidden shadow-xs flex flex-col justify-between">
                        <!-- Top Header Pill -->
                        <div class="bg-[#D97706] text-white px-3 py-1.5 flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-white">star</span>
                                <span class="font-extrabold text-xs tracking-wide">Baik</span>
                            </div>
                            <span class="bg-white text-[#D97706] font-black text-[11px] px-2 py-0.2 rounded-full shadow-2xs">
                                {{ $pctB }}%
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-2.5 flex flex-col justify-between space-y-2">
                            <div class="flex items-baseline justify-between">
                                <div class="text-2xl font-black text-[#D97706] font-mono tracking-tight leading-none">
                                    {{ $countB }} <span class="text-[11px] font-bold text-slate-600 font-sans">pegawai</span>
                                </div>
                                <span class="text-[9px] font-bold text-amber-800 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded uppercase tracking-wider">
                                    Kategori Standar &bull; SKP 75 - 84.9
                                </span>
                            </div>

                            <!-- Progress Distribution Bar -->
                            <div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden border border-slate-200">
                                    <div class="bg-[#D97706] h-1.5 rounded-full" style="width: {{ str_replace(',', '.', $pctB) }}%"></div>
                                </div>
                            </div>

                            <!-- Visual Tokens (Compact, Symmetrical) -->
                            <div class="pt-1.5 border-t border-slate-100">
                                <div class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center justify-between">
                                    <span>Representasi Pegawai:</span>
                                    <span class="font-mono text-amber-800 font-bold">{{ $countB }} / {{ $totalPegawaiDinilai }}</span>
                                </div>
                                <div class="flex flex-wrap gap-1 items-center">
                                    @for($i = 0; $i < $countB; $i++)
                                        <div class="w-4.5 h-4.5 rounded bg-amber-50 border border-amber-200 text-[#D97706] flex items-center justify-center text-[8px] font-bold shadow-2xs" title="Pegawai {{ $i + 1 }}">
                                            <span class="material-symbols-outlined text-[11px]">person</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Balanced 3-Block Summary Strip -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-2 grid grid-cols-3 gap-2 text-xs shadow-2xs">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded bg-[#0A1931] text-[#FFB800] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-sm">groups</span>
                        </div>
                        <div>
                            <div class="text-[9px] uppercase font-bold text-slate-500 tracking-wider">Total Pegawai Dinilai</div>
                            <div class="text-[11px] font-black text-slate-800">
                                <span class="text-teal-700 font-mono">{{ $totalPegawaiDinilai }} orang</span>
                                <span class="text-[9px] font-normal text-slate-500">({{ $totalPegawaiBalai ?? $totalPegawaiDinilai }} terdata)</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 border-l border-slate-200 pl-2">
                        <div class="w-7 h-7 rounded bg-[#0A1931] text-[#FFB800] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-sm">analytics</span>
                        </div>
                        <div>
                            <div class="text-[9px] uppercase font-bold text-slate-500 tracking-wider">Persentase Penilaian</div>
                            <div class="text-[11px] font-black text-slate-800">
                                <span class="text-teal-700 font-mono">{{ $pctPenilaian }}%</span>
                                <span class="text-[9px] font-semibold text-emerald-600">Terpenuhi</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 border-l border-slate-200 pl-2">
                        <div class="w-7 h-7 rounded bg-[#0A1931] text-[#FFB800] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-sm">trophy</span>
                        </div>
                        <div>
                            <div class="text-[9px] uppercase font-bold text-slate-500 tracking-wider">Kategori Dominan</div>
                            <div class="text-[11px] font-black text-teal-800">
                                Sangat Baik <span class="font-mono text-[9px] font-bold text-teal-600">({{ $pctSB }}%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ----------------------------------------------------------------- -->
            <!-- SECTION 03: DAFTAR NAMA PEGAWAI PER PREDIKAT (SIDE-BY-SIDE)       -->
            <!-- ----------------------------------------------------------------- -->
            <section class="space-y-1.5 print-break-inside-avoid">
                <!-- Section Header Banner with Marker -->
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-[#FFB800] text-[#0A1931] font-black text-[11px] flex items-center justify-center shrink-0 shadow-2xs">
                        03
                    </div>
                    <div class="bg-[#0A1931] text-white font-extrabold text-[10.5px] uppercase tracking-wider px-2.5 py-0.5 rounded shadow-2xs">
                        DAFTAR NAMA PEGAWAI PER PREDIKAT
                    </div>
                </div>

                <!-- 2 Side-by-Side Structured Lists (Clean Compact Rows) -->
                <div class="grid grid-cols-2 gap-3.5">
                    <!-- Column 1: Predikat Sangat Baik -->
                    <div class="border border-teal-500/40 rounded-xl overflow-hidden bg-white shadow-xs flex flex-col">
                        <!-- Teal Banner Header -->
                        <div class="bg-[#00828A] text-white px-3 py-1.5 flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-white">military_tech</span>
                                <h3 class="font-extrabold text-xs tracking-wide">
                                    Predikat Sangat Baik ({{ $countSB }} Pegawai)
                                </h3>
                            </div>
                            <span class="text-[9.5px] font-bold bg-white/20 px-2 py-0.2 rounded-full">
                                SKP &ge; 85.0
                            </span>
                        </div>

                        <!-- Clean Structured Rows -->
                        <div class="divide-y divide-slate-100 text-[11px]">
                            @forelse($sangatBaikList as $idx => $item)
                                <div class="px-2.5 py-0.5 flex items-center justify-between gap-1.5 odd:bg-white even:bg-slate-50/70">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="font-mono font-bold text-[#00828A] w-4.5 text-right shrink-0">{{ $idx + 1 }}.</span>
                                        <span class="text-slate-800 font-semibold truncate" title="{{ $item['nama'] }}">{{ $item['nama'] }}</span>
                                    </div>
                                    @if(isset($item['avg']))
                                        <span class="text-[9.5px] font-mono font-bold text-[#00828A] shrink-0 bg-teal-50 px-1.5 py-0.2 rounded border border-teal-200">
                                            {{ number_format($item['avg'], 2) }}
                                        </span>
                                    @endif
                                </div>
                            @empty
                                <p class="text-[11px] text-slate-400 italic p-2 text-center">Belum ada data pegawai.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Column 2: Predikat Baik -->
                    <div class="border border-amber-500/40 rounded-xl overflow-hidden bg-white shadow-xs flex flex-col">
                        <!-- Gold Banner Header -->
                        <div class="bg-[#D97706] text-white px-3 py-1.5 flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-white">star</span>
                                <h3 class="font-extrabold text-xs tracking-wide">
                                    Predikat Baik ({{ $countB }} Pegawai)
                                </h3>
                            </div>
                            <span class="text-[9.5px] font-bold bg-white/20 px-2 py-0.2 rounded-full">
                                SKP 75 - 84.9
                            </span>
                        </div>

                        <!-- Clean Structured Rows -->
                        <div class="divide-y divide-slate-100 text-[11px]">
                            @forelse($baikList as $idx => $item)
                                <div class="px-2.5 py-0.5 flex items-center justify-between gap-1.5 odd:bg-white even:bg-slate-50/70">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="font-mono font-bold text-[#D97706] w-4.5 text-right shrink-0">{{ $idx + 1 }}.</span>
                                        <span class="text-slate-800 font-semibold truncate" title="{{ $item['nama'] }}">{{ $item['nama'] }}</span>
                                    </div>
                                    @if(isset($item['avg']))
                                        <span class="text-[9.5px] font-mono font-bold text-[#D97706] shrink-0 bg-amber-50 px-1.5 py-0.2 rounded border border-amber-200">
                                            {{ number_format($item['avg'], 2) }}
                                        </span>
                                    @endif
                                </div>
                            @empty
                                <p class="text-[11px] text-slate-400 italic p-2 text-center">Belum ada data pegawai.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- If Cukup or Kurang exist -->
                @if(($countC ?? 0) > 0 || ($countK ?? 0) > 0)
                    <div class="grid grid-cols-2 gap-3.5 mt-2">
                        @if(($countC ?? 0) > 0)
                            <div class="border border-slate-300 rounded-xl overflow-hidden bg-white shadow-xs">
                                <div class="bg-slate-700 text-white px-2.5 py-1 flex items-center justify-between">
                                    <h3 class="font-bold text-xs">Predikat Cukup ({{ $countC }} Pegawai)</h3>
                                    <span class="text-[9px] bg-white/20 px-1.5 py-0.2 rounded">SKP 60 - 74.9</span>
                                </div>
                                <div class="divide-y divide-slate-100 text-[11px]">
                                    @foreach($cukupList as $idx => $item)
                                        <div class="px-2.5 py-0.5 flex items-center justify-between">
                                            <span class="truncate">{{ $idx + 1 }}. {{ $item['nama'] }}</span>
                                            <span class="font-mono font-bold text-slate-600">{{ number_format($item['avg'], 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if(($countK ?? 0) > 0)
                            <div class="border border-rose-300 rounded-xl overflow-hidden bg-white shadow-xs">
                                <div class="bg-rose-700 text-white px-2.5 py-1 flex items-center justify-between">
                                    <h3 class="font-bold text-xs">Predikat Kurang ({{ $countK }} Pegawai)</h3>
                                    <span class="text-[9px] bg-white/20 px-1.5 py-0.2 rounded">SKP &lt; 60</span>
                                </div>
                                <div class="divide-y divide-slate-100 text-[11px]">
                                    @foreach($kurangList as $idx => $item)
                                        <div class="px-2.5 py-0.5 flex items-center justify-between">
                                            <span class="truncate">{{ $idx + 1 }}. {{ $item['nama'] }}</span>
                                            <span class="font-mono font-bold text-rose-600">{{ number_format($item['avg'], 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </section>

            <!-- ----------------------------------------------------------------- -->
            <!-- SECTION 04: CATATAN PENILAIAN                                     -->
            <!-- ----------------------------------------------------------------- -->
            <section class="space-y-1.5 print-break-inside-avoid">
                <!-- Section Header Banner with Marker -->
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-[#FFB800] text-[#0A1931] font-black text-[11px] flex items-center justify-center shrink-0 shadow-2xs">
                        04
                    </div>
                    <div class="bg-[#0A1931] text-white font-extrabold text-[10.5px] uppercase tracking-wider px-2.5 py-0.5 rounded shadow-2xs">
                        CATATAN PENILAIAN
                    </div>
                </div>

                <!-- Content Box -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-2.5 flex items-center gap-2.5 relative overflow-hidden shadow-2xs">
                    <div class="w-7 h-7 rounded-full bg-[#0A1931] text-[#FFB800] flex items-center justify-center shrink-0 shadow-xs">
                        <span class="material-symbols-outlined text-[16px]">edit_note</span>
                    </div>
                    <div class="text-[11px] text-slate-700 leading-snug font-medium">
                        Seluruh data predikat SKP <strong>{{ $periodeTitle }} {{ $tahun }}</strong> disusun berdasarkan hasil evaluasi capaian kinerja berkala pegawai dan dapat digunakan sebagai bahan evaluasi serta tindak lanjut pembinaan karier ke depan.
                    </div>
                </div>
            </section>

            <!-- ----------------------------------------------------------------- -->
            <!-- FOOTER BANNER (OFFICIAL PU IDENTITY)                              -->
            <!-- ----------------------------------------------------------------- -->
            <footer class="bg-[#0A1931] text-white rounded-xl p-2.5 flex flex-row items-center justify-between gap-2.5 text-xs shadow-md print-break-inside-avoid">
                <!-- Left: Location Info -->
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#FFB800] text-sm">location_on</span>
                    <div>
                        <div class="font-bold text-white text-[11px] leading-tight">BPSDM Kementerian Pekerjaan Umum</div>
                        <div class="text-[9px] text-slate-300">Balai Pengembangan Kompetensi Wilayah VIII Makassar</div>
                    </div>
                </div>

                <!-- Center: Slogan Quote -->
                <div class="text-center font-serif italic text-[#FFB800] text-[11px] tracking-wide">
                    &ldquo;Kompeten, Berintegritas, Melayani Negeri&rdquo;
                </div>

                <!-- Right: Logo PUPR Square / Trim -->
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded bg-white flex items-center justify-center font-black text-[#0A1931] text-[10px] shadow-2xs">
                        PU
                    </div>
                    <div class="text-right">
                        <div class="text-[8px] text-slate-300 uppercase font-bold leading-none">KinPro Portal</div>
                        <div class="text-[8px] text-amber-400 font-mono font-semibold">{{ date('Y') }}</div>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <!-- 1:1 High-Fidelity Print CSS (Guaranteed 1 Single Page A4) -->
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 4mm 5mm;
            }
            *, *::before, *::after {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            html, body {
                background: #ffffff !important;
                color: #0f172a !important;
                font-family: 'Plus Jakarta Sans', sans-serif !important;
                margin: 0 !important;
                padding: 0 !important;
                height: 100% !important;
                max-height: 100% !important;
                overflow: hidden !important;
            }
            .h-screen, .overflow-hidden, .overflow-y-auto {
                height: auto !important;
                overflow: visible !important;
                max-height: none !important;
            }
            aside, nav, header:not(#infografisSheet header), .no-print, button, form {
                display: none !important;
            }
            main {
                padding: 0 !important;
                margin: 0 !important;
                background: transparent !important;
                overflow: visible !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            #infografisSheet {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                background-color: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
                border-radius: 0.75rem !important;
                box-shadow: none !important;
                zoom: 0.86 !important;
                page-break-after: avoid !important;
                break-after: avoid !important;
            }
            /* Explicit side-by-side 2-column layout in print & save PDF */
            .grid-cols-2 {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.75rem !important;
            }
            .grid-cols-3 {
                display: grid !important;
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                gap: 0.5rem !important;
            }
            .print-break-inside-avoid, header, section, footer {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }
        }
    </style>
</x-app-layout>