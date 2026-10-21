<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('images/logo-kinpro.png') }}?v={{ file_exists(public_path('images/logo-kinpro.png')) ? filemtime(public_path('images/logo-kinpro.png')) : time() }}" alt="KinPro" class="block h-10 w-auto object-contain">
                        <span class="font-bold text-lg text-[#0F2747]">KinPro</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('penilaian.index')" :active="request()->routeIs('penilaian.*')">
                        {{ __('Penilaian Kinerja') }}
                    </x-nav-link>

                    <x-nav-link :href="route('izin.index')" :active="request()->routeIs('izin.*')">
                        {{ __('Izin / Cuti') }}
                    </x-nav-link>

                    <x-nav-link :href="route('pengaduan.index')" :active="request()->routeIs('pengaduan.*')">
                        {{ __('Pengaduan') }}
                    </x-nav-link>

                    <x-nav-link :href="route('rating.index')" :active="request()->routeIs('rating.*')">
                        {{ __('Rating Pegawai') }}
                    </x-nav-link>

                    <x-nav-link :href="route('absen.index')" :active="request()->routeIs('absen.*')">
                        {{ __('Lupa Absen') }}
                    </x-nav-link>

                    <x-nav-link :href="route('pajokka.index')" :active="request()->routeIs('pajokka.*')">
                        {{ __('Perjalanan Dinas') }}
                    </x-nav-link>

                    @if(auth()->user()->role === 'admin')
                    <x-nav-link :href="route('admin.unit-kerja.index')" :active="request()->routeIs('admin.*')">
                        {{ __('Master Data') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.293 9.707a1px 1px 0 010-1.414l3-3a1px 1px 0 011.414 0l3 3a1px 1px 0 01-1.414 1.414L9 8.414V13a1px 1px 0 11-2 0V8.414l-1.293 1.293a1px 1px 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log in/out' == 'Log in/out' ? 'Log Out' : __('Log Out')) }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <main-responsive-nav :open="open" />
</nav>
