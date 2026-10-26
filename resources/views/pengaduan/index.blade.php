<x-app-layout>
    <div class="space-y-4" x-data="{
        selectedId: {{ $pengaduans->first()?->id ?? 'null' }},
        items: @js($pengaduans->items()),
        catatanAdmin: '',

        get activeItem() {
            if (!this.selectedId && this.items.length > 0) {
                return this.items[0];
            }
            return this.items.find(i => i.id == this.selectedId) || (this.items[0] || null);
        },

        selectItem(item) {
            this.selectedId = item.id;
            this.catatanAdmin = item.alasan_penolakan || '';
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            try {
                const d = new Date(dateStr);
                return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
            } catch (e) {
                return dateStr;
            }
        }
    }">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/60 border-l-4 border-emerald-500 rounded-lg text-emerald-800 dark:text-emerald-300 text-xs font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-sm">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 bg-rose-50 dark:bg-rose-950/60 border-l-4 border-rose-500 rounded-lg text-rose-800 dark:text-rose-300 text-xs font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-sm">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Slim Modern Header with Live Status Pills -->
        <div class="flex flex-wrap items-center justify-between gap-2.5 pb-0.5">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Pengaduan:</span>
                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800/50 text-amber-900 dark:text-amber-300 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-xs font-semibold">{{ $pendingCount }} Butuh Respon</span>
                    <span class="text-[9px] font-mono bg-amber-200/70 dark:bg-amber-900/60 text-amber-900 dark:text-amber-200 font-bold px-1 py-0.2 rounded">&lt;48 jam</span>
                </div>
                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/50 text-emerald-900 dark:text-emerald-300 shadow-2xs">
                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-sm">check_circle</span>
                    <span class="text-xs font-semibold">{{ $approvedCount }} Disetujui</span>
                </div>
                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800/50 text-rose-900 dark:text-rose-300 shadow-2xs">
                    <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-sm">cancel</span>
                    <span class="text-xs font-semibold">{{ $rejectedCount }} Ditolak</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('rekap.export', 'excel') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-surface-container-lowest dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 text-xs font-semibold shadow-2xs transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-sm">ios_share</span>
                    <span>Ekspor</span>
                </a>
                @if(auth()->user()->role === 'pegawai')
                    <a href="{{ route('pengaduan.create') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary-container text-white text-xs font-semibold hover:bg-blue-700 shadow-2xs transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span>Buat Tiket</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- ==================== SPLIT WORKSPACE: TICKET FEED + DETAIL VERIFIER ==================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
            <!-- LEFT COLUMN (5 Cols): Task/Ticket Feed & Filters -->
            <div class="lg:col-span-5 space-y-2.5">
                <!-- Feed Filter Header -->
                <form method="GET" action="{{ route('pengaduan.index') }}" id="feedFilterForm" class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-2.5 shadow-2xs space-y-2 transition-colors">
                    <input type="hidden" name="status" id="statusInput" value="{{ request('status', 'all') }}">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

                    <!-- Segmented Filter Pills -->
                    <div class="flex items-center gap-1 overflow-x-auto pb-0.5 text-xs">
                        @php
                            $curSt = request('status', 'all');
                        @endphp
                        <button type="button" onclick="document.getElementById('statusInput').value='all'; document.getElementById('feedFilterForm').submit();"
                            class="px-2 py-1 rounded-md transition-colors cursor-pointer {{ $curSt === 'all' ? 'bg-primary-container text-white font-bold shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium' }}">
                            Semua ({{ $totalAll }})
                        </button>
                        <button type="button" onclick="document.getElementById('statusInput').value='pending'; document.getElementById('feedFilterForm').submit();"
                            class="px-2 py-1 rounded-md transition-colors cursor-pointer flex items-center gap-1 {{ $curSt === 'pending' ? 'bg-primary-container text-white font-bold shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium' }}">
                            <span>Menunggu</span>
                            <span class="px-1.5 py-0.2 rounded-full {{ $curSt === 'pending' ? 'bg-white/25 text-white' : 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300' }} text-[9px] font-mono font-bold">{{ $pendingCount }}</span>
                        </button>
                        <button type="button" onclick="document.getElementById('statusInput').value='approved'; document.getElementById('feedFilterForm').submit();"
                            class="px-2 py-1 rounded-md transition-colors cursor-pointer flex items-center gap-1 {{ $curSt === 'approved' ? 'bg-primary-container text-white font-bold shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium' }}">
                            <span>Disetujui</span>
                            <span class="text-slate-500 dark:text-slate-400 font-mono text-[9px]">{{ $approvedCount }}</span>
                        </button>
                        <button type="button" onclick="document.getElementById('statusInput').value='rejected'; document.getElementById('feedFilterForm').submit();"
                            class="px-2 py-1 rounded-md transition-colors cursor-pointer flex items-center gap-1 {{ $curSt === 'rejected' ? 'bg-primary-container text-white font-bold shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium' }}">
                            <span>Ditolak</span>
                            <span class="text-slate-500 dark:text-slate-400 font-mono text-[9px]">{{ $rejectedCount }}</span>
                        </button>
                    </div>

                    <!-- Search & Fast Category Toggle -->
                    <div class="flex items-center gap-2 pt-1 border-t border-slate-100 dark:border-slate-800">
                        <div class="relative flex-1">
                            <span class="material-symbols-outlined absolute left-2 top-1.5 text-slate-500 dark:text-slate-400 text-sm pointer-events-none">search</span>
                            <input name="search" value="{{ request('search') }}" class="w-full pl-7 pr-2 py-1 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md text-xs text-on-surface dark:text-slate-100 placeholder:text-slate-500 dark:placeholder:text-slate-400 focus:outline-none focus:border-primary-container focus:bg-white dark:focus:bg-slate-900" placeholder="Filter tiket / nama..." type="text">
                        </div>
                        <select name="jenis" onchange="this.form.submit()" class="pl-2 pr-7 py-1 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md text-xs text-slate-600 dark:text-slate-200 focus:outline-none cursor-pointer max-w-[140px]">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('jenis') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <!-- Interactive Ticket Cards List -->
                <div class="space-y-2 custom-scrollbar max-h-[580px] overflow-y-auto pr-0.5">
                    @forelse($pengaduans as $index => $item)
                        @php
                            $initial = strtoupper(substr($item->pegawai->nama ?? 'P', 0, 2));
                            $ticketCode = '#TK-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
                            $isPending = $item->status === 'pending';
                            $isApproved = $item->status === 'approved';
                            $isRejected = $item->status === 'rejected';

                            $catStyle = match(strtolower($item->jenis_laporan)) {
                                'lupa absen masuk' => 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800/40',
                                'lupa absen pulang' => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800/40',
                                default => 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700'
                            };

                            $catIcon = match(strtolower($item->jenis_laporan)) {
                                'lupa absen masuk' => 'login',
                                'lupa absen pulang' => 'schedule',
                                default => 'report'
                            };
                        @endphp
                        <div @click="selectItem({{ json_encode($item) }})"
                            class="group relative p-3 rounded-xl transition-all cursor-pointer border"
                            :class="activeItem && activeItem.id == {{ $item->id }}
                                ? 'bg-white dark:bg-slate-800 border-2 border-primary-container shadow-sm'
                                : 'bg-surface-container-lowest dark:bg-slate-900 border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-2xs'">

                            <!-- Left active indicator strip -->
                            <div class="absolute -left-1 top-3 bottom-3 w-1 bg-primary-container rounded-r" x-show="activeItem && activeItem.id == {{ $item->id }}"></div>

                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-bold text-xs ring-1 ring-indigo-200 dark:ring-indigo-800/40 shrink-0">
                                        {{ $initial }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-slate-900 dark:text-slate-100 text-xs truncate">{{ $item->pegawai->nama ?? '-' }}</span>
                                            <span class="px-1.5 py-0.2 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[9px] font-mono shrink-0">{{ $ticketCode }}</span>
                                        </div>
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono truncate">NIP. {{ $item->pegawai->nip ?? '-' }}</div>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="shrink-0">
                                    @if($isPending)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800/40">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                            <span>Pending</span>
                                        </span>
                                    @elseif($isApproved)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800/40">
                                            <span class="material-symbols-outlined text-xs">check</span>
                                            <span>Approved</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border border-rose-300 dark:border-rose-800/40">
                                            <span class="material-symbols-outlined text-xs">close</span>
                                            <span>Rejected</span>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-2 flex items-center gap-1.5 flex-wrap">
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[10px] font-semibold border {{ $catStyle }}">
                                    <span class="material-symbols-outlined text-xs">{{ $catIcon }}</span>
                                    <span>{{ $item->jenis_laporan }}</span>
                                </span>
                                <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs text-slate-500 dark:text-slate-400">calendar_today</span>
                                    <span>{{ $item->tanggal_kejadian }}</span>
                                </span>
                            </div>

                            <div class="mt-1.5 text-[11px] text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/70 p-1.5 rounded border border-slate-100 dark:border-slate-800 line-clamp-2">
                                <span class="text-slate-500 dark:text-slate-400 font-mono">Alasan:</span> <span class="italic font-medium">“{{ $item->keterangan }}”</span>
                            </div>

                            @if($item->alasan_penolakan)
                                <div class="mt-1.5 text-[10px] text-rose-700 dark:text-rose-300 bg-rose-50/60 dark:bg-rose-950/30 p-1.5 rounded border border-rose-200 dark:border-rose-800/40">
                                    <span class="font-semibold">Catatan:</span> {{ $item->alasan_penolakan }}
                                </div>
                            @endif

                            <div class="mt-2 flex items-center justify-between text-[10px] text-slate-500 dark:text-slate-400 pt-1.5 border-t border-slate-100 dark:border-slate-800">
                                <span class="flex items-center gap-1 text-slate-500 dark:text-slate-400">
                                    @if($item->bukti)
                                        <span class="material-symbols-outlined text-xs text-primary">attach_file</span>
                                        <span class="text-primary font-medium">Ada Lampiran</span>
                                    @else
                                        <span class="material-symbols-outlined text-xs">attach_file_off</span>
                                        <span>Tanpa Lampiran</span>
                                    @endif
                                </span>
                                <span class="font-semibold text-primary-container dark:text-blue-400 flex items-center gap-0.5" x-show="activeItem && activeItem.id == {{ $item->id }}">
                                    <span>Sedang Dilihat</span>
                                    <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-500 dark:text-slate-400 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl bg-surface-container-lowest dark:bg-slate-900">
                            <span class="material-symbols-outlined text-2xl mb-1 text-slate-400">inbox</span>
                            <p class="text-xs font-semibold">Tidak ada data tiket pengaduan.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Feed Pagination Footer -->
                <div class="flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 px-1 pt-1 border-t border-slate-100 dark:border-slate-800 gap-2">
                    <span class="text-[10px] text-slate-500 dark:text-slate-400">Hal. {{ $pengaduans->currentPage() }} dari {{ $pengaduans->lastPage() }} ({{ $pengaduans->total() }} entri)</span>
                    <div>
                        {{ $pengaduans->links('vendor.pagination.compact') }}
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN (7 Cols): Quick Verification Workspace & Live Audit Inspector -->
            <div class="lg:col-span-7 space-y-3.5">
                <template x-if="activeItem">
                    <div class="space-y-3.5">
                        <!-- Main Inspector Card -->
                        <div class="bg-surface-container-lowest dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors">
                            <!-- Inspector Top Bar -->
                            <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/70 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-2.5">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full" :class="activeItem.status === 'pending' ? 'bg-amber-500' : (activeItem.status === 'approved' ? 'bg-emerald-500' : 'bg-rose-500')"></span>
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300">Pemeriksaan Tiket Aktif</span>
                                    <span class="font-mono text-[11px] font-semibold px-1.5 py-0.2 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200" x-text="'#TK-' + String(activeItem.id).padStart(4, '0')"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-slate-500 dark:text-slate-400" x-text="'Diajukan: ' + formatDate(activeItem.tanggal_pengaduan || activeItem.created_at)"></span>
                                </div>
                            </div>

                            <div class="p-4 space-y-4">
                                <!-- ASN Header Brief -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 rounded-xl bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 gap-2.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-2xs shrink-0" x-text="activeItem.pegawai ? activeItem.pegawai.nama.substring(0,2).toUpperCase() : 'PE'">
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm leading-tight truncate" x-text="activeItem.pegawai ? activeItem.pegawai.nama : '-'"></h3>
                                            <div class="flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">
                                                <span x-text="activeItem.pegawai ? 'NIP. ' + activeItem.pegawai.nip : '-'"></span>
                                            </div>
                                            <div class="text-[10px] text-slate-600 dark:text-slate-400 mt-0.5 truncate" x-text="activeItem.pegawai ? (activeItem.pegawai.jabatan || 'Aparatur Sipil Negara') + ' — ' + (activeItem.pegawai.unit_kerja ? activeItem.pegawai.unit_kerja.nama_unit : 'Bapekom VIII') : '-'"></div>
                                        </div>
                                    </div>
                                    <div class="text-left sm:text-right shrink-0">
                                        <div class="text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Pegawai</div>
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.2 rounded border border-emerald-200 dark:border-emerald-800/40 mt-0.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            Aktif Bapekom VIII
                                        </span>
                                    </div>
                                </div>

                                <!-- Comparison Matrix (Audit Jam Scan vs Klaim) -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <!-- Card Submisi Klaim -->
                                    <div class="p-3 rounded-lg border border-purple-200 dark:border-purple-800/40 bg-purple-50/30 dark:bg-purple-950/20">
                                        <div class="flex items-center justify-between text-xs font-bold text-purple-900 dark:text-purple-300 pb-1.5 border-b border-purple-200/60 dark:border-purple-800/40">
                                            <span class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-sm text-purple-700 dark:text-purple-400">edit_calendar</span>
                                                Klaim Pengaduan ASN
                                            </span>
                                            <span class="text-[10px] font-mono bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 px-1 py-0.2 rounded font-bold">Koreksi</span>
                                        </div>
                                        <div class="mt-2 space-y-1.5 text-xs">
                                            <div class="flex justify-between">
                                                <span class="text-slate-500 dark:text-slate-400 text-[11px]">Jenis Kendala:</span>
                                                <span class="font-semibold text-slate-800 dark:text-slate-200 text-xs" x-text="activeItem.jenis_laporan"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-500 dark:text-slate-400 text-[11px]">Tanggal Target:</span>
                                                <span class="font-mono font-bold text-slate-800 dark:text-slate-200 text-xs" x-text="activeItem.tanggal_kejadian"></span>
                                            </div>
                                            <div class="pt-1.5 border-t border-purple-100 dark:border-purple-800/40">
                                                <span class="text-slate-500 dark:text-slate-400 block mb-1 text-[11px]">Alasan Submisi:</span>
                                                <p class="italic text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-800 p-2 rounded border border-purple-100 dark:border-purple-800 text-[11px] leading-relaxed" x-text="'“' + activeItem.keterangan + '”'"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card Log Audit Mesin & GPS -->
                                    <div class="p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40">
                                        <div class="flex items-center justify-between text-xs font-bold text-slate-800 dark:text-slate-200 pb-1.5 border-b border-slate-200 dark:border-slate-700">
                                            <span class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-sm text-slate-600 dark:text-slate-400">fingerprint</span>
                                                Log Audit Presensi
                                            </span>
                                            <span class="text-[9px] font-mono bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-1 py-0.2 rounded">SiDAENG</span>
                                        </div>
                                        <div class="mt-2 space-y-1.5 text-xs">
                                            <div class="flex justify-between">
                                                <span class="text-slate-500 dark:text-slate-400 text-[11px]">Absen Masuk:</span>
                                                <span class="font-mono text-emerald-600 dark:text-emerald-400 font-semibold text-xs">Terekam Valid</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-500 dark:text-slate-400 text-[11px]">Geolocation:</span>
                                                <span class="text-slate-700 dark:text-slate-300 text-xs">Bapekom VIII</span>
                                            </div>
                                            <div class="pt-1.5 border-t border-slate-200 dark:border-slate-700">
                                                <span class="text-slate-500 dark:text-slate-400 block mb-1 text-[11px]">Status Validasi:</span>
                                                <div class="flex items-center gap-1.5 text-blue-600 dark:text-blue-400 font-medium bg-blue-50 dark:bg-blue-950/40 p-1.5 rounded border border-blue-200 dark:border-blue-800/50 text-[10px]">
                                                    <span class="material-symbols-outlined text-sm">verified_user</span>
                                                    <span>Menunggu telaah verifikator Kasubbag TU</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lampiran & Kuota Row -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-0.5">
                                    <!-- Lampiran Bukti File -->
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-2.5">
                                        <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                                            <span>Lampiran Berkas Pendukung</span>
                                            <span class="text-[10px] text-slate-400 font-mono" x-text="activeItem.bukti ? '1 Berkas' : '0 Berkas'"></span>
                                        </div>
                                        <template x-if="activeItem.bukti">
                                            <div class="p-2.5 bg-blue-50/50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800/50 rounded-lg flex items-center justify-between">
                                                <div class="flex items-center gap-1.5 min-w-0">
                                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">description</span>
                                                    <span class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate max-w-[130px]">Dokumen_Bukti.pdf</span>
                                                </div>
                                                <a :href="'{{ url('/') }}/' + activeItem.bukti" target="_blank" class="px-2 py-0.5 bg-primary text-white rounded text-[11px] font-bold hover:bg-primary-container shrink-0">
                                                    Unduh
                                                </a>
                                            </div>
                                        </template>
                                        <template x-if="!activeItem.bukti">
                                            <div class="border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-lg p-2.5 text-center bg-slate-50/50 dark:bg-slate-800/40">
                                                <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 text-xl">folder_off</span>
                                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Pegawai tidak melampirkan berkas bukti fisik.</p>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Monitoring Kuota Bulanan ASN -->
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-2.5 bg-white dark:bg-slate-800">
                                        <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                                            <span>Status Tiket Saat Ini</span>
                                            <span class="font-mono text-xs font-bold capitalize" :class="activeItem.status === 'approved' ? 'text-emerald-600' : (activeItem.status === 'rejected' ? 'text-rose-600' : 'text-amber-600')" x-text="activeItem.status"></span>
                                        </div>
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400 mb-1.5">Toleransi lupa absen reguler maksimal 3x per bulan.</div>
                                        <!-- Progress Bar -->
                                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-primary h-1.5 rounded-full" style="width: 75%"></div>
                                        </div>
                                        <div class="flex items-center justify-between text-[10px] text-slate-500 dark:text-slate-400 mt-1">
                                            <span>Kategori: Kendala Absensi</span>
                                            <span class="text-emerald-600 font-semibold">Tersinkronisasi</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Verification Form & Catatan Verifikator -->
                                @if(auth()->user()->role === 'admin')
                                    <form :action="'{{ url('/pengaduan') }}/' + activeItem.id + '/status'" method="POST" class="pt-2 border-t border-slate-200 dark:border-slate-800 space-y-2.5">
                                        @csrf
                                        @method('PATCH')

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                                Catatan / Instruksi Verifikator (Wajib diisi jika ditolak):
                                            </label>
                                            <textarea name="alasan_penolakan" x-model="catatanAdmin" class="w-full text-xs p-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 placeholder:text-slate-500 dark:placeholder:text-slate-400 focus:outline-none focus:border-primary-container focus:bg-white dark:focus:bg-slate-900 resize-none" placeholder="Tuliskan catatan tindak lanjut, arahan perbaikan, atau alasan jika ditolak..." rows="2"></textarea>
                                        </div>

                                        <!-- Quick Action Verification Buttons -->
                                        <div class="flex flex-wrap items-center justify-between gap-2 pt-1 border-t border-slate-200 dark:border-slate-800">
                                            <div class="flex items-center gap-2">
                                                <button type="submit" name="status" value="pending" class="px-2.5 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs flex items-center gap-1 transition-colors cursor-pointer">
                                                    <span class="material-symbols-outlined text-sm">contact_support</span>
                                                    <span>Minta Bukti Tambahan</span>
                                                </button>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="submit" name="status" value="rejected" class="px-3 py-1.5 rounded-lg bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 dark:hover:bg-rose-900/40 border border-rose-300 dark:border-rose-800/60 text-rose-700 dark:text-rose-300 font-bold text-xs flex items-center gap-1 transition-colors cursor-pointer">
                                                    <span class="material-symbols-outlined text-sm">close</span>
                                                    <span>Tolak Pengaduan</span>
                                                </button>
                                                <button type="submit" name="status" value="approved" class="px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1 shadow-2xs transition-colors cursor-pointer">
                                                    <span class="material-symbols-outlined text-sm">check_circle</span>
                                                    <span>Setujui Pengaduan</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- Mini Guideline Banner below Workspace -->
                        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-3 flex flex-col sm:flex-row items-center justify-between gap-2.5 text-xs shadow-2xs transition-colors">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-base">policy</span>
                                </div>
                                <div class="text-[11px] leading-relaxed">
                                    <span class="font-bold text-slate-800 dark:text-slate-200">Pedoman Verifikasi Juknis Bapekom Wilayah VIII:</span>
                                    <span class="text-slate-500 dark:text-slate-400 ml-1">Keterangan uji coba seperti "tes" atau "lupa" tanpa dokumen resmi otomatis ditolak sesuai SOP Pasal 4 ayat (2).</span>
                                </div>
                            </div>
                            <a class="text-primary dark:text-blue-400 text-xs font-bold hover:underline shrink-0 flex items-center gap-1" href="{{ route('rekap.export', 'pdf') }}" target="_blank">
                                <span>Lihat SOP</span>
                                <span class="material-symbols-outlined text-xs">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- ==================== BOTTOM INFORMATIONAL PANELS ==================== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-0.5">
            <!-- Quick Audit Step 1 -->
            <div class="p-3 rounded-xl bg-surface-container-lowest dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-start gap-2.5 shadow-2xs transition-colors">
                <span class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/60 text-blue-800 dark:text-blue-300 font-bold text-[11px] flex items-center justify-center shrink-0">1</span>
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-slate-200 text-xs">Pengajuan Aparatur (1x24 Jam)</h4>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">Pegawai menginput form lupa absen atau kendala presensi selambat-lambatnya 1 hari kerja setelah insiden.</p>
                </div>
            </div>
            <!-- Quick Audit Step 2 -->
            <div class="p-3 rounded-xl bg-surface-container-lowest dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-start gap-2.5 shadow-2xs transition-colors">
                <span class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-800 dark:text-indigo-300 font-bold text-[11px] flex items-center justify-center shrink-0">2</span>
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-slate-200 text-xs">Review &amp; Validasi Kasubbag</h4>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">Admin membandingkan log GPS presensi, riwayat cuti, dan keabsahan alasan sebelum mengesahkan.</p>
                </div>
            </div>
            <!-- Quick Audit Step 3 -->
            <div class="p-3 rounded-xl bg-surface-container-lowest dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-start gap-2.5 shadow-2xs transition-colors">
                <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-bold text-[11px] flex items-center justify-center shrink-0">3</span>
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-slate-200 text-xs">Sinkronisasi Tukin SI-ASN</h4>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">Hasil persetujuan otomatis memperbaiki potongan tunkin dan rekapitulasi penilaian SKP periode berjalan.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
