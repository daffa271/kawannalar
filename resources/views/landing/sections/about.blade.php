{{-- Section: Tentang Kami — Storytelling 5 Tahap --}}
<section id="tentang-kami" class="py-20 lg:py-28 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ── Section Header ─────────────────────────────────────────── --}}
        <div class="text-center max-w-2xl mx-auto mb-16 lg:mb-20">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary/10 text-primary rounded-full text-xs sm:text-sm font-semibold mb-4">
                <x-icon name="diversity_3" class="h-4 w-4" />
                Tentang KawanNalar
            </span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight mb-4">
                Lahir dari <span class="text-primary">Kepedulian,</span><br class="hidden sm:block">
                Tumbuh untuk <span class="text-primary">Magetan</span>
            </h2>
            <p class="text-gray-500 text-base leading-relaxed">
                Sebuah perjalanan panjang untuk menjembatani kesenjangan akses pendidikan di daerah.
            </p>
        </div>

        {{-- ── TAHAP 1: WHY ─────────────────────────────────────────────── --}}
        <div class="relative mb-20 lg:mb-28">
            <div class="absolute -top-16 -left-16 w-80 h-80 bg-primary/5 rounded-full blur-3xl -z-10"></div>
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">

                {{-- Visual --}}
                <div class="rounded-3xl bg-gradient-to-br from-primary to-primary-dark p-8 text-white shadow-xl lg:p-10">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15">
                        <x-icon name="report" class="h-7 w-7" />
                    </div>
                    <p class="mb-2 text-4xl font-black tabular-nums lg:text-5xl"><span data-count-to="73" data-count-suffix="%">73%</span></p>
                    <p class="text-sm leading-relaxed text-white/80">siswa SMA/SMK di daerah tidak memiliki akses bimbingan belajar terstruktur untuk persiapan UTBK-SNBT</p>
                    <div class="mt-8 grid grid-cols-2 gap-4">
                        <div class="rounded-2xl bg-white/10 p-4">
                            <p class="text-2xl font-bold tabular-nums"><span data-count-to="31" data-count-suffix="+">31+</span></p>
                            <p class="mt-1 text-xs text-white/70">SMA/SMK di Kab. Magetan</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-4">
                            <p class="text-2xl font-bold">Terbatas</p>
                            <p class="mt-1 text-xs text-white/70">Akses bimbel berkualitas</p>
                        </div>
                    </div>
                </div>

                {{-- Text --}}
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold uppercase tracking-wider mb-5">
                        <span class="w-2 h-2 bg-primary rounded-full"></span>
                        01 — Mengapa Kami Ada
                    </div>
                    <h3 class="text-2xl lg:text-3xl font-extrabold text-gray-900 mb-5 leading-tight">
                        Kesenjangan Akses Bimbingan Belajar di Kabupaten Magetan
                    </h3>
                    <div class="space-y-4 text-gray-600 text-sm lg:text-base leading-relaxed">
                        <p>
                            Siswa SMA/SMK di Kabupaten Magetan menghadapi tantangan nyata: minimnya akses bimbingan belajar berkualitas dan mahalnya biaya bimbel konvensional untuk persiapan <strong class="text-gray-800">UTBK-SNBT</strong>.
                        </p>
                        <p>
                            Sementara siswa di kota-kota besar dengan mudah mendapatkan mentor, tryout, dan modul lengkap — adik-adik di Magetan berjuang sendiri. Ketimpangan ini yang mendorong lahirnya KawanNalar.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAHAP 2: WHAT ────────────────────────────────────────────── --}}
        <div class="mb-20 lg:mb-28 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold uppercase tracking-wider mb-5">
                <span class="w-2 h-2 bg-primary rounded-full"></span>
                02 — Apa Itu KawanNalar
            </div>
            <h3 class="text-2xl lg:text-3xl font-extrabold text-gray-900 mb-6 max-w-3xl mx-auto leading-tight">
                Platform Edukasi Digital Kolaboratif Berbasis <span class="text-primary">Peer Mentoring</span>
            </h3>
            <p class="text-gray-500 text-sm lg:text-base leading-relaxed max-w-2xl mx-auto mb-10">
                KawanNalar menghubungkan <strong class="text-gray-700">siswa SMA/SMK</strong> dengan <strong class="text-gray-700">mahasiswa PTN asal Magetan</strong> dalam satu ekosistem belajar yang inklusif, interaktif, dan gratis sepenuhnya.
            </p>
            @php
                $pilars = [
                    ['icon' => 'menu_book', 'label' => 'Ruang Nalar', 'desc' => 'Modul & materi terverifikasi'],
                    ['icon' => 'quiz', 'label' => 'Uji Nalar', 'desc' => 'Latihan soal gamifikasi & XP'],
                    ['icon' => 'groups', 'label' => 'Teman Nalar', 'desc' => 'Mentoring 1-on-1 via Meet'],
                ];
            @endphp
            <div class="grid sm:grid-cols-3 gap-5 max-w-4xl mx-auto">
                @foreach ($pilars as $pilar)
                    <div class="rounded-2xl bg-surface p-6 text-center transition-colors duration-200 hover:bg-primary/5">
                        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-white">
                            <x-icon :name="$pilar['icon']" class="h-6 w-6" />
                        </div>
                        <p class="font-bold text-gray-900 mb-1">{{ $pilar['label'] }}</p>
                        <p class="text-xs text-gray-500">{{ $pilar['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── TAHAP 3: HOW ─────────────────────────────────────────────── --}}
        <div class="mb-20 lg:mb-28">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold uppercase tracking-wider mb-5">
                    <span class="w-2 h-2 bg-primary rounded-full"></span>
                    03 — Cara Kerjanya
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-gray-900">
                    3 Langkah Menuju <span class="text-primary">PTN Impianmu</span>
                </h3>
            </div>
            @php
                $steps = [
                    ['step' => '01', 'icon' => 'menu_book', 'title' => 'Ruang Nalar', 'subtitle' => 'Belajar dari Modul Terbaik', 'desc' => 'Akses dan baca modul PDF terverifikasi yang dikurasi dari mahasiswa PTN. Materi UTBK-SNBT lengkap, siap diakses kapan saja.', 'badge_icon' => 'verified', 'badge' => 'Modul Terverifikasi'],
                    ['step' => '02', 'icon' => 'quiz', 'title' => 'Uji Nalar', 'subtitle' => 'Latihan Soal Gamifikasi', 'desc' => 'Kerjakan latihan soal interaktif, raih XP, dan bersaing di Leaderboard bersama siswa se-Magetan. Belajar jadi menyenangkan!', 'badge_icon' => 'leaderboard', 'badge' => 'Leaderboard & XP'],
                    ['step' => '03', 'icon' => 'videocam', 'title' => 'Teman Nalar', 'subtitle' => 'Mentoring 1-on-1', 'desc' => 'Booking slot sesi mentoring langsung bersama mahasiswa PTN asal Magetan via Google Meet. Tanya bebas, belajar tanpa canggung!', 'badge_icon' => 'videocam', 'badge' => 'Via Google Meet'],
                ];
            @endphp
            <div class="grid lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($steps as $step)
                    <div class="group rounded-3xl border border-gray-100 bg-white p-7 transition-all duration-300 hover:border-primary/20 hover:shadow-lg">
                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-white">
                                <x-icon :name="$step['icon']" class="h-6 w-6" />
                            </div>
                            <span class="text-4xl font-black text-primary/15 transition-colors group-hover:text-primary/30">{{ $step['step'] }}</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-gray-900 mb-1">{{ $step['title'] }}</h4>
                        <p class="text-xs font-semibold text-primary mb-3">{{ $step['subtitle'] }}</p>
                        <p class="text-sm text-gray-500 leading-relaxed mb-5">{{ $step['desc'] }}</p>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary">
                            <x-icon :name="$step['badge_icon']" class="h-4 w-4" />
                            {{ $step['badge'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── TAHAP 4: VALUE ───────────────────────────────────────────── --}}
        <div class="mb-20 lg:mb-28 rounded-3xl bg-surface p-6 sm:p-8 lg:p-12">
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white text-primary rounded-full text-xs font-bold uppercase tracking-wider mb-5 shadow-sm">
                    <span class="w-2 h-2 bg-primary rounded-full"></span>
                    04 — Yang Membedakan Kami
                </div>
                <h3 class="text-2xl lg:text-3xl font-extrabold text-gray-900">
                    Kenapa Harus <span class="text-primary">KawanNalar?</span>
                </h3>
            </div>
            @php
                $values = [
                    ['icon' => 'handshake', 'title' => 'Peer Mentoring Sejajar', 'desc' => 'Belajar dari kakak kelas yang sudah lolos PTN — lebih relatable, lebih nyaman, tanpa canggung. Mereka tahu perjuanganmu karena pernah ada di posisi yang sama.'],
                    ['icon' => 'sports_esports', 'title' => 'Gamifikasi Interaktif', 'desc' => 'XP, badge, dan leaderboard membuat belajar terasa seperti bermain. Kompetisi sehat antar siswa Magetan yang memotivasi untuk terus maju.'],
                    ['icon' => 'public', 'title' => 'Akses Inklusif & Gratis', 'desc' => '100% gratis untuk semua siswa SMA/SMK Magetan. Tidak ada paywall, tidak ada biaya tersembunyi. Karena akses pendidikan berkualitas adalah hak semua orang.'],
                ];
            @endphp
            <div class="grid sm:grid-cols-3 gap-6">
                @foreach ($values as $value)
                    <div class="rounded-2xl bg-white p-6 shadow-sm transition-shadow duration-200 hover:shadow-md">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <x-icon :name="$value['icon']" class="h-6 w-6" />
                        </div>
                        <h4 class="text-base font-bold text-gray-900 mb-3">{{ $value['title'] }}</h4>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $value['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── TAHAP 5: IMPACT ──────────────────────────────────────────── --}}
        <div class="text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold uppercase tracking-wider mb-6">
                <span class="w-2 h-2 bg-primary rounded-full"></span>
                05 — Dampak yang Kami Ciptakan
            </div>
            <h3 class="text-2xl lg:text-3xl font-extrabold text-gray-900 mb-6 max-w-2xl mx-auto">
                Bersama, Kita Bangun <span class="text-primary">Generasi Magetan</span> yang Berprestasi
            </h3>
            <p class="text-gray-500 text-sm lg:text-base leading-relaxed max-w-xl mx-auto mb-10">
                Target kami jelas: meningkatkan persentase kelulusan PTN siswa Magetan dan membangun jaringan alumni & mahasiswa yang solid dan suportif.
            </p>
            @php
                $impacts = [
                    ['icon' => 'trending_up', 'title' => 'Lulus PTN', 'desc' => 'Target peningkatan kelulusan PTN siswa Magetan'],
                    ['icon' => 'diversity_3', 'title' => 'Alumni Solid', 'desc' => 'Jaringan mahasiswa PTN asal Magetan yang suportif'],
                    ['icon' => 'auto_stories', 'title' => 'Belajar Merata', 'desc' => 'Akses materi berkualitas tanpa batas wilayah'],
                    ['icon' => 'verified', 'title' => 'Mandiri', 'desc' => 'Siswa lebih percaya diri dan siap menghadapi SNBT'],
                ];
            @endphp
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 max-w-4xl mx-auto">
                @foreach ($impacts as $impact)
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 text-center">
                        <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <x-icon :name="$impact['icon']" class="h-6 w-6" />
                        </div>
                        <p class="mb-1.5 text-base font-extrabold text-gray-900">{{ $impact['title'] }}</p>
                        <p class="text-xs leading-relaxed text-gray-500">{{ $impact['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>
