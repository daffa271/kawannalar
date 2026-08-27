<x-layouts.siswa title="{{ $quiz->title }} — Uji Nalar KawanNalar">
<div class="mx-auto max-w-3xl px-1 sm:px-0"
     x-data="{
        current: 0,
        total: {{ $questions->count() }},
        answers: {},
        timeLeft: {{ $quiz->total_questions * 60 }},
        timer: null,
        started: false,
        finished: false,
        start() {
            this.started = true;
            this.timer = setInterval(() => {
                if (this.timeLeft > 0) { this.timeLeft--; }
                else { clearInterval(this.timer); this.finished = true; }
            }, 1000);
        },
        get minutes() { return String(Math.floor(this.timeLeft / 60)).padStart(2, '0'); },
        get seconds() { return String(this.timeLeft % 60).padStart(2, '0'); },
        get progress() { return Math.round(((this.current + 1) / this.total) * 100); },
        get answeredCount() { return Object.keys(this.answers).length; }
     }"
     x-init="start()">

    {{-- Header --}}
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs text-gray-400 font-semibold">{{ $quiz->subject->name ?? '' }} · Kelas {{ $quiz->class_level }}</p>
            <h1 class="text-base font-extrabold text-gray-900 sm:text-lg mt-0.5">{{ $quiz->title }}</h1>
        </div>
        {{-- Timer --}}
        <div class="flex items-center gap-2 rounded-2xl border border-gray-100 bg-white px-4 py-2.5 shadow-sm shrink-0">
            <span class="text-base">⏱</span>
            <span class="font-extrabold text-gray-900 text-sm tabular-nums" x-text="`${minutes}:${seconds}`">00:00</span>
            <span class="text-xs text-gray-400">tersisa</span>
        </div>
    </div>

    {{-- Progress bar --}}
    <div class="mb-5">
        <div class="flex items-center justify-between text-xs text-gray-500 mb-1.5">
            <span x-text="`Soal ${current + 1} dari ${total}`">Soal 1 dari {{ $questions->count() }}</span>
            <span x-text="`${answeredCount} terjawab`">0 terjawab</span>
        </div>
        <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100">
            <div class="h-full rounded-full bg-gradient-to-r from-[#0A52C4] to-[#3B82F6] transition-all duration-500"
                 :style="`width: ${progress}%`"></div>
        </div>
    </div>

    <form action="{{ route('siswa.uji-nalar.submit', $quiz) }}" method="POST" id="quiz-form">
        @csrf
        {{-- Question cards --}}
        @foreach($questions as $i => $question)
        <div x-show="current === {{ $i }}" x-cloak class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-7">
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#0A52C4] mb-3">Soal {{ $i + 1 }}</p>
            <p class="text-sm font-semibold text-gray-900 leading-relaxed mb-5 sm:text-base">{{ $question->question_text }}</p>
            <div class="space-y-3">
                @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                @php $text = $question->{'option_' . strtolower($opt)}; @endphp
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border-2 p-3.5 transition-all
                              has-[:checked]:border-[#0A52C4] has-[:checked]:bg-[#EEF4FF]
                              border-gray-100 hover:border-[#0A52C4]/30 hover:bg-gray-50">
                    <input type="radio"
                           name="answers[{{ $question->id }}]"
                           value="{{ $opt }}"
                           class="mt-0.5 accent-[#0A52C4] shrink-0"
                           x-on:change="answers[{{ $question->id }}] = '{{ $opt }}'">
                    <div class="flex items-start gap-2">
                        <span class="font-extrabold text-gray-500 text-sm shrink-0">{{ $opt }}.</span>
                        <span class="text-sm text-gray-700 leading-relaxed">{{ $text }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Navigation --}}
        <div class="mt-5 flex items-center justify-between gap-3">
            <button type="button" @click="if(current > 0) current--"
                    :disabled="current === 0"
                    class="flex items-center gap-2 rounded-xl border border-gray-200 px-5 py-2.5 text-xs font-bold text-gray-600 disabled:opacity-40 hover:bg-gray-50 transition">
                ‹ Sebelumnya
            </button>

            <div class="flex gap-2 overflow-x-auto pb-1" style="max-width: 200px;">
                @foreach($questions as $i => $q)
                <button type="button" @click="current = {{ $i }}"
                        class="h-7 w-7 shrink-0 rounded-lg text-[11px] font-bold transition-all"
                        :class="current === {{ $i }}
                            ? 'bg-[#0A52C4] text-white'
                            : (answers[{{ $q->id }}] ? 'bg-[#22C55E] text-white' : 'bg-gray-100 text-gray-600')">
                    {{ $i + 1 }}
                </button>
                @endforeach
            </div>

            <template x-if="current < total - 1">
                <button type="button" @click="current++"
                        class="flex items-center gap-2 rounded-xl bg-[#0A52C4] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#0842A0] transition">
                    Berikutnya ›
                </button>
            </template>
            <template x-if="current === total - 1">
                <button type="submit"
                        class="flex items-center gap-2 rounded-xl bg-[#22C55E] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#16A34A] transition shadow-md">
                    ✓ Kumpulkan Jawaban
                </button>
            </template>
        </div>
    </form>
</div>
</x-layouts.siswa>
