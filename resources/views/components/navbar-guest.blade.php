{{--
    Navbar publik: Landing Page, Login, Register.
    Logo + Menu navigasi + Tombol Masuk / Daftar Gratis.
    Sticky, clean white bg, menggunakan warna brand #0A52C4 & #F28C28.
--}}
<header
    x-data="{
        isOpen: false,
        isScrolled: false,
        init() {
            window.addEventListener('scroll', () => {
                this.isScrolled = window.scrollY > 20;
            });
        }
    }"
    class="sticky top-0 z-50 transition-all duration-300 bg-white"
    :class="isScrolled ? 'shadow-md border-b border-gray-100' : 'shadow-none border-b border-gray-100'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 lg:h-[72px] flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('landing') }}" class="flex h-12 w-[138px] shrink-0 items-center group lg:h-14" aria-label="KawanNalar - Beranda">
            <img
                src="{{ asset('images/logokawannalar.jpeg') }}"
                alt="KawanNalar - Belajar Bersama, Raih Impian"
                class="h-full w-full object-contain transition-transform duration-200 group-hover:scale-[1.02]">
        </a>

        {{-- Desktop Nav --}}
        <nav class="hidden lg:flex items-center gap-6 xl:gap-8 text-xs xl:text-sm font-medium text-gray-600">
            <a href="{{ route('landing') }}" class="text-primary font-semibold hover:text-primary-dark transition-colors">Beranda</a>
            <a href="#fitur" class="hover:text-primary transition-colors">Fitur</a>
            <a href="#tentang" class="hover:text-primary transition-colors">Tentang Kami</a>
            <a href="#" class="hover:text-primary transition-colors">Untuk Sekolah</a>
            <a href="#" class="hover:text-primary transition-colors">Jejak Nalar</a>
            <a href="#" class="hover:text-primary transition-colors">Kabar Nalar</a>
        </nav>

        {{-- Desktop CTA --}}
        <div class="hidden lg:flex items-center gap-3">
            <a
                href="{{ route('login') }}"
                class="px-5 py-2.5 rounded-lg border border-primary/40 text-primary text-xs xl:text-sm font-semibold hover:border-primary hover:bg-primary/5 transition-all">
                Masuk
            </a>
            <a
                href="{{ route('register') }}"
                class="px-5 py-2.5 rounded-lg bg-cta text-white text-xs xl:text-sm font-semibold hover:bg-cta-dark transition-all shadow-sm hover:shadow-md"
                style="box-shadow: 0 2px 8px rgba(242,140,40,0.35);">
                Daftar Gratis
            </a>
        </div>

        {{-- Mobile Toggle --}}
        <button
            @click="isOpen = !isOpen"
            class="lg:hidden w-10 h-10 flex flex-col items-center justify-center gap-1.5 rounded-lg hover:bg-gray-50 transition-colors"
            aria-label="Buka menu">
            <span
                x-show="!isOpen"
                class="w-6 h-0.5 bg-gray-600 rounded-full transition-all"></span>
            <span
                x-show="!isOpen"
                class="w-6 h-0.5 bg-gray-600 rounded-full transition-all"></span>
            <span
                x-show="!isOpen"
                class="w-4 h-0.5 bg-gray-600 rounded-full transition-all"></span>
            <svg x-show="isOpen" x-cloak class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div
        x-show="isOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden border-t border-gray-100 bg-white px-6 py-5 flex flex-col gap-4">
        <a href="{{ route('landing') }}" class="text-[#0A52C4] font-semibold py-1">Beranda</a>
        <a href="#fitur" class="text-gray-600 hover:text-[#0A52C4] py-1">Fitur</a>
        <a href="#tentang" class="text-gray-600 hover:text-[#0A52C4] py-1">Tentang Kami</a>
        <a href="#" class="text-gray-600 hover:text-[#0A52C4] py-1">Jejak Nalar</a>
        <a href="#" class="text-gray-600 hover:text-[#0A52C4] py-1">Kabar Nalar</a>
        <div class="pt-3 border-t border-gray-100 flex flex-col gap-3">
            <a href="{{ route('login') }}" class="text-center py-2.5 rounded-xl border-2 border-[#0A52C4]/20 text-[#0A52C4] font-semibold">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="text-center py-3 rounded-xl bg-[#F28C28] text-white font-semibold hover:bg-[#E07D1C] transition-colors">
                Daftar Gratis
            </a>
        </div>
    </div>
</header>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>