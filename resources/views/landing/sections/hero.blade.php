{{-- Hero Section — Landing Page KawanNalar --}}
<section id="beranda" class="relative pt-3 lg:pt-5 pb-6 lg:pb-8 overflow-hidden min-h-[calc(100vh-80px)] flex items-center">
    {{-- Background Gradient --}}
    <div class="absolute inset-0 bg-gradient-to-br from-[#F4F7FA] via-white to-[#F4F7FA] -z-10"></div>

    {{-- Decorative Blurs --}}
    <div class="absolute top-10 left-10 w-72 h-72 bg-[#0A52C4]/10 rounded-full blur-3xl -z-10"></div>
    <div class="absolute bottom-5 right-10 w-96 h-96 bg-[#F28C28]/10 rounded-full blur-3xl -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 w-full">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">

            {{-- Left Side: Copy --}}
            <div class="text-center lg:text-left">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 border border-[#0A52C4]/30 text-[#0A52C4] rounded-full text-xs sm:text-sm font-semibold mb-4">
                    <span class="w-2 h-2 bg-[#0A52C4] rounded-full animate-pulse"></span>
                    Platform Pendampingan Pendidikan untuk Siswa Magetan
                </div>

                {{-- Headline (Rapi sesuai desain UI/UX) --}}
                <h1 class="mb-3 text-3xl font-extrabold leading-tight text-gray-900 sm:text-4xl lg:text-[40px]">
                    Bersama <span class="text-[#0A52C4]">KawanNalar</span><br class="hidden sm:block" />
                    Belajar Bersama, <span class="text-[#F28C28]">Raih Impian</span>
                </h1>

                {{-- Subtitle --}}
                <p class="mx-auto mb-6 max-w-lg text-xs sm:text-sm leading-relaxed text-gray-600 lg:mx-0 lg:text-base">
                    Platform gratis untuk siswa SMA/MA/SMK Magetan.<br class="hidden sm:block">
                    Dapatkan pendampingan dari <strong class="text-gray-800">mentor mahasiswa</strong>, persiapan UTBK, informasi beasiswa, hingga AI Assistant untuk wujudkan mimpi kuliah di PTN favorit.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                    <a
                        href="{{ route('register') }}"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-[#F28C28] hover:bg-[#E07D1C] text-white font-bold rounded-2xl transition-all hover:-translate-y-0.5 hover:shadow-lg text-sm sm:text-base"
                        style="box-shadow: 0 4px 14px rgba(242,140,40,0.4);">
                        Mulai Perjalananmu
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a
                        href="#fitur"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white hover:bg-[#F4F7FA] text-[#0A52C4] font-bold rounded-2xl border-2 border-[#0A52C4]/20 hover:border-[#0A52C4]/40 transition-all hover:-translate-y-0.5 text-sm sm:text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Lihat Demo Platform
                    </a>
                </div>

                {{-- Social Proof / Benefits --}}
                <div class="mt-8 flex flex-wrap items-center justify-center lg:justify-start gap-5 text-xs sm:text-sm text-gray-500">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#F28C28]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>100% Gratis</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#F28C28]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Sistem Terintegrasi</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#F28C28]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Tanpa Batas Akses</span>
                    </div>
                </div>
            </div>

            {{-- Right Side: Ilustrasi & Floating Cards --}}
            <div class="relative">
                <div class="relative z-10">
                    <div class="bg-gradient-to-br from-[#F4F7FA] to-white rounded-3xl p-6 lg:p-8 shadow-2xl" style="box-shadow: 0 20px 40px -10px rgba(10,82,196,0.12);">
                        <div class="aspect-[4/3] max-w-md mx-auto">
                            <img
                                src="{{ asset('images/anaksma.png') }}"
                                alt="Tiga siswa SMA penuh semangat belajar"
                                class="w-full h-full object-contain object-center">
                        </div>

                        {{-- Floating Card 1 --}}
                        <div class="absolute -top-3 -right-2 bg-white rounded-2xl shadow-md p-3.5 animate-bounce" style="animation-duration: 3s;">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 bg-[#F28C28]/10 rounded-xl flex items-center justify-center">
                                    <svg class="w-4 h-4 text-[#F28C28]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-800">Mentor PTN</p>
                                    <p class="text-[10px] text-gray-400">Siap membimbingmu</p>
                                </div>
                            </div>
                        </div>

                        {{-- Floating Card 2 --}}
                        <div class="absolute -bottom-3 -left-2 bg-white rounded-2xl shadow-md p-3.5 animate-bounce" style="animation-duration: 4s; animation-delay: 1.5s;">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 bg-[#FFC000]/20 rounded-xl flex items-center justify-center">
                                    <svg class="w-4 h-4 text-[#FFC000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-800">AI 24/7</p>
                                    <p class="text-[10px] text-gray-400">Bantu jawab kapan saja</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>