<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-extrabold text-2xl text-[#0F2747] tracking-tight">
                    Dashboard
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Ringkasan Kinerja & Monitoring Sistem</p>
            </div>
            <div class="flex items-center space-x-4 mt-4 sm:mt-0">
                <!-- Notification Bell -->
                <div class="relative">
                    <button class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition shadow-sm relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white"></span>
                    </button>
                </div>

                <!-- User Profile Badge -->
                <div class="flex items-center space-x-3 bg-white border border-slate-200 px-4 py-2 rounded-2xl shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#0F2747] to-[#1769AA] text-white flex items-center justify-center font-bold text-sm shadow">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div>
                        <div class="text-xs font-bold text-[#0F2747]">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Administrator</div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Main Welcome Banner -->
            <div class="bg-gradient-to-r from-[#0F2747] via-[#1769AA] to-blue-700 rounded-3xl p-6 lg:p-8 text-white shadow-xl relative overflow-hidden flex flex-col lg:flex-row justify-between items-start lg:items-center">
                <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                
                <div class="relative z-10 max-w-2xl">
                    <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold mb-3">
                        <span>✦</span>
                        <span>Selamat Datang!</span>
                    </div>
                    <h3 class="text-2xl lg:text-3xl font-black tracking-tight mb-2">
                        Halo {{ Auth::user()->name }}, berikut ringkasan aktivitas Anda
                    </h3>
                    <p class="text-slate-200 text-sm">
                        Platform monitoring & penilaian kinerja pegawai terintegrasi Balai Pengembangan Kompetensi PU Wilayah VIII Makassar.
                    </p>
                </div>

                <div class="relative z-10 mt-6 lg:mt-0 bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-right">
                    <div class="text-xs text-slate-300 font-medium">Minggu, 18 Oktober 2026</div>
                    <div class="text-2xl lg:text-3xl font-mono font-black tracking-wider text-white mt-1">21:19:05 <span class="text-xs font-sans font-normal text-cyan-200">WITA</span></div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center space-x-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Periode:
                    </span>
                    <button class="px-4 py-2 bg-[#1769AA] text-white text-xs font-bold rounded-xl shadow-sm">Semua</button>
                    <button class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold rounded-xl transition">TW I</button>
                    <button class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold rounded-xl transition">TW II</button>
                    <button class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold rounded-xl transition">TW III</button>
                    <button class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold rounded-xl transition">TW IV</button>
                    <button class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold rounded-xl transition">SMI</button>
                    <button class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold rounded-xl transition">SMII</button>
                    <button class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold rounded-xl transition">Tahunan</button>
                </div>

                <div class="flex items-center space-x-3 w-full md:w-auto justify-end">
                    <select class="bg-slate-50 border border-slate-300 text-slate-700 text-xs font-semibold rounded-xl px-3 py-2 focus:ring-[#1769AA] focus:border-[#1769AA]">
                        <option>2026</option>
                        <option>2025</option>
                    </select>
                    <select class="bg-slate-50 border border-slate-300 text-slate-700 text-xs font-semibold rounded-xl px-3 py-2 focus:ring-[#1769AA] focus:border-[#1769AA]">
                        <option>Semua Bulan</option>
                        <option>Januari</option>
                    </select>
                </div>
            </div>

            <!-- Stat Cards (4 cards) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Pegawai -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-50 text-[#1769AA] rounded-2xl flex items-center justify-center font-bold text-xl">
                            👥
                        </div>
                        <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Terdaftar Aktif</span>
                    </div>
                    <h3 class="text-3xl font-black text-[#0F2747] tracking-tight">{{ $totalPegawai ?? 32 }}</h3>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-1">Total Pegawai</p>
                </div>

                <!-- Total Penilaian -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center font-bold text-xl">
                            ⭐
                        </div>
                        <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Semua Periode</span>
                    </div>
                    <h3 class="text-3xl font-black text-[#0F2747] tracking-tight">{{ $totalReports ?? 1 }}</h3>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-1">Total Penilaian</p>
                </div>

                <!-- Izin Pending -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 border-l-4 border-l-amber-400 shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center font-bold text-xl">
                            📅
                        </div>
                        <span class="bg-amber-50 text-amber-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Perlu Diproses</span>
                    </div>
                    <h3 class="text-3xl font-black text-[#0F2747] tracking-tight">{{ $totalAbsen ?? 4 }}</h3>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-1">Izin Pending</p>
                </div>

                <!-- Pengaduan Pending -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 border-l-4 border-l-rose-500 shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center font-bold text-xl">
                            💬
                        </div>
                        <span class="bg-rose-50 text-rose-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Perlu Ditangani</span>
                    </div>
                    <h3 class="text-3xl font-black text-[#0F2747] tracking-tight">10</h3>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-1">Pengaduan Pending</p>
                </div>
            </div>

            <!-- Charts (Row 2) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Distribusi Predikat -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold">📊</div>
                            <h4 class="font-bold text-lg text-[#0F2747]">Distribusi Predikat</h4>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center justify-around py-6">
                        <div class="relative w-40 h-40 rounded-full border-8 border-emerald-500 flex items-center justify-center shadow-inner">
                            <div class="text-center">
                                <span class="text-xs font-semibold text-slate-400 uppercase">Total</span>
                                <div class="text-2xl font-black text-[#0F2747]">1</div>
                            </div>
                        </div>
                        <div class="space-y-3 mt-6 sm:mt-0">
                            <div class="flex items-center space-x-2 text-sm">
                                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                <span class="text-slate-600 font-medium">Sangat Baik (100%)</span>
                            </div>
                            <div class="flex items-center space-x-2 text-sm">
                                <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                                <span class="text-slate-600 font-medium">Baik (0%)</span>
                            </div>
                            <div class="flex items-center space-x-2 text-sm">
                                <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                                <span class="text-slate-600 font-medium">Kurang (0%)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rata-rata Keseluruhan -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center font-bold">🎯</div>
                            <h4 class="font-bold text-lg text-[#0F2747]">Rata-rata Keseluruhan</h4>
                        </div>
                    </div>
                    <div class="flex flex-col items-center justify-center py-8">
                        <div class="w-36 h-36 rounded-full bg-emerald-50 border-8 border-emerald-500 flex flex-col items-center justify-center shadow-md">
                            <span class="text-3xl font-black text-emerald-600">91.2</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 mt-1">Sangat Baik</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-6 font-medium text-center">
                            Nilai rata-rata kinerja seluruh pegawai aktif pada periode aktif saat ini.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
