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

            const savedWidth = localStorage.getItem('kinpro_sidebar_width');
            const isCollapsed = localStorage.getItem('kinpro_sidebar_collapsed') === 'true';
            if (isCollapsed || (savedWidth && parseInt(savedWidth) <= 125)) {
                document.documentElement.classList.add('sidebar-is-collapsed');
                document.documentElement.style.setProperty('--sidebar-w', '68px');
            } else if (savedWidth) {
                document.documentElement.style.setProperty('--sidebar-w', savedWidth + 'px');
            }
        })();
    </script>
    <style>
        :root {
            --sidebar-w: 256px;
        }
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined' !important;
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            -moz-osx-font-smoothing: grayscale;
            font-feature-settings: 'liga';
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }
        /* Sidebar Resizing & Mini Mode */
        #app-sidebar.is-mini,
        #app-sidebar[data-collapsed="true"],
        html.sidebar-is-collapsed #app-sidebar {
            width: 68px !important;
            min-width: 68px !important;
        }
        #app-sidebar.is-mini .sidebar-text,
        #app-sidebar[data-collapsed="true"] .sidebar-text,
        html.sidebar-is-collapsed #app-sidebar .sidebar-text {
            display: none !important;
        }
        #app-sidebar.is-mini .sidebar-category,
        #app-sidebar[data-collapsed="true"] .sidebar-category,
        html.sidebar-is-collapsed #app-sidebar .sidebar-category {
            display: none !important;
        }
        #app-sidebar.is-mini .sidebar-divider,
        #app-sidebar[data-collapsed="true"] .sidebar-divider,
        html.sidebar-is-collapsed #app-sidebar .sidebar-divider {
            display: block !important;
        }
        #app-sidebar.is-mini .sidebar-nav-container,
        #app-sidebar[data-collapsed="true"] .sidebar-nav-container,
        html.sidebar-is-collapsed #app-sidebar .sidebar-nav-container {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        #app-sidebar.is-mini .sidebar-item,
        #app-sidebar[data-collapsed="true"] .sidebar-item,
        html.sidebar-is-collapsed #app-sidebar .sidebar-item {
            width: 2.75rem !important; /* 44px centered */
            height: 2.75rem !important;
            padding: 0 !important;
            margin-left: auto !important;
            margin-right: auto !important;
            justify-content: center !important;
        }
        #app-sidebar.is-mini .sidebar-item > span,
        #app-sidebar[data-collapsed="true"] .sidebar-item > span,
        html.sidebar-is-collapsed #app-sidebar .sidebar-item > span {
            justify-content: center !important;
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            gap: 0 !important;
        }
        #app-sidebar.is-mini .sidebar-item > span > div,
        #app-sidebar[data-collapsed="true"] .sidebar-item > span > div,
        html.sidebar-is-collapsed #app-sidebar .sidebar-item > span > div {
            background-color: transparent !important;
            width: 100% !important;
            height: 100% !important;
        }
        #app-sidebar.is-mini .sidebar-badge-dot,
        #app-sidebar[data-collapsed="true"] .sidebar-badge-dot,
        html.sidebar-is-collapsed #app-sidebar .sidebar-badge-dot {
            display: block !important;
        }
        #app-sidebar.is-mini .sidebar-brand-text,
        #app-sidebar[data-collapsed="true"] .sidebar-brand-text,
        html.sidebar-is-collapsed #app-sidebar .sidebar-brand-text {
            display: none !important;
        }
        #app-sidebar.is-mini .sidebar-brand-box,
        #app-sidebar[data-collapsed="true"] .sidebar-brand-box,
        html.sidebar-is-collapsed #app-sidebar .sidebar-brand-box {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        body.is-resizing {
            user-select: none !important;
            cursor: col-resize !important;
        }
        body.is-resizing * {
            user-select: none !important;
            cursor: col-resize !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-['Plus_Jakarta_Sans',sans-serif] antialiased bg-brand-beige dark:bg-slate-950 text-brand-navy dark:text-slate-100 transition-colors duration-200">
    <x-loading-screen />
    <div class="h-screen flex overflow-hidden">
            <!-- Sidebar (Navy #0A1931) -->
            <aside id="app-sidebar" class="relative bg-[#0A1931] text-slate-200 flex flex-col shrink-0 shadow-xl border-r border-slate-800/80 h-full transition-[width] duration-200 select-none" style="width: var(--sidebar-w, 256px); min-width: 68px; max-width: 380px;">
                <!-- Brand Header (Klik untuk Perkecil / Perbesar) -->
                <div id="sidebar-brand-header" class="h-16 flex items-center px-4 border-b border-slate-800/80 shrink-0 sidebar-brand-box cursor-pointer select-none hover:bg-white/5 transition-colors overflow-hidden" title="Klik untuk perkecil / perbesar sidebar">
                    <div class="flex items-center space-x-3 min-w-0 pointer-events-none">
                        <img src="{{ asset('images/logo-kinpro.png') }}?v={{ file_exists(public_path('images/logo-kinpro.png')) ? filemtime(public_path('images/logo-kinpro.png')) : time() }}" alt="Logo KinPro" class="w-9 h-9 object-contain shrink-0 mx-auto">
                        <div class="min-w-0 flex-1 sidebar-brand-text">
                            <span class="text-white font-extrabold tracking-tight text-base block leading-tight">KinPro</span>
                            <span class="text-[10px] text-brand-sky/80 truncate block mt-0.5">Bapekom PU Wil. VIII Makassar</span>
                        </div>
                    </div>
                </div>

                <nav class="flex-1 px-3 py-5 space-y-6 text-xs font-semibold overflow-y-auto overflow-x-hidden sidebar-nav-container">
                    <!-- Menu Utama -->
                    <div>
                        <div class="sidebar-category px-3 pb-2 text-[10px] uppercase tracking-wider text-sky-200/80 font-bold">
                            MENU UTAMA
                        </div>
                        <div class="sidebar-divider hidden my-2 mx-auto w-6 border-t border-slate-800"></div>
                        <div class="space-y-1">
                            <a href="{{ route('dashboard') }}" title="Dashboard" class="sidebar-item group flex items-center justify-between px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                <span class="flex items-center space-x-3 min-w-0">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'bg-blue-500/15 text-blue-300 group-hover:bg-blue-500/25 group-hover:text-white' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    </div>
                                    <span class="sidebar-text truncate">Dashboard</span>
                                </span>
                            </a>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.pegawai.index') }}" title="Data Pegawai" class="sidebar-item group flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.pegawai.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('admin.pegawai.*') ? 'bg-white/20 text-white' : 'bg-indigo-500/15 text-indigo-300 group-hover:bg-indigo-500/25 group-hover:text-white' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </div>
                                    <span class="sidebar-text truncate">Data Pegawai</span>
                                </a>
                                <a href="{{ route('penilaian.index') }}" title="Penilaian Kinerja" class="sidebar-item group flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('penilaian.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('penilaian.*') ? 'bg-white/20 text-white' : 'bg-emerald-500/15 text-emerald-300 group-hover:bg-emerald-500/25 group-hover:text-white' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span class="sidebar-text truncate">Penilaian Kinerja</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <!-- Manajemen & Verifikasi -->
                        <div>
                            <div class="sidebar-category px-3 pb-2 text-[10px] uppercase tracking-wider text-sky-200/80 font-bold">
                                MANAJEMEN & VERIFIKASI
                            </div>
                            <div class="sidebar-divider hidden my-2 mx-auto w-6 border-t border-slate-800"></div>
                            <div class="space-y-1">
                                <a href="{{ route('izin.index') }}" title="Kelola Izin" class="sidebar-item group flex items-center justify-between px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('izin.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <span class="flex items-center space-x-3 min-w-0">
                                        <div class="relative w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('izin.*') ? 'bg-white/20 text-white' : 'bg-amber-500/15 text-amber-300 group-hover:bg-amber-500/25 group-hover:text-white' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            @if(($badgeCounts['izin'] ?? 0) > 0)
                                                <span class="sidebar-badge-dot hidden absolute -top-1 -right-1 w-2.5 h-2.5 bg-amber-400 rounded-full ring-2 ring-[#0A1931]"></span>
                                            @endif
                                        </div>
                                        <span class="sidebar-text truncate">Kelola Izin</span>
                                    </span>
                                    @if(($badgeCounts['izin'] ?? 0) > 0)
                                        <span class="sidebar-text sidebar-badge bg-amber-500/20 text-amber-300 border border-amber-500/40 text-[10px] px-2 py-0.5 rounded-full font-bold shadow-2xs">{{ $badgeCounts['izin'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('pengaduan.index') }}" title="Kelola Pengaduan" class="sidebar-item group flex items-center justify-between px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('pengaduan.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <span class="flex items-center space-x-3 min-w-0">
                                        <div class="relative w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('pengaduan.*') ? 'bg-white/20 text-white' : 'bg-rose-500/15 text-rose-300 group-hover:bg-rose-500/25 group-hover:text-white' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                            @if(($badgeCounts['pengaduan'] ?? 0) > 0)
                                                <span class="sidebar-badge-dot hidden absolute -top-1 -right-1 w-2.5 h-2.5 bg-rose-400 rounded-full ring-2 ring-[#0A1931]"></span>
                                            @endif
                                        </div>
                                        <span class="sidebar-text truncate">Kelola Pengaduan</span>
                                    </span>
                                    @if(($badgeCounts['pengaduan'] ?? 0) > 0)
                                        <span class="sidebar-text sidebar-badge bg-rose-500/20 text-rose-300 border border-rose-500/40 text-[10px] px-2 py-0.5 rounded-full font-bold shadow-2xs">{{ $badgeCounts['pengaduan'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('absen.index') }}" title="Kelola Lupa Absen" class="sidebar-item group flex items-center justify-between px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('absen.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <span class="flex items-center space-x-3 min-w-0">
                                        <div class="relative w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('absen.*') ? 'bg-white/20 text-white' : 'bg-sky-500/15 text-sky-300 group-hover:bg-sky-500/25 group-hover:text-white' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @if(($badgeCounts['absen'] ?? 0) > 0)
                                                <span class="sidebar-badge-dot hidden absolute -top-1 -right-1 w-2.5 h-2.5 bg-sky-400 rounded-full ring-2 ring-[#0A1931]"></span>
                                            @endif
                                        </div>
                                        <span class="sidebar-text truncate">Kelola Lupa Absen</span>
                                    </span>
                                    @if(($badgeCounts['absen'] ?? 0) > 0)
                                        <span class="sidebar-text sidebar-badge bg-sky-500/20 text-sky-300 border border-sky-500/40 text-[10px] px-2 py-0.5 rounded-full font-bold shadow-2xs">{{ $badgeCounts['absen'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('pajokka.index') }}" title="Review Perjalanan Dinas" class="sidebar-item group flex items-center justify-between px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('pajokka.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <span class="flex items-center space-x-3 min-w-0">
                                        <div class="relative w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('pajokka.*') ? 'bg-white/20 text-white' : 'bg-teal-500/15 text-teal-300 group-hover:bg-teal-500/25 group-hover:text-white' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            @if(($badgeCounts['pajokka'] ?? 0) > 0)
                                                <span class="sidebar-badge-dot hidden absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-400 rounded-full ring-2 ring-[#0A1931]"></span>
                                            @endif
                                        </div>
                                        <span class="sidebar-text truncate">Review Perjalanan Dinas</span>
                                    </span>
                                    @if(($badgeCounts['pajokka'] ?? 0) > 0)
                                        <span class="sidebar-text sidebar-badge bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-[10px] px-2 py-0.5 rounded-full font-bold shadow-2xs">{{ $badgeCounts['pajokka'] }}</span>
                                    @endif
                                </a>
                            </div>
                        </div>

                        <!-- Laporan & Audit -->
                        <div>
                            <div class="sidebar-category px-3 pb-2 text-[10px] uppercase tracking-wider text-sky-200/80 font-bold">
                                LAPORAN & AUDIT
                            </div>
                            <div class="sidebar-divider hidden my-2 mx-auto w-6 border-t border-slate-800"></div>
                            <div class="space-y-1">
                                <a href="{{ route('rekap.index') }}" title="Rekap & Statistik" class="sidebar-item group flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('rekap.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('rekap.*') ? 'bg-white/20 text-white' : 'bg-purple-500/15 text-purple-300 group-hover:bg-purple-500/25 group-hover:text-white' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    </div>
                                    <span class="sidebar-text truncate">Rekap & Statistik</span>
                                </a>
                                <a href="{{ route('rating.index') }}" title="Rating Pegawai" class="sidebar-item group flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('rating.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('rating.*') ? 'bg-white/20 text-white' : 'bg-amber-400/15 text-amber-300 group-hover:bg-amber-400/25 group-hover:text-white' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    </div>
                                    <span class="sidebar-text truncate">Rating Pegawai</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Menu Pegawai: Layanan Mandiri Saja -->
                        <div>
                            <div class="sidebar-category px-3 pb-2 text-[10px] uppercase tracking-wider text-sky-200/80 font-bold">
                                INFORMASI & DIREKTORI
                            </div>
                            <div class="sidebar-divider hidden my-2 mx-auto w-6 border-t border-slate-800"></div>
                            <div class="space-y-1 mb-4">
                                <a href="{{ route('pegawai.index') }}" title="Data Pegawai" class="sidebar-item group flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('pegawai.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('pegawai.*') ? 'bg-white/20 text-white' : 'bg-indigo-500/15 text-indigo-300 group-hover:bg-indigo-500/25 group-hover:text-white' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </div>
                                    <span class="sidebar-text truncate">Data Pegawai</span>
                                </a>
                            </div>

                            <div class="sidebar-category px-3 pb-2 text-[10px] uppercase tracking-wider text-sky-200/80 font-bold">
                                LAYANAN PEGAWAI
                            </div>
                            <div class="sidebar-divider hidden my-2 mx-auto w-6 border-t border-slate-800"></div>
                            <div class="space-y-1">
                                <a href="{{ route('penilaian.index') }}" title="Penilaian Saya" class="sidebar-item group flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('penilaian.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('penilaian.*') ? 'bg-white/20 text-white' : 'bg-emerald-500/15 text-emerald-300 group-hover:bg-emerald-500/25 group-hover:text-white' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span class="sidebar-text truncate">Penilaian Saya</span>
                                </a>
                                <a href="{{ route('izin.index') }}" title="Pengajuan Izin" class="sidebar-item group flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('izin.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('izin.*') ? 'bg-white/20 text-white' : 'bg-amber-500/15 text-amber-300 group-hover:bg-amber-500/25 group-hover:text-white' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="sidebar-text truncate">Pengajuan Izin</span>
                                </a>
                                <a href="{{ route('absen.index') }}" title="Pengajuan Lupa Absen" class="sidebar-item group flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('absen.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('absen.*') ? 'bg-white/20 text-white' : 'bg-sky-500/15 text-sky-300 group-hover:bg-sky-500/25 group-hover:text-white' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span class="sidebar-text truncate">Pengajuan Lupa Absen</span>
                                </a>
                                <a href="{{ route('pengaduan.index') }}" title="Pengaduan Saya" class="sidebar-item group flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('pengaduan.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('pengaduan.*') ? 'bg-white/20 text-white' : 'bg-rose-500/15 text-rose-300 group-hover:bg-rose-500/25 group-hover:text-white' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    </div>
                                    <span class="sidebar-text truncate">Pengaduan Saya</span>
                                </a>
                                <a href="{{ route('pajokka.index') }}" title="Laporan Perjalanan Dinas" class="sidebar-item group flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('pajokka.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('pajokka.*') ? 'bg-white/20 text-white' : 'bg-teal-500/15 text-teal-300 group-hover:bg-teal-500/25 group-hover:text-white' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <span class="sidebar-text truncate">Laporan Perjalanan Dinas</span>
                                </a>
                                <a href="{{ route('rating.index') }}" title="Rating Pegawai" class="sidebar-item group flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('rating.*') ? 'bg-brand-teal text-white font-bold shadow-md ring-1 ring-white/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('rating.*') ? 'bg-white/20 text-white' : 'bg-amber-400/15 text-amber-300 group-hover:bg-amber-400/25 group-hover:text-white' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    </div>
                                    <span class="sidebar-text truncate">Rating Pegawai</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </nav>

                <!-- Drag Handle Resizer -->
                <div id="sidebar-resizer" 
                     class="sidebar-resizer absolute top-0 -right-2 w-4 h-full cursor-col-resize z-40 flex items-center justify-center group select-none"
                     title="Tarik ke samping untuk mengubah ukuran, klik 2x untuk minimize/maximize">
                    <div class="w-1 h-full group-hover:bg-sky-400/80 group-active:bg-sky-400 transition-colors flex items-center justify-center">
                        <div class="w-0.5 h-8 bg-slate-600/80 group-hover:bg-sky-200 group-active:bg-white rounded-full transition-colors"></div>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Top Header -->
                <header class="bg-brand-white dark:bg-[#0A1931] border-b border-brand-sky dark:border-slate-800/80 h-16 min-h-[4rem] max-h-[4rem] px-6 py-2.5 flex items-center justify-between shadow-xs transition-colors shrink-0">
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
                                                    <span class="text-[10px] text-slate-500 dark:text-slate-400 shrink-0 tabular-nums">
                                                        {{ $notif['time'] ? \Carbon\Carbon::parse($notif['time'])->diffForHumans(null, true) : 'Baru' }}
                                                    </span>
                                                </div>
                                                <p class="text-[11px] text-slate-600 dark:text-slate-400 line-clamp-2 mt-0.5 leading-relaxed">{{ $notif['desc'] }}</p>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="py-8 px-4 text-center">
                                            <div class="w-10 h-10 mx-auto rounded-full bg-brand-beige dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center mb-2">
                                                <span class="material-symbols-outlined text-[20px]">notifications_off</span>
                                            </div>
                                            <p class="text-xs font-semibold text-brand-navy dark:text-slate-300">Tidak ada pemberitahuan baru</p>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Semua permohonan dan laporan telah ditindaklanjuti.</p>
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

                        <!-- Divider -->
                        <div class="h-6 w-px bg-brand-sky dark:bg-slate-700 mx-1"></div>

                        <!-- User Profile Pill Dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="bg-brand-white dark:bg-slate-800 border border-brand-sky dark:border-slate-700 hover:border-brand-teal rounded-xl px-3 py-1.5 flex items-center space-x-3 transition shadow-xs cursor-pointer">
                                    @php
                                        $userFotoUrl = Auth::user()->pegawai?->foto_url;
                                    @endphp
                                    @if($userFotoUrl)
                                        <img src="{{ $userFotoUrl }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover object-center border border-brand-sky/60 dark:border-slate-600 shrink-0" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-8 h-8 rounded-full bg-brand-sky text-brand-navy font-extrabold hidden items-center justify-center text-xs shrink-0">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-brand-sky text-brand-navy font-extrabold flex items-center justify-center text-xs shrink-0">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div class="text-left hidden sm:block">
                                        <div class="text-xs font-bold text-brand-navy dark:text-slate-100 leading-tight">{{ Auth::user()->name }}</div>
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400 font-medium leading-tight mt-0.5">{{ Auth::user()->role === 'admin' ? 'Administrator Utama' : (Auth::user()->pegawai?->jabatan ?? 'Pegawai') }}</div>
                                    </div>
                                    <span class="material-symbols-outlined text-[16px] text-slate-500 dark:text-slate-400 shrink-0 ml-1.5">expand_more</span>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-brand-sky dark:border-slate-700 flex items-center space-x-2.5">
                                    @if($userFotoUrl)
                                        <img src="{{ $userFotoUrl }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover object-center border border-brand-sky shrink-0">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-brand-sky text-brand-navy font-extrabold flex items-center justify-center text-xs shrink-0">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-brand-navy dark:text-slate-200 truncate">{{ Auth::user()->name }}</div>
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ Auth::user()->email }}</div>
                                    </div>
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

            // Sidebar Resizing & Collapse Controller (Click & Drag)
            (function initSidebar() {
                const sidebar = document.getElementById('app-sidebar');
                const brandHeader = document.getElementById('sidebar-brand-header');
                const resizer = document.getElementById('sidebar-resizer');
                if (!sidebar || !resizer) return;

                const COLLAPSED_WIDTH = 68;
                const MIN_EXPANDED = 180;
                const MAX_WIDTH = 380;
                const DEFAULT_WIDTH = 256;
                const SNAP_THRESHOLD = 125;

                let isDragging = false;
                let startX = 0;
                let startWidth = DEFAULT_WIDTH;

                function applyState(width, isCollapsed, persist = true) {
                    const finalWidth = isCollapsed ? COLLAPSED_WIDTH : Math.min(Math.max(width, MIN_EXPANDED), MAX_WIDTH);

                    sidebar.style.width = finalWidth + 'px';
                    document.documentElement.style.setProperty('--sidebar-w', finalWidth + 'px');

                    if (isCollapsed) {
                        sidebar.classList.add('is-mini');
                        sidebar.setAttribute('data-collapsed', 'true');
                        document.documentElement.classList.add('sidebar-is-collapsed');
                    } else {
                        sidebar.classList.remove('is-mini');
                        sidebar.setAttribute('data-collapsed', 'false');
                        document.documentElement.classList.remove('sidebar-is-collapsed');
                    }

                    if (persist) {
                        localStorage.setItem('kinpro_sidebar_width', finalWidth);
                        localStorage.setItem('kinpro_sidebar_collapsed', isCollapsed ? 'true' : 'false');
                    }
                }

                function toggleSidebar() {
                    const isCollapsed = sidebar.classList.contains('is-mini') || sidebar.getAttribute('data-collapsed') === 'true';
                    sidebar.classList.add('transition-[width]', 'duration-200');

                    if (isCollapsed) {
                        const savedLast = parseInt(localStorage.getItem('kinpro_sidebar_last_expanded')) || DEFAULT_WIDTH;
                        const targetW = (savedLast >= MIN_EXPANDED && savedLast <= MAX_WIDTH) ? savedLast : DEFAULT_WIDTH;
                        applyState(targetW, false, true);
                    } else {
                        const currentW = sidebar.getBoundingClientRect().width;
                        if (currentW >= MIN_EXPANDED) {
                            localStorage.setItem('kinpro_sidebar_last_expanded', currentW);
                        }
                        applyState(COLLAPSED_WIDTH, true, true);
                    }
                }

                // Klik header brand untuk toggle perkecil/perbesar
                if (brandHeader) {
                    brandHeader.addEventListener('click', function(e) {
                        e.stopPropagation();
                        toggleSidebar();
                    });
                }

                // Saat sidebar dalam mode kecil (mini), klik di area sidebar manapun (selain klik link) otomatis perbesar
                sidebar.addEventListener('click', function(e) {
                    if (e.target.closest('a')) return;
                    if (e.target.closest('#sidebar-resizer')) return;

                    const isCollapsed = sidebar.classList.contains('is-mini') || sidebar.getAttribute('data-collapsed') === 'true';
                    if (isCollapsed) {
                        toggleSidebar();
                    }
                });

                resizer.addEventListener('dblclick', toggleSidebar);

                window.addEventListener('keydown', function(e) {
                    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b' && !['INPUT', 'TEXTAREA'].includes(document.activeElement?.tagName)) {
                        e.preventDefault();
                        toggleSidebar();
                    }
                });

                function startDrag(clientX) {
                    isDragging = true;
                    startX = clientX;
                    startWidth = sidebar.getBoundingClientRect().width;
                    sidebar.classList.remove('transition-[width]', 'duration-200');
                    document.body.classList.add('is-resizing');
                }

                function moveDrag(clientX) {
                    if (!isDragging) return;
                    const delta = clientX - startX;
                    const targetWidth = startWidth + delta;

                    if (targetWidth < SNAP_THRESHOLD) {
                        applyState(COLLAPSED_WIDTH, true, false);
                    } else {
                        const clamped = Math.min(Math.max(targetWidth, MIN_EXPANDED), MAX_WIDTH);
                        applyState(clamped, false, false);
                    }
                }

                function stopDrag() {
                    if (!isDragging) return;
                    isDragging = false;
                    document.body.classList.remove('is-resizing');
                    sidebar.classList.add('transition-[width]', 'duration-200');

                    const currentW = sidebar.getBoundingClientRect().width;
                    if (currentW <= SNAP_THRESHOLD) {
                        applyState(COLLAPSED_WIDTH, true, true);
                    } else {
                        const clamped = Math.min(Math.max(currentW, MIN_EXPANDED), MAX_WIDTH);
                        localStorage.setItem('kinpro_sidebar_last_expanded', clamped);
                        applyState(clamped, false, true);
                    }
                }

                resizer.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    startDrag(e.clientX);
                });

                window.addEventListener('mousemove', function(e) {
                    moveDrag(e.clientX);
                });

                window.addEventListener('mouseup', stopDrag);

                // Touch support
                resizer.addEventListener('touchstart', function(e) {
                    if (e.touches.length !== 1) return;
                    startDrag(e.touches[0].clientX);
                }, { passive: true });

                window.addEventListener('touchmove', function(e) {
                    if (!isDragging || e.touches.length !== 1) return;
                    moveDrag(e.touches[0].clientX);
                }, { passive: true });

                window.addEventListener('touchend', stopDrag);

                // Init from storage
                const savedWidth = parseInt(localStorage.getItem('kinpro_sidebar_width'));
                const isInitiallyCollapsed = localStorage.getItem('kinpro_sidebar_collapsed') === 'true';

                if (isInitiallyCollapsed || (savedWidth && savedWidth <= SNAP_THRESHOLD)) {
                    applyState(COLLAPSED_WIDTH, true, false);
                } else if (savedWidth) {
                    applyState(savedWidth, false, false);
                }
            })();
        </script>
    </body>
</html>
