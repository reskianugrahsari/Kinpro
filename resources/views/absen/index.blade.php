<x-app-layout>
    <div class="space-y-6" x-data="{
        reviewModalOpen: false,
        activeItem: null,
        activeStatus: 'approved',
        activeCatatan: '',

        openReview(item) {
            this.activeItem = item;
            this.activeStatus = item.status || 'approved';
            this.activeCatatan = item.catatan_admin || '';
            this.reviewModalOpen = true;
        }
    }">
        <!-- Flash Messages -->
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

        <!-- Breadcrumb & Header Title -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-1">
            <div>
                <div class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-1">
                    <a class="hover:text-primary dark:hover:text-blue-400 transition-colors" href="{{ route('dashboard') }}">Beranda</a>
                    <span>/</span>
                    <span class="text-primary dark:text-blue-400 font-semibold">Kelola Lupa Absen</span>
                </div>
                <div class="flex items-center gap-3">
                    <h1 class="text-xl sm:text-2xl font-bold text-on-surface dark:text-slate-100 tracking-tight">Kelola Lupa Absen</h1>
                    <span class="px-2.5 py-0.5 rounded text-[11px] font-semibold bg-surface-container-high dark:bg-slate-800 text-primary dark:text-blue-400 border border-primary/20 dark:border-slate-700">
                        {{ $pengaduans->total() }} Pengajuan
                    </span>
                </div>
            </div>

            <!-- Quick Top Action Controls -->
            <div class="flex items-center gap-2">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('absen.rekap-bulanan') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 shadow-2xs transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">bar_chart</span>
                        <span>Rekap Bulanan</span>
                    </a>
                @endif
                <a href="{{ route('rekap.export', 'excel') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 shadow-2xs transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">file_download</span>
                    <span>Ekspor Data</span>
                </a>
                @if(auth()->user()->role === 'pegawai')
                    <a href="{{ route('absen.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-white bg-primary-container rounded-lg hover:bg-primary shadow-2xs transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        <span>Pengajuan Manual</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Compact Structured KPI Cards (3 Cards) -->
        <section class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
            <!-- Card 1: Menunggu Verifikasi -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-outline-variant/40 dark:border-slate-800 p-4 border-l-4 border-l-amber-500 flex items-center justify-between shadow-2xs transition-colors">
                <div class="space-y-0.5">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menunggu Verifikasi</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-on-surface dark:text-slate-100 font-mono">{{ $pendingCount ?? 0 }}</span>
                        <span class="text-[11px] text-amber-600 dark:text-amber-400 font-semibold">Butuh tindakan</span>
                    </div>
                </div>
                <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-200 dark:border-amber-800/40 shrink-0">
                    <span class="material-symbols-outlined text-xl">pending_actions</span>
                </div>
            </div>

            <!-- Card 2: Telah Disetujui -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-outline-variant/40 dark:border-slate-800 p-4 border-l-4 border-l-emerald-600 flex items-center justify-between shadow-2xs transition-colors">
                <div class="space-y-0.5">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Disetujui Bulan Ini</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-on-surface dark:text-slate-100 font-mono">{{ $approvedCount ?? 0 }}</span>
                        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">Selesai diverifikasi</span>
                    </div>
                </div>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-200 dark:border-emerald-800/40 shrink-0">
                    <span class="material-symbols-outlined text-xl">verified</span>
                </div>
            </div>

            <!-- Card 3: Total Permohonan -->
            <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-outline-variant/40 dark:border-slate-800 p-4 border-l-4 border-l-primary-container flex items-center justify-between shadow-2xs transition-colors">
                <div class="space-y-0.5">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Laporan Periode</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-on-surface dark:text-slate-100 font-mono">{{ $totalAll ?? 0 }}</span>
                        <span class="text-[11px] text-slate-400 font-medium">{{ Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
                    </div>
                </div>
                <div class="w-9 h-9 rounded-lg bg-primary-fixed dark:bg-blue-950/60 text-primary dark:text-blue-400 flex items-center justify-center border border-primary/20 dark:border-blue-800/40 shrink-0">
                    <span class="material-symbols-outlined text-xl">event_repeat</span>
                </div>
            </div>
        </section>

        <!-- Clean Architectural Filter Bar -->
        <section class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-outline-variant/40 dark:border-slate-800 p-3.5 shadow-2xs transition-colors">
            <form method="GET" action="{{ route('absen.index') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

                <!-- Text Filter -->
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Cari Pegawai / NIP</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-base">person_search</span>
                        </span>
                        <input name="search" value="{{ request('search') }}" class="w-full pl-8 pr-3 py-1.5 text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-on-surface dark:text-slate-100 placeholder:text-slate-400 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none" placeholder="Masukkan nama, NIP, atau alasan..." type="text"/>
                    </div>
                </div>

                <!-- Status Dropdown -->
                <div class="w-full lg:w-44">
                    <label class="block text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Status Verifikasi</label>
                    <select name="status" class="w-full pl-2.5 pr-8 py-1.5 text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-on-surface dark:text-slate-100 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Type Dropdown -->
                <div class="w-full lg:w-48">
                    <label class="block text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Jenis Lupa Absen</label>
                    <select name="jenis" class="w-full pl-2.5 pr-8 py-1.5 text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-on-surface dark:text-slate-100 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none cursor-pointer">
                        <option value="">Semua Jenis</option>
                        <option value="masuk" {{ request('jenis') === 'masuk' ? 'selected' : '' }}>Lupa Absen Masuk</option>
                        <option value="pulang" {{ request('jenis') === 'pulang' ? 'selected' : '' }}>Lupa Absen Pulang</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end gap-2 pt-1 lg:pt-0">
                    <button type="submit" class="px-3.5 py-1.5 bg-primary-container text-white text-xs font-semibold rounded-lg hover:bg-primary transition-colors flex items-center gap-1.5 h-[34px] self-end cursor-pointer shadow-2xs">
                        <span class="material-symbols-outlined text-sm">filter_alt</span>
                        <span>Filter Data</span>
                    </button>
                    <a href="{{ route('absen.index') }}" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors h-[34px] flex items-center justify-center self-end cursor-pointer">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        <!-- Main Data Table Container -->
        <section class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-outline-variant/40 dark:border-slate-800 overflow-hidden shadow-2xs transition-colors">
            <div class="px-4 py-2.5 border-b border-outline-variant/30 dark:border-slate-800 flex items-center justify-between bg-surface-container-low/40 dark:bg-slate-800/40">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary dark:text-blue-400 text-lg">format_list_bulleted</span>
                    <h2 class="text-xs font-bold text-on-surface dark:text-slate-100 uppercase tracking-wider">Daftar Pengajuan Lupa Absen</h2>
                </div>
                <span class="text-[11px] text-slate-400">Menampilkan {{ $pengaduans->count() }} dari {{ $pengaduans->total() }} data</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low dark:bg-slate-800/80 border-b border-outline-variant/30 dark:border-slate-800 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <th class="py-2.5 px-3 w-12 text-center" scope="col">NO</th>
                            <th class="py-2.5 px-3 min-w-[200px]" scope="col">PEGAWAI PEMOHON</th>
                            <th class="py-2.5 px-3 min-w-[130px]" scope="col">TANGGAL LUPA</th>
                            <th class="py-2.5 px-3 min-w-[180px]" scope="col">JENIS LUPA ABSEN</th>
                            <th class="py-2.5 px-3 min-w-[140px]" scope="col">ALASAN</th>
                            <th class="py-2.5 px-3 min-w-[150px]" scope="col">ATASAN PENANDATANGAN</th>
                            <th class="py-2.5 px-3 min-w-[100px] text-center" scope="col">STATUS</th>
                            <th class="py-2.5 px-3 min-w-[160px] text-center" scope="col">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20 dark:divide-slate-800 text-xs">
                        @forelse($pengaduans as $index => $item)
                            @php
                                $isPending = $item->status === 'pending';
                                $isApproved = $item->status === 'approved';
                                $tglLupa = $item->tanggal_lupa ?? $item->tanggal_absen;
                                $hari = $tglLupa ? \Carbon\Carbon::parse($tglLupa)->translatedFormat('l') : '-';
                                $isMasuk = str_contains(strtolower($item->jenis_absen), 'masuk') || str_contains(strtolower($item->jenis_absen), 'datang');
                            @endphp
                            <tr class="hover:bg-surface-container-low/50 dark:hover:bg-slate-800/40 transition-colors {{ $isPending ? 'bg-amber-50/20 dark:bg-amber-950/10' : '' }}">
                                <!-- NO -->
                                <td class="py-2.5 px-3 text-center font-mono text-slate-400 text-xs font-semibold">
                                    {{ $pengaduans->firstItem() + $index }}
                                </td>

                                <!-- PEGAWAI PEMOHON -->
                                <td class="py-2.5 px-3">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-on-surface dark:text-slate-100 text-xs">{{ $item->pegawai->nama ?? '-' }}</span>
                                        <span class="font-mono text-[10px] text-slate-400">NIP. {{ $item->pegawai->nip ?? '-' }}</span>
                                    </div>
                                </td>

                                <!-- TANGGAL LUPA -->
                                <td class="py-2.5 px-3">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-on-surface dark:text-slate-200 text-xs">{{ $tglLupa ? \Carbon\Carbon::parse($tglLupa)->translatedFormat('d F Y') : '-' }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $hari }}</span>
                                    </div>
                                </td>

                                <!-- JENIS LUPA ABSEN -->
                                <td class="py-2.5 px-3">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold border {{ $isMasuk ? 'bg-amber-50 text-amber-800 border-amber-300 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/40' : 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/40' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $isMasuk ? 'bg-amber-600' : 'bg-blue-600' }}"></span>
                                        <span>{{ ucfirst($item->jenis_absen) }}</span>
                                    </span>
                                </td>

                                <!-- ALASAN -->
                                <td class="py-2.5 px-3">
                                    <span class="text-slate-600 dark:text-slate-300 italic text-xs truncate block max-w-xs" title="{{ $item->alasan }}">"{{ $item->alasan }}"</span>
                                </td>

                                <!-- ATASAN PENANDATANGAN -->
                                <td class="py-2.5 px-3">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[15px] text-slate-400">verified_user</span>
                                        <span class="font-medium text-on-surface dark:text-slate-200 text-xs truncate max-w-[150px]">{{ $item->atasan?->nama ?? 'Atasan Langsung' }}</span>
                                    </div>
                                </td>

                                <!-- STATUS -->
                                <td class="py-2.5 px-3 text-center">
                                    @if($isApproved)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800/40">
                                            Approved
                                        </span>
                                    @elseif($isPending)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800/40">
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border border-rose-300 dark:border-rose-800/40">
                                            Rejected
                                        </span>
                                    @endif
                                </td>

                                <!-- AKSI -->
                                <td class="py-2.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                        <!-- Cetak Surat Button -->
                                        @if($isApproved)
                                            <a href="{{ route('absen.show', $item) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-surface-container-lowest dark:bg-slate-800 border border-emerald-600 dark:border-emerald-500 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-slate-700 rounded text-[11px] font-semibold transition-colors" title="Cetak Surat Permohonan">
                                                <span class="material-symbols-outlined text-[13px]">print</span>
                                                <span>Cetak Surat</span>
                                            </a>
                                        @else
                                            <a href="{{ route('absen.show', $item) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-surface-container-lowest dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded text-[11px] font-semibold transition-colors" title="Preview Surat Permohonan">
                                                <span class="material-symbols-outlined text-[13px]">print</span>
                                                <span>Cetak Surat</span>
                                            </a>
                                        @endif

                                        <!-- Review Button (Admin) -->
                                        @if(auth()->user()->role === 'admin')
                                            <button type="button" @click="openReview({{ json_encode($item) }})" class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary-container text-white hover:bg-primary rounded text-[11px] font-semibold transition-colors cursor-pointer shadow-2xs" title="Review dan berikan keputusan">
                                                <span class="material-symbols-outlined text-[13px]">rate_review</span>
                                                <span>Review</span>
                                            </button>
                                        @endif

                                        <!-- Batalkan Button -->
                                        @if($isPending)
                                            <form action="{{ route('absen.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 hover:bg-rose-100 rounded text-[11px] font-semibold transition-colors cursor-pointer" title="Batalkan Pengajuan">
                                                    <span class="material-symbols-outlined text-[13px]">cancel</span>
                                                    <span>Batalkan</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400 dark:text-slate-500">
                                    <span class="material-symbols-outlined text-3xl mb-1 text-slate-300">event_busy</span>
                                    <p class="text-xs font-semibold">Belum ada riwayat pengajuan lupa absen.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Pagination Footer -->
            <div class="px-4 py-2.5 border-t border-outline-variant/30 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 bg-surface-container-lowest dark:bg-slate-900 text-xs">
                <div class="text-slate-400 text-[11px]">
                    Menampilkan <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $pengaduans->firstItem() ?? 0 }} - {{ $pengaduans->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $pengaduans->total() }}</span> permohonan
                </div>
                <div>
                    {{ $pengaduans->links('vendor.pagination.compact') }}
                </div>
            </div>
        </section>

        <!-- Institutional Notice / SOP Note -->
        <footer class="p-3 rounded-xl border border-outline-variant/40 dark:border-slate-800 bg-surface-container-low/60 dark:bg-slate-800/50 flex items-start gap-2.5 text-xs text-on-surface-variant dark:text-slate-300">
            <span class="material-symbols-outlined text-primary dark:text-blue-400 text-lg mt-0.5 shrink-0">info</span>
            <div class="leading-relaxed">
                <span class="font-semibold text-on-surface dark:text-slate-100">Perhatian Operasional:</span> Verifikasi lupa absen wajib ditandatangani oleh atasan langsung maksimal 3 hari kerja sejak tanggal permohonan diajukan. Surat keterangan absensi resmi dapat dicetak setelah status berubah menjadi <span class="text-emerald-700 dark:text-emerald-400 font-semibold">Approved</span>.
            </div>
        </footer>

        <!-- Review Modal (Admin) -->
        <div x-show="reviewModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" style="display: none;">
            <div @click.away="reviewModalOpen = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl max-w-md w-full p-5 text-left space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-2.5">
                    <h3 class="font-bold text-base text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-xl">fact_check</span>
                        <span>Review Pengajuan Lupa Absen</span>
                    </h3>
                    <button type="button" @click="reviewModalOpen = false" class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 p-1">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <template x-if="activeItem">
                    <form :action="'{{ url('/absen') }}/' + activeItem.id + '/status'" method="POST" class="space-y-3.5">
                        @csrf
                        @method('PATCH')

                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-xs space-y-1">
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Pegawai:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200" x-text="activeItem.pegawai ? activeItem.pegawai.nama : '-'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Jenis:</span>
                                <span class="font-semibold text-primary capitalize" x-text="activeItem.jenis_absen"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Alasan:</span>
                                <span class="italic text-slate-700 dark:text-slate-300" x-text="'“' + activeItem.alasan + '”'"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-1">Keputusan Status</label>
                            <select name="status" x-model="activeStatus" class="w-full border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-lg text-xs py-2 focus:ring-primary focus:border-primary">
                                <option value="approved">Setujui (Approved)</option>
                                <option value="rejected">Tolak (Rejected)</option>
                                <option value="pending">Kembalikan ke Pending</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-1">Catatan Admin</label>
                            <textarea name="catatan_admin" x-model="activeCatatan" rows="3" class="w-full border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-lg text-xs placeholder:text-slate-400 focus:ring-primary focus:border-primary" placeholder="Tuliskan catatan arahan atau keterangan tambahan..."></textarea>
                        </div>

                        <div class="flex justify-end space-x-2 pt-2 border-t border-slate-200 dark:border-slate-800">
                            <button type="button" @click="reviewModalOpen = false" class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-3.5 py-1.5 rounded-lg text-xs font-semibold hover:bg-slate-200 transition">Batal</button>
                            <button type="submit" class="bg-primary hover:bg-primary-container text-white px-4 py-1.5 rounded-lg text-xs font-bold shadow-xs transition">Simpan Keputusan</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</x-app-layout>
