{{-- Section: Testimoni — id="testimoni" --}}
<section id="testimoni" class="bg-blue-50 pt-16 lg:pt-20 pb-16 lg:pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="text-center max-w-xl mx-auto mb-12 lg:mb-14">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-white text-blue-600 rounded-full text-xs sm:text-sm font-semibold mb-4 shadow-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                </svg>
                Testimoni
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 mb-3">
                Cerita Nyata dari <span class="text-blue-600">Pengguna Kami</span>
            </h2>
            <p class="text-sm sm:text-base text-gray-500 leading-relaxed">
                Dengarkan pengalaman siswa & mentor dalam perjalanan mereka bersama KawanNalar.
            </p>
        </div>

        @php
            $testimonials = [
                [
                    'quote'      => 'Berkat KawanNalar, saya bisa akses modul UTBK-SNBT kapan saja. Mentor yang saya dapat juga super sabar dan tahu betul trik lolos seleksi. Alhamdulillah saya diterima di ITS Teknik Informatika!',
                    'name'       => 'Alya Putri Ramadhani',
                    'role'       => 'Mahasiswi ITS — Teknik Informatika 2024',
                    'school'     => 'Alumni SMAN 1 Magetan',
                    'avatar_bg'  => 'bg-gradient-to-br from-blue-500 to-blue-700',
                    'avatar_init'=> 'AP',
                    'stars'      => 5,
                    'tag'        => '🎓 Siswa',
                    'tag_color'  => 'bg-blue-100 text-blue-700',
                ],
                [
                    'quote'      => 'Menjadi mentor di KawanNalar adalah pengalaman yang sangat bermakna. Saya bisa berkontribusi langsung bagi adik-adik di Magetan dan membantu mereka menapaki jalur yang sama seperti saya dulu.',
                    'name'       => 'Rizky Maulana Pratama',
                    'role'       => 'Mahasiswa UGM — Kedokteran 2023',
                    'school'     => 'Mentor Aktif KawanNalar',
                    'avatar_bg'  => 'bg-gradient-to-br from-green-500 to-green-700',
                    'avatar_init'=> 'RM',
                    'stars'      => 5,
                    'tag'        => '👨‍🏫 Mentor',
                    'tag_color'  => 'bg-green-100 text-green-700',
                ],
                [
                    'quote'      => 'Fitur Uji Nalar dengan XP dan leaderboard bikin saya makin semangat belajar! Saya jadi kompetitif tapi tetap asik. Platform ini lengkap dan mudah dipakai, recommended banget buat siswa SMA!',
                    'name'       => 'Dewi Anggraini',
                    'role'       => 'Siswa Kelas 12 IPA',
                    'school'     => 'SMAN 2 Magetan',
                    'avatar_bg'  => 'bg-gradient-to-br from-purple-500 to-purple-700',
                    'avatar_init'=> 'DA',
                    'stars'      => 5,
                    'tag'        => '📚 Siswa',
                    'tag_color'  => 'bg-purple-100 text-purple-700',
                ],
            ];
        @endphp

        <div class="grid md:grid-cols-3 gap-5 lg:gap-6">
            @foreach ($testimonials as $t)
                <div class="bg-white rounded-3xl border border-blue-100 p-6 lg:p-7 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col gap-5 group">

                    {{-- Tag & Stars --}}
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-3 py-1 {{ $t['tag_color'] }} rounded-full text-xs font-semibold">
                            {{ $t['tag'] }}
                        </span>
                        <div class="flex items-center gap-0.5">
                            @for ($i = 0; $i < $t['stars']; $i++)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>

                    {{-- Quote --}}
                    <div class="relative">
                        <svg class="absolute -top-1 -left-1 w-8 h-8 text-blue-100" fill="currentColor" viewBox="0 0 32 32">
                            <path d="M10 8C6.686 8 4 10.686 4 14v10h10V14H7c0-1.657 1.343-3 3-3V8zm14 0c-3.314 0-6 2.686-6 6v10h10V14h-7c0-1.657 1.343-3 3-3V8z"/>
                        </svg>
                        <p class="text-sm text-gray-700 leading-relaxed pl-6">
                            {{ $t['quote'] }}
                        </p>
                    </div>

                    {{-- Author --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100 mt-auto">
                        <div class="w-11 h-11 rounded-2xl {{ $t['avatar_bg'] }} flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0 group-hover:scale-110 transition-transform duration-200">
                            {{ $t['avatar_init'] }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-gray-900 truncate">{{ $t['name'] }}</p>
                            <p class="text-xs text-blue-600 font-medium truncate">{{ $t['role'] }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $t['school'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CTA Below --}}
        <div class="text-center mt-12">
            <p class="text-sm text-gray-500 mb-4">Siap bergabung dan menjadi bagian dari cerita sukses berikutnya?</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-7 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all hover:-translate-y-0.5 shadow-lg hover:shadow-blue-200 text-sm">
                Mulai Sekarang — Gratis!
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
</section>