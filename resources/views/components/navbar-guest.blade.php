{{--
    Navbar publik: Landing Page, Login, Register.
    Menu: Beranda | Fitur | Tentang Kami | Testimoni | Hubungi Kami
    CTA: Masuk (outline) | Daftar (blue-600 primary)
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
                alt="KawanNalar"
                class="h-full w-full object-contain transition-transform duration-200 group-hover:scale-[1.02]">
        </a>

        {{-- Desktop Nav --}}
        <nav class="hidden lg:flex items-center gap-6 xl:gap-8 text-xs xl:text-sm font-medium text-gray-600">
            <a href="#" class="text-blue-600 font-semibold hover:text-blue-700 transition-colors">Beranda</a>
            <a href="#fitur" class="hover:text-blue-600 transition-colors">Fitur</a>
            <a href="#tentang-kami" class="hover:text-blue-600 transition-colors">Tentang Kami</a>
            <a href="#testimoni" class="hover:text-blue-600 transition-colors">Testimoni</a>
            <a href="#hubungi-kami" class="hover:text-blue-600 transition-colors">Hubungi Kami</a>
        </nav>

        {{-- Desktop CTA --}}
        <div class="hidden lg:flex items-center gap-3">
            <a
                href="{{ route('login') }}"
                class="px-5 py-2.5 rounded-lg border border-blue-600/40 text-blue-600 text-xs xl:text-sm font-semibold hover:border-blue-600 hover:bg-blue-50 transition-all">
                Masuk
            </a>
            <a
                href="{{ route('register') }}"
                class="px-5 py-2.5 rounded-lg bg-blue-600 text-white text-xs xl:text-sm font-semibold hover:bg-blue-700 transition-all shadow-sm hover:shadow-md">
                Daftar
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
        <a href="#" class="text-[#0A52C4] font-semibold py-1" @click="isOpen = false">Beranda</a>
        <a href="#fitur" class="text-gray-600 hover:text-[#0A52C4] py-1" @click="isOpen = false">Fitur</a>
        <a href="#tentang-kami" class="text-gray-600 hover:text-[#0A52C4] py-1" @click="isOpen = false">Tentang Kami</a>
        <a href="#testimoni" class="text-gray-600 hover:text-[#0A52C4] py-1" @click="isOpen = false">Testimoni</a>
        <a href="#hubungi-kami" class="text-gray-600 hover:text-[#0A52C4] py-1" @click="isOpen = false">Hubungi Kami</a>
        <div class="pt-3 border-t border-gray-100 flex flex-col gap-3">
            <a href="{{ route('login') }}" class="text-center py-2.5 rounded-xl border-2 border-[#0A52C4]/20 text-[#0A52C4] font-semibold">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="text-center py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors">
                Daftar
            </a>
        </div>
    </div>
</header>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
