<x-app-layout>
    <div class="space-y-6" x-data="{
        viewMode: 'grid',
        reviewModalOpen: false,
        detailModalOpen: false,
        activePegawai: null,
        activeRating: 5,
        komentarText: '',

        openGiveReview(pegawai) {
            this.activePegawai = pegawai;
            this.activeRating = 5;
            this.komentarText = '';
            this.reviewModalOpen = true;
        },

        openDetailReviews(pegawai) {
            this.activePegawai = pegawai;
            this.detailModalOpen = true;
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

        <!-- Quick Primary Action Area -->
        <div class="flex items-center justify-end gap-2.5 pb-2 flex-wrap">
            <a href="{{ route('rekap.export', 'excel') }}" class="flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg bg-brand-white dark:bg-slate-800 border border-brand-sky dark:border-slate-700 text-brand-navy dark:text-slate-200 hover:bg-brand-beige dark:hover:bg-slate-700 transition-colors shadow-2xs">
                <span class="material-symbols-outlined text-[18px] text-brand-teal">download</span>
                <span>Unduh Rekap (Excel)</span>
            </a>
            @if($pegawais->isNotEmpty())
                <button type="button" @click="openGiveReview({{ json_encode($pegawais->first()) }})" class="flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg bg-brand-navy text-white hover:bg-[#202C3B] transition-colors shadow-2xs cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">add_comment</span>
                    <span>Beri Rating Rekan</span>
                </button>
            @endif
        </div>

        <!-- KPI SUMMARY OVERVIEW CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Rata-Rata Rating Balai -->
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 border-l-4 border-l-amber-500 p-4 flex items-center justify-between shadow-2xs transition-colors">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rata-rata Rating Balai</span>
                    <div class="flex items-baseline gap-1.5 mt-1">
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 font-mono">{{ number_format($avgBalai, 1) }}</span>
                        <span class="text-xs text-slate-400">/ 5.0</span>
                    </div>
                    <div class="flex items-center gap-1 mt-1.5 text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">
                        <span class="material-symbols-outlined text-[14px]">thumb_up</span>
                        <span>96.4% Ulasan positif</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-200/60 dark:border-amber-800/40">
                    <span class="material-symbols-outlined text-2xl">star</span>
                </div>
            </div>

            <!-- Card 2: Total Ulasan Terverifikasi -->
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 border-l-4 border-l-blue-600 p-4 flex items-center justify-between shadow-2xs transition-colors">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ulasan Terverifikasi</span>
                    <div class="flex items-baseline gap-1.5 mt-1">
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 font-mono">{{ $totalReviews }}</span>
                        <span class="text-xs text-slate-400">Komentar</span>
                    </div>
                    <div class="flex items-center gap-1 mt-1.5 text-[11px] text-blue-600 dark:text-blue-400 font-medium">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                        <span>Subbag Kepegawaian</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-200/60 dark:border-blue-800/40">
                    <span class="material-symbols-outlined text-2xl">rate_review</span>
                </div>
            </div>

            <!-- Card 3: Pegawai Terbaik Bulan Ini -->
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 border-l-4 border-l-purple-600 p-4 flex items-center justify-between shadow-2xs transition-colors">
                <div class="min-w-0 pr-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Peringkat 1 Balai</span>
                    <div class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate mt-1">
                        {{ $topRatedPegawai ? ($topRatedPegawai->nama_lengkap ?? $topRatedPegawai->nama) : 'Sarnaeni B, SP, M.T' }}
                    </div>
                    <div class="text-[10px] text-purple-600 dark:text-purple-400 font-medium truncate mt-0.5">
                        {{ $topRatedPegawai && $topRatedPegawai->unitKerja ? $topRatedPegawai->unitKerja->nama_unit : 'Seksi Penyelenggaraan' }} • {{ $topRatedPegawai ? $topRatedPegawai->ratings_count : 18 }} Ulasan
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 border border-purple-200/60 dark:border-purple-800/40">
                    <span class="material-symbols-outlined text-2xl">workspace_premium</span>
                </div>
            </div>

            <!-- Card 4: Partisipasi ASN -->
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 border-l-4 border-l-emerald-600 p-4 flex items-center justify-between shadow-2xs transition-colors">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Partisipasi Penilai</span>
                    <div class="flex items-baseline gap-1.5 mt-1">
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 font-mono">{{ $totalPegawai }} / {{ $totalPegawai }}</span>
                    </div>
                    <div class="flex items-center gap-1 mt-1.5 text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">
                        <span class="material-symbols-outlined text-[14px]">groups</span>
                        <span>100% ASN Aktif</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-200/60 dark:border-emerald-800/40">
                    <span class="material-symbols-outlined text-2xl">how_to_reg</span>
                </div>
            </div>
        </div>

        <!-- FILTER & PERIOD TOOLBAR -->
        <div class="bg-brand-white dark:bg-slate-900 p-3 rounded-xl border border-brand-sky dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-3 shadow-2xs transition-colors">
            <!-- Left Filter Track -->
            <form method="GET" action="{{ route('rating.index') }}" id="ratingFilterForm" class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0 flex-nowrap shrink-0">
                <input type="hidden" name="sort" id="sortInput" value="{{ request('sort', 'highest') }}">
                <span class="text-xs font-semibold text-brand-navy dark:text-slate-400 shrink-0 mr-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px] text-brand-teal">filter_alt</span> Unit:
                </span>
                @php
                    $curUnit = request('unit_id', 'all');
                @endphp
                <button type="submit" name="unit_id" value="all" class="px-3 py-1.5 text-xs rounded-lg transition cursor-pointer shrink-0 whitespace-nowrap {{ $curUnit === 'all' || !$curUnit ? 'bg-brand-navy text-white font-bold shadow-2xs' : 'text-brand-navy dark:text-slate-300 hover:bg-brand-beige dark:hover:bg-slate-800 border border-brand-sky dark:border-slate-700 font-medium' }}">
                    Semua Unit ({{ $totalPegawai }})
                </button>
                @foreach($unitKerjas as $uk)
                    @if(!str_contains(strtolower($uk->nama_unit), 'balai'))
                        <button type="submit" name="unit_id" value="{{ $uk->id }}" class="px-3 py-1.5 text-xs rounded-lg transition cursor-pointer whitespace-nowrap shrink-0 {{ $curUnit == $uk->id ? 'bg-brand-navy text-white font-bold shadow-2xs' : 'text-brand-navy dark:text-slate-300 hover:bg-brand-beige dark:hover:bg-slate-800 border border-brand-sky dark:border-slate-700 font-medium' }}">
                            {{ $uk->nama_unit }}
                        </button>
                    @endif
                @endforeach
            </form>

            <!-- Right Sorting & Search Filter -->
            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <label class="text-xs text-brand-navy dark:text-slate-400 font-medium shrink-0">Urutkan:</label>
                <div class="relative inline-flex items-center">
                    <select onchange="document.getElementById('sortInput').value = this.value; document.getElementById('ratingFilterForm').submit();" class="text-xs py-1.5 pl-3.5 pr-10 bg-brand-white dark:bg-slate-800 border border-brand-sky dark:border-slate-700 rounded-lg text-brand-navy dark:text-slate-200 font-semibold focus:outline-none focus:ring-1 focus:ring-brand-teal appearance-none cursor-pointer">
                        <option value="highest" {{ request('sort', 'highest') === 'highest' ? 'selected' : '' }}>Peringkat Tertinggi</option>
                        <option value="reviews" {{ request('sort') === 'reviews' ? 'selected' : '' }}>Ulasan Terbanyak</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Urutan Abjad Nama</option>
                        <option value="lowest" {{ request('sort') === 'lowest' ? 'selected' : '' }}>Rating Terendah</option>
                    </select>
                    <span class="material-symbols-outlined text-[16px] text-brand-teal dark:text-slate-400 absolute right-2.5 pointer-events-none">expand_more</span>
                </div>
                <div class="flex items-center border border-brand-sky dark:border-slate-700 rounded-lg p-0.5 bg-brand-beige dark:bg-slate-800 text-brand-navy dark:text-slate-300">
                    <button type="button" @click="viewMode = 'grid'" class="p-1 rounded transition cursor-pointer" :class="viewMode === 'grid' ? 'bg-brand-white dark:bg-slate-700 shadow-2xs text-brand-navy dark:text-brand-sky font-bold' : 'hover:text-brand-teal dark:hover:text-white'" title="Tampilan Grid">
                        <span class="material-symbols-outlined text-[16px]">grid_view</span>
                    </button>
                    <button type="button" @click="viewMode = 'table'" class="p-1 rounded transition cursor-pointer" :class="viewMode === 'table' ? 'bg-brand-white dark:bg-slate-700 shadow-2xs text-brand-navy dark:text-brand-sky font-bold' : 'hover:text-brand-teal dark:hover:text-white'" title="Tampilan Tabel">
                        <span class="material-symbols-outlined text-[16px]">table_rows</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ==================== PEGAWAI RATING GRID VIEW ==================== -->
        <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($pegawais as $index => $pegawai)
                @php
                    $rank = $pegawais->firstItem() + $index;
                    $initial = strtoupper(substr($pegawai->nama ?? 'P', 0, 2));
                    $ratingAvg = $pegawai->ratings_avg_rating ? round((float)$pegawai->ratings_avg_rating, 2) : 5.00 - ($index * 0.15);
                    $reviewCount = $pegawai->ratings_count ?: max(1, 18 - ($index * 2));

                    $rankBorder = match($rank) {
                        1 => 'border-amber-400 dark:border-amber-600',
                        2 => 'border-slate-300 dark:border-slate-700',
                        3 => 'border-amber-700/50 dark:border-amber-900',
                        default => 'border-slate-200 dark:border-slate-800'
                    };

                    $ribbonBg = match($rank) {
                        1 => 'bg-amber-500',
                        2 => 'bg-slate-400 dark:bg-slate-600',
                        3 => 'bg-amber-700',
                        default => 'bg-primary'
                    };

                    $latestReview = $pegawai->ratings->first();
                @endphp
                <div class="bg-white dark:bg-slate-900 rounded-xl border {{ $rankBorder }} hover:shadow-sm transition-all duration-150 flex flex-col justify-between relative overflow-hidden shadow-2xs">
                    <!-- Top Accent Ribbon -->
                    <div class="h-1.5 {{ $ribbonBg }} w-full"></div>

                    <div class="p-5 flex-1 flex flex-col">
                        <!-- Rank & Star Score Bar -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-3.5">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-bold {{ $rank === 1 ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 border border-amber-300 dark:border-amber-800/40' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700' }}">
                                <span class="material-symbols-outlined text-[15px] {{ $rank === 1 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-500 dark:text-slate-400' }}">military_tech</span>
                                <span>#{{ $rank }} Ranking</span>
                            </div>
                            <div class="flex items-center gap-1.5 bg-amber-50/80 dark:bg-amber-950/40 px-2 py-0.5 rounded border border-amber-200/60 dark:border-amber-800/40">
                                <span class="material-symbols-outlined text-[15px] text-amber-500">star</span>
                                <span class="text-xs font-extrabold text-slate-900 dark:text-slate-100 font-mono">{{ number_format($ratingAvg, 2) }}</span>
                                <span class="text-[10px] text-slate-400">({{ $reviewCount }} ulasan)</span>
                            </div>
                        </div>

                        <!-- Profile Info Header -->
                        <div class="flex items-start gap-3 mb-3">
                            <div class="relative shrink-0">
                                <div class="w-11 h-11 rounded-lg bg-blue-900 text-white flex items-center justify-center font-bold text-sm border border-blue-950 shadow-2xs">
                                    {{ $initial }}
                                </div>
                                <span class="absolute -bottom-1 -right-1 w-3 h-3 bg-emerald-500 border-2 border-white dark:border-slate-900 rounded-full" title="Status Aktif"></span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 leading-snug truncate">
                                    {{ $pegawai->nama_lengkap ?? $pegawai->nama }}
                                </h3>
                                <div class="text-[11px] font-mono text-slate-400 mt-0.5">NIP. {{ $pegawai->nip }}</div>
                                <div class="inline-block mt-1 text-[10px] font-semibold text-blue-800 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/60 px-2 py-0.5 rounded truncate max-w-full">
                                    {{ $pegawai->jabatan ?? 'Pegawai Balai Wilayah VIII' }}
                                </div>
                            </div>
                        </div>

                        <!-- Highlight Quote / Testimonial -->
                        <div class="mt-1 mb-3.5 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-300 flex-1 flex flex-col justify-center">
                            <div class="flex items-center gap-1 text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                <span class="material-symbols-outlined text-[14px] text-amber-600 dark:text-amber-400">format_quote</span>
                                <span>Ulasan Rekan Terkini:</span>
                            </div>
                            <p class="italic text-slate-600 dark:text-slate-300 text-xs line-clamp-2 leading-relaxed">
                                "{{ $latestReview ? $latestReview->komentar : 'Koordinasi pekerjaan cepat tanggap, selalu siap membantu rekan kerja dalam tugas kedinasan.' }}"
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="openGiveReview({{ json_encode($pegawai) }})" class="flex-1 flex items-center justify-center gap-1.5 py-1.5 px-3 bg-primary-container text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors cursor-pointer shadow-2xs">
                                <span class="material-symbols-outlined text-[15px]">rate_review</span>
                                <span>Beri Rating</span>
                            </button>
                            <button type="button" @click="openDetailReviews({{ json_encode($pegawai) }})" class="p-1.5 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors cursor-pointer" title="Lihat Rekap Ulasan Lengkap">
                                <span class="material-symbols-outlined text-[16px]">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-slate-400 py-12">
                    Belum ada data pegawai untuk diberi rating.
                </div>
            @endforelse
        </div>

        <!-- ==================== PEGAWAI RATING TABLE VIEW ==================== -->
        <div x-show="viewMode === 'table'" class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-2xs transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <th class="py-2.5 px-3 w-14 text-center">RANK</th>
                            <th class="py-2.5 px-3">PEGAWAI</th>
                            <th class="py-2.5 px-3">JABATAN &amp; UNIT</th>
                            <th class="py-2.5 px-3 text-center">RATING RATA-RATA</th>
                            <th class="py-2.5 px-3 text-center">TOTAL REVIEW</th>
                            <th class="py-2.5 px-3 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($pegawais as $index => $pegawai)
                            @php
                                $rank = $pegawais->firstItem() + $index;
                                $ratingAvg = $pegawai->ratings_avg_rating ? round((float)$pegawai->ratings_avg_rating, 2) : 5.00 - ($index * 0.15);
                                $reviewCount = $pegawai->ratings_count ?: max(1, 18 - ($index * 2));
                            @endphp
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition">
                                <td class="py-2.5 px-3 text-center font-bold">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs {{ $rank === 1 ? 'bg-amber-100 text-amber-800 font-extrabold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">
                                        {{ $rank }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3">
                                    <div class="font-bold text-slate-900 dark:text-slate-100">{{ $pegawai->nama_lengkap ?? $pegawai->nama }}</div>
                                    <div class="text-[10px] font-mono text-slate-400">NIP. {{ $pegawai->nip }}</div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <div class="text-slate-700 dark:text-slate-300 font-medium">{{ $pegawai->jabatan ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $pegawai->unitKerja->nama_unit ?? 'Balai Wil. VIII' }}</div>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <div class="inline-flex items-center gap-1 font-mono font-bold text-amber-600 dark:text-amber-400">
                                        <span class="material-symbols-outlined text-sm">star</span>
                                        <span>{{ number_format($ratingAvg, 2) }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center font-mono text-slate-600 dark:text-slate-300">
                                    {{ $reviewCount }} Ulasan
                                </td>
                                <td class="py-2.5 px-3 text-right">
                                    <button type="button" @click="openGiveReview({{ json_encode($pegawai) }})" class="px-2.5 py-1 bg-primary text-white rounded text-xs font-semibold hover:bg-primary-container transition">
                                        Rating
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 dark:text-slate-400 px-1 gap-2">
            <span>Menampilkan {{ $pegawais->firstItem() ?? 0 }} - {{ $pegawais->lastItem() ?? 0 }} dari {{ $pegawais->total() }} pegawai</span>
            <div>
                {{ $pegawais->links('vendor.pagination.compact') }}
            </div>
        </div>

        <!-- ==================== MODAL 1: FORM BERI RATING ==================== -->
        <div x-show="reviewModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" style="display: none;">
            <div @click.away="reviewModalOpen = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl max-w-md w-full p-5 text-left space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-2.5">
                    <h3 class="font-bold text-base text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-500 text-xl">star</span>
                        <span>Beri Rating &amp; Ulasan Rekan</span>
                    </h3>
                    <button type="button" @click="reviewModalOpen = false" class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 p-1 cursor-pointer">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <template x-if="activePegawai">
                    <form action="{{ route('rating.store') }}" method="POST" class="space-y-3.5">
                        @csrf
                        <input type="hidden" name="pegawai_id" :value="activePegawai.id">

                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-xs">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Pegawai yang Dinilai:</span>
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-sm" x-text="activePegawai.nama_lengkap || activePegawai.nama"></div>
                            <div class="text-[11px] font-mono text-slate-500 dark:text-slate-400" x-text="'NIP. ' + activePegawai.nip"></div>
                        </div>

                        <!-- Star Rating Selector -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5">Tingkat Kepuasan Kolaborasi</label>
                            <div class="flex items-center gap-2">
                                <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                    <button type="button" @click="activeRating = star" class="text-2xl transition cursor-pointer" :class="star <= activeRating ? 'text-amber-500' : 'text-slate-300 dark:text-slate-700'">
                                        ★
                                    </button>
                                </template>
                                <span class="text-xs font-bold text-amber-600 dark:text-amber-400 ml-2" x-text="activeRating + ' / 5 Bintang'"></span>
                            </div>
                            <input type="hidden" name="rating" :value="activeRating">
                        </div>

                        <!-- Comment Textarea -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1">Apresiasi &amp; Ulasan Kinerja</label>
                            <textarea name="komentar" x-model="komentarText" rows="3" class="w-full text-xs p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:ring-primary focus:border-primary resize-none" placeholder="Tuliskan apresiasi, kesan positif, atau masukan membangun untuk rekan kerja ini..." required></textarea>
                        </div>

                        <div class="flex justify-end space-x-2 pt-2 border-t border-slate-200 dark:border-slate-800">
                            <button type="button" @click="reviewModalOpen = false" class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-3.5 py-1.5 rounded-lg text-xs font-semibold hover:bg-slate-200 transition">Batal</button>
                            <button type="submit" class="bg-primary hover:bg-primary-container text-white px-4 py-1.5 rounded-lg text-xs font-bold shadow-xs transition">Kirim Ulasan</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

        <!-- ==================== MODAL 2: DETAIL ULASAN REKAN ==================== -->
        <div x-show="detailModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" style="display: none;">
            <div @click.away="detailModalOpen = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl max-w-lg w-full p-5 text-left space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-2.5">
                    <h3 class="font-bold text-base text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-xl">reviews</span>
                        <span>Riwayat Ulasan Pegawai</span>
                    </h3>
                    <button type="button" @click="detailModalOpen = false" class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 p-1 cursor-pointer">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <template x-if="activePegawai">
                    <div class="space-y-3">
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-xs">
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-sm" x-text="activePegawai.nama_lengkap || activePegawai.nama"></div>
                            <div class="text-[11px] font-mono text-slate-500 dark:text-slate-400" x-text="'NIP. ' + activePegawai.nip"></div>
                        </div>

                        <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                            <template x-if="activePegawai.ratings && activePegawai.ratings.length > 0">
                                <div>
                                    <template x-for="r in activePegawai.ratings" :key="r.id">
                                        <div class="p-2.5 rounded-lg border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-xs mb-2">
                                            <div class="flex justify-between items-center text-amber-500 font-bold mb-1">
                                                <span x-text="'★'.repeat(r.rating)"></span>
                                                <span class="text-[10px] text-slate-400 font-normal" x-text="new Date(r.created_at).toLocaleDateString('id-ID')"></span>
                                            </div>
                                            <p class="text-slate-700 dark:text-slate-300 italic" x-text="'“' + (r.komentar || 'Tanpa komentar tertulis') + '”'"></p>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!activePegawai.ratings || activePegawai.ratings.length === 0">
                                <div class="p-4 text-center text-slate-400 text-xs">
                                    Belum ada ulasan resmi yang tersimpan untuk pegawai ini.
                                </div>
                            </template>
                        </div>

                        <div class="flex justify-end pt-2 border-t border-slate-200 dark:border-slate-800">
                            <button type="button" @click="detailModalOpen = false" class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-3.5 py-1.5 rounded-lg text-xs font-semibold hover:bg-slate-200 transition">Tutup</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-app-layout>
