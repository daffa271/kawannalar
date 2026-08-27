<x-layouts.siswa title="Uji Nalar — KawanNalar">
@php
    $top3   = $leaderboard->take(3);
    $rest   = $leaderboard->slice(3)->values(); // values() resets keys to 0,1,2... so rank = index+4 works correctly
    $podium = [1 => $top3->get(1), 0 => $top3->get(0), 2 => $top3->get(2)]; // 2-1-3 podium order
@endphp

<div class="mx-auto max-w-7xl space-y-6 px-1 sm:px-0">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-8">

        {{-- ═══════════════════════════════════════════════════════
             KOLOM UTAMA (8 kolom)
        ═══════════════════════════════════════════════════════ --}}
        <div class="space-y-6 lg:col-span-8">

            {{-- ── 1. BANNER UTAMA ─────────────────────────────────────── --}}
            <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#0A52C4] via-[#1565D8] to-[#0D3E96] p-6 text-white shadow-lg sm:p-8">
                <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/5"></div>
                <div class="absolute -bottom-6 right-24 h-32 w-32 rounded-full bg-white/5"></div>
                <div class="absolute right-4 top-4 text-[80px] leading-none opacity-20 select-none hidden sm:block">🏆</div>
                <div class="relative z-10">
                    <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider">Engine Evaluasi & Gamifikasi</p>
                    <h1 class="mt-2 text-xl font-extrabold leading-tight sm:text-2xl md:text-3xl">
                        Uji Nalar: Asah Kemampuan &amp; Raih Poin Peringkat Magetan!
                    </h1>
                    <p class="mt-2 text-sm leading-relaxed text-blue-100">
                        Latihan mikro 5 menit, persiapan ujian sekolah PTS/PAS,<br>atau tryout UTBK riil.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="#nalar-kilat" class="inline-flex items-center gap-2 rounded-xl bg-[#F28C28] px-5 py-2.5 text-sm font-bold text-white shadow hover:bg-[#E07D1C] transition-all">
                            ⚡ Mulai Latihan Kilat
                        </a>
                        <a href="#simulasi-utbk" class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-5 py-2.5 text-sm font-bold text-white hover:bg-white/20 transition-all">
                            🎯 Simulasi UTBK
                        </a>
                    </div>
                </div>
                <div class="absolute right-6 bottom-5 hidden text-[90px] leading-none opacity-25 sm:block select-none">🎯</div>
            </section>

            {{-- ── 2. NALAR FLASHCARD ──────────────────────────────────── --}}
            <section
                x-data="{
                    cards: @js($flashcardQuestions->values()),
                    current: 0,
                    flipped: false,
                    get total() { return this.cards.length },
                    next()  { if (this.current < this.total - 1) { this.current++; this.flipped = false; } },
                    prev()  { if (this.current > 0) { this.current--; this.flipped = false; } },
                    flip()  { this.flipped = !this.flipped }
                }"
                class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6"
            >
                <div class="flex items-center justify-between gap-2 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-base">📋</span>
                        <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">Nalar Flashcard: Tebak Rumus &amp; Istilah Hari Ini</h2>
                        <span class="text-gray-400 cursor-help text-xs" title="Klik kartu untuk melihat jawaban">ⓘ</span>
                    </div>
                    <span class="text-xs font-semibold text-gray-400" x-text="`Kartu ${current + 1} dari ${total}`"></span>
                </div>

                {{-- Card flip area --}}
                <div class="relative h-44 sm:h-52 cursor-pointer select-none" @click="flip()" style="perspective: 1000px;">
                    <div class="absolute inset-0 transition-transform duration-500"
                         :style="flipped ? 'transform: rotateY(180deg); transform-style: preserve-3d;' : 'transform: rotateY(0deg); transform-style: preserve-3d;'">
                        {{-- Front --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center rounded-2xl border-2 border-gray-100 bg-white shadow-md px-6 text-center backface-hidden"
                             style="backface-visibility: hidden;">
                            <template x-if="cards[current]">
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 leading-relaxed" x-text="cards[current].question_text"></p>
                                    <p class="mt-4 text-xs text-gray-400 flex items-center justify-center gap-1.5">
                                        <span>👆</span> Ketuk kartu untuk membalik &amp; lihat jawaban
                                    </p>
                                </div>
                            </template>
                        </div>
                        {{-- Back --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center rounded-2xl border-2 border-[#0A52C4]/20 bg-[#EEF4FF] shadow-md px-6 text-center backface-hidden"
                             style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <template x-if="cards[current]">
                                <div>
                                    <p class="text-sm text-gray-700 leading-relaxed" x-text="cards[current].explanation ?? 'Tidak ada penjelasan.'"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Dots --}}
                <div class="mt-4 flex items-center justify-center gap-1.5">
                    <template x-for="(c, i) in cards" :key="i">
                        <button @click="current = i; flipped = false"
                                class="h-2 rounded-full transition-all duration-300"
                                :class="i === current ? 'w-5 bg-[#0A52C4]' : 'w-2 bg-gray-300'"></button>
                    </template>
                </div>

                {{-- Nav + Actions --}}
                <div class="mt-4 flex items-center justify-between gap-3">
                    <button @click="prev()" :disabled="current === 0"
                            class="flex items-center justify-center rounded-full w-10 h-10 shrink-0 bg-[#0A52C4] text-white text-base font-bold shadow-sm transition hover:bg-[#0842A0] disabled:opacity-30 disabled:cursor-not-allowed">‹</button>
                    <div class="flex flex-1 gap-3">
                        <button class="flex-1 rounded-xl border-2 border-red-200 bg-red-50 py-2.5 text-xs font-bold text-red-600 hover:bg-red-100 transition">
                            ✕ Belum Paham
                        </button>
                        <button class="flex-1 rounded-xl bg-[#22C55E] py-2.5 text-xs font-bold text-white hover:bg-[#16A34A] transition shadow-sm">
                            ✓ Sudah Paham (+5 XP)
                        </button>
                    </div>
                    <button @click="next()" :disabled="current === total - 1"
                            class="flex items-center justify-center rounded-full w-10 h-10 shrink-0 bg-[#0A52C4] text-white text-base font-bold shadow-sm transition hover:bg-[#0842A0] disabled:opacity-30 disabled:cursor-not-allowed">›</button>
                </div>
            </section>

            {{-- ── 3. GRID MODE LATIHAN ────────────────────────────────── --}}
            <div class="grid gap-5 sm:grid-cols-3" id="nalar-kilat">

                {{-- A. Nalar Kilat --}}
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-start gap-2 mb-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#FFF3E5] text-lg">⚡</span>
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-sm">Nalar Kilat</h3>
                            <p class="text-[11px] text-[#F28C28] font-semibold">(Micro-Practice)</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">Latihan cepat 5–15 soal saat waktu luang.</p>
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        @foreach([5, 10, 15] as $n)
                        <a href="{{ route('siswa.uji-nalar.index') }}?kilat={{ $n }}"
                           class="rounded-xl border border-[#F28C28]/30 bg-[#FFF8F0] py-2 text-center text-xs font-bold text-[#C26A13] hover:bg-[#F28C28] hover:text-white hover:border-[#F28C28] transition-all">
                            {{ $n }} Soal
                        </a>
                        @endforeach
                    </div>
                    <a href="{{ route('siswa.uji-nalar.index') }}?kilat=5"
                       class="block w-full rounded-xl bg-[#F28C28] py-2.5 text-center text-xs font-bold text-white hover:bg-[#E07D1C] transition">
                        🚀 Mulai Latihan Kilat
                    </a>
                </div>

                {{-- B. Bank Soal Sekolah --}}
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm" x-data="{ kelas: '', mapel: '' }">
                    <div class="flex items-start gap-2 mb-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#E8F5E9] text-lg">📗</span>
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-sm">Bank Soal Sekolah</h3>
                            <p class="text-[11px] text-[#22863A] font-semibold">(PTS / PAS)</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">Persiapan ulangan harian &amp; semesteran Kelas 10, 11, 12.</p>
                    <div class="space-y-2 mb-4">
                        <select x-model="kelas"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-700 focus:border-[#0A52C4] focus:outline-none focus:ring-1 focus:ring-[#0A52C4]">
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $cls)
                            <option value="{{ $cls }}">Kelas {{ $cls }}</option>
                            @endforeach
                        </select>
                        <select x-model="mapel"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-700 focus:border-[#0A52C4] focus:outline-none focus:ring-1 focus:ring-[#0A52C4]">
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <a :href="`/uji-nalar?kelas=${kelas}&subject=${mapel}`"
                       class="block w-full rounded-xl bg-[#22863A] py-2.5 text-center text-xs font-bold text-white hover:bg-[#1A6B2E] transition">
                        📋 Pilih Paket Soal
                    </a>
                </div>

                {{-- C. Simulasi UTBK --}}
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm" id="simulasi-utbk">
                    <div class="flex items-start gap-2 mb-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#FFF0F0] text-lg">🎯</span>
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-sm">Simulasi Riil UTBK</h3>
                            <p class="text-[11px] text-[#DC2626] font-semibold">(Full Tryout)</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">Simulasi tryout lengkap dengan timer 195 menit &amp; pembobotan IRT.</p>
                    <div class="mb-4 space-y-2">
                        <div class="flex items-center gap-2 rounded-xl bg-gray-50 px-3 py-2 text-xs text-gray-600">
                            <span>⏱</span> <span>195 Menit</span>
                        </div>
                        <div class="flex items-center gap-2 rounded-xl bg-[#FFF8F0] px-3 py-2 text-xs font-semibold text-[#C26A13]">
                            <span>🏆</span> <span>+100 XP Hadiah</span>
                        </div>
                    </div>
                    <button class="w-full rounded-xl bg-[#DC2626] py-2.5 text-xs font-bold text-white hover:bg-[#B91C1C] transition">
                        🔥 Ikuti Simulasi UTBK
                    </button>
                </div>
            </div>

            {{-- ── 4. BANK SOAL APPROVED (list) ────────────────────────── --}}
            @if($bankSoalQuizzes->isNotEmpty())
            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">📦 Paket Soal Tersedia</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Soal terverifikasi siap dikerjakan</p>
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach($bankSoalQuizzes as $quiz)
                    <a href="{{ route('siswa.uji-nalar.show', $quiz) }}"
                       class="flex items-center gap-3 rounded-xl border border-gray-100 p-3.5 transition hover:border-[#0A52C4]/30 hover:bg-[#F4F7FF] group">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#0A52C4] to-[#1E40AF] text-white font-bold text-sm">
                            {{ $quiz->total_questions }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-800 truncate group-hover:text-[#0A52C4]">{{ $quiz->title }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">
                                {{ $quiz->subject->name ?? '-' }} · Kelas {{ $quiz->class_level }} · {{ $quiz->total_questions }} Soal
                            </p>
                        </div>
                        <span class="text-[#0A52C4] text-sm">›</span>
                    </a>
                    @endforeach
                </div>
            </section>
            @endif

        </div>{{-- end kolom utama --}}

        {{-- ═══════════════════════════════════════════════════════
             SIDEBAR KANAN (4 kolom)
        ═══════════════════════════════════════════════════════ --}}
        <aside class="space-y-5 lg:col-span-4">

            {{-- ── PAPAN PERINGKAT ─────────────────────────────── --}}
            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-extrabold text-gray-900 text-sm flex items-center gap-1.5">
                        <span>🏆</span> Papan Peringkat Pelajar Magetan
                    </h2>
                    <a href="#" class="text-xs font-bold text-[#0A52C4]">Lihat Semua</a>
                </div>

                {{-- Top 3 Podium --}}
                <div class="flex items-end justify-center gap-3 mb-5">
                    @php
                        $podiumOrder = [1, 0, 2]; // 2nd place left, 1st center, 3rd right
                        $podiumColors = [
                            0 => ['bg' => 'bg-[#FFC000]', 'crown' => '👑', 'h' => 'h-16', 'ring' => 'ring-[#FFC000]'],
                            1 => ['bg' => 'bg-gray-300',  'crown' => '🥈', 'h' => 'h-12', 'ring' => 'ring-gray-300'],
                            2 => ['bg' => 'bg-[#CD7F32]', 'crown' => '🥉', 'h' => 'h-10', 'ring' => 'ring-[#CD7F32]'],
                        ];
                    @endphp
                    @foreach($podiumOrder as $pos)
                    @php $p = $leaderboard->get($pos); @endphp
                    @if($p)
                    <div class="flex flex-col items-center">
                        <span class="text-base mb-1">{{ $podiumColors[$pos]['crown'] }}</span>
                        <div class="h-12 w-12 rounded-full ring-2 {{ $podiumColors[$pos]['ring'] }} bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-sm font-bold text-gray-700 mb-1">
                            {{ strtoupper(substr($p->name, 0, 1)) }}
                        </div>
                        <p class="text-[10px] font-bold text-gray-800 text-center w-16 truncate">{{ explode(' ', $p->name)[0] }}</p>
                        <p class="text-[10px] font-extrabold {{ $pos === 0 ? 'text-[#D4AC0D]' : ($pos === 1 ? 'text-gray-500' : 'text-[#CD7F32]') }} mt-0.5">
                            {{ number_format($p->xp_points) }} XP
                        </p>
                        <div class="{{ $podiumColors[$pos]['h'] }} w-12 {{ $podiumColors[$pos]['bg'] }} rounded-t-lg mt-1 flex items-center justify-center text-white font-extrabold text-xs">
                            {{ $pos + 1 }}
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                {{-- Ranking List 4–10 --}}
                <div class="divide-y divide-gray-50">
                    <div class="grid grid-cols-3 px-2 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                        <span>#</span><span>Nama Siswa</span><span class="text-right">Total XP</span>
                    </div>

                    @foreach($rest as $index => $leader)
                    @php
                        $rank      = $index + 4;  // index is now 0-based thanks to values()
                        $isMe      = $leader->id === $user->id;
                    @endphp
                    <div class="flex items-center gap-2 py-2.5 px-1 rounded-lg transition
                                {{ $isMe ? 'bg-[#EEF4FF] -mx-1 px-2' : 'hover:bg-gray-50' }}">
                        <span class="w-6 shrink-0 text-center text-xs font-extrabold
                                     {{ $isMe ? 'text-[#0A52C4]' : 'text-gray-400' }}">
                            {{ $rank }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold truncate {{ $isMe ? 'text-[#0A52C4]' : 'text-gray-800' }}">
                                {{ $leader->name }}
                                @if($isMe)
                                    <span class="text-[9px] bg-[#0A52C4] text-white px-1.5 py-0.5 rounded-full ml-1">Kamu</span>
                                @endif
                            </p>
                            <p class="text-[10px] text-gray-400 flex items-center gap-1 mt-0.5 truncate">
                                <span>🏫</span> {{ $leader->school_name ?? '-' }}
                            </p>
                        </div>
                        <span class="text-xs font-extrabold text-[#F28C28] shrink-0">{{ number_format($leader->xp_points) }} XP</span>
                    </div>
                    @endforeach

                    {{-- Separator + posisi user jika di luar top 10 --}}
                    @if($userRank !== null && $userRank > 10)
                    <div class="pt-1">
                        <div class="flex items-center gap-1 py-1">
                            <div class="flex-1 border-t border-dashed border-gray-200"></div>
                            <span class="text-[10px] text-gray-300 px-1">···</span>
                            <div class="flex-1 border-t border-dashed border-gray-200"></div>
                        </div>
                        <div class="flex items-center gap-2 py-2.5 px-2 bg-[#EEF4FF] rounded-xl">
                            <span class="w-6 shrink-0 text-center text-xs font-extrabold text-[#0A52C4]">{{ $userRank }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-[#0A52C4] truncate">
                                    {{ $user->name }}
                                    <span class="text-[9px] bg-[#0A52C4] text-white px-1.5 py-0.5 rounded-full ml-1">Kamu</span>
                                </p>
                                <p class="text-[10px] text-gray-400 flex items-center gap-1 mt-0.5">🏫 {{ $user->school_name ?? '-' }}</p>
                            </div>
                            <span class="text-xs font-extrabold text-[#F28C28] shrink-0">{{ number_format($user->xp_points) }} XP</span>
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
            @endphp
            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h2 class="font-extrabold text-gray-900 text-sm flex items-center gap-1.5 mb-4">
                    <span>📊</span> Performa Saya
                </h2>

                {{-- Stats grid --}}
                <div class="grid grid-cols-3 gap-3 mb-5">
                    <div class="rounded-xl bg-[#F0FDF4] p-3 text-center">
                        <p class="text-[10px] text-gray-500 mb-1">Akurasi</p>
                        <p class="text-xl font-extrabold text-[#16A34A]">{{ $accuracy }}%</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Rata-rata benar</p>
                    </div>
                    <div class="rounded-xl bg-[#EFF6FF] p-3 text-center">
                        <p class="text-[10px] text-gray-500 mb-1">Soal Dikerjakan</p>
                        <p class="text-xl font-extrabold text-[#2563EB]">{{ $totalAnswered }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Total</p>
                    </div>
                    <div class="rounded-xl bg-[#FFF7ED] p-3 text-center">
                        <p class="text-[10px] text-gray-500 mb-1">Streak</p>
                        <p class="text-xl font-extrabold text-[#EA580C]">{{ $user->streak_days }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Hari Berlath!</p>
                    </div>
                </div>

                {{-- XP Progress --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <p class="text-xs font-bold text-gray-700">XP Mingguan</p>
                        <p class="text-xs font-bold text-gray-500">{{ number_format($xpProgress) }} / {{ number_format($xpForNextLevel) }} XP</p>
                    </div>
                    <div class="h-3 w-full overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full rounded-full bg-gradient-to-r from-[#F28C28] to-[#FFC000] transition-all duration-700"
                             style="width: {{ $xpPercent }}%"></div>
                    </div>
                    <div class="mt-2 flex items-center justify-between">
                        <p class="text-[10px] text-gray-400">
                            @if($xpPercent >= 60)
                                Mantap! Kamu semakin dekat ke Level {{ $level + 1 }}
                            @else
                                Terus berlatih dan raih peringkat tertinggi!
                            @endif
                        </p>
                        <div class="flex items-center gap-1 text-[10px] font-bold text-[#D4AC0D]">
                            <span>👑</span> Level {{ $level }}
                        </div>
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
