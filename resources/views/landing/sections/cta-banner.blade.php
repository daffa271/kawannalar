{{--
    CTA Banner — Landing Page KawanNalar.
    Banner terakhir sebelum footer, aksen warna #F28C28.
    Ajakan daftar + ilustrasi placeholder.
--}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="relative overflow-hidden rounded-2xl border border-[#F4D9A6] bg-[#FFF4DE]">

        {{-- Decorative blobs --}}
        <div class="absolute left-0 top-0 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-accent/10"></div>
        <div class="absolute bottom-0 right-0 h-96 w-96 translate-x-1/3 translate-y-1/3 rounded-full bg-cta/10"></div>

        <div class="relative z-10 grid lg:grid-cols-2 gap-8 lg:gap-12 items-center px-8 lg:px-16 py-12 lg:py-16">

            {{-- Left: Ilustrasi --}}
            <div class="hidden lg:flex justify-center">
                <div class="relative w-64 h-64">
                    {{-- Placeholder ilustrasi: dekorasi geometris --}}
                    <div class="absolute inset-0 flex items-center justify-center rounded-3xl bg-cta/10">
                        <svg class="h-32 w-32 text-cta/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    {{-- Floating badge --}}
                    <div class="absolute -top-3 -right-3 bg-white rounded-2xl px-4 py-2 shadow-lg">
                        <p class="text-xs font-bold text-[#0A52C4]">Kemudahan Belajar!</p>
                    </div>
                    <div class="absolute -bottom-3 -left-3 bg-[#FFC000] rounded-2xl px-4 py-2 shadow-lg">
                        <p class="text-xs font-bold text-white">100% Tanpa Biaya</p>
                    </div>
                </div>
            </div>

            {{-- Right: Copy + CTA --}}
            <div class="text-center text-gray-900 lg:text-left">
                <h2 class="text-3xl lg:text-4xl font-bold mb-4 leading-tight">
                    Siap Memulai Perjalananmu<br>
                    bersama <span class="text-primary">KawanNalar</span>?
                </h2>
                <p class="mx-auto mb-8 max-w-md text-base leading-relaxed text-gray-600 lg:mx-0 lg:text-lg">
                    Ribuan siswa Magetan sudah bergabung. Sekarang giliranmu untuk meraih mimpi di PTN favorit!
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a
                        href="{{ route('register') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-cta px-6 py-3 text-xs font-bold text-white transition-all hover:bg-cta-dark hover:shadow-xl"
                        style="box-shadow: 0 4px 14px rgba(0,0,0,0.15);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Daftar Gratis Sekarang
                    </a>
                    <a
                        href="#fitur"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-primary/40 bg-white/60 px-6 py-3 text-xs font-semibold text-primary transition-all hover:border-primary hover:bg-white">
                        Pelajari Fitur Kami
                    </a>
                </div>

                {{-- Trust micro-badges --}}
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4 text-sm text-gray-500 lg:justify-start">
                    <span class="flex items-center gap-1.5">
                        <svg class="h-4 w-4 text-accent-dark" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Belajar Terintegrasi
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="h-4 w-4 text-accent-dark" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Data aman & privat
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="h-4 w-4 text-accent-dark" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Langsung dapat mentor
                    </span>
                </div>
            </div>

        </div>
    </div>
</section>