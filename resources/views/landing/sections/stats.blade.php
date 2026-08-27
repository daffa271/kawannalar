<section class="bg-[#F4F7FA] pt-2 lg:pt-4 pb-16 lg:pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
            class="rounded-3xl overflow-hidden"
            style="background: linear-gradient(135deg, #0A52C4 0%, #0842A0 100%);"
        >
            <div class="px-8 lg:px-14 py-10 lg:py-12">
                <div class="grid grid-cols-2 items-center gap-8 lg:grid-cols-[1.35fr_repeat(4,1fr)] lg:gap-6">

                    <div class="col-span-2 text-center lg:col-span-1 lg:text-left">
                        <h2 class="mb-2 text-xl font-bold text-white lg:text-2xl">Bersama, Kita Wujudkan Mimpi</h2>
                        <p class="max-w-xs mx-auto lg:mx-0 text-xs leading-relaxed text-white/70">
                            KawanNalar hadir untuk memastikan setiap siswa Magetan memiliki kesempatan yang sama meraih pendidikan terbaik.
                        </p>
                    </div>

                    {{-- Stats data --}}
                    @php
                        $stats = [
                            [
                                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                                'value' => '2.345+',
                                'label' => 'Siswa Bergabung',
                            ],
                            [
                                'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
                                'value' => '340+',
                                'label' => 'Mentor Aktif',
                            ],
                            [
                                'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                                'value' => '1.200+',
                                'label' => 'Materi & Modul',
                            ],
                            [
                                'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                                'value' => '95%',
                                'label' => 'Siswa Puas',
                            ],
                        ];
                    @endphp

                    @foreach ($stats as $stat)
                        <div class="relative text-center group">
                            {{-- Icon --}}
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/10 mb-4 group-hover:bg-white/20 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}" />
                                </svg>
                            </div>

                            {{-- Value --}}
                            <div class="text-3xl lg:text-4xl font-extrabold text-white mb-1 tracking-tight">
                                {{ $stat['value'] }}
                            </div>

                            {{-- Label --}}
                            <div class="text-sm text-white/60 font-medium">
                                {{ $stat['label'] }}
                            </div>

                            {{-- Divider (hide last) --}}
                            @if (!$loop->last)
                                <div class="hidden lg:block absolute right-0 top-1/4 bottom-1/4 w-px bg-white/10"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>