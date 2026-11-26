<x-app-layout>
    <div class="space-y-6">
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
        @if($errors->any())
            <div class="p-3.5 bg-rose-50 dark:bg-rose-950/60 border-l-4 border-rose-500 rounded-xl text-rose-800 dark:text-rose-300 text-xs font-medium space-y-1 shadow-xs">
                <div class="font-bold">Terjadi kesalahan validasi:</div>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 1. HERO HEADER ADMIN PAJOKKA -->
        <div class="bg-[#0A1931] rounded-2xl p-6 text-white shadow-md border border-slate-800/80 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-1.5 bg-brand-teal/40 text-brand-sky border border-brand-teal/60 px-2.5 py-0.5 rounded-full text-[11px] font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-sky"></span>
                    <span>Panel Administrator Pajokka</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                    Manajemen Laporan Perjalanan Dinas (Pajokka)
                </h1>
                <p class="text-slate-300 text-xs max-w-2xl leading-relaxed">
                    Kelola verifikasi laporan perjalanan dinas pegawai, master data Pejabat Pembuat Komitmen (PPK), kop surat ekspor PDF/Word, dan ringkasan anggaran dinas.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('rekap.export', 'excel') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-xs">
                    <span class="material-symbols-outlined text-sm">download</span>
                    <span>Export Rekap Laporan</span>
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/15 transition">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    <span>Dashboard Admin</span>
                </a>
            </div>
        </div>

        <!-- 2. PAJOKKA ADMIN SUB-MENU TABS (Menu Inti Admin Pajokka) -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-2 shadow-xs">
            <nav class="flex flex-wrap items-center gap-1.5 text-xs font-bold" aria-label="Menu Admin Pajokka">
                <a href="{{ route('admin.pajokka.index', ['tab' => 'laporan']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl transition {{ $activeTab === 'laporan' ? 'bg-[#0A1931] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined text-[17px]">fact_check</span>
                    <span>Verifikasi &amp; Laporan</span>
                    @if($pendingCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $activeTab === 'laporan' ? 'bg-amber-400 text-[#0A1931]' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300' }}">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.pajokka.index', ['tab' => 'master-ppk']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl transition {{ $activeTab === 'master-ppk' ? 'bg-[#0A1931] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined text-[17px]">badge</span>
                    <span>Master PPK</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $activeTab === 'master-ppk' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                        {{ $ppks->count() }}
                    </span>
                </a>

                <a href="{{ route('admin.pajokka.index', ['tab' => 'kop-surat']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl transition {{ $activeTab === 'kop-surat' ? 'bg-[#0A1931] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined text-[17px]">article</span>
                    <span>Pengaturan Kop Surat</span>
                </a>

                <a href="{{ route('admin.pajokka.index', ['tab' => 'rekap']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl transition {{ $activeTab === 'rekap' ? 'bg-[#0A1931] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined text-[17px]">monitoring</span>
                    <span>Rekap &amp; Anggaran Dinas</span>
                </a>
            </nav>
        </div>

        <!-- ===================================================================== -->
        <!-- TAB 1: VERIFIKASI & DAFTAR LAPORAN PERJALANAN DINAS                   -->
        <!-- ===================================================================== -->
        @if($activeTab === 'laporan')
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Laporan</p>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white font-mono mt-1">{{ $totalCount }}</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Seluruh pengajuan dinas</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-[#1769AA] dark:text-blue-400 flex items-center justify-center">
                        <span class="material-symbols-outlined">folder_copy</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-amber-200/70 dark:border-amber-800/50 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Menunggu Review</p>
                        <div class="text-2xl font-bold text-amber-900 dark:text-amber-300 font-mono mt-1">{{ $pendingCount }}</div>
                        <p class="text-[11px] text-amber-700 dark:text-amber-400 mt-0.5">Perlu verifikasi admin</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <span class="material-symbols-outlined">pending_actions</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-emerald-200/70 dark:border-emerald-800/50 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Disetujui</p>
                        <div class="text-2xl font-bold text-emerald-900 dark:text-emerald-300 font-mono mt-1">{{ $approvedCount }}</div>
                        <p class="text-[11px] text-emerald-700 dark:text-emerald-400 mt-0.5">Laporan terverifikasi</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <span class="material-symbols-outlined">verified</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-rose-200/70 dark:border-rose-800/50 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-rose-700 dark:text-rose-400">Revisi / Ditolak</p>
                        <div class="text-2xl font-bold text-rose-900 dark:text-rose-300 font-mono mt-1">{{ $revisionCount }}</div>
                        <p class="text-[11px] text-rose-700 dark:text-rose-400 mt-0.5">Butuh perbaikan pegawai</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                        <span class="material-symbols-outlined">change_circle</span>
                    </div>
                </div>
            </section>

            <!-- Filter Bar -->
            <section class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-3">
                <form method="GET" action="{{ route('admin.pajokka.index') }}" id="adminPajokkaFilterForm">
                    <input type="hidden" name="tab" value="laporan">
                    <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                        <div class="relative flex-1 max-w-md">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none">search</span>
                            <input name="search" value="{{ request('search') }}" class="w-full h-10 pl-9 pr-4 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#1769AA] text-slate-900 dark:text-slate-100" placeholder="Cari Nama, NIP, No. Surat Tugas, Tujuan, atau Perihal..." type="text"/>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <select name="status" onchange="document.getElementById('adminPajokkaFilterForm').submit()" class="h-10 px-3 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-200">
                                <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Semua Status ({{ $totalCount }})</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Review ({{ $pendingCount }})</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui ({{ $approvedCount }})</option>
                                <option value="revision" {{ request('status') === 'revision' ? 'selected' : '' }}>Revisi / Ditolak ({{ $revisionCount }})</option>
                            </select>

                            <select name="tujuan" onchange="document.getElementById('adminPajokkaFilterForm').submit()" class="h-10 px-3 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-200">
                                <option value="">Semua Kota Tujuan</option>
                                @foreach($tujuanList as $tuj)
                                    <option value="{{ $tuj }}" {{ request('tujuan') === $tuj ? 'selected' : '' }}>{{ $tuj }}</option>
                                @endforeach
                            </select>

                            <select name="tahun" onchange="document.getElementById('adminPajokkaFilterForm').submit()" class="h-10 px-3 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-200">
                                <option value="">Semua Tahun</option>
                                @foreach($tahunList as $yr)
                                    <option value="{{ $yr }}" {{ (string) request('tahun') === (string) $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                                @endforeach
                            </select>

                            <button type="submit" class="h-10 px-4 rounded-xl bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold transition">
                                Cari
                            </button>
                            <a href="{{ route('admin.pajokka.index', ['tab' => 'laporan']) }}" class="h-10 px-3 flex items-center justify-center rounded-xl border border-slate-200 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold transition" title="Reset Filter">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <!-- Reports Table -->
            <section class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                <th class="py-3.5 px-4 text-center w-12">No</th>
                                <th class="py-3.5 px-4 min-w-[200px]">Pelaksana &amp; NIP</th>
                                <th class="py-3.5 px-4 min-w-[190px]">No. Surat &amp; Perihal</th>
                                <th class="py-3.5 px-4 min-w-[150px]">Tujuan &amp; Periode</th>
                                <th class="py-3.5 px-4 min-w-[160px]">PPK</th>
                                <th class="py-3.5 px-4 text-right min-w-[120px]">Total Biaya</th>
                                <th class="py-3.5 px-4 text-center min-w-[125px]">Status</th>
                                <th class="py-3.5 px-4 text-center min-w-[260px]">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-xs">
                            @forelse($reports as $index => $item)
                                @php
                                    $st = strtolower($item->status);
                                    $tglMulai = $item->tgl_mulai_dinas ?? $item->tanggal_berangkat;
                                    $tglSelesai = $item->tgl_selesai_dinas ?? $item->tanggal_kembali;
                                    $ppkName = $item->ppk->nama ?? ($item->masterPpk->nama ?? '-');
                                    $pelaksanaName = $item->pegawai->nama ?? ($item->user->name ?? 'Pegawai');
                                    $pelaksanaNip = $item->pegawai->nip ?? ($item->user->nip ?? '-');
                                    $itemCost = (float) ($item->total_cost > 0 ? $item->total_cost : ($item->total_biaya > 0 ? $item->total_biaya : $item->expenses->sum('amount')));
                                @endphp
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3.5 px-4 text-center font-semibold text-slate-500 dark:text-slate-400">
                                        {{ $reports->firstItem() + $index }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $pelaksanaName }}</div>
                                        <div class="font-mono text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">NIP. {{ $pelaksanaNip }}</div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <a href="{{ route('pajokka.show', $item) }}" class="font-bold text-[#1769AA] dark:text-sky-400 hover:underline block">
                                            {{ $item->nomor_surat_tugas ?? ($item->nomor_surat ?? '-') }}
                                        </a>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-1 mt-0.5">{{ $item->isi_pointer ?? ($item->maksud_perjalanan ?? '-') }}</span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $item->kota_tujuan ?? ($item->tujuan ?? '-') }}</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                            {{ $tglMulai ? \Carbon\Carbon::parse($tglMulai)->format('d/m/Y') : '-' }} - {{ $tglSelesai ? \Carbon\Carbon::parse($tglSelesai)->format('d/m/Y') : '-' }}
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-700 dark:text-slate-300 font-medium">
                                        {{ $ppkName }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-800 dark:text-slate-200">
                                        Rp {{ number_format($itemCost, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if($st === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Approved</span>
                                        @elseif(in_array($st, ['revision', 'revision_required']))
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800">Revisi</span>
                                        @elseif($st === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-red-600 text-white">Ditolak</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800">Menunggu</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="inline-flex items-center justify-center gap-1.5 flex-wrap">
                                            <!-- Review Modal -->
                                            <div x-data="{ openReviewModal: false }" class="inline-block">
                                                <button type="button" @click="openReviewModal = true" class="px-2.5 py-1.5 rounded-lg bg-[#0A1931] hover:bg-[#1769AA] text-white font-bold text-[11px] transition cursor-pointer inline-flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px]">rate_review</span>
                                                    <span>Verifikasi</span>
                                                </button>

                                                <div x-show="openReviewModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
                                                    <div @click.away="openReviewModal = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-6 rounded-2xl shadow-xl max-w-md w-full text-left space-y-4">
                                                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                                                            <h3 class="font-bold text-sm text-slate-900 dark:text-white">Verifikasi Laporan Perjalanan Dinas</h3>
                                                            <button type="button" @click="openReviewModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                                                        </div>

                                                        <div class="p-3 bg-slate-50 dark:bg-slate-800 rounded-xl text-xs space-y-1">
                                                            <div class="font-bold text-slate-900 dark:text-white">{{ $pelaksanaName }}</div>
                                                            <div class="font-mono text-[11px] text-slate-500 dark:text-slate-400">{{ $item->nomor_surat_tugas ?? $item->nomor_surat }}</div>
                                                            <div class="text-[11px] text-slate-500 dark:text-slate-400">Tujuan: {{ $item->kota_tujuan ?? $item->tujuan }}</div>
                                                        </div>

                                                        <form action="{{ route('pajokka.status', $item) }}" method="POST" class="space-y-3">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div>
                                                                <label class="block text-[11px] font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Keputusan Status</label>
                                                                <select name="status" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                                                                    <option value="approved" {{ $item->status === 'approved' ? 'selected' : '' }}>Setujui (Approved)</option>
                                                                    <option value="revision_required" {{ in_array($item->status, ['revision', 'revision_required']) ? 'selected' : '' }}>Minta Revisi (Revision)</option>
                                                                    <option value="rejected" {{ $item->status === 'rejected' ? 'selected' : '' }}>Tolak (Rejected)</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="block text-[11px] font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Catatan Verifikator (Wajib jika Revisi)</label>
                                                                <textarea name="admin_notes" rows="3" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs" placeholder="Tuliskan catatan pemeriksaan atau revisi untuk pegawai..."></textarea>
                                                            </div>
                                                            <div class="flex justify-end gap-2 pt-2">
                                                                <button type="button" @click="openReviewModal = false" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300">Batal</button>
                                                                <button type="submit" class="px-4 py-2 rounded-xl bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold">Simpan Status</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <a href="{{ route('pajokka.show', $item) }}" class="px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold text-[11px] transition">
                                                Detail
                                            </a>
                                            <a href="{{ route('pajokka.pdf', $item) }}" class="px-2 py-1.5 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 font-bold text-[11px] hover:bg-rose-100 transition">
                                                PDF
                                            </a>
                                            <a href="{{ route('pajokka.docx', $item) }}" class="px-2 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 font-bold text-[11px] hover:bg-blue-100 transition">
                                                DOCX
                                            </a>
                                            <form action="{{ route('pajokka.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus laporan perjalanan dinas ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
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
                                        Belum ada laporan perjalanan dinas yang sesuai filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
                    <div>
                        Menampilkan <span class="font-bold text-slate-800 dark:text-slate-200">{{ $reports->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800 dark:text-slate-200">{{ $reports->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800 dark:text-slate-200">{{ $reports->total() }}</span> laporan
                    </div>
                    <div>
                        {{ $reports->links('vendor.pagination.compact') }}
                    </div>
                </div>
            </section>
        @endif

        <!-- ===================================================================== -->
        <!-- TAB 2: MASTER DATA PPK (PEJABAT PEMBUAT KOMITMEN)                     -->
        <!-- ===================================================================== -->
        @if($activeTab === 'master-ppk')
            <section x-data="{ addPpkOpen: false }" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-base text-slate-900 dark:text-white">Master Pejabat Pembuat Komitmen (PPK)</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Data PPK penandatangan laporan perjalanan dinas dan SPPD pada modul Pajokka.</p>
                    </div>
                    <button type="button" @click="addPpkOpen = true" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold rounded-xl transition cursor-pointer shadow-xs">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span>Tambah PPK Baru</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                <th class="py-3.5 px-4 text-center w-12">No</th>
                                <th class="py-3.5 px-4">Nama PPK</th>
                                <th class="py-3.5 px-4">NIP</th>
                                <th class="py-3.5 px-4">Jabatan</th>
                                <th class="py-3.5 px-4 text-center w-44">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-xs">
                            @forelse($ppks as $idx => $ppk)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                                    <td class="py-3.5 px-4 text-center text-slate-500 dark:text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">{{ $ppk->nama }}</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-600 dark:text-slate-300">{{ $ppk->nip }}</td>
                                    <td class="py-3.5 px-4 text-slate-700 dark:text-slate-300">{{ $ppk->jabatan }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="inline-flex items-center gap-2" x-data="{ editPpkOpen: false }">
                                            <button type="button" @click="editPpkOpen = true" class="px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 font-bold text-[11px] hover:bg-amber-100 transition">
                                                Edit
                                            </button>

                                            <form action="{{ route('admin.master-ppk.destroy', $ppk) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data PPK ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="redirect_to" value="pajokka-admin">
                                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 font-bold text-[11px] hover:bg-rose-100 transition">
                                                    Hapus
                                                </button>
                                            </form>

                                            <!-- Edit Modal -->
                                            <div x-show="editPpkOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 text-left">
                                                <div @click.away="editPpkOpen = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 max-w-md w-full space-y-4 shadow-xl">
                                                    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                                                        <h3 class="font-bold text-sm text-slate-900 dark:text-white">Edit Data PPK</h3>
                                                        <button type="button" @click="editPpkOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                                                    </div>
                                                    <form action="{{ route('admin.master-ppk.update', $ppk) }}" method="POST" class="space-y-3">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="redirect_to" value="pajokka-admin">
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">Nama PPK</label>
                                                            <input type="text" name="nama" value="{{ $ppk->nama }}" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">NIP</label>
                                                            <input type="text" name="nip" value="{{ $ppk->nip }}" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">Jabatan</label>
                                                            <input type="text" name="jabatan" value="{{ $ppk->jabatan }}" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                                                        </div>
                                                        <div class="flex justify-end gap-2 pt-2">
                                                            <button type="button" @click="editPpkOpen = false" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300">Batal</button>
                                                            <button type="submit" class="px-4 py-2 rounded-xl bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 px-4 text-center text-slate-500 dark:text-slate-400">Belum ada data Master PPK.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Create Modal -->
                <div x-show="addPpkOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
                    <div @click.away="addPpkOpen = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 max-w-md w-full space-y-4 shadow-xl">
                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white">Tambah Master PPK</h3>
                            <button type="button" @click="addPpkOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                        </div>
                        <form action="{{ route('admin.master-ppk.store') }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="redirect_to" value="pajokka-admin">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">Nama PPK <span class="text-rose-500">*</span></label>
                                <input type="text" name="nama" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">NIP <span class="text-rose-500">*</span></label>
                                <input type="text" name="nip" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">Jabatan <span class="text-rose-500">*</span></label>
                                <input type="text" name="jabatan" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" @click="addPpkOpen = false" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300">Batal</button>
                                <button type="submit" class="px-4 py-2 rounded-xl bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold">Simpan PPK</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        @endif

        <!-- ===================================================================== -->
        <!-- TAB 3: PENGATURAN KOP SURAT                                           -->
        <!-- ===================================================================== -->
        @if($activeTab === 'kop-surat')
            <section class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                <div class="lg:col-span-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs p-6 space-y-4">
                    <div class="border-b border-slate-200 dark:border-slate-800 pb-3">
                        <h2 class="font-bold text-base text-slate-900 dark:text-white">Pengaturan Kop Surat Laporan</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Teks kop surat ini dipakai saat mengekspor laporan perjalanan dinas ke PDF dan Word.</p>
                    </div>

                    <form action="{{ route('admin.pajokka.kop-surat.update') }}" method="POST" class="space-y-3 text-xs">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Kementerian <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_kementerian" value="{{ old('nama_kementerian', $kopPreview['kementerian']) }}" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Badan <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_badan" value="{{ old('nama_badan', $kopPreview['badan']) }}" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Unit Kerja <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_unit_kerja" value="{{ old('nama_unit_kerja', $kopPreview['unit_kerja']) }}" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat <span class="text-rose-500">*</span></label>
                            <input type="text" name="alamat" value="{{ old('alamat', ($kop && !str_contains((string)$kop->alamat, 'Pengayoman')) ? $kop->alamat : 'Jalan Nuri No.19-Maksassar 90122') }}" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Telephone</label>
                                <input type="text" name="telepon" value="{{ old('telepon', ($kop && !str_contains((string)$kop->alamat, 'Pengayoman')) ? $kop->telepon : '0411-873749/854547') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Email</label>
                                <input type="text" name="email" value="{{ old('email', ($kop && !str_contains((string)$kop->alamat, 'Pengayoman')) ? $kop->email : 'bapekom8makassar@pu.go.id') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Website</label>
                            <input type="text" name="website" value="{{ old('website', $kop->website ?? 'https://bpsdm.pu.go.id') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-[#1769AA] hover:bg-[#0F2747] text-white font-bold rounded-xl text-xs transition shadow-xs">
                                Simpan Kop Surat
                            </button>
                        </div>
                    </form>
                </div>

                <div class="lg:col-span-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs p-6 space-y-4">
                    <div class="border-b border-slate-200 dark:border-slate-800 pb-3">
                        <h2 class="font-bold text-base text-slate-900 dark:text-white">Pratinjau Kop Surat Resmi</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tampilan kop surat pada dokumen laporan PDF dan Word.</p>
                    </div>

                    <div class="bg-white text-black p-6 rounded-xl border border-slate-300 shadow-inner overflow-x-auto">
                        <div class="flex items-center gap-3 min-w-[460px]">
                            @if(file_exists(public_path('images/logo-pu.png')))
                                <img src="{{ asset('images/logo-pu.png') }}" alt="Logo PU" class="w-16 h-16 shrink-0 object-contain">
                            @endif
                            <div class="flex-1 text-center border-b-[2.5px] border-black pb-1.5" style="font-family: Arial, Helvetica, sans-serif;">
                                <div class="font-bold uppercase tracking-wide leading-tight" style="font-size: 16px;">{{ $kopPreview['kementerian'] }}</div>
                                <div class="font-bold uppercase leading-tight mt-0.5" style="font-size: 11px;">{{ $kopPreview['badan'] }}</div>
                                <div class="font-bold uppercase leading-tight mt-0.5" style="font-size: 9.5px;">{{ $kopPreview['unit_kerja'] }}</div>
                                <div class="mt-0.5 leading-tight" style="font-size: 8px;">{{ $kopPreview['address_line'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- ===================================================================== -->
        <!-- TAB 4: REKAP & ANGGARAN DINAS                                         -->
        <!-- ===================================================================== -->
        @if($activeTab === 'rekap')
            <section class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Grand Total Biaya Dinas</p>
                        <div class="text-2xl font-extrabold text-emerald-700 dark:text-emerald-400 font-mono mt-1.5">
                            Rp {{ number_format($totalAnggaran, 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Akumulasi dari {{ $totalCount }} laporan</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-blue-200/70 dark:border-blue-800/50 shadow-xs">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-[#1769AA] dark:text-blue-400">Total Pembayaran KKP</p>
                        <div class="text-2xl font-extrabold text-[#1769AA] dark:text-blue-400 font-mono mt-1.5">
                            Rp {{ number_format($totalKkp, 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kartu Kredit Pemerintah</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-amber-200/70 dark:border-amber-800/50 shadow-xs">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Total Pembayaran Cash</p>
                        <div class="text-2xl font-extrabold text-amber-700 dark:text-amber-400 font-mono mt-1.5">
                            Rp {{ number_format($totalCash, 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Tunai / Reimburse</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <h2 class="font-bold text-base text-slate-900 dark:text-white">Rekapitulasi Perjalanan Dinas per Kota Tujuan</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Distribusi jumlah perjalanan dan total biaya berdasarkan kota tujuan.</p>
                        </div>
                        <a href="{{ route('rekap.export', 'excel') }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition">
                            Unduh CSV Rekap
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                    <th class="py-3.5 px-4 text-center w-12">No</th>
                                    <th class="py-3.5 px-4">Kota Tujuan</th>
                                    <th class="py-3.5 px-4 text-center">Jumlah Laporan</th>
                                    <th class="py-3.5 px-4 text-right">Total Biaya Tercatat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @forelse($rekapByTujuan as $idx => $row)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                                        <td class="py-3 px-4 text-center text-slate-500 dark:text-slate-400">{{ $idx + 1 }}</td>
                                        <td class="py-3 px-4 font-bold text-slate-900 dark:text-white">{{ $row->tujuan_nama }}</td>
                                        <td class="py-3 px-4 text-center font-mono font-bold text-[#1769AA] dark:text-blue-400">{{ $row->total_laporan }}</td>
                                        <td class="py-3 px-4 text-right font-mono font-bold text-slate-800 dark:text-slate-200">Rp {{ number_format((float) $row->total_biaya, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 px-4 text-center text-slate-500 dark:text-slate-400">Belum ada data perjalanan dinas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
