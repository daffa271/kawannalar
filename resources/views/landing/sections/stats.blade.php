<section class="bg-surface pt-2 lg:pt-4 pb-16 lg:pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl overflow-hidden bg-gradient-to-br from-primary to-primary-dark">
            <div class="px-5 py-10 sm:px-8 lg:px-14 lg:py-12">
                <div class="grid grid-cols-2 items-start gap-x-4 gap-y-8 lg:grid-cols-[1.35fr_repeat(4,1fr)] lg:items-center lg:gap-6">

                    <div class="col-span-2 text-center lg:col-span-1 lg:text-left">
                        <h2 class="mb-2 text-xl font-bold text-white lg:text-2xl">Bersama, Kita Wujudkan Mimpi</h2>
                        <p class="max-w-xs mx-auto lg:mx-0 text-xs leading-relaxed text-white/70">
                            KawanNalar hadir untuk memastikan setiap siswa Magetan memiliki kesempatan yang sama meraih pendidikan terbaik.
                        </p>
                    </div>

                    {{-- Angka bergerak dari 1 ke nilai akhir saat terlihat (resources/js/modules/landing/count-up.js) --}}
                    @php
                        $stats = [
                            ['icon' => 'groups', 'count' => 2345, 'suffix' => '+', 'label' => 'Siswa Bergabung'],
                            ['icon' => 'school', 'count' => 340, 'suffix' => '+', 'label' => 'Mentor Aktif'],
                            ['icon' => 'menu_book', 'count' => 1200, 'suffix' => '+', 'label' => 'Materi & Modul'],
                            ['icon' => 'thumb_up', 'count' => 95, 'suffix' => '%', 'label' => 'Siswa Puas'],
                        ];
                    @endphp

                    @foreach ($stats as $stat)
                        <div class="relative text-center">
                            <div class="mb-3 inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10 text-white sm:mb-4 sm:h-12 sm:w-12">
                                <x-icon :name="$stat['icon']" class="h-6 w-6" />
                            </div>

                            <div class="mb-1 text-3xl font-extrabold tracking-tight text-white tabular-nums lg:text-4xl">
                                <span data-count-to="{{ $stat['count'] }}" data-count-suffix="{{ $stat['suffix'] }}">{{ number_format($stat['count'], 0, ',', '.') }}{{ $stat['suffix'] }}</span>
                            </div>

                            <div class="text-xs font-medium text-white/70 sm:text-sm">
                                {{ $stat['label'] }}
                            </div>

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
