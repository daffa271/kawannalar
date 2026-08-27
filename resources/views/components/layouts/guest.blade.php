{{--
    Layout utama untuk semua halaman PUBLIK: Landing Page, Login, Register.
    Menginclude: Navbar Guest + Footer Guest.
--}}
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'KawanNalar — Belajar Bersama, Raih Impian' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased text-gray-800 bg-white font-sans">
    <x-navbar-guest />

    <main>
        {{ $slot }}
    </main>

    <x-footer-guest />
</body>

</html>
