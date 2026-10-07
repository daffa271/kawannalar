<section id="fitur" class="bg-surface py-20 scroll-mt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-semibold mb-4">
                <x-icon name="auto_awesome" class="h-4 w-4" />
                8 Fitur Unggulan
            </span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                Ekosistem Belajar Lengkap dalam<br>
                <span class="text-primary">Satu Platform</span>
            </h2>
            <p class="text-gray-600">Semua yang kamu butuhkan untuk meraih impian kuliah di PTN favorit dalam genggaman.</p>
        </div>

        {{-- Features Grid: satu gaya ikon; label "Segera Hadir" untuk fitur yang belum dibuka --}}
        @php
            $features = [
                ['icon' => 'menu_book', 'label' => 'Ruang Nalar', 'desc' => 'Akses & bagikan modul, ringkasan materi, dan e-book berkualitas dari mentor.', 'soon' => false],
                ['icon' => 'groups', 'label' => 'Teman Nalar', 'desc' => 'Belajar langsung dengan mentor mahasiswa PTN melalui kelas online & mentoring privat.', 'soon' => false],
                ['icon' => 'forum', 'label' => 'Nalar Diskusi', 'desc' => 'Tanya, diskusi, dan berbagi solusi soal bersama komunitas siswa dan mentor aktif.', 'soon' => true],
                ['icon' => 'smart_toy', 'label' => 'NalarBot AI', 'desc' => 'Asisten cerdas untuk minat bakat, peluang masuk PTN, dan teman cerita seputar belajar.', 'soon' => true],
                ['icon' => 'quiz', 'label' => 'Uji Nalar', 'desc' => 'Latihan soal, Nalar Kilat, flashcard, dan leaderboard pelajar se-Magetan.', 'soon' => false],
                ['icon' => 'route', 'label' => 'Jejak Nalar', 'desc' => 'Temukan jejak alumni Magetan di PTN favorit beserta tips dan strategi sukses.', 'soon' => true],
                ['icon' => 'newspaper', 'label' => 'Kabar Nalar', 'desc' => 'Info beasiswa, lomba, dan perguruan tinggi yang diverifikasi Admin sebelum tayang.', 'soon' => true],
                ['icon' => 'timer', 'label' => 'Nalar Focus', 'desc' => 'Timer Pomodoro dan musik fokus untuk membantumu konsentrasi saat belajar.', 'soon' => false],
            ];
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6 items-stretch">
            @foreach ($features as $feature)
                <div class="group h-full flex flex-col bg-white rounded-2xl p-5 lg:p-6 border border-gray-100 hover:border-primary/20 hover:shadow-lg transition-all duration-300">
                    <div class="mb-4 flex items-start justify-between gap-2">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                            <x-icon :name="$feature['icon']" class="h-6 w-6" />
                        </div>
                        @if ($feature['soon'])
                            <span class="shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-500">Segera Hadir</span>
                        @endif
                    </div>

                    <h3 class="font-bold text-gray-900 text-base mb-2 group-hover:text-primary transition-colors">
                        {{ $feature['label'] }}
                    </h3>

                    <p class="text-sm text-gray-500 leading-relaxed">
                        {{ $feature['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
