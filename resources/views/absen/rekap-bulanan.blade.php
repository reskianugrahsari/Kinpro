<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-[#0F2747] tracking-tight">
                    Rekapitulasi Bulanan Lupa Absen
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Analisis frekuensi dan statistik lupa absen pegawai Balai Wilayah VIII Makassar</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('absen.index') }}" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Daftar Pengajuan</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filter Card: Solid Navy & Gold (Khas Don't Forget) -->
        <div class="rounded-2xl bg-[#0F2747] p-6 text-white shadow-md border border-slate-700/60">
            <form method="GET" action="{{ route('absen.rekap-bulanan') }}" class="flex flex-col gap-5">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold tracking-tight text-white">Filter Periode Laporan</h3>
                        <p class="text-xs text-slate-300">Pilih bulan dan tahun untuk menyaring data rekapitulasi pegawai.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 w-full md:w-auto min-w-[280px]">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-amber-300 mb-1">Bulan</label>
                            <select name="bulan" onchange="this.form.submit()" class="w-full border border-white/20 rounded-xl bg-white/10 text-white text-xs px-3 py-2 outline-none focus:ring-2 focus:ring-amber-400">
                                <option value="" class="bg-[#0F2747] text-white">Semua Bulan</option>
                                @foreach([1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'] as $mNum => $mName)
                                    <option value="{{ $mNum }}" {{ (string)$bulan === (string)$mNum ? 'selected' : '' }} class="bg-[#0F2747] text-white">{{ $mName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-amber-300 mb-1">Tahun</label>
                            <select name="tahun" onchange="this.form.submit()" class="w-full border border-white/20 rounded-xl bg-white/10 text-white text-xs px-3 py-2 outline-none focus:ring-2 focus:ring-amber-400">
                                @for ($y = now()->year; $y >= now()->year - 4; $y--)
                                    <option value="{{ $y }}" {{ (string)$tahun === (string)$y ? 'selected' : '' }} class="bg-[#0F2747] text-white">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 border-t border-white/10 pt-4">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-amber-300 mb-1">Cari Nama Pegawai</label>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Ketik nama atau NIP pegawai..." class="w-full border border-white/20 rounded-xl bg-white/10 text-white placeholder-slate-400 text-xs px-3.5 py-2 outline-none focus:ring-2 focus:ring-amber-400" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-amber-300 mb-1">Kategori Absen</label>
                        <select name="jenis_absen" onchange="this.form.submit()" class="w-full border border-white/20 rounded-xl bg-white/10 text-white text-xs px-3 py-2 outline-none focus:ring-2 focus:ring-amber-400">
                            <option value="" class="bg-[#0F2747] text-white">Semua Kategori</option>
                            <option value="masuk" {{ $jenisAbsen === 'masuk' ? 'selected' : '' }} class="bg-[#0F2747] text-white">Lupa Absen Masuk</option>
                            <option value="pulang" {{ $jenisAbsen === 'pulang' ? 'selected' : '' }} class="bg-[#0F2747] text-white">Lupa Absen Pulang</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Content Grid (2 Kolom: Tabel Frekuensi + Widget Breakdown) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Left Side: Tabel Frekuensi per Pegawai -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-sm text-[#0F2747]">Frekuensi Lupa Absen per Pegawai</h3>
                    <span class="text-xs text-slate-400">{{ count($pegawaiData) }} Pegawai Terdaftar</span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-[#0F2747] text-white uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Nama Pegawai</th>
                                <th class="px-4 py-3">NIP</th>
                                <th class="px-4 py-3 text-center bg-amber-600">Lupa Datang</th>
                                <th class="px-4 py-3 text-center bg-blue-600">Lupa Pulang</th>
                                <th class="px-4 py-3 text-right bg-amber-400 text-slate-900 font-extrabold">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @php $no = 1; @endphp
                            @forelse ($pegawaiData as $row)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-3 text-slate-400">{{ $no++ }}</td>
                                    <td class="px-4 py-3 font-bold text-slate-900">
                                        <div class="flex items-center space-x-2.5">
                                            <div class="w-7 h-7 rounded-full bg-[#0F2747] text-amber-300 font-bold flex items-center justify-center text-[10px] shrink-0">
                                                {{ strtoupper(substr($row['nama'], 0, 2)) }}
                                            </div>
                                            <span>{{ $row['nama'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-[11px] text-slate-500">{{ $row['nip'] }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-amber-600">
                                        {{ $row['total_masuk'] }}x
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-blue-600">
                                        {{ $row['total_pulang'] }}x
                                    </td>
                                    <td class="px-4 py-3 text-right font-black text-slate-900">
                                        {{ $row['total_frekuensi'] }}x
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada catatan lupa absen pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Side: Breakdown Kategori Lupa Absen -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                <h3 class="font-bold text-sm text-[#0F2747]">Breakdown Kategori Lupa Absen</h3>
                
                @php
                    $totalAll = $jenisBreakdown['total'] ?? 0;
                    $pctMasuk = $totalAll > 0 ? round(($jenisBreakdown['masuk'] / $totalAll) * 100) : 0;
                    $pctPulang = $totalAll > 0 ? round(($jenisBreakdown['pulang'] / $totalAll) * 100) : 0;
                @endphp

                <div class="space-y-4 pt-2">
                    <!-- Datang / Masuk -->
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200/70">
                        <div class="flex justify-between items-center text-xs mb-2">
                            <span class="font-bold text-amber-800">Lupa Absen Datang (Masuk)</span>
                            <span class="font-extrabold text-amber-900">{{ $jenisBreakdown['masuk'] }} ({{ $pctMasuk }}%)</span>
                        </div>
                        <div class="w-full bg-amber-200/60 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-2.5 rounded-full transition-all" style="width: {{ $pctMasuk }}%"></div>
                        </div>
                    </div>

                    <!-- Pulang -->
                    <div class="p-4 rounded-xl bg-blue-50 border border-blue-200/70">
                        <div class="flex justify-between items-center text-xs mb-2">
                            <span class="font-bold text-blue-800">Lupa Absen Pulang</span>
                            <span class="font-extrabold text-blue-900">{{ $jenisBreakdown['pulang'] }} ({{ $pctPulang }}%)</span>
                        </div>
                        <div class="w-full bg-blue-200/60 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-2.5 rounded-full transition-all" style="width: {{ $pctPulang }}%"></div>
                        </div>
                    </div>

                    <!-- Total Ringkasan -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Pengajuan Terdata</div>
                        <div class="text-2xl font-black text-[#0F2747] mt-1">{{ $totalAll }} <span class="text-xs font-normal text-slate-500">berkas</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
