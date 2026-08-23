{{--
    Layout utama untuk halaman INTERNAL aplikasi: Dashboard Siswa, Mentor, Sekolah, Admin.
    Menginclude: Navbar App (Topbar) + Sidebar App + Footer App (minimalis).
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description ?? 'KawanNalar — Platform Belajar Berpikir Kritis untuk Anak' }}">
    <title>{{ $title ?? 'KawanNalar' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>

<body class="bg-[#F4F7FA] font-sans antialiased">

    <div class="min-h-screen flex flex-col">

        <!-- Topbar -->
        <x-navbar-app />

        <div class="flex flex-1">

            <!-- Sidebar -->
            <x-sidebar-app />

            <!-- Main Content -->
            <main class="flex-1 min-h-screen bg-gray-50 pt-20 transition-all duration-300 lg:ml-72">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 md:py-8 lg:px-8">
                    {{ $slot }}
                </div>

                <!-- Footer App -->
                <x-footer-app />
            </main>
        </div>
    </div>

    <!-- Scripts -->
    @stack('scripts')
</body>

</html>