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
        @if(session('error'))
            <div class="p-3.5 bg-rose-50 dark:bg-rose-950/60 border-l-4 border-rose-500 rounded-lg text-rose-800 dark:text-rose-300 text-xs font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-sm">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- 1. PAGE TOP HEADER & PRIMARY ACTIONS                                      -->
        <!-- ========================================================================= -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-on-surface dark:text-slate-100 tracking-tight">
                    {{ auth()->user()->role === 'admin' ? __('Review Perjalanan Dinas') : __('Laporan Perjalanan Dinas (Pajokka)') }}
                </h1>
                <p class="text-xs text-on-surface-variant dark:text-slate-400 mt-0.5">
                    Validasi kepatuhan Surat Perintah Tugas (SPT) dan Surat Perjalanan Dinas (SPD) aparatur balai.
                </p>
            </div>
            <!-- Action Trigger Buttons -->
            <div class="flex items-center gap-2.5 flex-wrap">
                @if(auth()->user()->role === 'pegawai')
                    <a href="{{ route('pajokka.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-primary-container text-white font-semibold text-xs hover:bg-primary transition-colors shadow-xs cursor-pointer active:scale-95">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span>Buat Laporan Baru</span>
                    </a>
                @endif
                <button type="button" onclick="alert('Konfigurasi SPT Balai aktif: Standard Permenkeu SBU 2026 / Perpres SPPD.')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-outline-variant/60 dark:border-slate-700 bg-surface-container-lowest dark:bg-slate-800 text-on-surface dark:text-slate-200 font-semibold text-xs hover:bg-surface-container-low dark:hover:bg-slate-700 transition-colors shadow-xs cursor-pointer active:scale-95">
                    <span class="material-symbols-outlined text-on-surface-variant dark:text-slate-400 text-sm">tune</span>
                    <span>Konfigurasi SPT</span>
                </button>
                <a href="{{ route('rekap.export', 'excel') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-primary-container text-white font-semibold text-xs hover:bg-primary transition-colors shadow-xs cursor-pointer active:scale-95">
                    <span class="material-symbols-outlined text-sm">download</span>
                    <span>Export Rekap Laporan</span>
                </a>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 2. KPI / SUMMARY METRICS CARDS (Bento Row)                               -->
        <!-- ========================================================================= -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Pengajuan -->
            <div class="p-4 rounded-xl bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/40 dark:border-slate-800 shadow-xs flex items-center justify-between transition-all hover:border-outline-variant">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-on-surface-variant dark:text-slate-400">Total Pengajuan</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-on-surface dark:text-slate-100 font-mono">{{ $totalCount ?? $reports->total() }}</span>
                        <span class="text-[11px] text-on-surface-variant dark:text-slate-400">SPD Masuk</span>
                    </div>
                    <p class="text-xs text-secondary dark:text-slate-400 flex items-center gap-1">
                        <span class="material-symbols-outlined text-emerald-600 text-sm">trending_up</span>
                        <span class="text-emerald-700 dark:text-emerald-400 font-semibold">+100%</span> tercatat
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-surface-container-low dark:bg-slate-800 border border-outline-variant/30 dark:border-slate-700 flex items-center justify-center text-primary dark:text-blue-400">
                    <span class="material-symbols-outlined filled" style="font-variation-settings: 'FILL' 1; font-size: 24px;">folder_copy</span>
                </div>
            </div>

            <!-- Card 2: Menunggu Review -->
            <div class="p-4 rounded-xl bg-surface-container-lowest dark:bg-slate-900 border border-amber-200/60 dark:border-amber-800/50 shadow-xs flex items-center justify-between transition-all hover:border-amber-300">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-amber-800 dark:text-amber-400">Menunggu Review</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-amber-900 dark:text-amber-300 font-mono">{{ $pendingCount ?? 0 }}</span>
                        <span class="text-[11px] text-amber-700 dark:text-amber-400 font-semibold">Berkas</span>
                    </div>
                    <p class="text-xs text-amber-700 dark:text-amber-400 flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-amber-600 text-sm">hourglass_top</span>
                        Perlu respon cepat
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800/60 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <span class="material-symbols-outlined filled" style="font-variation-settings: 'FILL' 1; font-size: 24px;">pending_actions</span>
                </div>
            </div>

            <!-- Card 3: Disetujui (Approved) -->
            <div class="p-4 rounded-xl bg-surface-container-lowest dark:bg-slate-900 border border-emerald-200/60 dark:border-emerald-800/50 shadow-xs flex items-center justify-between transition-all hover:border-emerald-300">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-400">Disetujui (Approved)</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-emerald-900 dark:text-emerald-300 font-mono">{{ $approvedCount ?? 0 }}</span>
                        <span class="text-[11px] text-emerald-700 dark:text-emerald-400 font-semibold">Berkas</span>
                    </div>
                    <p class="text-xs text-emerald-700 dark:text-emerald-400 flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-emerald-600 text-sm">task_alt</span>
                        Telah disahkan PPK
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 flex items-center justify-center text-emerald-700 dark:text-emerald-400">
                    <span class="material-symbols-outlined filled" style="font-variation-settings: 'FILL' 1; font-size: 24px;">verified</span>
                </div>
            </div>

            <!-- Card 4: Perlu Revisi / Tolak -->
            <div class="p-4 rounded-xl bg-surface-container-lowest dark:bg-slate-900 border border-rose-200/60 dark:border-rose-800/50 shadow-xs flex items-center justify-between transition-all hover:border-rose-300">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-rose-800 dark:text-rose-400">Perlu Revisi / Tolak</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-rose-900 dark:text-rose-300 font-mono">{{ $revisionCount ?? 0 }}</span>
                        <span class="text-[11px] text-rose-700 dark:text-rose-400 font-semibold">Berkas</span>
                    </div>
                    <p class="text-xs text-rose-700 dark:text-rose-400 flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-rose-600 text-sm">error_outline</span>
                        Catatan perbaikan ada
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800/60 flex items-center justify-center text-rose-600 dark:text-rose-400">
                    <span class="material-symbols-outlined filled" style="font-variation-settings: 'FILL' 1; font-size: 24px;">change_circle</span>
                </div>
            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- 3. FILTER & CONTROL PANEL                                                -->
        <!-- ========================================================================= -->
        <section class="bg-surface-container-lowest dark:bg-slate-900 p-4 rounded-xl border border-outline-variant/40 dark:border-slate-800 shadow-xs space-y-3">
            <form method="GET" action="{{ route('pajokka.index') }}" id="pajokkaFilterForm">
                <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                    <!-- Search Bar -->
                    <div class="relative flex-1 max-w-md">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline dark:text-slate-400 text-sm pointer-events-none">search</span>
                        <input name="search" value="{{ request('search') }}" class="w-full h-10 pl-9 pr-4 text-xs bg-surface-container-low dark:bg-slate-800 border border-outline-variant/50 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary placeholder:text-outline-variant text-on-surface dark:text-slate-100 transition-all" placeholder="Cari Nama Pegawai, NIP, atau No. Surat Tugas..." type="text"/>
                    </div>

                    <!-- Multi Filter Controls -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <!-- Status Dropdown Filter -->
                        <div class="relative">
                            <select name="status" onchange="document.getElementById('pajokkaFilterForm').submit()" class="h-10 pl-3 pr-8 text-xs bg-surface-container-low dark:bg-slate-800 border border-outline-variant/50 dark:border-slate-700 rounded-lg text-on-surface dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary appearance-none cursor-pointer">
                                <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>Semua Status ({{ $totalCount ?? $reports->total() }})</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Review ({{ $pendingCount ?? 0 }})</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui ({{ $approvedCount ?? 0 }})</option>
                                <option value="revision" {{ request('status') === 'revision' ? 'selected' : '' }}>Perlu Revisi ({{ $revisionCount ?? 0 }})</option>
                            </select>
                            <span class="material-symbols-outlined text-[14px] absolute right-2.5 top-1/2 -translate-y-1/2 text-outline dark:text-slate-400 pointer-events-none">expand_more</span>
                        </div>

                        <!-- Kota Tujuan Dropdown -->
                        <div class="relative">
                            <select name="tujuan" onchange="document.getElementById('pajokkaFilterForm').submit()" class="h-10 pl-3 pr-8 text-xs bg-surface-container-low dark:bg-slate-800 border border-outline-variant/50 dark:border-slate-700 rounded-lg text-on-surface dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary appearance-none cursor-pointer">
                                <option value="">Semua Tujuan</option>
                                @foreach($tujuanList ?? [] as $tuj)
                                    <option value="{{ $tuj }}" {{ request('tujuan') == $tuj ? 'selected' : '' }}>{{ $tuj }}</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined text-[14px] absolute right-2.5 top-1/2 -translate-y-1/2 text-outline dark:text-slate-400 pointer-events-none">expand_more</span>
                        </div>

                        <!-- Date Range Selector Trigger -->
                        <div class="h-10 px-3.5 flex items-center gap-2 bg-surface-container-low dark:bg-slate-800 border border-outline-variant/50 dark:border-slate-700 rounded-lg text-xs text-on-surface dark:text-slate-200">
                            <span class="material-symbols-outlined text-outline dark:text-slate-400 text-sm">calendar_today</span>
                            <span class="font-medium">TA. {{ date('Y') }} / Q1 - Q4</span>
                        </div>

                        <!-- Reset Filter Button -->
                        <a href="{{ route('pajokka.index') }}" class="h-10 w-10 flex items-center justify-center rounded-lg border border-outline-variant/50 dark:border-slate-700 text-outline dark:text-slate-400 hover:text-on-surface dark:hover:text-slate-100 hover:bg-surface-container-low dark:hover:bg-slate-800 transition-colors cursor-pointer" title="Reset Filter">
                            <span class="material-symbols-outlined text-sm">restart_alt</span>
                        </a>
                    </div>
                </div>

                <!-- Filter Pills Bar (Quick toggles) -->
                <div class="flex items-center gap-2 pt-2 border-t border-outline-variant/20 dark:border-slate-800 overflow-x-auto text-xs">
                    <span class="text-on-surface-variant dark:text-slate-400 whitespace-nowrap font-medium">Filter Cepat:</span>
                    <a href="{{ route('pajokka.index', ['status' => 'all']) }}" class="px-3 py-1 rounded-full text-xs font-semibold transition-all {{ request('status') === 'all' || !request('status') ? 'bg-primary text-white shadow-2xs' : 'bg-surface-container dark:bg-slate-800 text-on-surface dark:text-slate-300 hover:bg-surface-container-high' }}">
                        Semua ({{ $totalCount ?? $reports->total() }})
                    </a>
                    <a href="{{ route('pajokka.index', ['status' => 'pending']) }}" class="px-3 py-1 rounded-full text-xs font-semibold transition-all {{ request('status') === 'pending' ? 'bg-primary text-white shadow-2xs' : 'bg-surface-container dark:bg-slate-800 text-on-surface dark:text-slate-300 hover:bg-surface-container-high' }}">
                        Menunggu Review ({{ $pendingCount ?? 0 }})
                    </a>
                    <a href="{{ route('pajokka.index', ['status' => 'approved']) }}" class="px-3 py-1 rounded-full text-xs font-semibold transition-all {{ request('status') === 'approved' ? 'bg-primary text-white shadow-2xs' : 'bg-surface-container dark:bg-slate-800 text-on-surface dark:text-slate-300 hover:bg-surface-container-high' }}">
                        Disetujui ({{ $approvedCount ?? 0 }})
                    </a>
                    <a href="{{ route('pajokka.index', ['status' => 'revision']) }}" class="px-3 py-1 rounded-full text-xs font-semibold transition-all {{ request('status') === 'revision' ? 'bg-primary text-white shadow-2xs' : 'bg-surface-container dark:bg-slate-800 text-on-surface dark:text-slate-300 hover:bg-surface-container-high' }}">
                        Revisi ({{ $revisionCount ?? 0 }})
                    </a>
                    <span class="text-outline-variant dark:text-slate-700">|</span>
                    <span class="text-on-surface-variant dark:text-slate-400 flex items-center gap-1 text-[11px]">
                        <span class="material-symbols-outlined text-xs text-emerald-600">check_circle</span>
                        Sinkronisasi SAPPUK Terverifikasi
                    </span>
                </div>
            </form>
        </section>

        <!-- ========================================================================= -->
        <!-- 4. VERIFICATION MASTER DATA TABLE                                        -->
        <!-- ========================================================================= -->
        <section class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-outline-variant/40 dark:border-slate-800 shadow-xs overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-outline-variant/50 dark:border-slate-700 text-[11px] font-bold tracking-wider text-slate-600 dark:text-slate-400 uppercase">
                            <th class="py-3.5 px-4 text-center w-12" scope="col">NO</th>
                            <th class="py-3.5 px-4 min-w-[220px]" scope="col">PEGAWAI / PELAKSANA</th>
                            <th class="py-3.5 px-4 min-w-[170px]" scope="col">NOMOR SURAT</th>
                            <th class="py-3.5 px-4 min-w-[130px]" scope="col">TUJUAN</th>
                            <th class="py-3.5 px-4 min-w-[180px]" scope="col">TANGGAL PELAKSANAAN</th>
                            <th class="py-3.5 px-4 min-w-[200px]" scope="col">PPK</th>
                            <th class="py-3.5 px-4 text-center min-w-[130px]" scope="col">STATUS</th>
                            <th class="py-3.5 px-4 text-center min-w-[220px]" scope="col">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30 dark:divide-slate-800 text-xs">
                        @forelse($reports as $index => $item)
                            @php
                                $st = strtolower($item->status);
                                $rowHighlight = match($st) {
                                    'submitted', 'pending' => 'bg-amber-50/20 dark:bg-amber-950/10',
                                    'revision_required', 'revision', 'rejected' => 'bg-rose-50/20 dark:bg-rose-950/10',
                                    default => ''
                                };
                                $initials = strtoupper(substr($item->pegawai->nama ?? ($item->user->name ?? 'P'), 0, 2));
                                $ppkName = $item->ppk->nama ?? ($item->masterPpk->nama ?? '-');
                                $ppkInitials = strtoupper(substr($ppkName !== '-' ? $ppkName : 'PK', 0, 2));
                                
                                $tglMulai = $item->tgl_mulai_dinas ?? $item->tanggal_berangkat;
                                $tglSelesai = $item->tgl_selesai_dinas ?? $item->tanggal_kembali;
                                $lamaHari = $item->lama_hari;
                                if (!$lamaHari && $tglMulai && $tglSelesai) {
                                    $lamaHari = \Carbon\Carbon::parse($tglMulai)->diffInDays(\Carbon\Carbon::parse($tglSelesai)) + 1;
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors {{ $rowHighlight }}">
                                <td class="py-4 px-4 text-center font-medium text-on-surface-variant dark:text-slate-400">
                                    {{ $reports->firstItem() + $index }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center text-[10px] font-bold shrink-0">
                                            {{ $initials }}
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="font-bold text-on-surface dark:text-slate-100 text-[13px] truncate">
                                                {{ $item->pegawai->nama ?? ($item->user->name ?? 'Pegawai Balai') }}
                                            </span>
                                            <span class="font-mono text-[11px] text-on-surface-variant dark:text-slate-400 tracking-tight mt-0.5">
                                                {{ $item->pegawai->nip ?? ($item->user->nip ?? '-') }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <a href="{{ route('pajokka.show', $item) }}" class="font-medium text-sky-700 dark:text-sky-400 hover:text-sky-900 dark:hover:text-sky-300 hover:underline inline-flex items-center gap-1 group">
                                        <span class="truncate">{{ $item->nomor_surat_tugas ?? ($item->nomor_surat ?? '-') }}</span>
                                        <span class="material-symbols-outlined text-[14px] opacity-0 group-hover:opacity-100 transition-opacity">open_in_new</span>
                                    </a>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-1.5 text-on-surface dark:text-slate-200 font-medium">
                                        <span class="material-symbols-outlined text-rose-500 text-[16px]">location_on</span>
                                        <span class="truncate">{{ $item->kota_tujuan ?? ($item->tujuan ?? '-') }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-on-surface dark:text-slate-200 font-medium text-xs">
                                            {{ $tglMulai ? \Carbon\Carbon::parse($tglMulai)->format('d/m/Y') : '-' }} s.d. {{ $tglSelesai ? \Carbon\Carbon::parse($tglSelesai)->format('d/m/Y') : '-' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 text-[11px] text-secondary dark:text-slate-400 mt-0.5">
                                            <span class="material-symbols-outlined text-[12px]">schedule</span> {{ $lamaHari ? $lamaHari . ' Hari Kerja' : '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-on-surface dark:text-slate-200">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-700 dark:text-slate-300 shrink-0">
                                            {{ $ppkInitials }}
                                        </div>
                                        <span class="font-medium text-xs truncate max-w-[160px]">{{ $ppkName }}</span>
                                    </div>
                                </td>
                                <!-- Status Badge -->
                                <td class="py-4 px-4 text-center">
                                    @if($st === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            APPROVED
                                        </span>
                                    @elseif(in_array($st, ['revision', 'revision_required']))
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-rose-50 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-300 dark:border-rose-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                            REVISION
                                        </span>
                                    @elseif($st === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-rose-50 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-300 dark:border-rose-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                            REJECTED
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-amber-50 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            WAITING REVIEW
                                        </span>
                                    @endif
                                </td>
                                <!-- Actions -->
                                <td class="py-4 px-4 text-center">
                                    <div class="inline-flex items-center justify-center gap-1.5">
                                        @if(auth()->user()->role === 'admin')
                                            <!-- Review Button (Primary Theme Action with Modal) -->
                                            <div x-data="{ openReviewModal: false }" class="inline-block">
                                                <button type="button" @click="openReviewModal = true" class="h-8 px-2.5 rounded-md bg-primary-container text-white font-semibold text-xs hover:bg-primary transition-all shadow-xs cursor-pointer active:scale-95 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-sm">rate_review</span>
                                                    <span>Review</span>
                                                </button>

                                                <!-- Review Modal -->
                                                <div x-show="openReviewModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" style="display: none;">
                                                    <div @click.away="openReviewModal = false" class="bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/60 dark:border-slate-800 p-6 rounded-2xl shadow-xl max-w-md w-full text-left">
                                                        <div class="flex items-center justify-between pb-3 border-b border-outline-variant/40 dark:border-slate-800 mb-4">
                                                            <div class="flex items-center gap-2">
                                                                <span class="material-symbols-outlined text-primary dark:text-blue-400">rate_review</span>
                                                                <h3 class="font-bold text-sm text-on-surface dark:text-slate-100">Review Perjalanan Dinas</h3>
                                                            </div>
                                                            <button type="button" @click="openReviewModal = false" class="text-slate-400 hover:text-slate-600">
                                                                <span class="material-symbols-outlined text-base">close</span>
                                                            </button>
                                                        </div>

                                                        <div class="mb-3 p-3 bg-surface-container-low dark:bg-slate-800 rounded-lg text-xs space-y-1">
                                                            <div class="font-bold text-on-surface dark:text-slate-100">{{ $item->pegawai->nama ?? ($item->user->name ?? '-') }}</div>
                                                            <div class="text-on-surface-variant dark:text-slate-400 font-mono text-[11px]">{{ $item->nomor_surat_tugas ?? $item->nomor_surat }}</div>
                                                            <div class="text-[11px] text-slate-500">Tujuan: {{ $item->kota_tujuan ?? $item->tujuan }}</div>
                                                        </div>

                                                        <form action="{{ route('pajokka.status', $item) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="mb-3">
                                                                <label class="block text-[11px] font-bold uppercase text-on-surface-variant dark:text-slate-400 mb-1">Status Verifikasi</label>
                                                                <select name="status" class="w-full border-outline-variant/60 dark:border-slate-700 bg-surface-container-lowest dark:bg-slate-800 dark:text-slate-100 rounded-lg text-xs p-2.5 focus:ring-1 focus:ring-primary">
                                                                    <option value="approved" {{ $item->status === 'approved' ? 'selected' : '' }}>Setujui (Approved)</option>
                                                                    <option value="revision_required" {{ in_array($item->status, ['revision', 'revision_required']) ? 'selected' : '' }}>Perlu Revisi (Revision)</option>
                                                                    <option value="rejected" {{ $item->status === 'rejected' ? 'selected' : '' }}>Tolak (Rejected)</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-4">
                                                                <label class="block text-[11px] font-bold uppercase text-on-surface-variant dark:text-slate-400 mb-1">Catatan / Arahan Verifikator</label>
                                                                <textarea name="admin_notes" rows="3" class="w-full border-outline-variant/60 dark:border-slate-700 bg-surface-container-lowest dark:bg-slate-800 dark:text-slate-100 rounded-lg text-xs p-2.5 focus:ring-1 focus:ring-primary" placeholder="Tuliskan catatan perbaikan atau instruksi jika perlu revisi...">{{ $item->admin_notes }}</textarea>
                                                            </div>
                                                            <div class="flex justify-end gap-2">
                                                                <button type="button" @click="openReviewModal = false" class="px-3.5 py-1.5 rounded-lg border border-outline-variant/60 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300">Batal</button>
                                                                <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-primary-container text-white text-xs font-semibold shadow-xs">Simpan Status</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Detail Button -->
                                        <a href="{{ route('pajokka.show', $item) }}" class="h-8 px-2.5 rounded-md border border-slate-300 dark:border-slate-700 bg-surface-container-lowest dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors cursor-pointer active:scale-95 flex items-center justify-center">
                                            Detail
                                        </a>

                                        <!-- DOCX download button -->
                                        <a href="{{ route('pajokka.docx', $item) }}" class="h-8 px-2 rounded-md bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 hover:bg-blue-100 border border-blue-200 dark:border-blue-800 text-xs font-bold flex items-center justify-center transition-colors" title="Unduh DOCX">
                                            DOCX
                                        </a>

                                        <!-- PDF download button -->
                                        <a href="{{ route('pajokka.pdf', $item) }}" class="h-8 px-2 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 hover:bg-rose-100 border border-rose-200 dark:border-rose-800 text-xs font-bold flex items-center justify-center transition-colors" title="Unduh PDF">
                                            PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-10 px-4 text-center text-on-surface-variant dark:text-slate-400">
                                    <span class="material-symbols-outlined text-3xl text-outline-variant mb-1">folder_off</span>
                                    <p class="text-xs font-semibold text-on-surface dark:text-slate-200">Belum ada berkas perjalanan dinas.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Semua data SPT dan SPD yang diajukan aparatur akan terdaftar di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer Pagination -->
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/80 border-t border-outline-variant/40 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-on-surface-variant dark:text-slate-400">
                <div>
                    Menampilkan <span class="font-bold text-on-surface dark:text-slate-200">{{ $reports->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-on-surface dark:text-slate-200">{{ $reports->lastItem() ?? 0 }}</span> dari <span class="font-bold text-on-surface dark:text-slate-200">{{ $reports->total() }}</span> total berkas perjalanan dinas
                </div>
                <div>
                    {{ $reports->links('vendor.pagination.compact') }}
                </div>
            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- 5. AUDIT TRAIL & GUIDANCE INFORMATION FOOTER                              -->
        <!-- ========================================================================= -->
        <footer class="pt-4 pb-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-on-surface-variant dark:text-slate-400 border-t border-outline-variant/30 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-outline text-sm">policy</span>
                <span>Standar Operasional Prosedur Verifikasi Perjalanan Dinas Bapekom PU Wil. VIII Makassar TA {{ date('Y') }}</span>
            </div>
            <div class="flex items-center gap-3 text-[11px] font-semibold">
                <a href="javascript:void(0)" onclick="alert('Panduan Verifikator: Periksa kesesuaian tanggal SPT (H+2 SPPD), bukti kuitansi KKP/Cash, dan dokumentasi kegiatan.')" class="hover:text-primary dark:hover:text-sky-300 transition-colors">Panduan Verifikator</a>
                <span class="text-outline-variant dark:text-slate-700">•</span>
                <a href="javascript:void(0)" onclick="alert('Regulasi SBU Menkeu: PMK No. 49/2023 tentang Standar Biaya Masukan.')" class="hover:text-primary dark:hover:text-sky-300 transition-colors">Regulasi SBU Menkeu</a>
                <span class="text-outline-variant dark:text-slate-700">•</span>
                <a href="javascript:void(0)" onclick="alert('Log audit sistem: Seluruh riwayat approval dan revisi tersimpan pada tabel revisi.')" class="hover:text-primary dark:hover:text-sky-300 transition-colors">Log Audit Sistem</a>
            </div>
        </footer>
    </div>
</x-app-layout>
