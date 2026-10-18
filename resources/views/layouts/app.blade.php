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
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-900">
        <div class="min-h-screen flex">
            <!-- Sidebar (Solid Blue #0F2747) -->
            <aside class="w-64 bg-[#0F2747] text-white flex flex-col shrink-0 shadow-lg">
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

                    <!-- Manajemen -->
                    <div>
                        <div class="px-4 pb-2 text-[10px] uppercase tracking-wider text-slate-400 font-bold">Manajemen & Direktori</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.pegawai.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('admin.pegawai.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Data Pegawai
                            </a>
                            <a href="{{ route('penilaian.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('penilaian.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Penilaian Kinerja
                            </a>
                            <a href="{{ route('pengaduan.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('pengaduan.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Pengaduan
                                </span>
                                <span class="bg-rose-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">10</span>
                            </a>
                            <a href="{{ route('izin.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('izin.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Kelola Izin
                                </span>
                                <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">4</span>
                            </a>
                            <a href="{{ route('rekap.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('rekap.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Rekap & Statistik
                            </a>
                            <a href="{{ route('rating.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('rating.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                Rating Pegawai
                            </a>
                            @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.unit-kerja.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('admin.unit-kerja.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Master Unit Kerja
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Personal -->
                    <div>
                        <div class="px-4 pb-2 text-[10px] uppercase tracking-wider text-slate-400 font-bold">Personal</div>
                        <div class="space-y-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('profile.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil Saya
                            </a>
                            <a href="{{ route('penilaian.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition text-slate-200">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Penilaian Saya
                            </a>
                            <a href="{{ route('absen.index') }}" class="flex items-center px-4 py-2.5 rounded-xl hover:bg-[#1769AA] transition {{ request()->routeIs('absen.*') ? 'bg-[#1769AA] text-white font-semibold' : 'text-slate-200' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pengajuan Izin / Absen
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
                <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo-kinpro.png') }}" alt="KinPro" class="h-8 w-auto object-contain">
                        <h2 class="font-semibold text-xl text-[#0F2747] leading-tight">
                            {{ $header ?? 'Dashboard' }}
                        </h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</span>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-8 bg-gray-50 overflow-y-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
