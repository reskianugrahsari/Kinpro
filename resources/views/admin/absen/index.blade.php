<x-app-layout>
    <div class="space-y-6" x-data="{
        reviewModalOpen: false,
        activeItem: null,
        activeStatus: 'approved',
        activeCatatan: '',

        openReview(item) {
            this.activeItem = item;
            this.activeStatus = item.status === 'pending' ? 'approved' : (item.status || 'approved');
            this.activeCatatan = item.catatan_admin || '';
            this.reviewModalOpen = true;
        }
    }">
        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/60 border-l-4 border-emerald-500 rounded-xl text-emerald-800 dark:text-emerald-300 text-xs font-semibold shadow-xs flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="p-3.5 bg-rose-50 dark:bg-rose-950/60 border-l-4 border-rose-500 rounded-xl text-rose-800 dark:text-rose-300 text-xs font-semibold shadow-xs flex items-center gap-2">
                <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-base">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- 1. HERO HEADER ADMIN DON'T FORGET -->
        <div class="bg-[#0A1931] rounded-2xl p-6 text-white shadow-md border border-slate-800/80 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-1.5 bg-sky-500/20 text-brand-sky border border-sky-400/40 px-2.5 py-0.5 rounded-full text-[11px] font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-sky"></span>
                    <span>Panel Administrator Don't Forget</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                    Kelola Lupa Absen (Don't Forget)
                </h1>
                <p class="text-slate-300 text-xs max-w-2xl leading-relaxed">
                    Verifikasi permohonan lupa absen masuk/pulang pegawai, pantau rekapitulasi bulanan presensi, serta cetak arsip surat keterangan lupa absen yang telah disetujui.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('admin.absen.export', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-xs">
                    <span class="material-symbols-outlined text-sm">download</span>
                    <span>Export CSV Absen</span>
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/15 transition">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    <span>Dashboard Admin</span>
                </a>
            </div>
        </div>

        <!-- 2. SUB-MENU TABS ADMIN LUPA ABSEN -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-2 shadow-xs">
            <nav class="flex flex-wrap items-center gap-1.5 text-xs font-bold" aria-label="Menu Admin Lupa Absen">
                <a href="{{ route('admin.absen.index', ['tab' => 'pengajuan']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl transition {{ $activeTab === 'pengajuan' ? 'bg-[#0A1931] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined text-[17px]">fact_check</span>
                    <span>Verifikasi &amp; Pengajuan</span>
                    @if($pendingCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $activeTab === 'pengajuan' ? 'bg-amber-400 text-[#0A1931]' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300' }}">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.absen.index', ['tab' => 'rekap']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl transition {{ $activeTab === 'rekap' ? 'bg-[#0A1931] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined text-[17px]">bar_chart</span>
                    <span>Rekap Bulanan</span>
                </a>

                <a href="{{ route('admin.absen.index', ['tab' => 'arsip']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl transition {{ $activeTab === 'arsip' ? 'bg-[#0A1931] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined text-[17px]">print</span>
                    <span>Arsip Surat Disetujui</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $activeTab === 'arsip' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300' }}">
                        {{ $approvedCount }}
                    </span>
                </a>
            </nav>
        </div>

        <!-- ===================================================================== -->
        <!-- TAB 1: VERIFIKASI & DAFTAR PENGAJUAN LUPA ABSEN                       -->
        <!-- ===================================================================== -->
        @if($activeTab === 'pengajuan')
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Pengajuan</p>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white font-mono mt-1">{{ $totalAll }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Seluruh klaim lupa absen</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-[#1769AA] dark:text-blue-400 flex items-center justify-center">
                        <span class="material-symbols-outlined">event_repeat</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-amber-200/70 dark:border-amber-800/50 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Menunggu Verifikasi</p>
                        <div class="text-2xl font-bold text-amber-900 dark:text-amber-300 font-mono mt-1">{{ $pendingCount }}</div>
                        <p class="text-[11px] text-amber-700 dark:text-amber-400 mt-0.5">Perlu persetujuan admin</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <span class="material-symbols-outlined">pending_actions</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-emerald-200/70 dark:border-emerald-800/50 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Disetujui</p>
                        <div class="text-2xl font-bold text-emerald-900 dark:text-emerald-300 font-mono mt-1">{{ $approvedCount }}</div>
                        <p class="text-[11px] text-emerald-700 dark:text-emerald-400 mt-0.5">Surat siap dicetak</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <span class="material-symbols-outlined">verified</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-rose-200/70 dark:border-rose-800/50 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-rose-700 dark:text-rose-400">Ditolak</p>
                        <div class="text-2xl font-bold text-rose-900 dark:text-rose-300 font-mono mt-1">{{ $rejectedCount }}</div>
                        <p class="text-[11px] text-rose-700 dark:text-rose-400 mt-0.5">Tidak disetujui</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                        <span class="material-symbols-outlined">cancel</span>
                    </div>
                </div>
            </section>

            <!-- Filter Bar -->
            <section class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
                <form method="GET" action="{{ route('admin.absen.index') }}" id="adminAbsenFilterForm" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                    <input type="hidden" name="tab" value="pengajuan">
                    <div class="relative flex-1 max-w-md">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none">search</span>
                        <input name="search" value="{{ request('search') }}" class="w-full h-10 pl-9 pr-4 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100" placeholder="Cari Nama Pegawai, NIP, Jenis Absen, atau Alasan..." type="text">
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <select name="status" onchange="document.getElementById('adminAbsenFilterForm').submit()" class="h-10 px-3 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-200">
                            <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Semua Status ({{ $totalAll }})</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending ({{ $pendingCount }})</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved ({{ $approvedCount }})</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected ({{ $rejectedCount }})</option>
                        </select>

                        <select name="jenis" onchange="document.getElementById('adminAbsenFilterForm').submit()" class="h-10 px-3 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-200">
                            <option value="all" {{ request('jenis', 'all') === 'all' ? 'selected' : '' }}>Semua Jenis</option>
                            <option value="masuk" {{ request('jenis') === 'masuk' ? 'selected' : '' }}>Lupa Absen Masuk</option>
                            <option value="pulang" {{ request('jenis') === 'pulang' ? 'selected' : '' }}>Lupa Absen Pulang</option>
                        </select>

                        <button type="submit" class="h-10 px-4 rounded-xl bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold transition">
                            Cari
                        </button>
                        <a href="{{ route('admin.absen.index', ['tab' => 'pengajuan']) }}" class="h-10 px-3 flex items-center justify-center rounded-xl border border-slate-200 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold transition">
                            Reset
                        </a>
                    </div>
                </form>
            </section>

            <!-- Table Pengajuan -->
            <section class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                <th class="py-3.5 px-4 text-center w-12">No</th>
                                <th class="py-3.5 px-4 min-w-[200px]">Pegawai Pemohon</th>
                                <th class="py-3.5 px-4 min-w-[140px]">Tanggal Lupa</th>
                                <th class="py-3.5 px-4 min-w-[160px]">Jenis Lupa Absen</th>
                                <th class="py-3.5 px-4 min-w-[180px]">Alasan</th>
                                <th class="py-3.5 px-4 min-w-[150px]">Atasan</th>
                                <th class="py-3.5 px-4 text-center min-w-[110px]">Status</th>
                                <th class="py-3.5 px-4 text-center min-w-[240px]">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-xs">
                            @forelse($pengajuans as $index => $item)
                                @php
                                    $st = strtolower((string) $item->status);
                                    $isApproved = in_array($st, ['approved', 'disetujui'], true);
                                    $isPending = $st === 'pending';
                                    $tglLupa = $item->tanggal_lupa ?? $item->tanggal_absen;
                                    $hari = $tglLupa ? \Carbon\Carbon::parse($tglLupa)->translatedFormat('l') : '-';
                                    $isMasuk = str_contains(strtolower((string) $item->jenis_absen), 'masuk') || str_contains(strtolower((string) $item->jenis_absen), 'datang');
                                @endphp
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3.5 px-4 text-center text-slate-500 dark:text-slate-400 font-semibold">
                                        {{ $pengajuans->firstItem() + $index }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $item->pegawai?->nama ?? '-' }}</div>
                                        <div class="font-mono text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">NIP. {{ $item->pegawai?->nip ?? '-' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $tglLupa ? \Carbon\Carbon::parse($tglLupa)->translatedFormat('d F Y') : '-' }}</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $hari }}</div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $isMasuk ? 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800' : 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800' }}">
                                            {{ ucfirst($item->jenis_absen) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300">
                                        <span class="line-clamp-2" title="{{ $item->alasan }}">{{ $item->alasan }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-700 dark:text-slate-300 font-medium">
                                        {{ $item->atasan?->nama ?? 'Atasan Langsung' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if($isApproved)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Approved</span>
                                        @elseif($isPending)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800">Pending</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="inline-flex items-center justify-center gap-1.5 flex-wrap">
                                            <button type="button" @click="openReview({{ json_encode($item) }})" class="px-2.5 py-1.5 rounded-lg bg-[#0A1931] hover:bg-[#1769AA] text-white font-bold text-[11px] transition cursor-pointer inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">rate_review</span>
                                                <span>Verifikasi</span>
                                            </button>

                                            <a href="{{ route('absen.show', $item) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold text-[11px] transition inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">print</span>
                                                <span>Surat</span>
                                            </a>

                                            <form action="{{ route('absen.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengajuan lupa absen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="redirect_to" value="absen-admin">
                                                <button type="submit" class="px-2 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-950/60 font-bold text-[11px] transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-10 px-4 text-center text-slate-500 dark:text-slate-400">
                                        Belum ada data pengajuan lupa absen yang sesuai filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
                    <div>
                        Menampilkan <span class="font-bold text-slate-800 dark:text-slate-200">{{ $pengajuans->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800 dark:text-slate-200">{{ $pengajuans->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800 dark:text-slate-200">{{ $pengajuans->total() }}</span> pengajuan
                    </div>
                    <div>
                        {{ $pengajuans->links('vendor.pagination.compact') }}
                    </div>
                </div>
            </section>
        @endif

        <!-- ===================================================================== -->
        <!-- TAB 2: REKAP BULANAN LUPA ABSEN                                       -->
        <!-- ===================================================================== -->
        @if($activeTab === 'rekap')
            @php
                $namaBulan = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
            @endphp
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Kasus Periode</p>
                    <div class="text-2xl font-bold text-slate-900 dark:text-white font-mono mt-1">{{ $rekapSummary['total'] }}</div>
                </div>
                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-amber-200/70 dark:border-amber-800/50 shadow-xs">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Lupa Absen Masuk</p>
                    <div class="text-2xl font-bold text-amber-700 dark:text-amber-300 font-mono mt-1">{{ $rekapSummary['masuk'] }}</div>
                </div>
                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-blue-200/70 dark:border-blue-800/50 shadow-xs">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-[#1769AA] dark:text-blue-400">Lupa Absen Pulang</p>
                    <div class="text-2xl font-bold text-[#1769AA] dark:text-blue-300 font-mono mt-1">{{ $rekapSummary['pulang'] }}</div>
                </div>
                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-emerald-200/70 dark:border-emerald-800/50 shadow-xs">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Sudah Diverifikasi</p>
                    <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-300 font-mono mt-1">{{ $rekapSummary['approved'] }}</div>
                </div>
            </section>

            <section class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
                <form method="GET" action="{{ route('admin.absen.index') }}" class="flex flex-wrap items-center gap-2.5 text-xs">
                    <input type="hidden" name="tab" value="rekap">
                    <select name="bulan" class="h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-200">
                        <option value="all" {{ (string) $bulan === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                        @foreach($namaBulan as $num => $lbl)
                            <option value="{{ $num }}" {{ (string) $bulan === (string) $num ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                    <select name="tahun" class="h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-200">
                        @foreach([date('Y'), date('Y') - 1, date('Y') - 2] as $yr)
                            <option value="{{ $yr }}" {{ (string) $tahun === (string) $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                        @endforeach
                    </select>
                    <select name="jenis_absen" class="h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-200">
                        <option value="">Semua Jenis</option>
                        <option value="masuk" {{ $jenisAbsen === 'masuk' ? 'selected' : '' }}>Lupa Absen Masuk</option>
                        <option value="pulang" {{ $jenisAbsen === 'pulang' ? 'selected' : '' }}>Lupa Absen Pulang</option>
                    </select>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIP..." class="h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100">
                    <button type="submit" class="h-10 px-4 rounded-xl bg-[#1769AA] hover:bg-[#0F2747] text-white font-bold transition">Tampilkan Rekap</button>
                    <a href="{{ route('absen.rekap-bulanan', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="h-10 px-3.5 inline-flex items-center rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        Tampilan Rekap Lengkap &rarr;
                    </a>
                </form>
            </section>

            <section class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                <th class="py-3.5 px-4 text-center w-12">No</th>
                                <th class="py-3.5 px-4">Pegawai &amp; NIP</th>
                                <th class="py-3.5 px-4">Unit Kerja</th>
                                <th class="py-3.5 px-4 text-center">Lupa Masuk</th>
                                <th class="py-3.5 px-4 text-center">Lupa Pulang</th>
                                <th class="py-3.5 px-4 text-center">Total Frekuensi</th>
                                <th class="py-3.5 px-4 text-center">Disetujui / Pending</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse($pegawaiRekap as $idx => $row)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                                    <td class="py-3 px-4 text-center text-slate-500 dark:text-slate-400">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $row['nama'] }}</div>
                                        <div class="font-mono text-[11px] text-slate-500 dark:text-slate-400">NIP. {{ $row['nip'] }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-slate-700 dark:text-slate-300">{{ $row['unit'] }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-bold text-amber-700 dark:text-amber-400">{{ $row['total_masuk'] }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-bold text-[#1769AA] dark:text-blue-400">{{ $row['total_pulang'] }}</td>
                                    <td class="py-3 px-4 text-center font-mono font-extrabold text-slate-900 dark:text-white">{{ $row['total_frekuensi'] }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ $row['approved'] }} Approved</span>
                                        <span class="text-slate-400 mx-1">/</span>
                                        <span class="text-amber-600 dark:text-amber-400 font-bold">{{ $row['pending'] }} Pending</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 px-4 text-center text-slate-500 dark:text-slate-400">Tidak ada data lupa absen pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

        <!-- ===================================================================== -->
        <!-- TAB 3: ARSIP SURAT DISETUJUI                                          -->
        <!-- ===================================================================== -->
        @if($activeTab === 'arsip')
            <section class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
                <form method="GET" action="{{ route('admin.absen.index') }}" class="flex flex-wrap items-center gap-2.5 text-xs">
                    <input type="hidden" name="tab" value="arsip">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pegawai, NIP, atau alasan..." class="flex-1 min-w-[220px] max-w-md h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100">
                    <button type="submit" class="h-10 px-4 rounded-xl bg-[#1769AA] hover:bg-[#0F2747] text-white font-bold transition">Cari Arsip</button>
                    <a href="{{ route('admin.absen.index', ['tab' => 'arsip']) }}" class="h-10 px-3 flex items-center rounded-xl border border-slate-200 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold transition">Reset</a>
                </form>
            </section>

            <section class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                <th class="py-3.5 px-4 text-center w-12">No</th>
                                <th class="py-3.5 px-4">Pegawai Pemohon</th>
                                <th class="py-3.5 px-4">Tanggal Lupa</th>
                                <th class="py-3.5 px-4">Jenis Lupa Absen</th>
                                <th class="py-3.5 px-4">Atasan Penandatangan</th>
                                <th class="py-3.5 px-4 text-center">Cetak Surat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse($arsips as $idx => $item)
                                @php $tglLupa = $item->tanggal_lupa ?? $item->tanggal_absen; @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                                    <td class="py-3.5 px-4 text-center text-slate-500 dark:text-slate-400">{{ $arsips->firstItem() + $idx }}</td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $item->pegawai?->nama ?? '-' }}</div>
                                        <div class="font-mono text-[11px] text-slate-500 dark:text-slate-400">NIP. {{ $item->pegawai?->nip ?? '-' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-slate-200">
                                        {{ $tglLupa ? \Carbon\Carbon::parse($tglLupa)->translatedFormat('d F Y') : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4">{{ ucfirst($item->jenis_absen) }}</td>
                                    <td class="py-3.5 px-4 text-slate-700 dark:text-slate-300">{{ $item->atasan?->nama ?? 'Atasan Langsung' }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <a href="{{ route('absen.show', $item) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition">
                                            <span class="material-symbols-outlined text-[15px]">print</span>
                                            <span>Cetak Surat Resmi</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 px-4 text-center text-slate-500 dark:text-slate-400">Belum ada surat lupa absen yang disetujui.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($arsips, 'links'))
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
                        <div>
                            Menampilkan <span class="font-bold text-slate-800 dark:text-slate-200">{{ $arsips->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800 dark:text-slate-200">{{ $arsips->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800 dark:text-slate-200">{{ $arsips->total() }}</span> arsip disetujui
                        </div>
                        <div>
                            {{ $arsips->links('vendor.pagination.compact') }}
                        </div>
                    </div>
                @endif
            </section>
        @endif

        <!-- Review Modal (Admin) -->
        <div x-show="reviewModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" style="display: none;">
            <div @click.away="reviewModalOpen = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl max-w-md w-full p-6 text-left space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#1769AA] text-lg">fact_check</span>
                        <span>Verifikasi Pengajuan Lupa Absen</span>
                    </h3>
                    <button type="button" @click="reviewModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <template x-if="activeItem">
                    <form :action="'{{ url('/absen') }}/' + activeItem.id + '/status'" method="POST" class="space-y-3 text-xs">
                        @csrf
                        @method('PATCH')

                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-y-1">
                            <div class="font-bold text-slate-900 dark:text-white" x-text="activeItem.pegawai ? activeItem.pegawai.nama : '-'"></div>
                            <div class="text-slate-500 dark:text-slate-400" x-text="activeItem.jenis_absen"></div>
                            <div class="italic text-slate-600 dark:text-slate-300" x-text="'“' + (activeItem.alasan || '') + '”'"></div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Keputusan Status</label>
                            <select name="status" x-model="activeStatus" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                                <option value="approved">Setujui (Approved)</option>
                                <option value="rejected">Tolak (Rejected)</option>
                                <option value="pending">Kembalikan ke Pending</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Catatan Admin</label>
                            <textarea name="catatan_admin" x-model="activeCatatan" rows="3" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs" placeholder="Tuliskan catatan atau keterangan tambahan..."></textarea>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" @click="reviewModalOpen = false" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300">Batal</button>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold">Simpan Keputusan</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</x-app-layout>
