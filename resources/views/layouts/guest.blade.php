<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KinPro') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo-kinpro.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Theme Sync (Follows Admin/Pegawai Light & Dark Theme) -->
        <script>
            (function() {
                const applyTheme = () => {
                    const savedTheme = localStorage.getItem('theme');
                    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                };
                applyTheme();
                window.addEventListener('storage', function(e) {
                    if (e.key === 'theme') applyTheme();
                });
            })();
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-[#0B2A5B] dark:text-slate-100 antialiased bg-[#F7F9FC] dark:bg-[#071121] transition-colors duration-200">
        <x-intro-splash />
        <x-loading-screen />
        <div class="min-h-screen w-full">
            {{ $slot }}
        </div>
    </body>
</html>
