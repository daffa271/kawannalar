<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary to-primary-dark">

        {{-- Dekorasi lingkaran tipis (tint putih, tanpa warna tambahan) --}}
        <div class="absolute -left-20 -top-24 h-72 w-72 rounded-full border-[40px] border-white/5"></div>
        <div class="absolute -bottom-28 -right-16 h-80 w-80 rounded-full border-[40px] border-white/5"></div>

        <div class="relative z-10 mx-auto max-w-2xl px-6 py-12 text-center sm:px-10 lg:py-16">
            <h2 class="mb-4 text-2xl font-bold leading-tight text-white sm:text-3xl lg:text-4xl">
                Siap Memulai Perjalananmu bersama KawanNalar?
            </h2>
            <p class="mx-auto mb-8 max-w-md text-sm leading-relaxed text-white/80 sm:text-base">
                Ribuan siswa Magetan sudah bergabung. Sekarang giliranmu untuk meraih mimpi di PTN favorit!
            </p>

            {{-- CTA: oranye = aksi utama, outline putih = aksi kedua --}}
            <div class="flex flex-col justify-center gap-3 sm:flex-row sm:gap-4">
                <a
                    href="{{ route('register') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-cta px-6 py-3.5 text-sm font-bold text-navy shadow-sm transition-all hover:-translate-y-0.5 hover:bg-cta-dark hover:shadow-md">
                    <x-icon name="person_add" class="h-5 w-5" />
                    Daftar Gratis Sekarang
                </a>
                <a
                    href="#fitur"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/40 px-6 py-3.5 text-sm font-semibold text-white transition-all hover:border-white hover:bg-white/10">
                    Pelajari Fitur Kami
                </a>
            </div>

            {{-- Trust micro-badges --}}
            <div class="mt-8 flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-xs text-white/80 sm:text-sm">
                @foreach (['Belajar Terintegrasi', 'Data aman & privat', 'Langsung dapat mentor'] as $trust)
                    <span class="flex items-center gap-1.5">
                        <x-icon name="check_circle" class="h-4 w-4" />
                        {{ $trust }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</section>
