{{-- Hero Section — Landing Page KawanNalar --}}
<section id="beranda" class="relative pt-3 lg:pt-5 pb-6 lg:pb-8 overflow-hidden min-h-[calc(100vh-80px)] flex items-center">
    {{-- Background Gradient --}}
    <div class="absolute inset-0 bg-gradient-to-br from-[#F4F7FA] via-white to-[#F4F7FA] -z-10"></div>

    {{-- Decorative Blurs (tint brand, sangat lembut) --}}
    <div class="absolute top-10 left-10 w-72 h-72 bg-primary/10 rounded-full blur-3xl -z-10"></div>
    <div class="absolute bottom-5 right-10 w-96 h-96 bg-cta/10 rounded-full blur-3xl -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 w-full">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">

            {{-- Left Side: Copy --}}
            <div class="text-center lg:text-left">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 border border-primary/30 text-primary rounded-full text-xs sm:text-sm font-semibold mb-4">
                    <span class="w-2 h-2 bg-primary rounded-full"></span>
                    Platform Pendampingan Pendidikan untuk Siswa Magetan
                </div>

                {{-- Headline --}}
                <h1 class="mb-3 text-3xl font-extrabold leading-tight text-gray-900 sm:text-4xl lg:text-[40px]">
                    Bersama <span class="text-primary">KawanNalar</span><br class="hidden sm:block" />
                    Belajar Bersama, <span class="text-cta">Raih Impian</span>
                </h1>

                {{-- Subtitle --}}
                <p class="mx-auto mb-6 max-w-lg text-xs sm:text-sm leading-relaxed text-gray-600 lg:mx-0 lg:text-base">
                    Platform gratis untuk siswa SMA/MA/SMK Magetan.<br class="hidden sm:block">
                    Dapatkan pendampingan dari <strong class="text-gray-800">mentor mahasiswa</strong>, persiapan UTBK, dan modul belajar terverifikasi untuk wujudkan mimpi kuliah di PTN favorit. NalarBot AI segera hadir.
                </p>

                {{-- CTA Buttons: oranye = aksi utama (teks navy, kontras 6,7:1) --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                    <a
                        href="{{ route('register') }}"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-cta hover:bg-cta-dark text-navy font-bold rounded-2xl shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md text-sm sm:text-base">
                        Mulai Perjalananmu
                        <x-icon name="arrow_forward" class="h-5 w-5" />
                    </a>
                    <a
                        href="#fitur"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white hover:bg-surface text-primary font-bold rounded-2xl border-2 border-primary/20 hover:border-primary/40 transition-all hover:-translate-y-0.5 text-sm sm:text-base">
                        <x-icon name="play_circle" class="h-5 w-5" />
                        Lihat Fitur Platform
                    </a>
                </div>

                {{-- Benefits --}}
                <div class="mt-8 flex flex-wrap items-center justify-center lg:justify-start gap-x-5 gap-y-2 text-xs sm:text-sm text-gray-500">
                    @foreach (['100% Gratis', 'Sistem Terintegrasi', 'Tanpa Batas Akses'] as $benefit)
                        <div class="flex items-center gap-1.5">
                            <x-icon name="check_circle" class="h-4 w-4 text-primary" />
                            <span>{{ $benefit }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right Side: Ilustrasi --}}
            <div class="relative">
                <div class="rounded-3xl bg-gradient-to-br from-surface to-white p-6 lg:p-8" style="box-shadow: 0 20px 40px -10px rgba(10,82,196,0.12);">
                    <div class="aspect-[4/3] max-w-md mx-auto">
                        <img
                            src="{{ asset('images/anaksma.png') }}"
                            alt="Tiga siswa SMA penuh semangat belajar"
                            class="w-full h-full object-contain object-center">
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
