<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-[#0F2747] dark:text-slate-100 tracking-tight">
                    Data Pegawai
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Kelola seluruh data pegawai, profil kompetensi, dan status kepegawaian</p>
            </div>
            <div>
                <a href="{{ route('admin.pegawai.create') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Pegawai</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto space-y-6">
            @if(session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-950/70 border-l-4 border-emerald-500 p-4 rounded-xl text-emerald-700 dark:text-emerald-300 shadow-sm font-medium text-xs">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter Toolbar Container (Exact as image) -->
            <form method="GET" action="{{ route('admin.pegawai.index') }}" class="bg-white dark:bg-slate-900 p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col lg:flex-row justify-between items-center gap-4 transition-colors">
                <input type="hidden" name="view" value="{{ request('view', 'grid') }}">

                <div class="w-full lg:flex-1 relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, jabatan..." class="block w-full pl-12 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 dark:text-slate-100 rounded-xl text-xs font-medium focus:ring-[#1769AA] focus:border-[#1769AA]" />
                </div>

                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto justify-end">
                    <select name="status" class="bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl pl-3.5 pr-9 py-2.5 focus:ring-[#1769AA] focus:border-[#1769AA] cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="PNS" {{ request('status') === 'PNS' ? 'selected' : '' }}>PNS</option>
                        <option value="PPPK" {{ request('status') === 'PPPK' ? 'selected' : '' }}>PPPK</option>
                    </select>

                    <select name="jabatan" class="bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl pl-3.5 pr-9 py-2.5 focus:ring-[#1769AA] focus:border-[#1769AA] max-w-xs cursor-pointer">
                        <option value="">Semua Jabatan</option>
                        @foreach($jabatanList as $jbt)
                            <option value="{{ $jbt }}" {{ request('jabatan') === $jbt ? 'selected' : '' }}>{{ $jbt }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-5 py-2.5 bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center space-x-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('admin.pegawai.index', ['view' => request('view', 'grid')]) }}" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl transition flex items-center space-x-1.5 shadow-xs cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset</span>
                    </a>
                    <div class="flex items-center space-x-1 border-l pl-3 border-slate-200 dark:border-slate-700">
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" 
                           class="p-2.5 rounded-xl shadow-xs transition {{ request('view', 'grid') === 'grid' ? 'bg-[#1769AA] text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}" 
                           title="Tampilan Grid Kartu">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" 
                           class="p-2.5 rounded-xl shadow-xs transition {{ request('view') === 'list' ? 'bg-[#1769AA] text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}" 
                           title="Tampilan List Tabel">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </a>
                    </div>
                </div>
            </form>

            <!-- Section Info -->
            <div class="flex justify-between items-center px-1">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#4F46E5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <h3 class="font-bold text-lg text-[#0F2747]">Daftar Pegawai</h3>
                </div>
                <span class="text-xs font-bold bg-indigo-50 text-[#4F46E5] px-3.5 py-1 rounded-full border border-indigo-100">{{ $pegawais->total() ?? count($pegawais) }} data ditemukan</span>
            </div>

            @if(request('view') === 'list')
                <!-- Table / List View Mode -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden transition-colors">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead class="bg-slate-50/80 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-300">
                                <tr>
                                    <th class="py-3.5 px-4">Pegawai</th>
                                    <th class="py-3.5 px-4">NIP</th>
                                    <th class="py-3.5 px-4">Status</th>
                                    <th class="py-3.5 px-4">Jabatan</th>
                                    <th class="py-3.5 px-4">Unit Kerja</th>
                                    <th class="py-3.5 px-4 text-center">Skor Kinerja</th>
                                    <th class="py-3.5 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
                                @forelse($pegawais as $p)
                                    @php
                                        $skor = $p->latestPenilaian ? round($p->latestPenilaian->rata_rata) : '-';
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition">
                                        <td class="py-3 px-4 flex items-center space-x-3">
                                            @if($p->foto_url)
                                                <img src="{{ $p->foto_url }}" alt="{{ $p->nama }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-slate-700" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="w-9 h-9 rounded-full bg-slate-200 dark:bg-slate-700 hidden items-center justify-center font-bold text-slate-700 dark:text-slate-200 text-xs">
                                                    {{ strtoupper(substr($p->nama, 0, 2)) }}
                                                </div>
                                            @else
                                                <div class="w-9 h-9 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-700 dark:text-slate-200 text-xs">
                                                    {{ strtoupper(substr($p->nama, 0, 2)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-slate-900 dark:text-slate-100">{{ $p->nama }}</div>
                                                <div class="text-[10px] text-slate-400 dark:text-slate-500">{{ $p->user?->email ?? '-' }}</div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 font-mono text-[11px] text-slate-700 dark:text-slate-300">{{ $p->nip ?? '-' }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $p->status_kepegawaian === 'PPPK' ? 'bg-amber-100 dark:bg-amber-950/70 text-amber-800 dark:text-amber-300' : 'bg-blue-100 dark:bg-blue-950/70 text-[#1769AA] dark:text-blue-300' }}">
                                                {{ $p->status_kepegawaian }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 max-w-xs truncate text-slate-700 dark:text-slate-300" title="{{ $p->jabatan }}">{{ $p->jabatan ?? '-' }}</td>
                                        <td class="py-3 px-4 text-slate-500 dark:text-slate-400">{{ $p->unitKerja?->nama ?? 'Bapekom Wil. VIII' }}</td>
                                        <td class="py-3 px-4 text-center">
                                            @if($skor !== '-')
                                                <span class="font-bold px-2 py-0.5 rounded-lg text-xs {{ $skor >= 85 ? 'bg-emerald-100 dark:bg-emerald-950/70 text-emerald-800 dark:text-emerald-300' : ($skor >= 75 ? 'bg-blue-100 dark:bg-blue-950/70 text-[#1769AA] dark:text-blue-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300') }}">
                                                    {{ $skor }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 dark:text-slate-500">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right space-x-2">
                                            <a href="{{ route('admin.pegawai.edit', $p) }}" class="px-3 py-1 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 transition inline-block">Edit</a>
                                            <form action="{{ route('admin.pegawai.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pegawai ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 text-xs font-bold rounded-lg border border-rose-200 dark:border-rose-800 transition">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500 font-medium">Tidak ada data pegawai yang sesuai filter.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <!-- Grid Cards View Mode (FUT FIFA Style) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 justify-items-center">
                    @forelse($pegawais as $p)
                        @php
                            $penilaian = $p->latestPenilaian;
                            $score = $penilaian ? (string) round($penilaian->rata_rata) : '-';
                            $numScore = $penilaian ? round($penilaian->rata_rata) : 0;

                            // Silver — single color for all cards
                            $cardWrap = 'bg-gradient-to-b from-[#F8FAFC] via-[#CBD5E1] to-[#64748B] text-[#1E293B] border-2 border-slate-300 shadow-xl shadow-slate-900/15';
                            $ratingCol = 'text-[#0F172A]';
                            $subCol = 'text-slate-600';
                            $nameCol = 'text-[#0F172A]';
                            $roleCol = 'text-slate-700';
                            $lineCol = 'border-slate-400/40';
                            $numCol = 'text-[#0F172A]';
                            $lblCol = 'text-slate-600';
                            $badgeStyle = 'bg-slate-700 text-slate-100 border border-slate-600';
                            $avatarGrad = 'from-slate-400 to-slate-600';

                            $disVal = $penilaian ? round($penilaian->nilai_kedisiplinan) : '-';
                            $kepVal = $penilaian ? round($penilaian->kepemimpinan) : '-';
                            $kinVal = $penilaian ? round($penilaian->kinerja) : '-';
                            $loyVal = $penilaian ? round($penilaian->loyalitas) : '-';
                            $sikVal = $penilaian ? round($penilaian->sikap) : '-';
                            $itVal = $penilaian ? round($penilaian->it) : '-';
                        @endphp

                        <!-- Flip Card Container -->
                        <div class="w-full max-w-[290px] flex flex-col items-center group" style="perspective: 1000px;">
                            <div class="relative w-full transition-transform duration-700 group-hover:[transform:rotateY(180deg)]" style="transform-style: preserve-3d; height: 395px;">

                                <!-- ===== FRONT FACE ===== -->
                                <div class="absolute inset-0 [backface-visibility:hidden]">
                                    <div class="{{ $cardWrap }} w-full h-full p-4 rounded-[2.5rem] relative overflow-hidden flex flex-col justify-between"
                                         style="clip-path: polygon(0% 0%, 100% 0%, 100% 92%, 50% 100%, 0% 92%);">

                                        <!-- Header / Upper Region -->
                                        <div class="flex justify-between items-start pt-0.5 px-1">
                                            <div class="flex flex-col items-center pl-1">
                                                <span class="text-3xl font-black font-sans leading-none {{ $ratingCol }} tracking-tight">{{ $score }}</span>
                                                <span class="text-[8px] font-black uppercase tracking-widest mt-1 {{ $subCol }}">BAPEKOM8MKS</span>
                                                <div class="flex flex-col items-center space-y-1 mt-2">
                                                    <div class="w-5 h-3.5 bg-red-600 border border-white/60 rounded-xs overflow-hidden shadow-xs">
                                                        <div class="h-1/2 bg-red-600"></div>
                                                        <div class="h-1/2 bg-white"></div>
                                                    </div>
                                                    <div class="w-5 h-5 rounded-full bg-amber-400 flex items-center justify-center border border-amber-600/40 shadow-xs">
                                                        <span class="text-[7px] font-black text-blue-900 leading-none">PU</span>
                                                    </div>
                                                    <div class="w-5 h-5 rounded-full bg-blue-900 flex items-center justify-center border border-amber-400/60 shadow-xs">
                                                        <span class="text-[6px] font-black text-amber-300 leading-none">VIII</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="w-32 h-32 rounded-2xl flex items-center justify-center relative overflow-hidden -mr-1">
                                                @if($p->foto_url)
                                                    <img src="{{ $p->foto_url }}" alt="{{ $p->nama }}" class="w-28 h-32 object-cover object-top rounded-2xl shadow-md border-2 border-white/40" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="w-28 h-32 rounded-2xl bg-gradient-to-tr {{ $avatarGrad }} hidden items-center justify-center font-black text-2xl text-white shadow-md border-2 border-white/30">
                                                        <span class="drop-shadow">{{ strtoupper(substr($p->nama, 0, 2)) }}</span>
                                                    </div>
                                                @else
                                                    <div class="w-28 h-32 rounded-2xl bg-gradient-to-tr {{ $avatarGrad }} flex items-center justify-center font-black text-2xl text-white shadow-md border-2 border-white/30">
                                                        <span class="drop-shadow">{{ strtoupper(substr($p->nama, 0, 2)) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Mid Section -->
                                        <div class="text-center px-2">
                                            <div class="w-full border-t {{ $lineCol }} mb-1.5"></div>
                                            <h4 class="font-black text-xs uppercase tracking-wide truncate {{ $nameCol }}" title="{{ $p->nama }}">{{ $p->nama }}</h4>
                                            <p class="text-[8px] font-bold uppercase tracking-wider {{ $roleCol }} line-clamp-1 mt-0.5">{{ $p->jabatan ?? 'OPERATOR LAYANAN OPERASIONAL' }}</p>
                                            <div class="inline-block mt-1">
                                                <span class="{{ $badgeStyle }} text-[8px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider">{{ $p->status_kepegawaian }}</span>
                                            </div>
                                            <div class="w-full border-t {{ $lineCol }} mt-1.5"></div>
                                        </div>

                                        <!-- Bottom: 6 Metrik -->
                                        <div class="grid grid-cols-2 gap-x-6 gap-y-0.5 px-5 pb-5 text-center font-bold">
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs font-black {{ $numCol }}">{{ $disVal }}</span>
                                                <span class="text-[9px] uppercase font-bold {{ $lblCol }}">DIS</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs font-black {{ $numCol }}">{{ $kepVal }}</span>
                                                <span class="text-[9px] uppercase font-bold {{ $lblCol }}">KEP</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs font-black {{ $numCol }}">{{ $kinVal }}</span>
                                                <span class="text-[9px] uppercase font-bold {{ $lblCol }}">KIN</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs font-black {{ $numCol }}">{{ $loyVal }}</span>
                                                <span class="text-[9px] uppercase font-bold {{ $lblCol }}">LOY</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs font-black {{ $numCol }}">{{ $sikVal }}</span>
                                                <span class="text-[9px] uppercase font-bold {{ $lblCol }}">SIK</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs font-black {{ $numCol }}">{{ $itVal }}</span>
                                                <span class="text-[9px] uppercase font-bold {{ $lblCol }}">IT</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===== BACK FACE ===== -->
                                <div class="absolute inset-0 [backface-visibility:hidden] [transform:rotateY(180deg)]">
                                    <div class="{{ $cardWrap }} w-full h-full p-5 rounded-[2.5rem] relative overflow-hidden flex flex-col justify-between"
                                         style="clip-path: polygon(0% 0%, 100% 0%, 100% 92%, 50% 100%, 0% 92%);">

                                        <!-- Back Header -->
                                        <div class="text-center pt-1">
                                            <div class="w-16 h-16 rounded-full mx-auto mb-2 overflow-hidden border-2 border-white/40 shadow-md">
                                                @if($p->foto_url)
                                                    <img src="{{ $p->foto_url }}" alt="{{ $p->nama }}" class="w-full h-full object-cover object-top" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="w-full h-full bg-gradient-to-tr {{ $avatarGrad }} hidden items-center justify-center font-black text-sm text-white">{{ strtoupper(substr($p->nama, 0, 2)) }}</div>
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-tr {{ $avatarGrad }} flex items-center justify-center font-black text-sm text-white">{{ strtoupper(substr($p->nama, 0, 2)) }}</div>
                                                @endif
                                            </div>
                                            <h4 class="font-black text-xs uppercase tracking-wide truncate {{ $nameCol }}">{{ $p->nama }}</h4>
                                            <div class="w-full border-t {{ $lineCol }} mt-2"></div>
                                        </div>

                                        <!-- Detail Info -->
                                        <div class="space-y-2.5 px-2 flex-1 flex flex-col justify-center">
                                            <div class="flex items-start space-x-2.5">
                                                <span class="material-symbols-outlined text-[16px] {{ $subCol }} mt-px">badge</span>
                                                <div>
                                                    <p class="text-[8px] font-bold uppercase tracking-wider {{ $lblCol }}">NIP</p>
                                                    <p class="text-[11px] font-bold {{ $nameCol }} font-mono">{{ $p->nip ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start space-x-2.5">
                                                <span class="material-symbols-outlined text-[16px] {{ $subCol }} mt-px">mail</span>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-[8px] font-bold uppercase tracking-wider {{ $lblCol }}">Email</p>
                                                    <p class="text-[11px] font-bold {{ $nameCol }} truncate">{{ $p->user?->email ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start space-x-2.5">
                                                <span class="material-symbols-outlined text-[16px] {{ $subCol }} mt-px">call</span>
                                                <div>
                                                    <p class="text-[8px] font-bold uppercase tracking-wider {{ $lblCol }}">No. HP</p>
                                                    <p class="text-[11px] font-bold {{ $nameCol }}">{{ $p->no_hp ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start space-x-2.5">
                                                <span class="material-symbols-outlined text-[16px] {{ $subCol }} mt-px">apartment</span>
                                                <div>
                                                    <p class="text-[8px] font-bold uppercase tracking-wider {{ $lblCol }}">Unit Kerja</p>
                                                    <p class="text-[11px] font-bold {{ $nameCol }}">{{ $p->unitKerja?->nama ?? 'Bapekom Wil. VIII' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start space-x-2.5">
                                                <span class="material-symbols-outlined text-[16px] {{ $subCol }} mt-px">military_tech</span>
                                                <div>
                                                    <p class="text-[8px] font-bold uppercase tracking-wider {{ $lblCol }}">Pangkat / Gol</p>
                                                    <p class="text-[11px] font-bold {{ $nameCol }}">{{ $p->pangkat_gol ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Back Action Buttons -->
                                        <div class="flex items-center justify-center space-x-2 pb-5 pt-1">
                                            <a href="{{ route('admin.pegawai.edit', $p) }}" class="px-4 py-1.5 bg-white/90 hover:bg-white text-slate-700 text-xs font-bold rounded-lg border border-white/40 shadow-xs transition">Edit</a>
                                            <form action="{{ route('admin.pegawai.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pegawai ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-4 py-1.5 bg-rose-500/90 hover:bg-rose-600 text-white text-xs font-bold rounded-lg border border-rose-400/40 shadow-xs transition cursor-pointer">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white p-12 text-center rounded-3xl border border-slate-200">
                            <p class="text-slate-500 font-medium">Tidak ada data pegawai yang sesuai filter.</p>
                            <a href="{{ route('admin.pegawai.index') }}" class="mt-3 inline-block text-xs font-bold text-[#4F46E5] hover:underline">Reset Semua Filter</a>
                        </div>
                    @endforelse
                </div>
            @endif

            <!-- Pagination -->
            <div class="mt-6">
                {{ $pegawais->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
