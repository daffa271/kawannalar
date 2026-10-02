@props([
'title' => 'Fitur Sedang Dalam Pengembangan',
'description' => null,
])

<section class="flex min-h-[calc(100vh-15rem)] items-center justify-center py-6 sm:py-10">
    <div class="w-full max-w-2xl rounded-2xl border border-gray-100 bg-white px-5 py-8 text-center shadow-sm sm:px-10 sm:py-12">
        <img
            src="{{ asset('images/maintenancefitur.png') }}"
            alt="Ilustrasi fitur sedang dalam pengembangan"
            class="mx-auto h-auto w-full max-w-[350px] object-contain sm:max-w-[450px]">

        <span class="mt-6 inline-flex items-center gap-1.5 rounded-full bg-[#FFC000]/15 px-3 py-1 text-xs font-extrabold text-[#B27D00]">
            <x-icon name="schedule" class="h-4 w-4" /> Segera Hadir
        </span>
        <h1 class="mt-4 text-xl font-extrabold leading-tight text-gray-900 sm:text-2xl">
            {{ $title }}
        </h1>
        <p class="mx-auto mt-3 max-w-lg text-sm leading-relaxed text-gray-500 sm:text-base">
            {{ $description ?? 'Kami sedang menyiapkan fitur ini agar dapat memberikan pengalaman belajar yang lebih baik untukmu.' }}
        </p>
        <a
            href="{{ route('dashboard') }}"
            class="mt-7 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#0A52C4] px-5 py-3 text-sm font-bold text-white shadow-sm transition-all duration-200 hover:bg-[#08449F] hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#0A52C4]/30 focus:ring-offset-2 sm:w-auto">
            <x-icon name="arrow_back" class="h-4 w-4" />
            Kembali ke Dashboard
        </a>
    </div>
</section>
