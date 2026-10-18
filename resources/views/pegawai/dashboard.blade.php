<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <div class="flex items-center space-x-2">
                    <h2 class="font-extrabold text-2xl text-[#0F2747] tracking-tight">
                        Dashboard
                    </h2>
                    <span class="text-xs bg-blue-50 text-[#1769AA] font-bold px-2.5 py-0.5 rounded-full border border-blue-200">
                        Dashboard Pegawai
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Portal Kinerja & Layanan Mandiri Pegawai Bapekom Wilayah VIII Makassar</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <a href="{{ route('pajokka.create') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-[#1769AA] hover:bg-[#0F2747] text-white text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Buat Laporan Dinas</span>
                </a>
                <div class="flex items-center space-x-3 bg-white pl-2 pr-4 py-1.5 rounded-xl border border-slate-200 shadow-xs">
                    <div class="w-8 h-8 rounded-lg bg-[#0F2747] text-white font-black flex items-center justify-center text-xs">
                        {{ strtoupper(substr($pegawai->nama ?? Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="text-left">
                        <div class="text-xs font-bold text-slate-800 leading-tight">{{ $pegawai->nama ?? Auth::user()->name }}</div>
                        <div class="text-[10px] text-slate-500 font-medium flex items-center space-x-1">
                            <span>NIP. {{ $pegawai->nip ?? '123456789012345678' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Employee Identity & Welcome Header Card -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs relative overflow-hidden">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="flex items-start sm:items-center space-x-4">
                        <div class="w-16 h-16 rounded-2xl bg-[#0F2747] text-white font-extrabold flex items-center justify-center text-2xl shadow-xs shrink-0 border border-slate-300">
                            {{ strtoupper(substr($pegawai->nama ?? Auth::user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h1 class="text-xl font-black text-[#0F2747] tracking-tight">
                                    {{ $pegawai->nama ?? Auth::user()->name }}
                                </h1>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-[#1769AA] border border-blue-200">
                                    {{ $pegawai->status_kepegawaian ?? 'PPPK / ASN' }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center space-x-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Aktif Bekerja</span>
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-y-1 gap-x-4 mt-2 text-xs text-slate-500 font-medium">
                                <span class="flex items-center">
                                    <strong class="text-slate-700 mr-1">NIP:</strong> {{ $pegawai->nip ?? '123456789012345678' }}
                                </span>
                                <span class="flex items-center">
                                    <strong class="text-slate-700 mr-1">Jabatan:</strong> {{ $pegawai->jabatan ?? 'Pegawai Pelaksana Teknis' }}
                                </span>
                                <span class="flex items-center">
                                    <strong class="text-slate-700 mr-1">Unit Kerja:</strong> {{ $pegawai->unitKerja->nama ?? 'Balai Pengembangan Kompetensi PUPR Wil. VIII Makassar' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 shrink-0 pt-4 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                        <div class="bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200 text-left">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Waktu Sistem (WITA)</div>
                            <div class="text-base font-mono font-bold text-[#0F2747]"
                                 x-data="{ time: new Date().toLocaleTimeString('id-ID') + ' WITA' }" 
                                 x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID') + ' WITA', 1000)" 
                                 x-text="time">
                                21:19:05 WITA
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Shortcuts -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                <a href="{{ route('pajokka.create') }}" class="group bg-white p-4 rounded-xl border border-slate-200/90 shadow-xs hover:border-[#1769AA] hover:bg-blue-50/30 transition flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1769AA] flex items-center justify-center shrink-0 group-hover:bg-[#1769AA] group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800 leading-tight">Laporan Pajokka</div>
                        <div class="text-[10px] text-slate-400 font-medium">Buat SPPD & Kuitansi</div>
                    </div>
                </a>

                <a href="{{ route('izin.create') }}" class="group bg-white p-4 rounded-xl border border-slate-200/90 shadow-xs hover:border-[#1769AA] hover:bg-blue-50/30 transition flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 group-hover:bg-amber-500 group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800 leading-tight">Ajukan Izin/Cuti</div>
                        <div class="text-[10px] text-slate-400 font-medium">Form Cuti Online</div>
                    </div>
                </a>

                <a href="{{ route('absen.create') }}" class="group bg-white p-4 rounded-xl border border-slate-200/90 shadow-xs hover:border-[#1769AA] hover:bg-blue-50/30 transition flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800 leading-tight">Lupa Presensi</div>
                        <div class="text-[10px] text-slate-400 font-medium">Klaim Absen Kerja</div>
                    </div>
                </a>

                <a href="{{ route('pengaduan.create') }}" class="group bg-white p-4 rounded-xl border border-slate-200/90 shadow-xs hover:border-[#1769AA] hover:bg-blue-50/30 transition flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800 leading-tight">Aspirasi & Lapor</div>
                        <div class="text-[10px] text-slate-400 font-medium">Pengaduan Pegawai</div>
                    </div>
                </a>
            </div>

            <!-- 4 Key Personal Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Metric 1: Skor Kinerja -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Skor Kinerja Pegawai</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <div class="text-3xl font-black text-[#0F2747]">{{ $skorKinerja }}</div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ $predikatKinerja }}
                        </span>
                    </div>
                    <div class="mt-3 w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ min(100, $skorKinerja) }}%"></div>
                    </div>
                </div>

                <!-- Metric 2: Perjalanan Dinas (Pajokka) -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tugas Dinas (Pajokka)</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#1769AA] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <div class="text-3xl font-black text-[#0F2747]">{{ $totalDinas }}</div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-[#1769AA] border border-blue-200">
                            Laporan Selesai
                        </span>
                    </div>
                    <div class="mt-3 text-[11px] text-slate-500 font-medium">
                        Riwayat SPPD terverifikasi aktif
                    </div>
                </div>

                <!-- Metric 3: Izin & Cuti -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengajuan Izin / Cuti</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <div class="text-3xl font-black text-[#0F2747]">{{ $totalIzin }}</div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            Kuota: 12 Hari/Thn
                        </span>
                    </div>
                    <div class="mt-3 text-[11px] text-slate-500 font-medium">
                        Cuti tahunan & izin dinas
                    </div>
                </div>

                <!-- Metric 4: Klaim Lupa Absen -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Lupa Presensi</span>
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <div class="text-3xl font-black text-[#0F2747]">{{ $totalAbsen }}</div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                            Presensi Sesuai
                        </span>
                    </div>
                    <div class="mt-3 text-[11px] text-slate-500 font-medium">
                        Pengajuan presensi manual
                    </div>
                </div>
            </div>

            <!-- Two Columns Section: Real-time Workstream & Performance Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column (2 Cols): Riwayat Laporan & Aktivitas Terkini -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Card 1: Riwayat Laporan Pajokka -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#1769AA] flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <h3 class="font-bold text-sm text-[#0F2747]">Riwayat Laporan Perjalanan Dinas (Pajokka)</h3>
                            </div>
                            <a href="{{ route('pajokka.index') }}" class="text-xs font-bold text-[#1769AA] hover:text-[#0F2747] transition">
                                Lihat Semua →
                            </a>
                        </div>

                        <div class="mt-4 overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="text-slate-400 uppercase tracking-wider text-[10px] border-b border-slate-100">
                                        <th class="py-2.5 font-bold">Nomor SPPD / Tujuan</th>
                                        <th class="py-2.5 font-bold">Tanggal Kegiatan</th>
                                        <th class="py-2.5 font-bold">Total Biaya</th>
                                        <th class="py-2.5 font-bold">Status</th>
                                        <th class="py-2.5 font-bold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium">
                                    @forelse($myReports as $report)
                                        <tr class="hover:bg-slate-50/60 transition">
                                            <td class="py-3">
                                                <div class="font-bold text-[#0F2747]">{{ $report->nomor_sppd ?? 'SPPD-'.str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</div>
                                                <div class="text-[11px] text-slate-500">{{ $report->tujuan ?? 'Kab/Kota Tujuan' }}</div>
                                            </td>
                                            <td class="py-3 text-slate-600">
                                                {{ $report->tgl_berangkat ? \Carbon\Carbon::parse($report->tgl_berangkat)->format('d M Y') : '-' }}
                                            </td>
                                            <td class="py-3 font-semibold text-slate-700">
                                                Rp {{ number_format($report->total_biaya ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="py-3">
                                                @if($report->status === 'approved')
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>
                                                @elseif($report->status === 'submitted')
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-[#1769AA] border border-blue-200">Verifikasi</span>
                                                @elseif($report->status === 'revision')
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Revisi</span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Draft</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-right">
                                                <a href="{{ route('pajokka.show', $report->id) }}" class="text-[#1769AA] hover:text-[#0F2747] font-bold text-xs underline">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-8 text-center text-slate-400">
                                                Belum ada pengajuan laporan perjalanan dinas.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Card 2: Status Izin & Presensi Terakhir -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <h3 class="font-bold text-sm text-[#0F2747]">Riwayat Izin & Klarifikasi Presensi</h3>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            @forelse($myAbsen as $absen)
                                <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-200/60 text-slate-600 flex items-center justify-center text-xs font-bold">
                                            ABS
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-800">Lupa Presensi: {{ $absen->tanggal ? \Carbon\Carbon::parse($absen->tanggal)->format('d M Y') : 'Hari Kerja' }}</div>
                                            <div class="text-[11px] text-slate-500">{{ $absen->alasan ?? 'Klarifikasi kehadiran harian' }}</div>
                                        </div>
                                    </div>
                                    <div>
                                        @if(($absen->status ?? '') === 'approved')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Menunggu</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-6 text-center text-xs text-slate-400">
                                    Tidak ada pengajuan izin atau absen terbaru.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Right Column (1 Col): Breakdown 6 Aspek Kinerja & Informasi Unit -->
                <div class="space-y-6">
                    
                    <!-- Card: Breakdown 6 Aspek Kinerja -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6">
                        <div class="flex items-center space-x-2.5 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#1769AA] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h3 class="font-bold text-sm text-[#0F2747]">6 Aspek Evaluasi Kinerja</h3>
                        </div>

                        <div class="mt-5 space-y-4">
                            <!-- Aspek 1 -->
                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                                    <span>Kedisiplinan (DIS)</span>
                                    <span class="text-[#1769AA]">{{ $myPenilaian->nilai_kedisiplinan ?? 92 }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-[#1769AA] h-2 rounded-full" style="width: {{ $myPenilaian->nilai_kedisiplinan ?? 92 }}%"></div>
                                </div>
                            </div>

                            <!-- Aspek 2 -->
                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                                    <span>Kinerja & Output (KIN)</span>
                                    <span class="text-[#1769AA]">{{ $myPenilaian->kinerja ?? 95 }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-[#1769AA] h-2 rounded-full" style="width: {{ $myPenilaian->kinerja ?? 95 }}%"></div>
                                </div>
                            </div>

                            <!-- Aspek 3 -->
                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                                    <span>Sikap & Perilaku (SIK)</span>
                                    <span class="text-[#1769AA]">{{ $myPenilaian->sikap ?? 90 }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-[#1769AA] h-2 rounded-full" style="width: {{ $myPenilaian->sikap ?? 90 }}%"></div>
                                </div>
                            </div>

                            <!-- Aspek 4 -->
                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                                    <span>Kepemimpinan (KEP)</span>
                                    <span class="text-[#1769AA]">{{ $myPenilaian->kepemimpinan ?? 88 }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-[#1769AA] h-2 rounded-full" style="width: {{ $myPenilaian->kepemimpinan ?? 88 }}%"></div>
                                </div>
                            </div>

                            <!-- Aspek 5 -->
                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                                    <span>Loyalitas (LOY)</span>
                                    <span class="text-[#1769AA]">{{ $myPenilaian->loyalitas ?? 94 }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-[#1769AA] h-2 rounded-full" style="width: {{ $myPenilaian->loyalitas ?? 94 }}%"></div>
                                </div>
                            </div>

                            <!-- Aspek 6 -->
                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                                    <span>Literasi & TI (IT)</span>
                                    <span class="text-[#1769AA]">{{ $myPenilaian->it ?? 88 }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-[#1769AA] h-2 rounded-full" style="width: {{ $myPenilaian->it ?? 88 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500">Nilai Akhir Rata-rata:</span>
                            <span class="text-sm font-black text-[#0F2747]">{{ $skorKinerja }} / 100</span>
                        </div>
                    </div>

                    <!-- Card: Kebijakan & Informasi Bapekom Wilayah VIII -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6">
                        <h4 class="font-bold text-xs uppercase tracking-wider text-[#0F2747] mb-3">Informasi Kedinasan</h4>
                        <div class="text-xs text-slate-600 space-y-2.5">
                            <div class="flex items-start space-x-2">
                                <span class="text-[#1769AA] font-bold">▪</span>
                                <span>Jam kerja kedinasan reguler: Senin - Jumat (07.30 - 16.30 WITA).</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <span class="text-[#1769AA] font-bold">▪</span>
                                <span>Laporan pertanggungjawaban dinas (Pajokka) wajib diunggah maksimal H+3 penugasan.</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <span class="text-[#1769AA] font-bold">▪</span>
                                <span>Hubungi Subbagian Umum & Kepegawaian untuk pembaruan profil atau kendala presensi.</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
