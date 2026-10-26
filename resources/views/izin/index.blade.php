<x-app-layout>
    <div class="space-y-6" x-data="{
        calendarModalOpen: false,
        reviewModalOpen: false,
        detailModalOpen: false,
        activeIzin: null,
        activeReviewStatus: 'approved',
        activeCatatan: '',

        openReview(item) {
            this.activeIzin = item;
            this.activeReviewStatus = 'approved';
            this.activeCatatan = item.catatan_admin || '';
            this.reviewModalOpen = true;
        },
        openDetail(item) {
            this.activeIzin = item;
            this.detailModalOpen = true;
        }
    }">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/60 border-l-4 border-emerald-500 rounded-lg text-emerald-800 dark:text-emerald-300 text-xs font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-sm">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 dark:bg-rose-950/60 border-l-4 border-rose-500 rounded-lg text-rose-800 dark:text-rose-300 text-xs font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-sm">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Breadcrumb & Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <!-- Breadcrumb -->
                <nav class="flex items-center gap-2 text-body-sm font-body-sm text-on-surface-variant dark:text-slate-400 mb-1">
                    <a class="hover:text-primary dark:hover:text-blue-400 transition-colors" href="{{ route('dashboard') }}">Beranda</a>
                    <span class="text-outline-variant dark:text-slate-600">/</span>
                    <span class="text-on-surface dark:text-slate-200 font-semibold">Kelola Izin &amp; Cuti</span>
                </nav>
                <h1 class="text-headline-lg font-headline-lg font-bold text-on-surface dark:text-slate-100 tracking-tight">Kelola Izin &amp; Cuti Pegawai</h1>
                <p class="text-body-sm font-body-sm text-on-surface-variant dark:text-slate-400 mt-0.5">
                    Verifikasi, persetujuan dan monitoring pengajuan izin, cuti tahunan, serta dispensasi aparatur Bapekom PU Wilayah VIII Makassar.
                </p>
            </div>

            <!-- Quick Action Buttons (Sejajar dan Proporsional) -->
            <div class="flex items-center gap-2 self-start md:self-center shrink-0">
                <button type="button" @click="calendarModalOpen = true" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-surface-container-lowest dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-on-surface dark:text-slate-200 hover:bg-surface-container dark:hover:bg-slate-700 hover:border-outline transition-colors cursor-pointer shadow-xs whitespace-nowrap">
                    <span class="material-symbols-outlined text-[16px] text-secondary dark:text-slate-400">calendar_month</span>
                    <span>Kalender Cuti</span>
                </button>
                <a href="{{ route('rekap.export', 'excel') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-surface-container-lowest dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-on-surface dark:text-slate-200 hover:bg-surface-container dark:hover:bg-slate-700 hover:border-outline transition-colors cursor-pointer shadow-xs whitespace-nowrap">
                    <span class="material-symbols-outlined text-[16px] text-secondary dark:text-slate-400">file_download</span>
                    <span>Ekspor Rekap</span>
                </a>
                @if(auth()->user()->role === 'pegawai')
                    <a href="{{ route('izin.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg bg-primary-container text-on-primary hover:bg-primary transition-colors cursor-pointer shadow-xs whitespace-nowrap active:scale-95">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        <span>Ajukan Izin</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- 4 KPI Summary Cards with 4px Accent Strips -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- KPI 1: Menunggu Persetujuan -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-lg border border-outline-variant/50 dark:border-slate-800 p-4 border-l-4 border-l-[#F59E0B] flex items-start justify-between shadow-xs transition-colors">
                <div class="space-y-1">
                    <span class="text-label-caps font-label-caps uppercase text-on-surface-variant dark:text-slate-400 font-bold tracking-wider">MENUNGGU PERSETUJUAN</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-kpi-value font-kpi-value font-extrabold text-on-surface dark:text-slate-100">{{ $pendingCount }}</span>
                        <span class="text-body-sm font-body-sm font-semibold text-on-surface-variant dark:text-slate-400">Berkas</span>
                    </div>
                    <p class="text-body-sm font-body-sm text-[#B45309] dark:text-amber-400 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">priority_high</span>
                        <span>Perlu tindakan segera (&lt;24 jam)</span>
                    </p>
                </div>
                <div class="w-10 h-10 rounded bg-[#FFFBEB] dark:bg-amber-950/40 text-[#D97706] dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-200 dark:border-amber-800/40">
                    <span class="material-symbols-outlined text-[22px]">pending_actions</span>
                </div>
            </div>

            <!-- KPI 2: Disetujui Bulan Ini -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-lg border border-outline-variant/50 dark:border-slate-800 p-4 border-l-4 border-l-[#10B981] flex items-start justify-between shadow-xs transition-colors">
                <div class="space-y-1">
                    <span class="text-label-caps font-label-caps uppercase text-on-surface-variant dark:text-slate-400 font-bold tracking-wider">DISETUJUI BULAN {{ strtoupper(Carbon\Carbon::now()->translatedFormat('F')) }}</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-kpi-value font-kpi-value font-extrabold text-on-surface dark:text-slate-100">{{ $approvedThisMonth }}</span>
                        <span class="text-body-sm font-body-sm font-semibold text-on-surface-variant dark:text-slate-400">Pengajuan</span>
                    </div>
                    <p class="text-body-sm font-body-sm text-[#047857] dark:text-emerald-400 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">check_circle</span>
                        <span>Cuti &amp; Izin telah diverifikasi</span>
                    </p>
                </div>
                <div class="w-10 h-10 rounded bg-[#ECFDF5] dark:bg-emerald-950/40 text-[#059669] dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-200 dark:border-emerald-800/40">
                    <span class="material-symbols-outlined text-[22px]">task_alt</span>
                </div>
            </div>

            <!-- KPI 3: Sedang Berlangsung Hari Ini -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-lg border border-outline-variant/50 dark:border-slate-800 p-4 border-l-4 border-l-[#1D4ED8] flex items-start justify-between shadow-xs transition-colors">
                <div class="space-y-1">
                    <span class="text-label-caps font-label-caps uppercase text-on-surface-variant dark:text-slate-400 font-bold tracking-wider">SEDANG BERLANGSUNG HARI INI</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-kpi-value font-kpi-value font-extrabold text-on-surface dark:text-slate-100">{{ $todayActiveCount }}</span>
                        <span class="text-body-sm font-body-sm font-semibold text-on-surface-variant dark:text-slate-400">Pegawai</span>
                    </div>
                    <p class="text-body-sm font-body-sm text-[#1D4ED8] dark:text-blue-400 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">person_off</span>
                        <span>Tercatat izin sah di presensi</span>
                    </p>
                </div>
                <div class="w-10 h-10 rounded bg-[#EFF6FF] dark:bg-blue-950/40 text-[#1D4ED8] dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-200 dark:border-blue-800/40">
                    <span class="material-symbols-outlined text-[22px]">directions_walk</span>
                </div>
            </div>

            <!-- KPI 4: Sisa Kuota Cuti Kolektif -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-lg border border-outline-variant/50 dark:border-slate-800 p-4 border-l-4 border-l-[#7C3AED] flex items-start justify-between shadow-xs transition-colors">
                <div class="space-y-1">
                    <span class="text-label-caps font-label-caps uppercase text-on-surface-variant dark:text-slate-400 font-bold tracking-wider">KUOTA CUTI TAHUNAN BALAI</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-kpi-value font-kpi-value font-extrabold text-on-surface dark:text-slate-100">{{ $sisaKuotaBalaiPct }}%</span>
                        <span class="text-body-sm font-body-sm font-semibold text-on-surface-variant dark:text-slate-400">Tersedia</span>
                    </div>
                    <p class="text-body-sm font-body-sm text-[#6D28D9] dark:text-purple-400 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">pie_chart</span>
                        <span>Rata-rata 9.4 hari per pegawai</span>
                    </p>
                </div>
                <div class="w-10 h-10 rounded bg-[#F5F3FF] dark:bg-purple-950/40 text-[#7C3AED] dark:text-purple-400 flex items-center justify-center shrink-0 border border-purple-200 dark:border-purple-800/40">
                    <span class="material-symbols-outlined text-[22px]">domain_verification</span>
                </div>
            </div>
        </div>

        <!-- Filter & Segmented Controls Track -->
        <form method="GET" action="{{ route('izin.index') }}" id="filterIzinForm" class="bg-surface-container-lowest dark:bg-slate-900 rounded-lg border border-outline-variant/50 dark:border-slate-800 p-4 space-y-4 shadow-xs transition-colors">
            <input type="hidden" name="status" id="statusFilterInput" value="{{ request('status', 'all') }}">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

            <!-- Segmented Status Tabs & View Toggles -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 border-b border-outline-variant/30 dark:border-slate-800 pb-3">
                <!-- Status Tabs Track -->
                <div class="inline-flex p-1 rounded bg-[#F1F5F9] dark:bg-slate-800 border border-outline-variant/40 dark:border-slate-700 space-x-1 overflow-x-auto text-body-sm font-body-sm">
                    @php
                        $curStatus = request('status', 'all');
                    @endphp
                    <button type="button" onclick="document.getElementById('statusFilterInput').value='all'; document.getElementById('filterIzinForm').submit();"
                        class="px-3.5 py-1.5 rounded font-semibold transition-colors cursor-pointer {{ $curStatus === 'all' ? 'bg-primary-container text-on-primary' : 'text-on-surface-variant dark:text-slate-300 hover:text-on-surface dark:hover:text-white hover:bg-surface-container-lowest/60 dark:hover:bg-slate-700' }}">
                        Semua Permohonan
                    </button>
                    <button type="button" onclick="document.getElementById('statusFilterInput').value='pending'; document.getElementById('filterIzinForm').submit();"
                        class="px-3.5 py-1.5 rounded font-semibold transition-colors flex items-center gap-2 cursor-pointer {{ $curStatus === 'pending' ? 'bg-primary-container text-on-primary' : 'text-on-surface-variant dark:text-slate-300 hover:text-on-surface dark:hover:text-white hover:bg-surface-container-lowest/60 dark:hover:bg-slate-700' }}">
                        <span>Menunggu Review</span>
                        @if($pendingCount > 0)
                            <span class="px-1.5 py-0.2 bg-[#F59E0B] text-white rounded-full text-[10px] font-bold">{{ $pendingCount }}</span>
                        @endif
                    </button>
                    <button type="button" onclick="document.getElementById('statusFilterInput').value='approved'; document.getElementById('filterIzinForm').submit();"
                        class="px-3.5 py-1.5 rounded font-semibold transition-colors cursor-pointer {{ $curStatus === 'approved' ? 'bg-primary-container text-on-primary' : 'text-on-surface-variant dark:text-slate-300 hover:text-on-surface dark:hover:text-white hover:bg-surface-container-lowest/60 dark:hover:bg-slate-700' }}">
                        Disetujui
                    </button>
                    <button type="button" onclick="document.getElementById('statusFilterInput').value='rejected'; document.getElementById('filterIzinForm').submit();"
                        class="px-3.5 py-1.5 rounded font-semibold transition-colors cursor-pointer {{ $curStatus === 'rejected' ? 'bg-primary-container text-on-primary' : 'text-on-surface-variant dark:text-slate-300 hover:text-on-surface dark:hover:text-white hover:bg-surface-container-lowest/60 dark:hover:bg-slate-700' }}">
                        Ditolak
                    </button>
                </div>

                <!-- Quick Summary Tag -->
                <div class="flex items-center gap-2 text-body-sm font-body-sm text-on-surface-variant dark:text-slate-400">
                    <span class="material-symbols-outlined text-[18px] text-tertiary dark:text-emerald-400">check_box</span>
                    <span>Mode Verifikasi Multi-Persetujuan Aktif</span>
                </div>
            </div>

            <!-- Filter Controls Bar -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                <!-- Search Input -->
                <div class="md:col-span-5 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline dark:text-slate-400 text-[18px] pointer-events-none">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pegawai, NIP, atau keterangan..."
                        class="w-full pl-9 pr-3 py-2 text-body-sm font-body-sm bg-surface dark:bg-slate-800 rounded border border-outline-variant/60 dark:border-slate-700 focus:outline-none focus:border-primary text-on-surface dark:text-slate-100 placeholder-secondary dark:placeholder-slate-500" />
                </div>

                <!-- Filter Jenis Izin -->
                <div class="md:col-span-3">
                    <select name="jenis_izin" onchange="this.form.submit()" class="w-full py-2 pl-3 pr-8 text-body-sm font-body-sm bg-surface dark:bg-slate-800 rounded border border-outline-variant/60 dark:border-slate-700 focus:outline-none focus:border-primary text-on-surface dark:text-slate-200 cursor-pointer">
                        <option value="">Semua Jenis Izin / Cuti</option>
                        <option value="cuti_tahunan" {{ request('jenis_izin') === 'cuti_tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                        <option value="izin_khusus" {{ request('jenis_izin') === 'izin_khusus' ? 'selected' : '' }}>Izin Khusus</option>
                        <option value="sakit" {{ request('jenis_izin') === 'sakit' ? 'selected' : '' }}>Izin Sakit</option>
                        <option value="dinas_luar" {{ request('jenis_izin') === 'dinas_luar' ? 'selected' : '' }}>Dinas Luar</option>
                        <option value="cuti_alasan_penting" {{ request('jenis_izin') === 'cuti_alasan_penting' ? 'selected' : '' }}>Cuti Alasan Penting</option>
                        <option value="cuti_melahirkan" {{ request('jenis_izin') === 'cuti_melahirkan' ? 'selected' : '' }}>Cuti Melahirkan</option>
                    </select>
                </div>

                <!-- Filter Periode Bulan -->
                <div class="md:col-span-2">
                    <select name="bulan" onchange="this.form.submit()" class="w-full py-2 pl-3 pr-8 text-body-sm font-body-sm bg-surface dark:bg-slate-800 rounded border border-outline-variant/60 dark:border-slate-700 focus:outline-none focus:border-primary text-on-surface dark:text-slate-200 cursor-pointer">
                        <option value="">Semua Bulan</option>
                        @for($m = 1; $m <= 12; $m++)
                            @php
                                $mName = Carbon\Carbon::create(null, $m, 1)->translatedFormat('F');
                            @endphp
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ $mName }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Filter Reset Button -->
                <div class="md:col-span-2 flex items-center gap-2">
                    <a href="{{ route('izin.index') }}" class="w-full py-2 px-3 rounded border border-outline-variant dark:border-slate-700 text-body-sm font-body-sm font-semibold text-on-surface-variant dark:text-slate-300 hover:bg-surface dark:hover:bg-slate-800 transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                        <span>Reset Filter</span>
                    </a>
                </div>
            </div>
        </form>

        <!-- Main Data Table: Kelola Izin & Cuti -->
        <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-lg border border-outline-variant/50 dark:border-slate-800 overflow-hidden shadow-xs transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F8FAFC] dark:bg-slate-800/80 border-b border-[#CBD5E1] dark:border-slate-700 text-label-caps font-label-caps uppercase text-[#475569] dark:text-slate-400">
                            <th class="py-3 px-4 w-12 text-center font-bold" scope="col">NO</th>
                            <th class="py-3 px-4 min-w-[220px] font-bold" scope="col">PEGAWAI / APARATUR</th>
                            <th class="py-3 px-4 min-w-[140px] font-bold" scope="col">JENIS IZIN</th>
                            <th class="py-3 px-4 min-w-[180px] font-bold" scope="col">TANGGAL &amp; DURASI</th>
                            <th class="py-3 px-4 min-w-[200px] font-bold" scope="col">KETERANGAN / ALASAN</th>
                            <th class="py-3 px-4 min-w-[150px] font-bold" scope="col">DOKUMEN</th>
                            <th class="py-3 px-4 min-w-[130px] font-bold text-center" scope="col">STATUS</th>
                            <th class="py-3 px-4 min-w-[160px] text-right font-bold" scope="col">AKSI VERIFIKASI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0] dark:divide-slate-800 text-body-sm font-body-sm">
                        @forelse($izins as $index => $item)
                            @php
                                $initial = strtoupper(substr($item->pegawai->nama ?? 'P', 0, 2));
                                $mulai = Carbon\Carbon::parse($item->tanggal_mulai);
                                $selesai = Carbon\Carbon::parse($item->tanggal_selesai);
                                $durasi = $mulai->diffInDays($selesai) + 1;
                                $isPending = $item->status === 'pending';
                                $isApproved = $item->status === 'approved';
                                $isRejected = $item->status === 'rejected';

                                // Badge styling per jenis izin
                                $ji = strtolower($item->jenis_izin ?? '');
                                if (str_contains($ji, 'tahunan')) {
                                    $jiIcon = 'beach_access';
                                    $jiClass = 'bg-[#F5F3FF] text-[#7C3AED] border-[#DDD6FE] dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800/40';
                                } elseif (str_contains($ji, 'khusus')) {
                                    $jiIcon = 'badge';
                                    $jiClass = 'bg-[#EFF6FF] text-[#1D4ED8] border-[#BFDBFE] dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/40';
                                } elseif (str_contains($ji, 'sakit')) {
                                    $jiIcon = 'medical_services';
                                    $jiClass = 'bg-[#FEF2F2] text-[#B91C1C] border-[#FECACA] dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/40';
                                } elseif (str_contains($ji, 'dinas')) {
                                    $jiIcon = 'business_center';
                                    $jiClass = 'bg-[#ECFDF5] text-[#047857] border-[#A7F3D0] dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/40';
                                } else {
                                    $jiIcon = 'event_note';
                                    $jiClass = 'bg-surface-container text-on-surface border-outline-variant dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700';
                                }
                            @endphp
                            <tr class="hover:bg-[#F1F5F9] dark:hover:bg-slate-800/50 transition-colors {{ $isPending ? 'bg-[#FFFDF5] dark:bg-amber-950/10' : '' }}">
                                <!-- NO -->
                                <td class="py-3.5 px-4 text-center font-code-mono text-outline dark:text-slate-400 font-semibold">
                                    {{ $izins->firstItem() + $index }}
                                </td>

                                <!-- PEGAWAI / APARATUR -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#E0E7FF] dark:bg-slate-800 text-primary dark:text-blue-400 flex items-center justify-center font-bold text-xs shrink-0 border border-blue-200 dark:border-slate-700">
                                            {{ $initial }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-bold text-on-surface dark:text-slate-100 hover:text-primary dark:hover:text-blue-400 transition-colors block truncate">
                                                {{ $item->pegawai->nama ?? '-' }}
                                            </span>
                                            <span class="text-[11px] font-code-mono text-on-surface-variant dark:text-slate-400 block leading-tight">
                                                NIP. {{ $item->pegawai->nip ?? '-' }}
                                            </span>
                                            <span class="text-[10px] text-outline dark:text-slate-500 font-medium truncate block">
                                                {{ $item->pegawai->jabatan ?? ($item->pegawai->unitKerja->nama_unit ?? 'Pegawai Balai VIII') }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- JENIS IZIN -->
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-semibold border {{ $jiClass }}">
                                        <span class="material-symbols-outlined text-[14px]">{{ $jiIcon }}</span>
                                        <span>{{ ucwords(str_replace('_', ' ', $item->jenis_izin)) }}</span>
                                    </span>
                                </td>

                                <!-- TANGGAL & DURASI -->
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-on-surface dark:text-slate-200 flex items-center gap-1.5 whitespace-nowrap">
                                        <span class="material-symbols-outlined text-[15px] text-outline dark:text-slate-400">calendar_today</span>
                                        <span>{{ $mulai->translatedFormat('d M') }} – {{ $selesai->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <div class="text-[11px] font-code-mono text-on-surface-variant dark:text-slate-400 mt-0.5">
                                        Durasi: <span class="font-bold text-on-surface dark:text-slate-200">{{ $durasi }} Hari Kerja</span>
                                    </div>
                                </td>

                                <!-- KETERANGAN / ALASAN -->
                                <td class="py-3.5 px-4">
                                    <p class="text-on-surface dark:text-slate-300 line-clamp-2" title="{{ $item->keterangan }}">
                                        {{ $item->keterangan ?: 'Tidak ada keterangan rinci' }}
                                    </p>
                                    <span class="text-[10px] text-outline dark:text-slate-500 mt-0.5 block">
                                        Diajukan: {{ $item->created_at ? $item->created_at->translatedFormat('d M Y, H:i') . ' WITA' : '-' }}
                                    </span>
                                </td>

                                <!-- DOKUMEN -->
                                <td class="py-3.5 px-4">
                                    @if($item->dokumen)
                                        <a href="{{ asset($item->dokumen) }}" target="_blank" class="inline-flex items-center gap-1.5 px-2 py-1 rounded text-xs font-medium text-primary dark:text-blue-400 bg-primary-fixed/30 dark:bg-blue-950/60 hover:bg-primary-fixed hover:text-on-primary-fixed transition-colors border border-outline-variant/40 dark:border-slate-700">
                                            <span class="material-symbols-outlined text-[15px]">picture_as_pdf</span>
                                            <span class="truncate max-w-[110px]">Berkas.pdf</span>
                                        </a>
                                    @else
                                        <span class="text-outline dark:text-slate-500 text-xs italic">Tanpa Berkas</span>
                                    @endif
                                </td>

                                <!-- STATUS -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($isPending)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-[#FEF3C7] text-[#92400E] border border-[#FDE68A] dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800/40">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#D97706] animate-pulse"></span>
                                            <span>Pending</span>
                                        </span>
                                        <span class="text-[10px] text-[#B45309] dark:text-amber-400 block mt-0.5 font-medium">Menunggu Atasan</span>
                                    @elseif($isApproved)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-[#ECFDF5] text-[#065F46] border border-[#A7F3D0] dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800/40">
                                            <span class="material-symbols-outlined text-[14px]">check</span>
                                            <span>Disetujui</span>
                                        </span>
                                        <span class="text-[10px] text-[#047857] dark:text-emerald-400 block mt-0.5 font-medium">Sah di Presensi</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-[#FEE2E2] text-[#991B1B] border border-[#FECACA] dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-800/40">
                                            <span class="material-symbols-outlined text-[14px]">close</span>
                                            <span>Ditolak</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- AKSI VERIFIKASI -->
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    @if(auth()->user()->role === 'admin')
                                        @if($isPending)
                                            <div class="inline-flex items-center gap-1">
                                                <button type="button" @click="openReview({{ json_encode($item) }})" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded text-xs font-semibold bg-primary-container text-on-primary hover:bg-primary transition-colors cursor-pointer shadow-none" title="Review dan berikan persetujuan">
                                                    <span class="material-symbols-outlined text-[15px]">check</span>
                                                    <span>Review</span>
                                                </button>
                                                <form action="{{ route('izin.status', $item) }}" method="POST" class="inline" onsubmit="return confirm('Tolak permohonan izin ini?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <input type="hidden" name="catatan_admin" value="Ditolak oleh Administrator">
                                                    <button type="submit" class="inline-flex items-center gap-1 px-2 py-1.5 rounded text-xs font-semibold border border-error text-error hover:bg-error-container transition-colors cursor-pointer" title="Tolak langsung permohonan">
                                                        <span class="material-symbols-outlined text-[15px]">close</span>
                                                        <span>Tolak</span>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <button type="button" @click="openDetail({{ json_encode($item) }})" class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-semibold bg-surface dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-on-surface dark:text-slate-200 hover:bg-surface-container dark:hover:bg-slate-700 transition-colors cursor-pointer shadow-xs">
                                                <span class="material-symbols-outlined text-[15px]">visibility</span>
                                                <span>Detail</span>
                                            </button>
                                        @endif
                                    @else
                                        <!-- Pegawai Actions -->
                                        @if($isPending)
                                            <form action="{{ route('izin.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan pengajuan izin ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded text-xs font-semibold border border-error text-error hover:bg-error-container transition-colors cursor-pointer">
                                                    <span class="material-symbols-outlined text-[15px]">delete</span>
                                                    <span>Batalkan</span>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" @click="openDetail({{ json_encode($item) }})" class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-semibold bg-surface dark:bg-slate-800 border border-outline-variant/60 dark:border-slate-700 text-on-surface dark:text-slate-200 hover:bg-surface-container dark:hover:bg-slate-700 transition-colors cursor-pointer shadow-xs">
                                                <span class="material-symbols-outlined text-[15px]">visibility</span>
                                                <span>Detail</span>
                                            </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 px-4 text-center text-on-surface-variant dark:text-slate-400">
                                    <span class="material-symbols-outlined text-4xl text-outline-variant mb-2">event_busy</span>
                                    <p class="text-body-md font-semibold text-on-surface dark:text-slate-200">Belum ada data permohonan izin atau cuti yang sesuai.</p>
                                    <p class="text-body-sm text-on-surface-variant dark:text-slate-400 mt-1">Gunakan tombol reset filter untuk melihat seluruh arsip pengajuan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer / Pagination -->
            <div class="px-5 py-3.5 bg-[#F8FAFC] dark:bg-slate-800/80 border-t border-[#CBD5E1] dark:border-slate-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-body-sm font-body-sm text-on-surface-variant dark:text-slate-400">
                <div class="flex items-center gap-2">
                    <span>Menampilkan <span class="font-bold text-on-surface dark:text-slate-100">{{ $izins->firstItem() ?? 0 }} - {{ $izins->lastItem() ?? 0 }}</span> dari <span class="font-bold text-on-surface dark:text-slate-100">{{ $izins->total() }}</span> permohonan izin</span>
                    <span class="text-outline dark:text-slate-600">|</span>
                    <div class="flex items-center gap-1">
                        <span>Baris:</span>
                        <select onchange="window.location.href = '{{ request()->fullUrlWithQuery(['per_page' => 'PLACEHOLDER']) }}'.replace('PLACEHOLDER', this.value)" class="bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant/60 dark:border-slate-700 rounded pl-2 pr-6 py-0.5 text-on-surface dark:text-slate-200 text-xs cursor-pointer">
                            <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                        </div>
                        </div>
                        <div>
                            {{ $izins->links('vendor.pagination.compact') }}
                        </div>
            </div>
        </div>

        <!-- Structural Bottom Bento: Workflow & Regulatory Guideline -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <!-- Box 1: Multi-tier Approval Flow -->
            <div class="lg:col-span-2 bg-surface-container-lowest dark:bg-slate-900 rounded-lg border border-outline-variant/50 dark:border-slate-800 p-5 space-y-3 shadow-xs transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary dark:text-blue-400 text-[20px]">account_tree</span>
                        <h2 class="text-headline-sm font-headline-sm font-bold text-on-surface dark:text-slate-100">Alur Persetujuan Bertingkat Permohonan Izin</h2>
                    </div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded bg-surface-container dark:bg-slate-800 text-primary dark:text-blue-400 border border-outline-variant/40 dark:border-slate-700">SOP BKN No. 24/2017</span>
                </div>
                <p class="text-body-sm font-body-sm text-on-surface-variant dark:text-slate-400">
                    Setiap permohonan yang diajukan oleh pegawai Balai Pengembangan Kompetensi PUPR Wilayah VIII Makassar wajib melewati rantai verifikasi berikut sebelum diterbitkan Surat Keputusan Izin/Cuti resmi:
                </p>

                <!-- Stepper Visual -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2">
                    <!-- Step 1 -->
                    <div class="p-3 rounded border border-outline-variant/40 dark:border-slate-700 bg-surface dark:bg-slate-800/60">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-5 h-5 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center">1</span>
                            <span class="font-bold text-on-surface dark:text-slate-200 text-body-sm">Pemohon ASN</span>
                        </div>
                        <p class="text-[11px] text-on-surface-variant dark:text-slate-400 leading-relaxed">
                            Pegawai mengisi e-form izin dan mengunggah dokumen pendukung (surat dokter, undangan, dll).
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="p-3 rounded border border-[#F59E0B]/50 dark:border-amber-800/60 bg-[#FFFBEB] dark:bg-amber-950/20">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-5 h-5 rounded-full bg-[#D97706] text-white text-[10px] font-bold flex items-center justify-center">2</span>
                            <span class="font-bold text-[#92400E] dark:text-amber-300 text-body-sm">Atasan Langsung</span>
                        </div>
                        <p class="text-[11px] text-[#78350F] dark:text-amber-400/90 leading-relaxed">
                            Kasubbag Tata Usaha / Kepala Seksi mereview kelayakan permohonan dan ketersediaan kuota cuti.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="p-3 rounded border border-outline-variant/40 dark:border-slate-700 bg-surface dark:bg-slate-800/60">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-5 h-5 rounded-full bg-outline dark:bg-slate-600 text-white text-[10px] font-bold flex items-center justify-center">3</span>
                            <span class="font-bold text-on-surface dark:text-slate-200 text-body-sm">Kepala Balai (PPK)</span>
                        </div>
                        <p class="text-[11px] text-on-surface-variant dark:text-slate-400 leading-relaxed">
                            Persetujuan final dan pengesahan tanda tangan elektronik untuk pembaharuan log presensi otomatis.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Box 2: Institutional Notice & Regulatory Box -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-lg border border-outline-variant/50 dark:border-slate-800 p-5 space-y-3 flex flex-col justify-between shadow-xs transition-colors">
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-[#B45309] dark:text-amber-400">
                        <span class="material-symbols-outlined text-[20px]">info</span>
                        <h3 class="text-headline-sm font-headline-sm font-bold text-on-surface dark:text-slate-100">Aturan Pelaporan Presensi</h3>
                    </div>
                    <p class="text-body-sm font-body-sm text-on-surface-variant dark:text-slate-400 leading-relaxed">
                        Pengajuan cuti tahunan diajukan paling lambat <strong>H-3 hari kerja</strong> sebelum tanggal mulai. Pengajuan izin khusus mendadak karena kemalangan wajib melampirkan berkas bukti paling lambat 1x24 jam setelah kembali berdinas.
                    </p>
                </div>
                <div class="p-3 rounded bg-surface dark:bg-slate-800/60 border border-outline-variant/40 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-on-surface dark:text-slate-200 block">Sinkronisasi SiDAENG &amp; Presensi PUPR</span>
                        <span class="text-[11px] text-tertiary dark:text-emerald-400 font-medium">Terhubung secara live</span>
                    </div>
                    <span class="material-symbols-outlined text-tertiary dark:text-emerald-400 text-[20px]">sync</span>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL 1: KALENDER CUTI BALAI                                              -->
        <!-- ========================================================================= -->
        <div x-show="calendarModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" style="display: none;">
            <div @click.away="calendarModalOpen = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-2xl w-full p-6 text-left space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-primary dark:text-blue-400 text-2xl">calendar_month</span>
                        <div>
                            <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100 leading-tight">Kalender Cuti Pegawai Balai</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Monitoring jadwal izin &amp; cuti aktif bulan {{ Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
                        </div>
                    </div>
                    <button type="button" @click="calendarModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Daftar Pegawai Sedang Cuti / Izin Bulan Ini:</h4>
                    @if(isset($calendarLeaves) && $calendarLeaves->isNotEmpty())
                        <div class="divide-y divide-slate-100 dark:divide-slate-800 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden max-h-72 overflow-y-auto">
                            @foreach($calendarLeaves as $cl)
                                <div class="p-3 bg-slate-50/50 dark:bg-slate-800/40 flex items-center justify-between gap-3 text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/40 text-primary dark:text-blue-300 font-bold flex items-center justify-center text-[10px]">
                                            {{ strtoupper(substr($cl->pegawai->nama ?? 'P', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 dark:text-slate-200">{{ $cl->pegawai->nama ?? '-' }}</div>
                                            <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ ucwords(str_replace('_', ' ', $cl->jenis_izin)) }} • {{ $cl->keterangan }}</div>
                                        </div>
                                    </div>
                                    <span class="font-code-mono text-slate-600 dark:text-slate-300 font-semibold whitespace-nowrap">
                                        {{ Carbon\Carbon::parse($cl->tanggal_mulai)->translatedFormat('d M') }} – {{ Carbon\Carbon::parse($cl->tanggal_selesai)->translatedFormat('d M') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center text-slate-400 dark:text-slate-500 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                            <span class="material-symbols-outlined text-3xl mb-1 text-slate-300">event_available</span>
                            <p class="text-xs font-medium">Tidak ada permohonan cuti aktif pada periode ini.</p>
                        </div>
                    @endif
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="button" @click="calendarModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container transition-colors cursor-pointer">
                        Tutup Kalender
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL 2: REVIEW / PERSETUJUAN IZIN (ADMIN)                                -->
        <!-- ========================================================================= -->
        <div x-show="reviewModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" style="display: none;">
            <div @click.away="reviewModalOpen = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-lg w-full p-6 text-left space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary dark:text-blue-400 text-xl">fact_check</span>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100">Verifikasi Pengajuan Izin</h3>
                    </div>
                    <button type="button" @click="reviewModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <template x-if="activeIzin">
                    <form :action="'{{ url('/izin') }}/' + activeIzin.id + '/status'" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <!-- Pegawai Detail Card inside Modal -->
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-1.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400 uppercase font-semibold text-[10px]">Nama Pegawai</span>
                                <span class="font-bold text-slate-800 dark:text-slate-100" x-text="activeIzin.pegawai ? activeIzin.pegawai.nama : '-'"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400 uppercase font-semibold text-[10px]">Jenis Izin</span>
                                <span class="font-semibold text-primary dark:text-blue-400" x-text="activeIzin.jenis_izin ? activeIzin.jenis_izin.replace('_', ' ').toUpperCase() : '-'"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400 uppercase font-semibold text-[10px]">Rentang Tanggal</span>
                                <span class="font-code-mono font-medium text-slate-700 dark:text-slate-300" x-text="activeIzin.tanggal_mulai + ' s/d ' + activeIzin.tanggal_selesai"></span>
                            </div>
                            <div class="pt-1 border-t border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300">
                                <span class="font-semibold text-slate-500 dark:text-slate-400">Alasan:</span>
                                <span x-text="activeIzin.keterangan"></span>
                            </div>
                        </div>

                        <!-- Decision Radio Segment -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Keputusan Verifikasi</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="border rounded-xl p-3 flex items-center gap-3 cursor-pointer transition-colors" :class="activeReviewStatus === 'approved' ? 'border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/30' : 'border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800'">
                                    <input type="radio" name="status" value="approved" x-model="activeReviewStatus" class="text-emerald-600 focus:ring-emerald-500">
                                    <div>
                                        <div class="text-xs font-bold text-emerald-800 dark:text-emerald-300">Setujui (Approved)</div>
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400">Sinkronkan ke presensi</div>
                                    </div>
                                </label>
                                <label class="border rounded-xl p-3 flex items-center gap-3 cursor-pointer transition-colors" :class="activeReviewStatus === 'rejected' ? 'border-rose-500 bg-rose-50/70 dark:bg-rose-950/30' : 'border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800'">
                                    <input type="radio" name="status" value="rejected" x-model="activeReviewStatus" class="text-rose-600 focus:ring-rose-500">
                                    <div>
                                        <div class="text-xs font-bold text-rose-800 dark:text-rose-300">Tolak (Rejected)</div>
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400">Kembalikan ke pemohon</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Catatan Admin -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">Catatan Verifikator / Atasan</label>
                            <textarea name="catatan_admin" rows="3" x-model="activeCatatan" placeholder="Tambahkan catatan resmi atau alasan jika menolak..." class="w-full border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-xl text-xs focus:ring-primary focus:border-primary"></textarea>
                        </div>

                        <div class="flex justify-end gap-2 pt-2 border-t border-slate-200 dark:border-slate-800">
                            <button type="button" @click="reviewModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 transition-colors cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-primary text-white hover:bg-primary-container transition-colors shadow-xs cursor-pointer">
                                Simpan Keputusan
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL 3: DETAIL PERMOHONAN                                                -->
        <!-- ========================================================================= -->
        <div x-show="detailModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" style="display: none;">
            <div @click.away="detailModalOpen = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 text-left space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary dark:text-blue-400 text-xl">description</span>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100">Rincian Permohonan Izin</h3>
                    </div>
                    <button type="button" @click="detailModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <template x-if="activeIzin">
                    <div class="space-y-3 text-xs">
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px]">Pegawai</span>
                                <span class="font-bold text-slate-800 dark:text-slate-100" x-text="activeIzin.pegawai ? activeIzin.pegawai.nama : '-'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px]">NIP</span>
                                <span class="font-code-mono text-slate-700 dark:text-slate-300" x-text="activeIzin.pegawai ? activeIzin.pegawai.nip : '-'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px]">Jenis Izin</span>
                                <span class="font-bold text-primary dark:text-blue-400" x-text="activeIzin.jenis_izin ? activeIzin.jenis_izin.replace('_', ' ').toUpperCase() : '-'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px]">Periode</span>
                                <span class="font-code-mono font-bold text-slate-800 dark:text-slate-200" x-text="activeIzin.tanggal_mulai + ' s/d ' + activeIzin.tanggal_selesai"></span>
                            </div>
                        </div>

                        <div>
                            <span class="text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px] block mb-1">Alasan / Keterangan</span>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300" x-text="activeIzin.keterangan || '-'"></div>
                        </div>

                        <div>
                            <span class="text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px] block mb-1">Status Verifikasi</span>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-full font-bold text-xs capitalize"
                                    :class="activeIzin.status === 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : (activeIzin.status === 'rejected' ? 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300')"
                                    x-text="activeIzin.status">
                                </span>
                            </div>
                        </div>

                        <template x-if="activeIzin.catatan_admin">
                            <div>
                                <span class="text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px] block mb-1">Catatan Verifikator</span>
                                <div class="p-3 rounded-xl bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/40 text-amber-900 dark:text-amber-300" x-text="activeIzin.catatan_admin"></div>
                            </div>
                        </template>

                        <template x-if="activeIzin.dokumen">
                            <div class="pt-2">
                                <a :href="'{{ url('/') }}/' + activeIzin.dokumen" target="_blank" class="w-full py-2 px-3 rounded-xl bg-primary text-white font-semibold text-xs flex items-center justify-center gap-2 hover:bg-primary-container transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">download</span>
                                    <span>Unduh Dokumen Lampiran</span>
                                </a>
                            </div>
                        </template>
                    </div>
                </template>

                <div class="pt-2 flex justify-end">
                    <button type="button" @click="detailModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 transition-colors cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
