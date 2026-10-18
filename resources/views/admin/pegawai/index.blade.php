<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-extrabold text-2xl text-[#0F2747] tracking-tight">
                    Data Pegawai
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Kelola seluruh data pegawai Kemen PU & Balai Pengembangan Kompetensi Wilayah VIII Makassar</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.pegawai.create') }}" class="bg-[#4F46E5] hover:bg-[#4338CA] text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Pegawai</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-100/70 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl text-emerald-700 shadow-sm font-medium text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter Toolbar -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col lg:flex-row justify-between items-center gap-4">
                <div class="w-full lg:w-1/3 relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" placeholder="Cari nama, NIP, jabatan..." class="block w-full pl-12 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-[#4F46E5] focus:border-[#4F46E5]" />
                </div>

                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto justify-end">
                    <select class="bg-slate-50 border border-slate-300 text-slate-700 text-xs font-semibold rounded-xl px-4 py-2.5 focus:ring-[#4F46E5] focus:border-[#4F46E5]">
                        <option>Semua Status</option>
                        <option>PNS</option>
                        <option>PPPK</option>
                    </select>
                    <select class="bg-slate-50 border border-slate-300 text-slate-700 text-xs font-semibold rounded-xl px-4 py-2.5 focus:ring-[#4F46E5] focus:border-[#4F46E5]">
                        <option>Semua Jabatan</option>
                        <option>Operator</option>
                        <option>Analis</option>
                        <option>Penata</option>
                    </select>
                    <button class="px-5 py-2.5 bg-[#4F46E5] hover:bg-[#4338CA] text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Filter</span>
                    </button>
                    <button class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition flex items-center space-x-1.5 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset</span>
                    </button>
                    <div class="flex items-center space-x-1 border-l pl-3 border-slate-200">
                        <button class="p-2.5 bg-[#4F46E5] text-white rounded-xl shadow-xs"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg></button>
                        <button class="p-2.5 bg-slate-100 text-slate-500 hover:bg-slate-200 rounded-xl transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg></button>
                    </div>
                </div>
            </div>

            <!-- Section Title -->
            <div class="flex justify-between items-center px-1">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#4F46E5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <h3 class="font-bold text-lg text-[#0F2747]">Daftar Pegawai</h3>
                </div>
                <span class="text-xs font-bold bg-indigo-50 text-[#4F46E5] px-3.5 py-1 rounded-full border border-indigo-100">{{ $pegawais->total() ?? count($pegawais) }} data ditemukan</span>
            </div>

            <!-- Wider, Well-Spaced FIFA Ultimate Team Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 justify-items-center">
                @forelse($pegawais as $index => $p)
                    @php
                        if ($index == 0) {
                            // Dark Blue Rare
                            $cardWrap = 'bg-gradient-to-b from-[#0F172A] via-[#1E293B] to-[#0A101D] text-white border-2 border-blue-400/50 shadow-2xl shadow-blue-950/40';
                            $ratingCol = 'text-amber-300';
                            $subCol = 'text-blue-200';
                            $nameCol = 'text-white';
                            $roleCol = 'text-blue-300';
                            $lineCol = 'border-blue-400/30';
                            $numCol = 'text-white';
                            $lblCol = 'text-blue-200';
                            $score = '91';
                            $badgeStyle = 'bg-amber-900/60 text-amber-200 border border-amber-600/40';
                            $avatarGrad = 'from-blue-600 to-indigo-900';
                        } elseif ($index == 1) {
                            // Gold
                            $cardWrap = 'bg-gradient-to-b from-[#FDE047] via-[#CA8A04] to-[#713F12] text-[#3B2406] border-2 border-amber-300 shadow-2xl shadow-amber-950/30';
                            $ratingCol = 'text-[#2C1802]';
                            $subCol = 'text-[#452203]';
                            $nameCol = 'text-[#2C1802]';
                            $roleCol = 'text-[#3B2406]/90';
                            $lineCol = 'border-amber-900/30';
                            $numCol = 'text-[#2C1802]';
                            $lblCol = 'text-[#452203]';
                            $score = '80';
                            $badgeStyle = 'bg-[#452203] text-amber-200 border border-amber-900/50';
                            $avatarGrad = 'from-amber-600 to-yellow-800';
                        } else {
                            // Silver
                            $cardWrap = 'bg-gradient-to-b from-[#F8FAFC] via-[#CBD5E1] to-[#64748B] text-[#1E293B] border-2 border-slate-300 shadow-xl shadow-slate-900/15';
                            $ratingCol = 'text-[#0F172A]';
                            $subCol = 'text-slate-600';
                            $nameCol = 'text-[#0F172A]';
                            $roleCol = 'text-slate-700';
                            $lineCol = 'border-slate-400/40';
                            $numCol = 'text-[#0F172A]';
                            $lblCol = 'text-slate-600';
                            $score = '-';
                            $badgeStyle = 'bg-slate-700 text-slate-100 border border-slate-600';
                            $avatarGrad = 'from-slate-400 to-slate-600';
                        }
                    @endphp

                    <!-- Width increased to max-w-[320px] for wider, more prominent cards -->
                    <div class="w-full max-w-[320px] flex flex-col items-center group transition-all duration-300 hover:-translate-y-2">
                        <!-- FUT Shield Container with Increased Width & Height -->
                        <div class="{{ $cardWrap }} w-full p-5 rounded-[2.75rem] relative overflow-hidden flex flex-col justify-between"
                             style="clip-path: polygon(0% 0%, 100% 0%, 100% 92%, 50% 100%, 0% 92%); height: 440px;">
                            
                            <!-- Header / Upper Region -->
                            <div class="flex justify-between items-start pt-1 px-1">
                                <!-- Rating & Emblems Column -->
                                <div class="flex flex-col items-center pl-1">
                                    <span class="text-4xl font-black font-sans leading-none {{ $ratingCol }} tracking-tight">{{ $score }}</span>
                                    <span class="text-[9px] font-black uppercase tracking-widest mt-1 {{ $subCol }}">BAPEKOM8MKS</span>
                                    
                                    <!-- Emblems -->
                                    <div class="flex flex-col items-center space-y-1.5 mt-2.5">
                                        <!-- Indonesia Flag -->
                                        <div class="w-6 h-4 bg-red-600 border border-white/60 rounded-xs overflow-hidden shadow-xs">
                                            <div class="h-1/2 bg-red-600"></div>
                                            <div class="h-1/2 bg-white"></div>
                                        </div>
                                        <!-- PU Badge -->
                                        <div class="w-6 h-6 rounded-full bg-amber-400 flex items-center justify-center border border-amber-600/40 shadow-xs">
                                            <span class="text-[8px] font-black text-blue-900 leading-none">PU</span>
                                        </div>
                                        <!-- Region VIII Badge -->
                                        <div class="w-6 h-6 rounded-full bg-blue-900 flex items-center justify-center border border-amber-400/60 shadow-xs">
                                            <span class="text-[7px] font-black text-amber-300 leading-none">VIII</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Employee Avatar Portrait -->
                                <div class="w-36 h-36 rounded-2xl flex items-center justify-center relative overflow-hidden -mr-1">
                                    <div class="w-32 h-34 rounded-2xl bg-gradient-to-tr {{ $avatarGrad }} flex items-center justify-center font-black text-3xl text-white shadow-md border-2 border-white/30">
                                        <span class="drop-shadow">{{ strtoupper(substr($p->nama, 0, 2)) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Mid Section: Name, Role, Pill Badge -->
                            <div class="text-center px-2">
                                <div class="w-full border-t {{ $lineCol }} mb-2"></div>
                                <h4 class="font-black text-sm uppercase tracking-wide truncate {{ $nameCol }}" title="{{ $p->nama }}">
                                    {{ $p->nama }}
                                </h4>
                                <p class="text-[9px] font-bold uppercase tracking-wider {{ $roleCol }} line-clamp-1 mt-0.5">
                                    {{ $p->jabatan ?? 'OPERATOR LAYANAN OPERASIONAL' }}
                                </p>
                                
                                <div class="inline-block mt-1.5">
                                    <span class="{{ $badgeStyle }} text-[9px] font-black px-3 py-0.5 rounded-full uppercase tracking-wider">
                                        PPPK
                                    </span>
                                </div>
                                <div class="w-full border-t {{ $lineCol }} mt-2"></div>
                            </div>

                            <!-- Bottom Section: 6 Metrik FIFA -->
                            <div class="grid grid-cols-2 gap-x-8 gap-y-1 px-6 pb-7 text-center font-bold">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-black {{ $numCol }}">{{ $score != '-' ? '80' : '-' }}</span>
                                    <span class="text-[10px] uppercase font-bold {{ $lblCol }}">DIS</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-black {{ $numCol }}">{{ $score != '-' ? '97' : '-' }}</span>
                                    <span class="text-[10px] uppercase font-bold {{ $lblCol }}">KEP</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-black {{ $numCol }}">{{ $score != '-' ? '100' : '-' }}</span>
                                    <span class="text-[10px] uppercase font-bold {{ $lblCol }}">KIN</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-black {{ $numCol }}">{{ $score != '-' ? '90' : '-' }}</span>
                                    <span class="text-[10px] uppercase font-bold {{ $lblCol }}">LOY</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-black {{ $numCol }}">{{ $score != '-' ? '90' : '-' }}</span>
                                    <span class="text-[10px] uppercase font-bold {{ $lblCol }}">SIK</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-black {{ $numCol }}">{{ $score != '-' ? '90' : '-' }}</span>
                                    <span class="text-[10px] uppercase font-bold {{ $lblCol }}">IT</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="mt-3 flex items-center space-x-2.5">
                            <a href="{{ route('admin.pegawai.edit', $p) }}" class="px-4 py-1.5 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-lg border border-slate-200 shadow-xs transition">Edit</a>
                            <form action="{{ route('admin.pegawai.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pegawai ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-lg border border-rose-200 shadow-xs transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 text-center rounded-3xl border border-slate-200">
                        <p class="text-slate-500 font-medium">Belum ada data pegawai.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $pegawais->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
