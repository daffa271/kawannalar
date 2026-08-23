{{--
    Testimonials Section — Landing Page KawanNalar.
    3 Cards testimoni dari siswa & alumni.
    Placeholder avatar (gradient initials).

    Spacing dikencangkan dari versi awal (padding & margin terlalu
    longgar bikin section ini terasa "berat" dan turut memaksa
    total tinggi halaman jadi lebih panjang dari perlu).
--}}
<section class="bg-white pt-16 lg:pt-20 pb-16 lg:pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="text-center max-w-xl mx-auto mb-10 lg:mb-12">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-[#FFC000]/20 text-[#0A52C4] rounded-full text-xs sm:text-sm font-semibold mb-4">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                </svg>
                Testimoni
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3">
                Cerita Mereka, <span class="text-[#F28C28]">Inspirasi</span> Kita
            </h2>
            <p class="text-sm sm:text-base text-gray-600">
                Dengarkan pengalaman siswa & mentor dalam perjalanan mereka bersama KawanNalar.
            </p>
        </div>

        {{--
            NOTE: 3 testimoni di bawah ini contoh/placeholder.
            Ganti dengan data asli (Testimonial::latest()->take(3)->get() dari Controller).
        --}}
        @php
            $testimonials = [
                [
                    'quote' => 'KawanNalar membantu saya memahami cara belajar efektif dan memberi saya mentor hebat. Alhamdulillah saya lolos SNBT di ITS!',
                    'name' => 'Alya Putri',
                    'role' => 'ITS — Teknik Informatika',
                    'school' => 'Alumni SMAN 1 Magetan',
                    'avatar_bg' => 'bg-gradient-to-br from-[#0A52C4] to-[#0842A0]',
                    'avatar_init' => 'AP',
                ],
                [
                    'quote' => 'Menjadi mentor di KawanNalar membuat saya bisa berbagi pengalaman dan ikut membantu adik-adik meraih mimpinya di PTN.',
                    'name' => 'Rizky Maulana',
                    'role' => 'UGM — Kedokteran',
                    'school' => 'Mentor Aktif KawanNalar',
                    'avatar_bg' => 'bg-gradient-to-br from-[#F28C28] to-[#E07D1C]',
                    'avatar_init' => 'RM',
                ],
                [
                    'quote' => 'Platform ini sangat lengkap dan mudah digunakan. Semua info penting — dari beasiswa sampai tryout — ada di satu tempat!',
                    'name' => 'Dewi Anggraini',
                    'role' => 'Siswa Kelas 12 IPA',
                    'school' => 'SMAN 2 Magetan',
                    'avatar_bg' => 'bg-gradient-to-br from-[#FFC000] to-[#E6AC00]',
                    'avatar_init' => 'DA',
                ],
            ];
        @endphp

        <div class="grid md:grid-cols-3 gap-4 lg:gap-5">
            @foreach ($testimonials as $t)
                <div class="bg-white rounded-2xl border border-gray-100 p-5 lg:p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col gap-4">
                    {{-- Quote Icon --}}
                    <div class="w-9 h-9 bg-[#0A52C4]/10 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#0A52C4]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    {{-- Quote Text --}}
                    <p class="text-sm text-gray-700 leading-relaxed flex-1 line-clamp-4">
                        "{{ $t['quote'] }}"
                    </p>

                    {{-- Author --}}
                    <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                        {{-- Avatar Placeholder --}}
                        <div class="w-10 h-10 rounded-xl {{ $t['avatar_bg'] }} flex items-center justify-center text-white font-bold text-xs shadow-sm shrink-0">
                            {{ $t['avatar_init'] }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-gray-900 truncate">{{ $t['name'] }}</p>
                            <p class="text-xs text-[#0A52C4] font-medium truncate">{{ $t['role'] }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $t['school'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Carousel Dots (visual only) --}}
        <div class="flex justify-center gap-2 mt-6 lg:mt-8">
            <span class="w-2.5 h-2.5 rounded-full bg-[#F28C28]"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-gray-300 hover:bg-gray-400 cursor-pointer transition-colors"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-gray-300 hover:bg-gray-400 cursor-pointer transition-colors"></span>
        </div>
    </div>
</section>