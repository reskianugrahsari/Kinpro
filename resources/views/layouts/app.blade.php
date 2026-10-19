<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KinPro v2') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-slate-950 text-gray-900 dark:text-slate-100 transition-colors duration-200">
        <div class="min-h-screen flex">
            <!-- Sidebar (Solid Blue #0F2747) -->
            <aside class="w-64 bg-[#0F2747] dark:bg-[#071322] text-white flex flex-col shrink-0 shadow-lg border-r border-[#1769AA]/20 dark:border-slate-800">
                <div class="h-16 flex items-center px-6 bg-[#0F2747] border-b border-[#1769AA]/40 text-lg font-bold tracking-wide space-x-3">
                    <img src="{{ asset('images/logo-kinpro.png') }}" alt="KinPro" class="h-8 w-auto object-contain">
                    <div>
                        <span class="text-white font-extrabold tracking-wider block text-base">KinPro</span>
                        <span class="text-[10px] text-slate-300 font-medium block">Bapekom Wil. VIII</span>
                    </div>
                </div>

                <nav class="flex-1 px-4 py-6 space-y-6 text-sm font-medium overflow-y-auto">
                    <!-- Menu Utama -->
                    <div>
                        <div class="px-4 pb-2 text-[10px] uppercase tracking-wider text-slate-400 font-bold">Menu Utama</div>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('dashboard') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <!-- Menu Admin: Kelola & Review Saja -->
                        <div>
                            <div class="px-4 pb-2 text-[10px] uppercase tracking-wider text-slate-400 font-bold">Manajemen & Verifikasi</div>
                            <div class="space-y-1">
                                <a href="{{ route('admin.pegawai.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('admin.pegawai.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    Data Pegawai
                                </a>
                                <a href="{{ route('penilaian.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('penilaian.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    Penilaian Kinerja
                                </a>
                                <a href="{{ route('izin.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('izin.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Kelola Izin
                                    </span>
                                    <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">{{ \App\Models\Izin::where('status', 'pending')->count() }}</span>
                                </a>
                                <a href="{{ route('pengaduan.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('pengaduan.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        Kelola Pengaduan
                                    </span>
                                    <span class="bg-rose-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">{{ \App\Models\Pengaduan::where('status', 'pending')->count() }}</span>
                                </a>
                                <a href="{{ route('absen.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('absen.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Kelola Lupa Absen
                                    </span>
                                    <span class="bg-indigo-400 text-white text-xs px-2 py-0.5 rounded-full font-bold">{{ \App\Models\PengajuanAbsen::where('status', 'pending')->count() }}</span>
                                </a>
                                <a href="{{ route('pajokka.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('pajokka.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Review Perjalanan Dinas
                                    </span>
                                    <span class="bg-emerald-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">{{ \App\Models\Report::where('status', 'draft')->orWhere('status', 'submitted')->count() }}</span>
                                </a>
                                <a href="{{ route('rekap.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('rekap.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    Rekap & Statistik
                                </a>
                                <a href="{{ route('rating.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('rating.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    Rating Pegawai
                                </a>
                            </div>
                        </div>

                        <!-- Master Data -->
                        <div>
                            <div class="px-4 pb-2 text-[10px] uppercase tracking-wider text-slate-400 font-bold">Master Data</div>
                            <div class="space-y-1">
                                <a href="{{ route('admin.unit-kerja.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('admin.unit-kerja.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Master Unit Kerja
                                </a>
                                <a href="{{ route('admin.master-ppk.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('admin.master-ppk.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Master PPK
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Menu Pegawai: Layanan Mandiri Saja -->
                        <div>
                            <div class="px-4 pb-2 text-[10px] uppercase tracking-wider text-slate-400 font-bold">Layanan Pegawai</div>
                            <div class="space-y-1">
                                <a href="{{ route('penilaian.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('penilaian.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Penilaian Saya
                                </a>
                                <a href="{{ route('izin.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('izin.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Pengajuan Izin
                                </a>
                                <a href="{{ route('absen.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('absen.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Pengajuan Lupa Absen
                                </a>
                                <a href="{{ route('pengaduan.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('pengaduan.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Pengaduan Saya
                                </a>
                                <a href="{{ route('pajokka.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('pajokka.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Laporan Perjalanan Dinas
                                </a>
                                <a href="{{ route('rating.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('rating.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    Rating Pegawai
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Akun Personal -->
                    <div>
                        <div class="px-4 pb-2 text-[10px] uppercase tracking-wider text-slate-400 font-bold">Personal</div>
                        <div class="space-y-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('profile.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil Saya
                            </a>
                        </div>
                    </div>
                </nav>

                <div class="p-4 border-t border-[#1769AA]/40 text-xs text-slate-300 flex items-center justify-between bg-[#0B132B]">
                    <div>Role: <span class="font-bold uppercase text-white">{{ Auth::user()->role ?? 'User' }}</span></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-rose-300 hover:text-white transition font-medium">Logout</button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Top Header -->
                <header class="bg-white dark:bg-[#071322] border-b border-slate-200/80 dark:border-slate-800/90 min-h-[4.25rem] px-8 py-3 flex items-center justify-between shadow-xs transition-colors">
                    <div>
                        @if (isset($header))
                            {{ $header }}
                        @else
                            <h2 class="font-bold text-xl text-[#0F172A] dark:text-white leading-tight">
                                Dashboard
                            </h2>
                        @endif
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Theme Toggle Button (Dark / Light) -->
                        <button type="button" 
                                onclick="toggleTheme()" 
                                class="w-10 h-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/80 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-amber-300 rounded-xl flex items-center justify-center transition shadow-xs cursor-pointer" 
                                title="Ganti Tema (Dark/Light)">
                            <!-- Sun Icon (Tampil saat Dark) -->
                            <svg id="theme-icon-sun" class="w-5 h-5 hidden dark:block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <!-- Moon Icon (Tampil saat Light) -->
                            <svg id="theme-icon-moon" class="w-5 h-5 block dark:hidden text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>

                        <!-- Notification Button -->
                        <button type="button" class="w-10 h-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/80 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl flex items-center justify-center relative transition shadow-xs cursor-pointer" title="Notifikasi">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white dark:ring-slate-900"></span>
                        </button>

                        <!-- Home Button -->
                        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/80 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl flex items-center justify-center transition shadow-xs" title="Beranda">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </a>

                        <!-- Divider -->
                        <div class="h-7 w-px bg-slate-200 dark:bg-slate-700 mx-1"></div>

                        <!-- User Profile Pill Dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 rounded-xl px-3 py-1.5 flex items-center space-x-3 transition shadow-xs cursor-pointer">
                                    @php
                                        $uPhoto = Auth::user()->pegawai?->foto_url;
                                    @endphp
                                    @if($uPhoto)
                                        <img src="{{ $uPhoto }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-slate-700" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <img src="{{ asset('images/default-avatar.svg') }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-slate-700 hidden">
                                    @else
                                        <img src="{{ asset('images/default-avatar.svg') }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-slate-700">
                                    @endif
                                    <div class="text-left hidden sm:block">
                                        <div class="text-xs font-bold text-[#0F172A] dark:text-slate-100 leading-tight">{{ Auth::user()->name }}</div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-400 font-medium leading-tight mt-0.5">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Pegawai' }}</div>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 ml-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700">
                                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ Auth::user()->name }}</div>
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
                <main class="flex-1 p-8 bg-gray-50 dark:bg-slate-900/60 overflow-y-auto transition-colors">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script>
            function toggleTheme() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
            }
        </script>
    </body>
</html>
