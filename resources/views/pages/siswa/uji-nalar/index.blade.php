<x-layouts.siswa title="Uji Nalar — KawanNalar">
@php
    $rest   = $leaderboard->slice(3)->values(); // values() resets keys to 0,1,2... so rank = index+4 works correctly
    $filterActive = $selectedKelas || $selectedSubject;
    $selectedSubjectName = $selectedSubject ? $subjects->firstWhere('id', $selectedSubject)?->name : null;
@endphp

<div class="mx-auto max-w-7xl space-y-6 px-1 sm:px-0">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-8">

        {{-- ═══════════════════════════════════════════════════════
             KOLOM UTAMA (8 kolom)
        ═══════════════════════════════════════════════════════ --}}
        <div class="space-y-6 lg:col-span-8">

            {{-- ── 1. BANNER UTAMA ─────────────────────────────────────── --}}
            <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary to-primary-dark p-6 text-white shadow-lg sm:p-8">
                <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/5"></div>
                <div class="absolute -bottom-6 right-24 h-32 w-32 rounded-full bg-white/5"></div>
                <x-icon name="trophy" class="absolute bottom-4 right-6 hidden h-28 w-28 text-white/10 sm:block" />
                <div class="relative z-10">
                    <p class="text-xs font-semibold uppercase tracking-wider text-white/70">Engine Evaluasi & Gamifikasi</p>
                    <h1 class="mt-2 text-xl font-extrabold leading-tight sm:text-2xl md:text-3xl">
                        Uji Nalar: Asah Kemampuan &amp; Raih Poin Peringkat Magetan!
                    </h1>
                    <p class="mt-2 text-sm leading-relaxed text-white/80">
                        Belajar dari soal yang sudah diverifikasi lewat Flashcard,<br class="hidden sm:block"> Nalar Kilat, dan paket Bank Soal.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="#nalar-kilat" class="inline-flex items-center gap-2 rounded-xl bg-cta px-5 py-2.5 text-sm font-bold text-navy shadow-sm transition-all hover:bg-cta-dark">
                            <x-icon name="bolt" class="h-5 w-5" /> Mulai Latihan Kilat
                        </a>
                        <a href="#flashcard" class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-5 py-2.5 text-sm font-bold text-white transition-all hover:bg-white/20">
                            <x-icon name="style" class="h-5 w-5" /> Flashcard
                        </a>
                    </div>
                </div>
            </section>

            {{-- ── 2. NALAR FLASHCARD ──────────────────────────────────── --}}
            <section id="flashcard"
                x-data="{
                    cards: @js($flashcardQuestions->values()),
                    started: new URLSearchParams(window.location.search).has('kartu'),
                    finished: false,
                    current: 0,
                    flipped: false,
                    understood: {},
                    get total() { return this.cards.length },
                    get understoodCount() { return Object.values(this.understood).filter(Boolean).length },
                    start() { this.started = true; this.finished = false; this.current = 0; this.flipped = false; this.understood = {}; },
                    next()  { if (this.current < this.total - 1) { this.current++; this.flipped = false; } else { this.finished = true; } },
                    prev()  { if (this.current > 0) { this.current--; this.flipped = false; } },
                    mark(value) { this.understood[this.current] = value; this.next(); },
                    flip()  { this.flipped = !this.flipped }
                }"
                class="scroll-mt-24 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6"
            >
                <div class="mb-4 flex items-start justify-between gap-2">
                    <div class="flex items-start gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <x-icon name="style" class="h-5 w-5" />
                        </span>
                        <div>
                            <h2 class="text-sm font-extrabold text-gray-900 sm:text-base">Nalar Flashcard</h2>
                            <p class="mt-0.5 text-xs text-gray-500">Belajar cepat dari soal-soal yang telah diverifikasi.</p>
                        </div>
                    </div>
                    <span x-show="started && !finished" x-cloak class="shrink-0 text-xs font-semibold text-gray-400" x-text="`Kartu ${current + 1} dari ${total}`"></span>
                </div>

                @if ($flashcardQuestions->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-6 py-10 text-center">
                    <p class="text-sm font-bold text-gray-700">Belum ada soal yang disetujui.</p>
                    <p class="mt-1 text-xs text-gray-500">Flashcard akan muncul otomatis setelah paket soal mentor disetujui admin.</p>
                </div>
                @else
                {{-- Mulai --}}
                <div x-show="!started" class="rounded-2xl border-2 border-dashed border-primary/20 bg-primary/5 px-6 py-8 text-center">
                    <p class="text-sm font-bold text-gray-800">{{ $flashcardQuestions->count() }} kartu acak dari Bank Soal</p>
                    <p class="mx-auto mt-1 max-w-md text-xs leading-relaxed text-gray-500">Baca soalnya, pikirkan jawabanmu, lalu balik kartu untuk melihat jawaban &amp; pembahasan.</p>
                    <button type="button" @click="start()" class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-primary px-5 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-primary-dark">
                        <x-icon name="play_arrow" class="h-4 w-4" /> Mulai Flashcard
                    </button>
                </div>

                {{-- Kartu --}}
                <div x-show="started && !finished" x-cloak>
                    <div class="relative h-44 cursor-pointer select-none sm:h-52" @click="flip()" style="perspective: 1000px;">
                        <div class="absolute inset-0 transition-transform duration-500"
                             :style="flipped ? 'transform: rotateY(180deg); transform-style: preserve-3d;' : 'transform: rotateY(0deg); transform-style: preserve-3d;'">
                            {{-- Front --}}
                            <div class="backface-hidden absolute inset-0 flex flex-col items-center justify-center overflow-y-auto rounded-2xl border-2 border-gray-100 bg-white px-6 text-center shadow-md"
                                 style="backface-visibility: hidden;">
                                <template x-if="cards[current]">
                                    <div>
                                        <p class="text-sm font-semibold leading-relaxed text-gray-700" x-text="cards[current].question_text"></p>
                                        <p class="mt-4 flex items-center justify-center gap-1.5 text-xs text-gray-400">
                                            <x-icon name="touch_app" class="h-4 w-4" /> Ketuk kartu untuk melihat jawaban &amp; pembahasan
                                        </p>
                                    </div>
                                </template>
                            </div>
                            {{-- Back --}}
                            <div class="backface-hidden absolute inset-0 flex flex-col items-center justify-center overflow-y-auto rounded-2xl border-2 border-primary/20 bg-primary/5 px-6 text-center shadow-md"
                                 style="backface-visibility: hidden; transform: rotateY(180deg);">
                                <template x-if="cards[current]">
                                    <div>
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-primary">Jawaban</p>
                                        <p class="mt-1 text-sm font-bold leading-relaxed text-gray-900" x-text="cards[current].answer"></p>
                                        <p class="mt-3 text-xs leading-relaxed text-gray-600" x-text="cards[current].explanation || 'Belum ada pembahasan untuk soal ini.'"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Dots --}}
                    <div class="mt-4 flex items-center justify-center gap-1.5">
                        <template x-for="(c, i) in cards" :key="i">
                            <button type="button" @click="current = i; flipped = false" :aria-label="`Kartu ${i + 1}`"
                                    class="h-2 rounded-full transition-all duration-300"
                                    :class="i === current ? 'w-5 bg-primary' : 'w-2 bg-gray-300'"></button>
                        </template>
                    </div>

                    {{-- Nav + penilaian diri (rekap latihan ini saja; bukan benar/salah, jadi tanpa merah/hijau) --}}
                    <div class="mt-4 flex items-center justify-between gap-2 sm:gap-3">
                        <button type="button" @click="prev()" :disabled="current === 0" aria-label="Kartu sebelumnya"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30">
                            <x-icon name="chevron_left" class="h-6 w-6" />
                        </button>
                        <div class="flex flex-1 gap-2 sm:gap-3">
                            <button type="button" @click="mark(false)" class="flex-1 rounded-xl border border-gray-200 bg-white py-2.5 text-xs font-bold text-gray-700 transition hover:bg-gray-50">
                                Belum Paham
                            </button>
                            <button type="button" @click="mark(true)" class="inline-flex flex-1 items-center justify-center gap-1 rounded-xl bg-primary py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-primary-dark">
                                <x-icon name="check" class="h-4 w-4" /> Sudah Paham
                            </button>
                        </div>
                        <button type="button" @click="next()" :aria-label="current === total - 1 ? 'Selesai' : 'Kartu berikutnya'"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-50">
                            <x-icon name="chevron_right" class="h-6 w-6" />
                        </button>
                    </div>
                </div>

                {{-- Selesai --}}
                <div x-show="finished" x-cloak class="rounded-2xl border-2 border-dashed border-primary/20 bg-primary/5 px-6 py-8 text-center">
                    <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-white text-primary shadow-sm">
                        <x-icon name="task_alt" class="h-6 w-6" />
                    </span>
                    <p class="mt-3 text-sm font-extrabold text-gray-900">Flashcard selesai!</p>
                    <p class="mt-1 text-xs text-gray-600" x-text="`Kamu menandai ${understoodCount} dari ${total} kartu sebagai sudah paham.`"></p>
                    <div class="mt-4 flex flex-wrap justify-center gap-3">
                        <button type="button" @click="start()" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 transition hover:bg-gray-50">
                            <x-icon name="restart_alt" class="h-4 w-4" /> Ulangi Kartu Ini
                        </button>
                        {{-- Link "#flashcard" saja tidak me-reload halaman; query unik memaksa server mengacak kartu baru --}}
                        <button type="button" @click="window.location.assign(@js(route('siswa.uji-nalar.index')) + '?kartu=' + Date.now() + '#flashcard')" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold text-white transition hover:bg-primary-dark">
                            <x-icon name="shuffle" class="h-4 w-4" /> Kartu Acak Baru
                        </button>
                    </div>
                </div>
                @endif
            </section>

            {{-- ── 3. GRID MODE LATIHAN ────────────────────────────────── --}}
            <div class="grid scroll-mt-24 gap-5 sm:grid-cols-3" id="nalar-kilat">

                {{-- A. Nalar Kilat --}}
                <div class="flex flex-col rounded-2xl border border-gray-100 bg-white p-5 shadow-sm"
                     x-data="{
                        jumlah: 5,
                        available: {{ $approvedQuestionCount }},
                        urls: @js(collect([5, 10, 15])->mapWithKeys(fn ($n) => [$n => route('siswa.uji-nalar.kilat', $n)]))
                     }">
                    <div class="mb-3 flex items-start gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <x-icon name="bolt" class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="text-sm font-extrabold text-gray-900">Nalar Kilat</h3>
                            <p class="text-[11px] font-semibold text-gray-500">Micro-Practice</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-500">Latihan singkat 5, 10, atau 15 soal.</p>
                    <p class="mb-4 mt-1 text-[11px] leading-relaxed text-gray-400">Soal diambil acak dari Bank Soal yang telah disetujui ({{ $approvedQuestionCount }} soal tersedia).</p>
                    @if (session('kilat_error'))
                    <div class="mb-3 flex items-start gap-1.5 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-[11px] font-semibold text-amber-800">
                        <x-icon name="warning" class="h-4 w-4" /> {{ session('kilat_error') }}
                    </div>
                    @endif
                    {{-- Segmented control: pilihan aktif biru, oranye khusus tombol mulai --}}
                    <div class="mb-4 grid grid-cols-3 gap-1 rounded-xl bg-gray-100 p-1" role="radiogroup" aria-label="Jumlah soal">
                        @foreach([5, 10, 15] as $n)
                        <button type="button" @click="jumlah = {{ $n }}" role="radio" :aria-checked="(jumlah === {{ $n }}).toString()"
                                :class="jumlah === {{ $n }} ? 'bg-primary text-white shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                                class="rounded-lg py-2 text-center text-xs font-bold transition-all">
                            {{ $n }} Soal
                        </button>
                        @endforeach
                    </div>
                    <div class="mt-auto">
                        <p x-show="jumlah > available" x-cloak class="mb-2 text-[11px] font-semibold text-amber-700">Belum tersedia cukup soal untuk latihan ini.</p>
                        <a href="{{ route('siswa.uji-nalar.kilat', 5) }}" :href="urls[jumlah]" x-show="jumlah <= available"
                           class="flex w-full items-center justify-center gap-1.5 rounded-xl bg-cta py-2.5 text-center text-xs font-bold text-navy transition hover:bg-cta-dark">
                            <x-icon name="play_arrow" class="h-4 w-4" /> Mulai Latihan Kilat
                        </a>
                        <button type="button" disabled x-show="jumlah > available" x-cloak
                                class="flex w-full cursor-not-allowed items-center justify-center gap-1.5 rounded-xl bg-gray-200 py-2.5 text-center text-xs font-bold text-gray-400">
                            <x-icon name="play_arrow" class="h-4 w-4" /> Mulai Latihan Kilat
                        </button>
                    </div>
                </div>

                {{-- B. Bank Soal Sekolah: form GET sungguhan, menyaring "Paket Soal Tersedia" --}}
                <form method="GET" action="{{ route('siswa.uji-nalar.index') }}#paket-soal" class="flex flex-col rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="mb-3 flex items-start gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <x-icon name="library_books" class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="text-sm font-extrabold text-gray-900">Bank Soal Sekolah</h3>
                            <p class="text-[11px] font-semibold text-gray-500">Paket Soal</p>
                        </div>
                    </div>
                    <p class="mb-4 text-xs leading-relaxed text-gray-500">Latihan berdasarkan paket soal.</p>
                    <div class="mb-4 space-y-2">
                        <label for="filter-kelas" class="sr-only">Kelas</label>
                        <select id="filter-kelas" name="kelas"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-700 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $cls)
                            <option value="{{ $cls }}" @selected($selectedKelas === $cls)>Kelas {{ $cls }}</option>
                            @endforeach
                        </select>
                        <label for="filter-mapel" class="sr-only">Mata pelajaran</label>
                        <select id="filter-mapel" name="subject"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-700 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected($selectedSubject === $subject->id)>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-auto space-y-2">
                        <button type="submit"
                           class="flex w-full items-center justify-center gap-1.5 rounded-xl bg-primary py-2.5 text-center text-xs font-bold text-white transition hover:bg-primary-dark">
                            <x-icon name="filter_list" class="h-4 w-4" /> Tampilkan Paket Soal
                        </button>
                        @if($filterActive)
                        <a href="{{ route('siswa.uji-nalar.index') }}#paket-soal" class="block text-center text-[11px] font-semibold text-gray-500 hover:text-primary">Reset filter</a>
                        @endif
                    </div>
                </form>

                {{-- C. Simulasi UTBK --}}
                <div class="flex flex-col rounded-2xl border border-gray-100 bg-white p-5 shadow-sm" id="simulasi-utbk">
                    <div class="mb-3 flex items-start gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-gray-500">
                            <x-icon name="assignment" class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="text-sm font-extrabold text-gray-900">Simulasi UTBK</h3>
                            <span class="mt-0.5 inline-block rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-bold text-gray-500">Segera Hadir</span>
                        </div>
                    </div>
                    <p class="mb-4 text-xs leading-relaxed text-gray-500">Fitur simulasi UTBK sedang disiapkan. Sementara itu, berlatihlah lewat Nalar Kilat dan Bank Soal.</p>
                    <button type="button" disabled class="mt-auto w-full cursor-not-allowed rounded-xl bg-gray-100 py-2.5 text-xs font-bold text-gray-400">
                        Belum tersedia
                    </button>
                </div>
            </div>

            {{-- ── 4. PAKET SOAL TERSEDIA (hasil filter Bank Soal) ─────── --}}
            @if($bankSoalQuizzes->isNotEmpty() || $filterActive)
            <section id="paket-soal" class="scroll-mt-24 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-4 flex flex-wrap items-start justify-between gap-2">
                    <div class="flex items-start gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <x-icon name="library_books" class="h-5 w-5" />
                        </span>
                        <div>
                            <h2 class="text-sm font-extrabold text-gray-900 sm:text-base">Paket Soal Tersedia</h2>
                            <p class="mt-0.5 text-xs text-gray-400">
                                @if($filterActive)
                                    Filter: {{ $selectedKelas ? 'Kelas '.$selectedKelas : 'Semua kelas' }} · {{ $selectedSubjectName ?? 'Semua mapel' }}
                                @else
                                    Soal terverifikasi siap dikerjakan
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($filterActive)
                    <a href="{{ route('siswa.uji-nalar.index') }}#paket-soal" class="text-xs font-bold text-primary hover:underline">Reset filter</a>
                    @endif
                </div>

                @if($bankSoalQuizzes->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-200 px-4 py-8 text-center">
                    <p class="text-sm font-bold text-gray-700">Belum ada paket soal untuk filter ini.</p>
                    <p class="mt-1 text-xs text-gray-500">Coba kelas atau mata pelajaran lain, atau latihan dulu lewat Nalar Kilat.</p>
                </div>
                @else
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach($bankSoalQuizzes as $quiz)
                    <a href="{{ route('siswa.uji-nalar.show', $quiz) }}"
                       class="group flex items-center gap-3 rounded-xl border border-gray-100 p-3.5 transition hover:border-primary/30 hover:bg-primary/5">
                        <div class="flex h-11 w-11 shrink-0 flex-col items-center justify-center rounded-xl bg-primary text-white">
                            <span class="text-sm font-bold leading-none">{{ $quiz->total_questions }}</span>
                            <span class="mt-0.5 text-[9px] font-semibold leading-none text-white/80">soal</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-xs font-bold text-gray-800 group-hover:text-primary">{{ $quiz->title }}</p>
                            <p class="mt-0.5 text-[10px] text-gray-400">
                                {{ $quiz->subject->name ?? '-' }} · Kelas {{ $quiz->class_level }} · {{ $quiz->total_questions }} Soal
                            </p>
                        </div>
                        <x-icon name="chevron_right" class="h-5 w-5 text-gray-400 group-hover:text-primary" />
                    </a>
                    @endforeach
                </div>
                @endif
            </section>
            @endif

        </div>{{-- end kolom utama --}}

        {{-- ═══════════════════════════════════════════════════════
             SIDEBAR KANAN (4 kolom)
        ═══════════════════════════════════════════════════════ --}}
        <aside class="space-y-5 lg:col-span-4">

            {{-- ── PAPAN PERINGKAT (tujuan "Lihat Peringkat Lengkap" di dashboard) ── --}}
            <section id="peringkat" class="scroll-mt-24 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h2 class="mb-4 flex items-center gap-2 text-sm font-extrabold text-gray-900">
                    <x-icon name="trophy" class="h-5 w-5 text-primary" /> Papan Peringkat Pelajar Magetan
                </h2>

                {{-- Top 3 Podium: batang biru bertingkat, medali hanya sebagai ikon kecil --}}
                @php
                    $podium = [
                        1 => ['bar' => 'h-12 bg-primary/75', 'medal' => 'text-[#9CA3AF]'],
                        0 => ['bar' => 'h-16 bg-primary', 'medal' => 'text-[#D4A017]'],
                        2 => ['bar' => 'h-10 bg-primary/55', 'medal' => 'text-[#B87333]'],
                    ];
                @endphp
                <div class="mb-5 flex items-end justify-center gap-3">
                    @foreach($podium as $pos => $style)
                    @php $p = $leaderboard->get($pos); @endphp
                    @if($p)
                    <div class="flex flex-col items-center">
                        <x-icon name="military_tech" class="mb-1 h-5 w-5 {{ $style['medal'] }}" />
                        <div class="mb-1 flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary ring-2 ring-primary/20">
                            {{ strtoupper(substr($p->name, 0, 1)) }}
                        </div>
                        <p class="w-16 truncate text-center text-[10px] font-bold text-gray-800">{{ explode(' ', $p->name)[0] }}</p>
                        <p class="mt-0.5 text-[10px] font-extrabold text-gray-900">{{ number_format($p->xp_points) }} XP</p>
                        <div class="{{ $style['bar'] }} mt-1 flex w-12 items-center justify-center rounded-t-lg text-xs font-extrabold text-white">
                            {{ $pos + 1 }}
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                {{-- Ranking List 4–10 --}}
                <div class="divide-y divide-gray-50">
                    <div class="grid grid-cols-3 px-2 pb-2 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                        <span>#</span><span>Nama Siswa</span><span class="text-right">Total XP</span>
                    </div>

                    @foreach($rest as $index => $leader)
                    @php
                        $rank = $index + 4;  // index is now 0-based thanks to values()
                        $isMe = $leader->id === $user->id;
                    @endphp
                    <div class="flex items-center gap-2 rounded-lg px-1 py-2.5 transition {{ $isMe ? '-mx-1 bg-primary/5 px-2' : 'hover:bg-gray-50' }}">
                        <span class="w-6 shrink-0 text-center text-xs font-extrabold {{ $isMe ? 'text-primary' : 'text-gray-400' }}">
                            {{ $rank }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-xs font-bold {{ $isMe ? 'text-primary' : 'text-gray-800' }}">
                                {{ $leader->name }}
                                @if($isMe)
                                    <span class="ml-1 rounded-full bg-primary px-1.5 py-0.5 text-[9px] text-white">Kamu</span>
                                @endif
                            </p>
                            <p class="mt-0.5 truncate text-[10px] text-gray-400">{{ $leader->studentProfile?->school ?? '-' }}</p>
                        </div>
                        <span class="shrink-0 text-xs font-extrabold text-gray-900">{{ number_format($leader->xp_points) }} XP</span>
                    </div>
                    @endforeach

                    {{-- Separator + posisi user jika di luar top 10 --}}
                    @if($userRank !== null && $userRank > 10)
                    <div class="pt-1">
                        <div class="flex items-center gap-1 py-1">
                            <div class="flex-1 border-t border-dashed border-gray-200"></div>
                            <span class="px-1 text-[10px] text-gray-300">···</span>
                            <div class="flex-1 border-t border-dashed border-gray-200"></div>
                        </div>
                        <div class="flex items-center gap-2 rounded-xl bg-primary/5 px-2 py-2.5">
                            <span class="w-6 shrink-0 text-center text-xs font-extrabold text-primary">{{ $userRank }}</span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-xs font-bold text-primary">
                                    {{ $user->name }}
                                    <span class="ml-1 rounded-full bg-primary px-1.5 py-0.5 text-[9px] text-white">Kamu</span>
                                </p>
                                <p class="mt-0.5 text-[10px] text-gray-400">{{ $user->studentProfile?->school ?? '-' }}</p>
                            </div>
                            <span class="shrink-0 text-xs font-extrabold text-gray-900">{{ number_format($user->xp_points) }} XP</span>
                        </div>
                    </div>
                    @endif
                </div>
            </section>

            {{-- ── PERFORMA SAYA ───────────────────────────────── --}}
            @php
                $xpForNextLevel = 1000;
                $xpProgress = min($xpThisLevel, $xpForNextLevel);
                $xpPercent  = round(($xpProgress / $xpForNextLevel) * 100);
                $performance = [
                    ['icon' => 'task_alt', 'label' => 'Akurasi', 'value' => $accuracy.'%', 'note' => 'Rata-rata benar'],
                    ['icon' => 'quiz', 'label' => 'Soal Dikerjakan', 'value' => $totalAnswered, 'note' => 'Total'],
                    ['icon' => 'local_fire_department', 'label' => 'Streak', 'value' => $user->streak_days, 'note' => 'Hari berlatih'],
                ];
            @endphp
            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h2 class="mb-4 flex items-center gap-2 text-sm font-extrabold text-gray-900">
                    <x-icon name="insights" class="h-5 w-5 text-primary" /> Performa Saya
                </h2>

                <div class="mb-5 grid grid-cols-3 gap-2 sm:gap-3">
                    @foreach($performance as $item)
                    <div class="rounded-xl bg-surface p-3 text-center">
                        <x-icon :name="$item['icon']" class="mx-auto h-5 w-5 text-primary" />
                        <p class="mt-1 text-[10px] text-gray-500">{{ $item['label'] }}</p>
                        <p class="text-lg font-extrabold text-gray-900">{{ $item['value'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $item['note'] }}</p>
                    </div>
                    @endforeach
                </div>

                {{-- XP Progress --}}
                <div>
                    <div class="mb-1.5 flex items-center justify-between">
                        <p class="text-xs font-bold text-gray-700">XP Mingguan</p>
                        <p class="text-xs font-bold text-gray-500">{{ number_format($xpProgress) }} / {{ number_format($xpForNextLevel) }} XP</p>
                    </div>
                    <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-100" role="progressbar" aria-label="Progres XP" aria-valuenow="{{ $xpPercent }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="h-full rounded-full bg-cta transition-all duration-700"
                             style="width: {{ $xpPercent }}%"></div>
                    </div>
                    <div class="mt-2 flex items-center justify-between gap-2">
                        <p class="text-[10px] text-gray-400">
                            @if($xpPercent >= 60)
                                Mantap! Kamu semakin dekat ke Level {{ $level + 1 }}
                            @else
                                Terus berlatih dan raih peringkat tertinggi!
                            @endif
                        </p>
                        <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-bold text-primary">
                            <x-icon name="workspace_premium" class="h-3.5 w-3.5" /> Level {{ $level }}
                        </span>
                    </div>
                </div>
            </section>

        </aside>{{-- end sidebar --}}

    </div>
</div>

<style>
    .backface-hidden { backface-visibility: hidden; }
</style>
</x-layouts.siswa>
