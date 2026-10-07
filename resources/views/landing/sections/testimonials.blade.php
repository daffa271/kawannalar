{{-- Section: Testimoni — id="testimoni" --}}
<section id="testimoni" class="bg-surface pt-16 lg:pt-20 pb-16 lg:pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="text-center max-w-xl mx-auto mb-12 lg:mb-14">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-white text-primary rounded-full text-xs sm:text-sm font-semibold mb-4 shadow-sm">
                <x-icon name="format_quote" class="h-4 w-4" />
                Testimoni
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 mb-3">
                Cerita Nyata dari <span class="text-primary">Pengguna Kami</span>
            </h2>
            <p class="text-sm sm:text-base text-gray-500 leading-relaxed">
                Dengarkan pengalaman siswa & mentor dalam perjalanan mereka bersama KawanNalar.
            </p>
        </div>

        {{-- Tag membedakan peran: Siswa (biru) / Mentor (oranye muda, teks navy) --}}
        @php
            $testimonials = [
                [
                    'quote' => 'Berkat KawanNalar, saya bisa akses modul UTBK-SNBT kapan saja. Mentor yang saya dapat juga super sabar dan tahu betul trik lolos seleksi. Alhamdulillah saya diterima di ITS Teknik Informatika!',
                    'name' => 'Alya Putri Ramadhani',
                    'role' => 'Mahasiswi ITS — Teknik Informatika 2024',
                    'school' => 'Alumni SMAN 1 Magetan',
                    'initials' => 'AP',
                    'tag' => 'Siswa',
                ],
                [
                    'quote' => 'Menjadi mentor di KawanNalar adalah pengalaman yang sangat bermakna. Saya bisa berkontribusi langsung bagi adik-adik di Magetan dan membantu mereka menapaki jalur yang sama seperti saya dulu.',
                    'name' => 'Rizky Maulana Pratama',
                    'role' => 'Mahasiswa UGM — Kedokteran 2023',
                    'school' => 'Mentor Aktif KawanNalar',
                    'initials' => 'RM',
                    'tag' => 'Mentor',
                ],
                [
                    'quote' => 'Fitur Uji Nalar dengan XP dan leaderboard bikin saya makin semangat belajar! Saya jadi kompetitif tapi tetap asik. Platform ini lengkap dan mudah dipakai, recommended banget buat siswa SMA!',
                    'name' => 'Dewi Anggraini',
                    'role' => 'Siswa Kelas 12 IPA',
                    'school' => 'SMAN 2 Magetan',
                    'initials' => 'DA',
                    'tag' => 'Siswa',
                ],
            ];
        @endphp

        <div class="grid md:grid-cols-3 gap-5 lg:gap-6">
            @foreach ($testimonials as $t)
                <div class="flex flex-col gap-5 rounded-3xl border border-gray-100 bg-white p-6 transition-all duration-300 hover:border-primary/20 hover:shadow-lg lg:p-7">

                    {{-- Tag & Stars --}}
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $t['tag'] === 'Mentor' ? 'bg-cta/15 text-navy' : 'bg-primary/10 text-primary' }}">
                            {{ $t['tag'] }}
                        </span>
                        <div class="flex items-center gap-0.5 text-cta" aria-label="Rating 5 dari 5">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>

                    {{-- Quote --}}
                    <div class="relative">
                        <x-icon name="format_quote" class="absolute -left-1 -top-1 h-7 w-7 text-primary/15" />
                        <p class="pl-6 text-sm leading-relaxed text-gray-700">
                            {{ $t['quote'] }}
                        </p>
                    </div>

                    {{-- Author --}}
                    <div class="mt-auto flex items-center gap-3 border-t border-gray-100 pt-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-primary-dark text-sm font-bold text-white">
                            {{ $t['initials'] }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold text-gray-900">{{ $t['name'] }}</p>
                            <p class="truncate text-xs font-medium text-primary">{{ $t['role'] }}</p>
                            <p class="truncate text-xs text-gray-400">{{ $t['school'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CTA Below --}}
        <div class="mt-12 text-center">
            <p class="mb-4 text-sm text-gray-500">Siap bergabung dan menjadi bagian dari cerita sukses berikutnya?</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-2xl bg-cta px-7 py-3 text-sm font-bold text-navy shadow-sm transition-all hover:-translate-y-0.5 hover:bg-cta-dark hover:shadow-md">
                Mulai Sekarang — Gratis!
                <x-icon name="arrow_forward" class="h-4 w-4" />
            </a>
        </div>
    </div>
</section>
