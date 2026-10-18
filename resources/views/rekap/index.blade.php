<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-extrabold text-2xl text-[#0F2747] tracking-tight">
                    Rekap & Statistik Gabungan
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Ringkasan performa dan metrik seluruh modul KinPro Wilayah VIII Makassar</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <a href="{{ route('rekap.export', 'csv') }}" class="bg-[#1769AA] hover:bg-[#0F2747] text-white px-4 py-2 rounded-xl font-bold text-xs shadow-sm transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Ekspor CSV / Excel</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Summary Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Pegawai -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pegawai</div>
                        <div class="text-3xl font-black text-[#0F2747] mt-1">{{ $totalPegawai }}</div>
                        <span class="inline-block mt-2 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">Terdaftar Aktif</span>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 text-[#1769AA] rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>

                <!-- Penilaian Kinerja -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Penilaian Kinerja</div>
                        <div class="text-3xl font-black text-[#0F2747] mt-1">{{ $totalPenilaian }}</div>
                        <span class="inline-block mt-2 text-[11px] font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md">Rata-rata: {{ $avgPenilaian }}</span>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>

                <!-- Perjalanan Dinas -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Perjalanan Dinas</div>
                        <div class="text-3xl font-black text-[#0F2747] mt-1">{{ $totalReports }}</div>
                        <span class="inline-block mt-2 text-[11px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md">{{ $reportsApproved }} Disetujui</span>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>

                <!-- Total Biaya Dinas -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Biaya Dinas</div>
                        <div class="text-2xl font-black text-[#0F2747] mt-1">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</div>
                        <span class="inline-block mt-2 text-[11px] font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md">Realisasi Anggaran</span>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Modul Breakdown & Detail Table -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Unit Kerja Breakdown -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="font-bold text-lg text-[#0F2747] mb-4 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-[#1769AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Distribusi Pegawai per Unit Kerja</span>
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="py-3 px-4 rounded-l-xl">Unit Kerja</th>
                                    <th class="py-3 px-4 text-center rounded-r-xl">Jumlah Pegawai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($pegawaiPerUnit as $unit)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="py-3 px-4 font-semibold text-slate-800">{{ $unit->nama }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="inline-block bg-blue-50 text-[#1769AA] font-bold px-3 py-1 rounded-full text-xs">
                                                {{ $unit->pegawai_count }} Pegawai
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-6 text-slate-400">Belum ada data unit kerja.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Status Layanan Pegawai (Izin, Absen, Pengaduan) -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-[#0F2747] mb-4 flex items-center space-x-2">
                            <svg class="w-5 h-5 text-[#1769AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Status Pengajuan & Layanan Kepegawaian</span>
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Izin -->
                            <div class="p-4 bg-slate-50 rounded-xl flex justify-between items-center">
                                <div>
                                    <div class="font-bold text-sm text-slate-800">Izin & Cuti</div>
                                    <div class="text-xs text-slate-500 mt-0.5">Total Pengajuan: {{ $totalIzin }}</div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-bold bg-amber-50 text-amber-700 px-2.5 py-1 rounded-md">{{ $izinPending }} Pending</span>
                                    <span class="text-xs font-bold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-md">{{ $izinApproved }} Disetujui</span>
                                </div>
                            </div>

                            <!-- Lupa Absen -->
                            <div class="p-4 bg-slate-50 rounded-xl flex justify-between items-center">
                                <div>
                                    <div class="font-bold text-sm text-slate-800">Lupa Absen (Don't Forget)</div>
                                    <div class="text-xs text-slate-500 mt-0.5">Total Pengajuan: {{ $totalAbsen }}</div>
                                </div>
                                <div>
                                    <span class="text-xs font-bold bg-amber-50 text-amber-700 px-2.5 py-1 rounded-md">{{ $absenPending }} Menunggu Verifikasi</span>
                                </div>
                            </div>

                            <!-- Pengaduan -->
                            <div class="p-4 bg-slate-50 rounded-xl flex justify-between items-center">
                                <div>
                                    <div class="font-bold text-sm text-slate-800">Pengaduan Masyarakat / WBS</div>
                                    <div class="text-xs text-slate-500 mt-0.5">Total Laporan: {{ $totalPengaduan }}</div>
                                </div>
                                <div>
                                    <span class="text-xs font-bold bg-rose-50 text-rose-700 px-2.5 py-1 rounded-md">{{ $pengaduanPending }} Perlu Ditangani</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
                        <span class="text-xs text-slate-400">Data sinkronisasi otomatis per {{ date('d F Y') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
