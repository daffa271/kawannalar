@php
    // Dipakai paket soal (Bank Soal) dan Nalar Kilat; paket soal memakai data $quiz.
    $pageTitle = $pageTitle ?? $quiz->title;
    $pageMeta = $pageMeta ?? (($quiz->subject->name ?? '').' · Kelas '.$quiz->class_level);
    $timeLimit = $timeLimit ?? $quiz->total_questions * 60;
    $submitUrl = $submitUrl ?? route('siswa.uji-nalar.submit', $quiz);
@endphp
<x-layouts.siswa title="{{ $pageTitle }} — Uji Nalar KawanNalar">
<div class="mx-auto max-w-3xl px-1 sm:px-0"
     x-data="{
        current: 0,
        total: {{ $questions->count() }},
        ids: @js($questions->pluck('id')->values()),
        answers: {},
        timeLeft: {{ $timeLimit }},
        timer: null,
        started: false,
        finished: false,
        submitting: false,
        confirmOpen: false,
        start() {
            this.started = true;
            this.timer = setInterval(() => {
                if (this.timeLeft > 0) { this.timeLeft--; }
                if (this.timeLeft <= 0) { clearInterval(this.timer); this.timeUp(); }
            }, 1000);
        },
        timeUp() {
            this.finished = true;
            this.confirmOpen = false;
            this.submitNow();
        },
        requestSubmit() {
            if (this.answeredCount < this.total) { this.confirmOpen = true; return; }
            this.submitNow();
        },
        submitNow() {
            if (this.submitting) return;
            this.submitting = true;
            clearInterval(this.timer);
            this.$refs.form.submit();
        },
        reviewFirstUnanswered() {
            const index = this.ids.findIndex((id) => !this.answers[id]);
            this.confirmOpen = false;
            if (index >= 0) this.current = index;
        },
        get minutes() { return String(Math.floor(this.timeLeft / 60)).padStart(2, '0'); },
        get seconds() { return String(this.timeLeft % 60).padStart(2, '0'); },
        get progress() { return Math.round(((this.current + 1) / this.total) * 100); },
        get answeredCount() { return Object.keys(this.answers).length; },
        get unanswered() { return this.total - this.answeredCount; },
        get warning() { return this.timeLeft <= 60; }
     }"
     x-init="start()">

    {{-- Header --}}
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400">{{ $pageMeta }}</p>
            <h1 class="mt-0.5 text-base font-extrabold text-gray-900 sm:text-lg">{{ $pageTitle }}</h1>
        </div>
        {{-- Timer: berubah oranye di 60 detik terakhir --}}
        <div class="flex shrink-0 items-center gap-2 rounded-2xl border px-4 py-2.5 shadow-sm transition-colors"
             :class="warning ? 'border-cta/60 bg-cta/10' : 'border-gray-100 bg-white'"
             role="timer" aria-live="polite">
            <x-icon name="timer" class="h-5 w-5 text-primary" x-show="!warning" />
            <x-icon name="hourglass_bottom" class="h-5 w-5 text-navy" x-show="warning" x-cloak />
            <span class="text-sm font-extrabold tabular-nums text-gray-900" x-text="`${minutes}:${seconds}`">{{ sprintf('%02d:%02d', intdiv($timeLimit, 60), $timeLimit % 60) }}</span>
            <span class="text-xs" :class="warning ? 'font-semibold text-navy' : 'text-gray-400'" x-text="warning ? 'Waktu hampir habis' : 'tersisa'">tersisa</span>
        </div>
    </div>

    {{-- Progress bar --}}
    <div class="mb-5">
        <div class="mb-1.5 flex items-center justify-between text-xs text-gray-500">
            <span x-text="`Soal ${current + 1} dari ${total}`">Soal 1 dari {{ $questions->count() }}</span>
            <span x-text="`${answeredCount} terjawab`">0 terjawab</span>
        </div>
        <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100">
            <div class="h-full rounded-full bg-primary transition-all duration-500" :style="`width: ${progress}%`"></div>
        </div>
    </div>

    <form action="{{ $submitUrl }}" method="POST" id="quiz-form" x-ref="form">
        @csrf
        {{-- Question cards --}}
        @foreach($questions as $i => $question)
        <div x-show="current === {{ $i }}" x-cloak class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-7">
            <p class="mb-3 text-[11px] font-bold uppercase tracking-wider text-primary">Soal {{ $i + 1 }}</p>
            <p class="mb-5 text-sm font-semibold leading-relaxed text-gray-900 sm:text-base">{{ $question->question_text }}</p>
            <div class="space-y-3" role="radiogroup" aria-label="Pilihan jawaban soal {{ $i + 1 }}">
                @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                @php $text = $question->{'option_' . strtolower($opt)}; @endphp
                {{-- Radio disembunyikan secara visual (tetap bisa dipakai keyboard); badge huruf jadi penanda pilihan --}}
                <label class="group flex cursor-pointer items-start gap-3 rounded-xl border-2 border-gray-100 p-3.5 transition-all hover:border-primary/30 hover:bg-gray-50 has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-primary/40">
                    <input type="radio"
                           name="answers[{{ $question->id }}]"
                           value="{{ $opt }}"
                           class="sr-only"
                           x-on:change="answers[{{ $question->id }}] = '{{ $opt }}'">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-xs font-extrabold text-gray-600 transition group-has-[:checked]:bg-primary group-has-[:checked]:text-white">{{ $opt }}</span>
                    <span class="pt-0.5 text-sm leading-relaxed text-gray-700">{{ $text }}</span>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Peta soal: dibuka = biru, terjawab = biru muda, belum = abu-abu --}}
        <div class="mt-5 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex flex-wrap gap-2">
                @foreach($questions as $i => $q)
                <button type="button" @click="current = {{ $i }}" aria-label="Soal {{ $i + 1 }}"
                        class="h-8 w-8 shrink-0 rounded-lg text-xs font-bold transition-all"
                        :class="current === {{ $i }}
                            ? 'bg-primary text-white'
                            : (answers[{{ $q->id }}] ? 'bg-primary/15 text-primary' : 'bg-gray-100 text-gray-500 hover:bg-gray-200')">
                    {{ $i + 1 }}
                </button>
                @endforeach
            </div>
            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-gray-500">
                <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded bg-primary"></span> Sedang dibuka</span>
                <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded bg-primary/15"></span> Terjawab</span>
                <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded bg-gray-200"></span> Belum dijawab</span>
            </div>
        </div>

        {{-- Navigasi --}}
        <div class="mt-4 grid grid-cols-2 gap-3">
            <button type="button" @click="if (current > 0) current--"
                    :disabled="current === 0"
                    class="inline-flex items-center justify-center gap-1 rounded-xl border border-gray-200 bg-white px-4 py-3 text-xs font-bold text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">
                <x-icon name="chevron_left" class="h-5 w-5" /> Sebelumnya
            </button>

            <button type="button" @click="current++" x-show="current < total - 1"
                    class="inline-flex items-center justify-center gap-1 rounded-xl bg-primary px-4 py-3 text-xs font-bold text-white transition hover:bg-primary-dark">
                Berikutnya <x-icon name="chevron_right" class="h-5 w-5" />
            </button>
            {{-- Aksi utama halaman: oranye + teks navy --}}
            <button type="button" @click="requestSubmit()" x-show="current === total - 1" x-cloak :disabled="submitting"
                    class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-cta px-4 py-3 text-xs font-bold text-navy shadow-sm transition hover:bg-cta-dark disabled:cursor-wait disabled:opacity-70">
                <x-icon name="send" class="h-4 w-4" />
                <span x-text="submitting ? 'Mengumpulkan…' : 'Kumpulkan Jawaban'">Kumpulkan Jawaban</span>
            </button>
        </div>
    </form>

    {{-- Konfirmasi bila masih ada soal kosong --}}
    <div x-show="confirmOpen" x-transition.opacity @keydown.escape.window="confirmOpen = false"
         class="fixed inset-0 z-[70] flex items-end justify-center bg-gray-900/60 p-4 backdrop-blur-sm sm:items-center"
         style="display:none;" x-cloak>
        <div @click.outside="confirmOpen = false" role="dialog" aria-modal="true" aria-labelledby="confirm-submit-title"
             class="w-full max-w-md rounded-2xl bg-white p-5 shadow-2xl sm:p-6">
            <div class="flex items-start gap-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-cta/15 text-navy">
                    <x-icon name="warning" class="h-6 w-6" />
                </span>
                <div class="min-w-0 flex-1">
                    <h3 id="confirm-submit-title" class="text-base font-extrabold text-gray-900">Kumpulkan sekarang?</h3>
                    <p class="mt-1 text-sm leading-relaxed text-gray-600" x-text="`Masih ada ${unanswered} soal belum dijawab. Soal yang kosong dihitung salah.`"></p>
                </div>
            </div>
            <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row">
                <button type="button" @click="reviewFirstUnanswered()"
                        class="flex-1 rounded-xl border border-gray-200 bg-white py-2.5 text-sm font-bold text-gray-700 transition hover:bg-gray-50">
                    Periksa Lagi
                </button>
                <button type="button" @click="submitNow()"
                        class="flex-1 rounded-xl bg-cta py-2.5 text-sm font-bold text-navy shadow-sm transition hover:bg-cta-dark">
                    Tetap Kumpulkan
                </button>
            </div>
        </div>
    </div>

    {{-- Waktu habis: jawaban dikumpulkan otomatis --}}
    <div x-show="finished" class="fixed inset-0 z-[80] flex items-center justify-center bg-gray-900/60 p-4" style="display:none;" x-cloak>
        <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-2xl" role="alertdialog" aria-labelledby="time-up-title">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-cta/15 text-navy">
                <x-icon name="hourglass_bottom" class="h-6 w-6" />
            </span>
            <p id="time-up-title" class="mt-3 text-base font-extrabold text-gray-900">Waktu habis</p>
            <p class="mt-1 text-sm text-gray-600">Jawabanmu sedang dikumpulkan…</p>
        </div>
    </div>
</div>
</x-layouts.siswa>
