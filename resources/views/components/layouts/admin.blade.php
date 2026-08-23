@props(['title' => 'Dashboard Admin — KawanNalar'])
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-[#F4F7FA] font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <x-navbar-app />
        <div class="flex flex-1">
            <x-sidebar-app />
            <main class="flex-1 min-h-screen bg-gray-50 pt-20 lg:ml-72">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 md:py-8 lg:px-8">
                    {{ $slot }}
                </div>
                <x-footer-app />
            </main>
        </div>
    </div>
</body>

</html>