<x-app-layout>
    <div class="space-y-5 pb-48">
        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/60 border-l-4 border-emerald-500 rounded-lg text-emerald-800 dark:text-emerald-300 text-xs font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-sm">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 bg-rose-50 dark:bg-rose-950/60 border-l-4 border-rose-500 rounded-lg text-rose-800 dark:text-rose-300 text-xs font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-sm">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Hero / Top Banner Section -->
        <div class="bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/60 dark:border-slate-800 rounded-lg p-4 sm:p-5 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 shadow-xs transition-colors">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-primary text-on-primary">SKP PERMENPAN-RB 6/2022</span>
                    <span class="text-[10px] text-secondary dark:text-slate-400 font-bold uppercase tracking-wider">BALAI WILAYAH VIII MAKASSAR</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-on-surface dark:text-slate-100 tracking-tight">Penilaian Kinerja Pegawai</h1>
                <p class="text-xs text-on-surface-variant dark:text-slate-400">Evaluasi 6 aspek kompetensi dan kinerja aparatur sipil negara secara transparan dan berkala.</p>
            </div>

            <!-- Action Button Group -->
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" onclick="alert('Sinkronisasi SIASN-BKN berhasil diinisialisasi. Data penilaian terhubung langsung dengan sistem BKN.')" class="flex items-center gap-1.5 px-3 py-1.5 rounded bg-surface-container-low dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-on-surface dark:text-slate-200 hover:bg-surface-container dark:hover:bg-slate-700 text-xs font-semibold transition-colors cursor-pointer shadow-xs">
                    <span class="material-symbols-outlined text-sm text-secondary dark:text-slate-400">sync</span>
                    <span>Sinkronisasi SIASN-BKN</span>
                </button>
                <a href="{{ route('rekap.export', 'excel') }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded bg-surface-container-low dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-on-surface dark:text-slate-200 hover:bg-surface-container dark:hover:bg-slate-700 text-xs font-semibold transition-colors cursor-pointer shadow-xs">
                    <span class="material-symbols-outlined text-sm text-secondary dark:text-slate-400">download</span>
                    <span>Ekspor Rekap (Excel/PDF)</span>
                </a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('penilaian.create') }}" class="flex items-center gap-1.5 px-3.5 py-1.5 rounded bg-primary-container text-on-primary hover:bg-primary text-xs font-semibold transition-colors shadow-xs cursor-pointer">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span>Tambah Penilaian</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- 4 KPI Summary Cards with 4px Accent Strips -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3.5">
            <!-- Card 1: Total Evaluasi -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/60 dark:border-slate-800 rounded-lg p-3.5 flex flex-col justify-between border-l-4 border-l-tertiary shadow-xs transition-colors">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] text-secondary dark:text-slate-400 uppercase font-bold tracking-wider">TOTAL EVALUASI PERIODE INI</span>
                        <div class="text-2xl font-extrabold text-on-surface dark:text-slate-100 mt-0.5 leading-tight">{{ $totalEvaluasi }}</div>
                    </div>
                    <div class="w-8 h-8 rounded bg-surface-container-low dark:bg-slate-800 flex items-center justify-center text-tertiary dark:text-emerald-400 border border-outline-variant/60 dark:border-slate-700 shrink-0">
                        <span class="material-symbols-outlined text-lg">assignment_turned_in</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-2.5 pt-2 border-t border-outline-variant/60 dark:border-slate-800">
                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-tertiary-fixed text-on-tertiary-fixed font-bold">100% Selesai</span>
                    <span class="text-[11px] text-secondary dark:text-slate-400">Terverifikasi Atasan</span>
                </div>
            </div>

            <!-- Card 2: Rata-Rata Skor Balai -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/60 dark:border-slate-800 rounded-lg p-3.5 flex flex-col justify-between border-l-4 border-l-primary shadow-xs transition-colors">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] text-secondary dark:text-slate-400 uppercase font-bold tracking-wider">RATA-RATA SKOR BALAI</span>
                        <div class="text-2xl font-extrabold text-on-surface dark:text-slate-100 mt-0.5 leading-tight font-mono">{{ number_format($rataRataSkor, 2) }}</div>
                    </div>
                    <div class="w-8 h-8 rounded bg-surface-container-low dark:bg-slate-800 flex items-center justify-center text-primary dark:text-blue-400 border border-outline-variant/60 dark:border-slate-700 shrink-0">
                        <span class="material-symbols-outlined text-lg">speed</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-2.5 pt-2 border-t border-outline-variant/60 dark:border-slate-800">
                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-primary text-on-primary font-bold">Baik</span>
                    <span class="text-[11px] text-secondary dark:text-slate-400">Target Balai: ≥ 78.00 Poin</span>
                </div>
            </div>

            <!-- Card 3: Distribusi Predikat -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/60 dark:border-slate-800 rounded-lg p-3.5 flex flex-col justify-between border-l-4 border-l-secondary shadow-xs transition-colors">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] text-secondary dark:text-slate-400 uppercase font-bold tracking-wider">DISTRIBUSI PREDIKAT</span>
                        <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                            <span class="px-1.5 py-0.5 rounded bg-surface-container-highest dark:bg-slate-800 text-on-surface dark:text-slate-200 font-semibold text-[11px]">{{ $predikatCounts['SB'] }} SB</span>
                            <span class="px-1.5 py-0.5 rounded bg-primary-container text-on-primary font-semibold text-[11px]">{{ $predikatCounts['B'] }} B</span>
                            <span class="px-1.5 py-0.5 rounded bg-surface-container dark:bg-slate-800 text-on-surface dark:text-slate-300 font-semibold text-[11px]">{{ $predikatCounts['C'] }} C</span>
                            <span class="px-1.5 py-0.5 rounded bg-error-container text-on-error-container font-semibold text-[11px]">{{ $predikatCounts['BP'] }} BP</span>
                        </div>
                    </div>
                    <div class="w-8 h-8 rounded bg-surface-container-low dark:bg-slate-800 flex items-center justify-center text-secondary dark:text-slate-400 border border-outline-variant/60 dark:border-slate-700 shrink-0">
                        <span class="material-symbols-outlined text-lg">pie_chart</span>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-2.5 pt-2 border-t border-outline-variant/60 dark:border-slate-800 text-[11px] text-secondary dark:text-slate-400">
                    <span>Sangat Baik: {{ $pctSB }}%</span>
                    <span>Butuh Perbaikan: {{ $pctBP }}%</span>
                </div>
            </div>

            <!-- Card 4: Indeks Aspek Balai -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/60 dark:border-slate-800 rounded-lg p-3.5 flex flex-col justify-between border-l-4 border-l-tertiary-container shadow-xs transition-colors">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] text-secondary dark:text-slate-400 uppercase font-bold tracking-wider">INDEKS ASPEK BALAI</span>
                        <div class="flex flex-col gap-0.5 mt-1">
                            <div class="flex items-center justify-between text-[11px] gap-2">
                                <span class="text-secondary dark:text-slate-400">Tertinggi ({{ $topAspect['name'] }}):</span>
                                <span class="font-mono font-bold text-on-surface dark:text-slate-100">{{ number_format($topAspect['score'], 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] gap-2">
                                <span class="text-secondary dark:text-slate-400">Terendah ({{ $lowAspect['name'] }}):</span>
                                <span class="font-mono font-bold text-error dark:text-rose-400">{{ number_format($lowAspect['score'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-8 h-8 rounded bg-surface-container-low dark:bg-slate-800 flex items-center justify-center text-primary dark:text-blue-400 border border-outline-variant/60 dark:border-slate-700 shrink-0">
                        <span class="material-symbols-outlined text-lg">insights</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-2.5 pt-2 border-t border-outline-variant/60 dark:border-slate-800">
                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-error text-on-error font-bold">Fokus Diklat</span>
                    <span class="text-[11px] text-secondary dark:text-slate-400">Literasi Digital Pegawai</span>
                </div>
            </div>
        </div>

        <!-- Filter & Period Bar -->
        <form method="GET" action="{{ route('penilaian.index') }}" id="filterForm" class="bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/60 dark:border-slate-800 rounded-lg p-3 sm:p-3.5 flex flex-wrap items-center justify-between gap-3 shadow-xs transition-colors">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

            <div class="flex items-center flex-wrap gap-2.5">
                <!-- Search Box -->
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-2.5 text-secondary dark:text-slate-400 text-xs pointer-events-none">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIP..." class="pl-7 pr-3 py-1.5 text-xs bg-surface-container-low dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 rounded text-on-surface dark:text-slate-100 placeholder-secondary dark:placeholder-slate-500 focus:border-primary focus:outline-none w-44 sm:w-52" />
                </div>

                <!-- Filter Tahun -->
                <div class="flex items-center gap-1.5">
                    <span class="text-[10px] text-secondary dark:text-slate-400 uppercase font-bold">Tahun:</span>
                    <select name="tahun" onchange="this.form.submit()" class="bg-surface-container-low dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-on-surface dark:text-slate-200 text-xs rounded pl-2.5 pr-6 py-1.5 focus:border-primary focus:outline-none font-mono cursor-pointer shadow-2xs">
                        <option value="Semua" {{ request('tahun') === 'Semua' ? 'selected' : '' }}>Semua</option>
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" {{ request('tahun', 2026) == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Bulan (Custom Dropdown Mengarah ke Bawah) -->
                <div class="flex items-center gap-1.5" x-data="{ openBulan: false }" @click.outside="openBulan = false">
                    <span class="text-[10px] text-secondary dark:text-slate-400 uppercase font-bold">Bulan:</span>
                    <input type="hidden" name="bulan" id="filterBulanInput" value="{{ request('bulan', 'Januari') }}">
                    <div class="relative">
                        <button type="button" @click="openBulan = !openBulan" class="bg-surface-container-low dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-on-surface dark:text-slate-200 text-xs rounded pl-2.5 pr-7 py-1.5 focus:border-primary focus:outline-none cursor-pointer flex items-center justify-between gap-2 shadow-2xs">
                            <span>{{ request('bulan') === 'Semua' ? 'Semua Bulan' : request('bulan', 'Januari') }}</span>
                            <span class="material-symbols-outlined text-[14px] text-secondary dark:text-slate-400">expand_more</span>
                        </button>
                        <div x-show="openBulan"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 top-full mt-1 w-40 bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/60 dark:border-slate-700 rounded-lg shadow-xl z-50 py-1 max-h-60 overflow-y-auto text-xs"
                             style="display: none;">
                            <button type="button" @click="document.getElementById('filterBulanInput').value = 'Semua'; document.getElementById('filterForm').submit();" class="w-full text-left px-3 py-1.5 hover:bg-surface-container-low dark:hover:bg-slate-800 text-on-surface dark:text-slate-200 {{ request('bulan') === 'Semua' ? 'font-bold bg-primary/10 text-primary dark:text-blue-400' : '' }}">
                                Semua Bulan
                            </button>
                            @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $m)
                                <button type="button" @click="document.getElementById('filterBulanInput').value = '{{ $m }}'; document.getElementById('filterForm').submit();" class="w-full text-left px-3 py-1.5 hover:bg-surface-container-low dark:hover:bg-slate-800 text-on-surface dark:text-slate-200 {{ request('bulan', 'Januari') === $m ? 'font-bold bg-primary/10 text-primary dark:text-blue-400' : '' }}">
                                    {{ $m }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Filter Predikat -->
                <div class="flex items-center gap-1.5">
                    <span class="text-[10px] text-secondary dark:text-slate-400 uppercase font-bold">Predikat:</span>
                    <select name="predikat" onchange="this.form.submit()" class="bg-surface-container-low dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-on-surface dark:text-slate-200 text-xs rounded pl-2.5 pr-6 py-1.5 focus:border-primary focus:outline-none cursor-pointer shadow-2xs">
                        <option value="Semua" {{ request('predikat') === 'Semua' || !request('predikat') ? 'selected' : '' }}>Semua Predikat</option>
                        <option value="Sangat Baik" {{ request('predikat') === 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                        <option value="Baik" {{ request('predikat') === 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Cukup" {{ request('predikat') === 'Cukup' ? 'selected' : '' }}>Cukup</option>
                        <option value="Butuh Perbaikan" {{ request('predikat') === 'Butuh Perbaikan' ? 'selected' : '' }}>Butuh Perbaikan</option>
                    </select>
                </div>

                <!-- Filter Unit Kerja (Tepat di samping Predikat) -->
                <div class="flex items-center gap-1.5">
                    <span class="text-[10px] text-secondary dark:text-slate-400 uppercase font-bold">Unit:</span>
                    <select name="unit_kerja_id" onchange="this.form.submit()" class="bg-surface-container-low dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-on-surface dark:text-slate-200 text-xs rounded pl-2.5 pr-6 py-1.5 focus:border-primary focus:outline-none max-w-[200px] cursor-pointer shadow-2xs">
                        <option value="Semua" {{ request('unit_kerja_id') === 'Semua' || !request('unit_kerja_id') ? 'selected' : '' }}>Semua Unit</option>
                        @foreach($unitKerjas as $uk)
                            <option value="{{ $uk->id }}" {{ request('unit_kerja_id') == $uk->id ? 'selected' : '' }}>{{ $uk->nama_unit }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs text-secondary dark:text-slate-400">Total: <strong class="text-on-surface dark:text-slate-200 font-semibold">{{ $penilaians->total() }}</strong> entri</span>
                <a href="{{ route('penilaian.index') }}" class="p-1 rounded border border-outline-variant/60 dark:border-slate-700 bg-surface-container-low dark:bg-slate-800 hover:bg-surface-container dark:hover:bg-slate-700 text-on-surface dark:text-slate-200 flex items-center justify-center transition cursor-pointer" title="Reset Filter">
                    <span class="material-symbols-outlined text-sm">restart_alt</span>
                </a>
            </div>
        </form>

        <!-- Main Data Table: Evaluasi 6 Aspek Aparatur Modern (Compact, Proportional Typography) -->
        <div class="bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/60 dark:border-slate-800 rounded-lg overflow-hidden flex flex-col shadow-xs transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low dark:bg-slate-800/80 border-b border-outline-variant/60 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-secondary dark:text-slate-400">
                            <th class="py-2.5 px-3 w-12 text-center" scope="col">NO</th>
                            <th class="py-2.5 px-3 min-w-[200px]" scope="col">PEGAWAI / NIP</th>
                            <th class="py-2.5 px-3 w-28 whitespace-nowrap" scope="col">PERIODE</th>
                            <th class="py-2.5 px-3 min-w-[340px]" scope="col">
                                6 ASPEK NILAI (DIS, KIN, SIK, KEP, LOY, IT)
                            </th>
                            <th class="py-2.5 px-3 w-24 text-center whitespace-nowrap" scope="col">RATA-RATA</th>
                            <th class="py-2.5 px-3 w-28 text-center whitespace-nowrap" scope="col">PREDIKAT</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="py-2.5 px-3 w-28 text-right whitespace-nowrap" scope="col">AKSI</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/60 dark:divide-slate-800 text-xs">
                        @forelse($penilaians as $index => $item)
                            @php
                                $initial = strtoupper(substr($item->pegawai->nama ?? 'P', 0, 1));
                                $rataVal = (float)$item->rata_rata;
                                $barColor = $rataVal >= 80 ? 'bg-primary' : ($rataVal >= 70 ? 'bg-secondary' : 'bg-error');

                                $pClean = trim($item->predikat);
                                $isSB = str_contains($pClean, 'Sangat') || $pClean === 'A';
                                $isB = (str_contains($pClean, 'Baik') && !$isSB) || $pClean === 'B';
                                $isC = str_contains($pClean, 'Cukup') || $pClean === 'C';
                            @endphp
                            <tr class="hover:bg-surface-container-low/50 dark:hover:bg-slate-800/40 transition-colors">
                                <!-- NO -->
                                <td class="py-3 px-3 text-center font-mono text-secondary dark:text-slate-400 text-xs font-semibold">
                                    {{ $penilaians->firstItem() + $index }}
                                </td>

                                <!-- PEGAWAI / NIP -->
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded bg-surface-container-high dark:bg-slate-800 flex items-center justify-center font-bold text-primary dark:text-blue-400 text-xs shrink-0 border border-outline-variant/40 dark:border-slate-700">
                                            {{ $initial }}
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="text-xs font-bold text-on-surface dark:text-slate-100 truncate">{{ $item->pegawai->nama ?? '-' }}</span>
                                            <span class="font-mono text-secondary dark:text-slate-400 text-[11px] truncate leading-tight">NIP. {{ $item->pegawai->nip ?? '-' }}</span>
                                            <span class="text-[10px] text-on-surface-variant dark:text-slate-500 truncate">{{ $item->pegawai->unitKerja->nama_unit ?? 'Balai Wil. VIII' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- PERIODE -->
                                <td class="py-3 px-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded bg-surface-container dark:bg-slate-800 text-on-surface dark:text-slate-300 text-xs font-medium border border-outline-variant/30 dark:border-slate-700">
                                        {{ $item->bulan }} {{ $item->tahun }}
                                    </span>
                                </td>

                                <!-- 6 ASPEK NILAI -->
                                <td class="py-3 px-3">
                                    <div class="flex flex-wrap items-center gap-1 font-mono text-[11px]">
                                        <span class="px-1.5 py-0.5 rounded border border-outline-variant/60 dark:border-slate-700 bg-surface-container-low dark:bg-slate-800 text-on-surface dark:text-slate-200">
                                            <strong class="{{ (float)$item->nilai_kedisiplinan >= 85 ? 'text-tertiary dark:text-emerald-400' : ((float)$item->nilai_kedisiplinan < 70 ? 'text-error dark:text-rose-400' : 'text-secondary dark:text-slate-400') }}">DIS:</strong> {{ number_format($item->nilai_kedisiplinan, 1) }}
                                        </span>
                                        <span class="px-1.5 py-0.5 rounded border border-outline-variant/60 dark:border-slate-700 bg-surface-container-low dark:bg-slate-800 text-on-surface dark:text-slate-200">
                                            <strong class="{{ (float)$item->kinerja >= 85 ? 'text-tertiary dark:text-emerald-400' : ((float)$item->kinerja < 70 ? 'text-error dark:text-rose-400' : 'text-secondary dark:text-slate-400') }}">KIN:</strong> {{ number_format($item->kinerja, 1) }}
                                        </span>
                                        <span class="px-1.5 py-0.5 rounded border border-outline-variant/60 dark:border-slate-700 bg-surface-container-low dark:bg-slate-800 text-on-surface dark:text-slate-200">
                                            <strong class="{{ (float)$item->sikap >= 85 ? 'text-tertiary dark:text-emerald-400' : ((float)$item->sikap < 70 ? 'text-error dark:text-rose-400' : 'text-secondary dark:text-slate-400') }}">SIK:</strong> {{ number_format($item->sikap, 1) }}
                                        </span>
                                        <span class="px-1.5 py-0.5 rounded border border-outline-variant/60 dark:border-slate-700 bg-surface-container-low dark:bg-slate-800 text-on-surface dark:text-slate-200">
                                            <strong class="{{ (float)$item->kepemimpinan >= 85 ? 'text-tertiary dark:text-emerald-400' : ((float)$item->kepemimpinan < 70 ? 'text-error dark:text-rose-400' : 'text-secondary dark:text-slate-400') }}">KEP:</strong> {{ number_format($item->kepemimpinan, 1) }}
                                        </span>
                                        <span class="px-1.5 py-0.5 rounded border border-outline-variant/60 dark:border-slate-700 bg-surface-container-low dark:bg-slate-800 text-on-surface dark:text-slate-200">
                                            <strong class="{{ (float)$item->loyalitas >= 85 ? 'text-tertiary dark:text-emerald-400' : ((float)$item->loyalitas < 70 ? 'text-error dark:text-rose-400' : 'text-secondary dark:text-slate-400') }}">LOY:</strong> {{ number_format($item->loyalitas, 1) }}
                                        </span>
                                        <span class="px-1.5 py-0.5 rounded border border-outline-variant/60 dark:border-slate-700 bg-surface-container-low dark:bg-slate-800 text-on-surface dark:text-slate-200">
                                            <strong class="{{ (float)$item->it >= 85 ? 'text-tertiary dark:text-emerald-400' : ((float)$item->it < 70 ? 'text-error dark:text-rose-400' : 'text-secondary dark:text-slate-400') }}">IT:</strong> {{ number_format($item->it, 1) }}
                                        </span>
                                    </div>
                                </td>

                                <!-- RATA-RATA -->
                                <td class="py-3 px-3 text-center whitespace-nowrap">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-xs font-bold text-primary dark:text-blue-400 font-mono">{{ number_format($rataVal, 2) }}</span>
                                        <div class="w-14 h-1 rounded-full bg-surface-container dark:bg-slate-800 mt-1 overflow-hidden">
                                            <div class="h-full {{ $barColor }}" style="width: {{ min(100, max(0, $rataVal)) }}%;"></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- PREDIKAT -->
                                <td class="py-3 px-3 text-center whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold {{ $isSB ? 'bg-surface-container-highest dark:bg-slate-800 text-on-surface dark:text-slate-200 border border-outline-variant/60 dark:border-slate-700' : ($isB ? 'bg-primary text-on-primary font-bold' : ($isC ? 'bg-surface-container dark:bg-slate-800 text-on-surface dark:text-slate-300 border border-outline-variant/60 dark:border-slate-700' : 'bg-error-container text-on-error-container font-semibold')) }}">
                                        {{ $pClean }}
                                    </span>
                                </td>

                                <!-- AKSI -->
                                @if(auth()->user()->role === 'admin')
                                    <td class="py-3 px-3 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('penilaian.edit', $item) }}" class="px-2 py-1 rounded bg-surface-container-low dark:bg-slate-800 hover:bg-surface-container dark:hover:bg-slate-700 text-primary dark:text-blue-400 font-semibold text-xs border border-outline-variant/60 dark:border-slate-700 transition-colors shadow-xs">
                                                Edit
                                            </a>
                                            <form action="{{ route('penilaian.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus penilaian ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 rounded bg-surface-container-low dark:bg-slate-800 hover:bg-surface-container dark:hover:bg-slate-700 text-error dark:text-rose-400 font-semibold text-xs border border-outline-variant/60 dark:border-slate-700 transition-colors cursor-pointer shadow-xs">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'admin' ? 7 : 6 }}" class="py-10 px-4 text-center text-secondary dark:text-slate-400">
                                    <span class="material-symbols-outlined text-3xl text-outline-variant mb-1">assignment_late</span>
                                    <p class="text-xs font-semibold text-on-surface dark:text-slate-200">Belum ada data penilaian kinerja.</p>
                                    <p class="text-[11px] text-secondary dark:text-slate-400 mt-0.5">Gunakan tombol "Tambah Penilaian" untuk menginput evaluasi pegawai.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-between p-3 sm:p-3.5 bg-surface-container-low dark:bg-slate-800/80 border-t border-outline-variant/60 dark:border-slate-700 gap-2.5 text-xs">
                <div class="flex items-center gap-2 text-secondary dark:text-slate-400">
                    <span>Tampilkan</span>
                    <select onchange="window.location.href = '{{ request()->fullUrlWithQuery(['per_page' => 'PLACEHOLDER']) }}'.replace('PLACEHOLDER', this.value)" class="bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/60 dark:border-slate-700 rounded px-2 py-0.5 text-on-surface dark:text-slate-200 text-xs cursor-pointer">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span>baris per halaman</span>
                </div>
                    <div>
                        {{ $penilaians->links('vendor.pagination.compact') }}
                    </div>
            </div>
        </div>

        <!-- Bottom Section: Analysis & Regulatory Compliance -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Card: Analisis Skor Rata-Rata per Aspek (Span 2) -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm flex flex-col justify-between transition-colors">
                <div>
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-4 mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 tracking-tight">Analisis Skor Rata-Rata per Aspek</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Agregasi 6 pilar penilaian kinerja Bapekom Wilayah VIII Makassar Tahun 2026</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900 text-xs font-mono font-semibold text-blue-700 dark:text-blue-300 whitespace-nowrap">
                            Target Balai: &ge; 78.00
                        </span>
                    </div>

                    <div class="space-y-4">
                        @foreach($aspekDisplay as $asp)
                            @php
                                $sc = (float)$asp['score'];
                                if ($asp['key'] === 'loyalitas' || $sc >= 86.0) {
                                    $statusText = 'Tertinggi';
                                    $colorClass = 'text-emerald-600 dark:text-emerald-400';
                                    $barClass = 'bg-emerald-600';
                                } elseif ($sc >= 83.5) {
                                    $statusText = 'Tercapai';
                                    $colorClass = 'text-emerald-600 dark:text-emerald-400';
                                    $barClass = 'bg-emerald-600';
                                } elseif ($sc < 78.0) {
                                    $statusText = 'Butuh Intervensi';
                                    $colorClass = 'text-amber-600 dark:text-amber-400';
                                    $barClass = 'bg-amber-500';
                                } elseif (abs($sc - 78.0) < 0.5) {
                                    $statusText = 'Batas Standar';
                                    $colorClass = 'text-blue-600 dark:text-blue-400';
                                    $barClass = 'bg-blue-600';
                                } else {
                                    $statusText = 'Tercapai';
                                    $colorClass = 'text-blue-600 dark:text-blue-400';
                                    $barClass = 'bg-blue-600';
                                }
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs mb-1.5">
                                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $asp['label'] }}</span>
                                    <span class="font-mono font-semibold {{ $colorClass }}">
                                        {{ number_format($sc, 2) }} ({{ $statusText }})
                                    </span>
                                </div>
                                <div class="w-full bg-blue-50 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden">
                                    <div class="{{ $barClass }} h-2.5 rounded-full transition-all duration-300" style="width: {{ min(100, max(0, $sc)) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Card: Panduan Regulasi (Span 1) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm flex flex-col justify-between space-y-6 transition-colors">
                <div class="space-y-4">
                    <!-- Title -->
                    <div class="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-4">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 tracking-tight">Panduan Regulasi</h2>
                    </div>

                    <!-- Info Item 1 -->
                    <div class="bg-slate-50 dark:bg-slate-800/60 border-l-4 border-blue-600 p-3 rounded-r-lg">
                        <h3 class="text-xs font-bold text-slate-900 dark:text-slate-100">PermenPAN-RB No. 6 Tahun 2022</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                            Pengelolaan Kinerja Pegawai ASN didasarkan pada dialog kinerja periodik dan ekspektasi pimpinan.
                        </p>
                    </div>

                    <!-- Info Item 2 -->
                    <div class="bg-slate-50 dark:bg-slate-800/60 border-l-4 border-emerald-600 p-3 rounded-r-lg">
                        <h3 class="text-xs font-bold text-slate-900 dark:text-slate-100">Kewajiban Penginputan Bulanan</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                            Batas akhir penginputan penilaian oleh atasan langsung selambat-lambatnya tanggal 5 setiap bulan berjalan.
                        </p>
                    </div>

                    <!-- Info Item 3 -->
                    <div class="bg-slate-50 dark:bg-slate-800/60 border-l-4 border-amber-500 p-3 rounded-r-lg">
                        <h3 class="text-xs font-bold text-slate-900 dark:text-slate-100">Sanksi Keterlambatan</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                            Keterlambatan input berdampak pada penundaan proses pencairan Tukin (Tunjangan Kinerja) pegawai terkait.
                        </p>
                    </div>
                </div>

                <!-- Download CTA -->
                <a href="{{ route('rekap.export', 'pdf') }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-3 px-4 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition text-blue-700 dark:text-blue-400 font-bold text-xs text-center shadow-xs">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 stroke-[2] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Unduh Juknis Evaluasi Pegawai (PDF)</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
