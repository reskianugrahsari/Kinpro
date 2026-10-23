<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KinPro v2') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

    <!-- Scripts -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-['Plus_Jakarta_Sans',sans-serif] antialiased bg-brand-beige dark:bg-slate-950 text-brand-navy dark:text-slate-100 transition-colors duration-200">
        <div class="h-screen flex overflow-hidden">
            <!-- Sidebar (Primary Navy #2F4156) -->
            <aside class="w-64 bg-brand-navy text-slate-200 flex flex-col shrink-0 shadow-xl border-r border-[#202C3B] h-full overflow-hidden">
                <!-- Brand Header -->
                <div class="h-16 flex items-center px-5 border-b border-[#202C3B] space-x-3 shrink-0">
                    <img src="{{ asset('images/logo-kinpro.png') }}?v={{ file_exists(public_path('images/logo-kinpro.png')) ? filemtime(public_path('images/logo-kinpro.png')) : time() }}" alt="Logo KinPro" class="w-9 h-9 object-contain shrink-0">
                    <div class="min-w-0 flex-1">
                        <span class="text-white font-extrabold tracking-tight text-base block leading-tight">KinPro</span>
                        <span class="text-[10px] text-brand-sky/80 truncate block mt-0.5">Bapekom PU Wil. VIII Makassar</span>
                    </div>
                </div>

                <nav class="flex-1 px-3 py-5 space-y-6 text-xs font-semibold overflow-y-auto">
                    <!-- Menu Utama -->
                    <div>
                        <div class="px-3 pb-2 text-[10px] uppercase tracking-wider text-brand-sky/70 font-bold">MENU UTAMA</div>
                        <div class="space-y-1">
                            <a href="{{ route('dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-brand-teal text-white font-bold shadow-sm' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                <span class="flex items-center space-x-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    <span>Dashboard</span>
                                </span>
                                @if(request()->routeIs('dashboard'))
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                @endif
                            </a>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.pegawai.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.pegawai.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <span>Data Pegawai</span>
                                </a>
                                <a href="{{ route('penilaian.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('penilaian.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Penilaian Kinerja</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <!-- Manajemen & Verifikasi -->
                        <div>
                            <div class="px-3 pb-2 text-[10px] uppercase tracking-wider text-brand-sky/70 font-bold">MANAJEMEN & VERIFIKASI</div>
                            <div class="space-y-1">
                                <a href="{{ route('izin.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('izin.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <span class="flex items-center space-x-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Kelola Izin</span>
                                    </span>
                                    @if(($badgeCounts['izin'] ?? 0) > 0)
                                        <span class="bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $badgeCounts['izin'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('pengaduan.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('pengaduan.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <span class="flex items-center space-x-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                        <span>Kelola Pengaduan</span>
                                    </span>
                                    @if(($badgeCounts['pengaduan'] ?? 0) > 0)
                                        <span class="bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $badgeCounts['pengaduan'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('absen.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('absen.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <span class="flex items-center space-x-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Kelola Lupa Absen</span>
                                    </span>
                                    @if(($badgeCounts['absen'] ?? 0) > 0)
                                        <span class="bg-brand-teal/40 text-brand-sky border border-brand-teal text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $badgeCounts['absen'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('pajokka.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('pajokka.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <span class="flex items-center space-x-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Review Perjalanan Dinas</span>
                                    </span>
                                    @if(($badgeCounts['pajokka'] ?? 0) > 0)
                                        <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $badgeCounts['pajokka'] }}</span>
                                    @endif
                                </a>
                            </div>
                        </div>

                        <!-- Laporan & Audit -->
                        <div>
                            <div class="px-3 pb-2 text-[10px] uppercase tracking-wider text-brand-sky/70 font-bold">LAPORAN & AUDIT</div>
                            <div class="space-y-1">
                                <a href="{{ route('rekap.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('rekap.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    <span>Rekap & Statistik</span>
                                </a>
                                <a href="{{ route('rating.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('rating.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    <span>Rating Pegawai</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Menu Pegawai: Layanan Mandiri Saja -->
                        <div>
                            <div class="px-3 pb-2 text-[10px] uppercase tracking-wider text-brand-sky/70 font-bold">LAYANAN PEGAWAI</div>
                            <div class="space-y-1">
                                <a href="{{ route('penilaian.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('penilaian.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Penilaian Saya</span>
                                </a>
                                <a href="{{ route('izin.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('izin.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Pengajuan Izin</span>
                                </a>
                                <a href="{{ route('absen.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('absen.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Pengajuan Lupa Absen</span>
                                </a>
                                <a href="{{ route('pengaduan.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('pengaduan.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    <span>Pengaduan Saya</span>
                                </a>
                                <a href="{{ route('pajokka.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('pajokka.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Laporan Perjalanan Dinas</span>
                                </a>
                                <a href="{{ route('rating.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('rating.*') ? 'bg-brand-teal text-white font-bold' : 'text-slate-200 hover:bg-brand-teal/30 hover:text-white' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    <span>Rating Pegawai</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </nav>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Top Header -->
                <header class="bg-brand-white dark:bg-[#202C3B] border-b border-brand-sky dark:border-slate-800 h-16 min-h-[4rem] max-h-[4rem] px-6 py-2.5 flex items-center justify-between shadow-xs transition-colors shrink-0">
                    <!-- Left: Realtime Date/Time Indicator -->
                    <div class="flex items-center space-x-2 bg-brand-beige dark:bg-slate-800/60 border border-brand-sky dark:border-slate-700/60 px-3.5 py-1.5 rounded-full text-xs text-brand-navy dark:text-slate-300 font-medium shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span id="header-realtime-clock" x-data="{ time: '' }" x-init="
                            const updateClock = () => {
                                const d = new Date();
                                const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                                const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                const pad = (n) => String(n).padStart(2, '0');
                                time = `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()} • ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())} WITA`;
                            };
                            updateClock(); setInterval(updateClock, 1000);
                        " x-text="time">Minggu, 18 Oktober 2026 • 21:19:05 WITA</span>
                    </div>

                    <!-- Right Controls: Action Icons & User Dropdown -->
                    <div class="flex items-center space-x-2.5">
                        <!-- Theme Toggle Button (Light/Dark Mode) -->
                        <button type="button" onclick="toggleTheme()" class="w-9 h-9 bg-brand-white dark:bg-slate-800 border border-brand-sky dark:border-slate-700 text-brand-navy dark:text-slate-300 hover:bg-brand-beige dark:hover:bg-slate-700 rounded-xl flex items-center justify-center relative shadow-xs transition cursor-pointer" title="Ubah Mode Gelap / Terang">
                            <!-- Sun icon shown in dark mode -->
                            <svg class="w-4 h-4 hidden dark:block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <!-- Moon icon shown in light mode -->
                            <svg class="w-4 h-4 block dark:hidden text-brand-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </button>

                        <!-- Notification Dropdown -->
                        <div class="relative" x-data="{ openNotif: false }" @click.outside="openNotif = false">
                            <button type="button" @click="openNotif = !openNotif" class="w-9 h-9 bg-brand-white dark:bg-slate-800 border border-brand-sky dark:border-slate-700 text-brand-navy dark:text-slate-300 hover:bg-brand-beige dark:hover:bg-slate-700 rounded-xl flex items-center justify-center relative shadow-xs transition cursor-pointer" title="Notifikasi Masuk">
                                <svg class="w-4 h-4 text-brand-navy dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                @if(($badgeCounts['total'] ?? 0) > 0)
                                    <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-brand-teal text-white rounded-full ring-2 ring-white dark:ring-slate-800 text-[10px] font-bold flex items-center justify-center tabular-nums">
                                        {{ $badgeCounts['total'] > 99 ? '99+' : $badgeCounts['total'] }}
                                    </span>
                                @endif
                            </button>

                            <!-- Notification Panel -->
                            <div x-show="openNotif" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-80 sm:w-96 bg-brand-white dark:bg-slate-900 border border-brand-sky dark:border-slate-800 rounded-2xl shadow-xl z-50 overflow-hidden" 
                                 style="display: none;">
                                <div class="px-4 py-3 bg-brand-navy text-white flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[18px] text-brand-sky">notifications_active</span>
                                        <span class="font-bold text-xs">Pemberitahuan Masuk</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white">
                                        {{ $badgeCounts['total'] ?? 0 }} Baru
                                    </span>
                                </div>

                                <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800">
                                    @forelse($headerNotifications ?? [] as $notif)
                                        <a href="{{ $notif['url'] }}" class="p-3.5 flex items-start gap-3 hover:bg-brand-beige/50 dark:hover:bg-slate-800/60 transition group">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $notif['color'] }}">
                                                <span class="material-symbols-outlined text-[16px]">{{ $notif['icon'] }}</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-2">
                                                    <h4 class="text-xs font-bold text-brand-navy dark:text-slate-100 truncate group-hover:text-brand-teal dark:group-hover:text-brand-sky transition-colors">{{ $notif['title'] }}</h4>
                                                    <span class="text-[10px] text-slate-400 shrink-0 tabular-nums">
                                                        {{ $notif['time'] ? \Carbon\Carbon::parse($notif['time'])->diffForHumans(null, true) : 'Baru' }}
                                                    </span>
                                                </div>
                                                <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2 mt-0.5 leading-relaxed">{{ $notif['desc'] }}</p>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="py-8 px-4 text-center">
                                            <div class="w-10 h-10 mx-auto rounded-full bg-brand-beige dark:bg-slate-800 text-slate-400 flex items-center justify-center mb-2">
                                                <span class="material-symbols-outlined text-[20px]">notifications_off</span>
                                            </div>
                                            <p class="text-xs font-semibold text-brand-navy dark:text-slate-300">Tidak ada pemberitahuan baru</p>
                                            <p class="text-[11px] text-slate-400 mt-0.5">Semua permohonan dan laporan telah ditindaklanjuti.</p>
                                        </div>
                                    @endforelse
                                </div>

                                @if(auth()->user()->role === 'admin')
                                    <div class="p-2.5 bg-brand-beige dark:bg-slate-800/50 border-t border-brand-sky dark:border-slate-800 text-center">
                                        <a href="{{ route('dashboard') }}" class="text-[11px] font-bold text-brand-teal dark:text-brand-sky hover:underline">
                                            Lihat Rekap Dashboard Admin &rarr;
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Activity Clock Button -->
                        <button type="button" class="w-9 h-9 bg-brand-white dark:bg-slate-800 border border-brand-sky dark:border-slate-700 text-brand-navy dark:text-slate-300 hover:bg-brand-beige dark:hover:bg-slate-700 rounded-xl flex items-center justify-center shadow-xs transition cursor-pointer" title="Aktivitas Waktu">
                            <svg class="w-4 h-4 text-brand-navy dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>

                        <!-- Help Center Button -->
                        <button type="button" class="w-9 h-9 bg-brand-white dark:bg-slate-800 border border-brand-sky dark:border-slate-700 text-brand-navy dark:text-slate-300 hover:bg-brand-beige dark:hover:bg-slate-700 rounded-xl flex items-center justify-center shadow-xs transition cursor-pointer" title="Pusat Bantuan">
                            <svg class="w-4 h-4 text-brand-navy dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>

                        <!-- Divider -->
                        <div class="h-6 w-px bg-brand-sky dark:bg-slate-700 mx-1"></div>

                        <!-- User Profile Pill Dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="bg-brand-white dark:bg-slate-800 border border-brand-sky dark:border-slate-700 hover:border-brand-teal rounded-xl px-3 py-1.5 flex items-center space-x-3 transition shadow-xs cursor-pointer">
                                    <div class="w-8 h-8 rounded-full bg-brand-sky text-brand-navy font-extrabold flex items-center justify-center text-xs">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                    </div>
                                    <div class="text-left hidden sm:block">
                                        <div class="text-xs font-bold text-brand-navy dark:text-slate-100 leading-tight">{{ Auth::user()->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-medium leading-tight mt-0.5">{{ Auth::user()->role === 'admin' ? 'Administrator Utama' : (Auth::user()->pegawai?->jabatan ?? 'Pegawai') }}</div>
                                    </div>
                                    <span class="material-symbols-outlined text-[16px] text-slate-400 shrink-0 ml-1.5">expand_more</span>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-brand-sky dark:border-slate-700">
                                    <div class="text-xs font-bold text-brand-navy dark:text-slate-200">{{ Auth::user()->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ Auth::user()->email }}</div>
                                </div>
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profil Saya') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-600 dark:text-rose-400">
                                        {{ __('Logout') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-6 lg:p-7 bg-brand-beige dark:bg-slate-900 overflow-y-auto transition-colors">
                    <div class="max-w-[1500px] mx-auto w-full">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <script>
            function toggleTheme() {
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        </script>
    </body>
</html>
