@php
    // Dipakai paket soal (Bank Soal) dan Nalar Kilat; paket soal memakai data $quiz.
    $pageTitle = $pageTitle ?? $quiz->title;
    $pageMeta = $pageMeta ?? (($quiz->subject->name ?? '').' · Kelas '.$quiz->class_level);
    $retryUrl = $retryUrl ?? route('siswa.uji-nalar.show', $quiz);
    $retryLabel = $retryLabel ?? 'Ulangi Quiz';
    $passed = $score >= 70;
@endphp
<x-layouts.siswa title="Hasil Quiz — Uji Nalar KawanNalar">
<div class="mx-auto max-w-3xl space-y-6 px-1 sm:px-0">

    {{-- Score Card --}}
    <div class="overflow-hidden rounded-2xl shadow-lg">
        <div class="bg-gradient-to-br from-primary to-primary-dark p-6 text-center text-white">
            <p class="mb-2 text-xs font-bold uppercase tracking-widest text-white/70">Hasil Quiz</p>
            <h1 class="mb-1 text-2xl font-extrabold sm:text-3xl">{{ $pageTitle }}</h1>
            <p class="text-sm text-white/80">{{ $pageMeta }}</p>
        </div>
        <div class="bg-white p-5 sm:p-6">
            <div class="grid grid-cols-3 gap-3 text-center sm:gap-4">
                {{-- Hijau/merah hanya untuk lulus/belum (semantik) --}}
                <div class="rounded-2xl p-3 sm:p-4 {{ $passed ? 'bg-green-50' : 'bg-red-50' }}">
                    <p class="text-2xl font-extrabold sm:text-3xl {{ $passed ? 'text-green-700' : 'text-red-600' }}">{{ $score }}</p>
                    <p class="mt-1 text-xs text-gray-500">Nilai</p>
                </div>
                <div class="rounded-2xl bg-surface p-3 sm:p-4">
                    <p class="text-2xl font-extrabold text-gray-900 sm:text-3xl">{{ $correctCount }}/{{ $total }}</p>
                    <p class="mt-1 text-xs text-gray-500">Benar</p>
                </div>
                <div class="rounded-2xl bg-cta/10 p-3 sm:p-4">
                    <p class="inline-flex items-center justify-center gap-0.5 text-2xl font-extrabold text-navy sm:text-3xl">
                        <x-icon name="bolt" class="h-6 w-6 text-cta-dark" />+{{ $xpGained }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">XP Didapat</p>
                </div>
            </div>
            @if($passed)
            <div class="mt-4 flex items-center justify-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
                <x-icon name="task_alt" class="h-5 w-5" /> Selamat! Kamu lulus paket soal ini!
            </div>
            @else
            <div class="mt-4 flex items-center justify-center gap-2 rounded-xl border border-primary/15 bg-primary/5 px-4 py-3 text-sm font-semibold text-primary-dark">
                <x-icon name="lightbulb" class="h-5 w-5" /> Jangan menyerah! Pelajari pembahasan, lalu coba lagi.
            </div>
            @endif
        </div>
    </div>

    {{-- Pembahasan --}}
    <div class="space-y-4">
        <h2 class="flex items-center gap-2 text-base font-extrabold text-gray-900">
            <x-icon name="menu_book" class="h-5 w-5 text-primary" /> Pembahasan Jawaban
        </h2>
        @foreach($results as $i => $result)
        @php
            $q = $result['question'];
            $right = $result['is_correct'];
        @endphp
        <div class="rounded-2xl border bg-white p-4 shadow-sm sm:p-5 {{ $right ? 'border-green-200' : 'border-red-200' }}">
            <div class="mb-3 flex items-start gap-3">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $right ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                    <x-icon :name="$right ? 'check' : 'close'" class="h-5 w-5" />
                </span>
                <div class="min-w-0">
                    <p class="mb-1 text-[11px] font-bold uppercase tracking-wider {{ $right ? 'text-green-700' : 'text-red-600' }}">
                        Soal {{ $i + 1 }} — {{ $right ? 'Benar' : ($result['given'] === '' ? 'Tidak dijawab' : 'Salah') }}
                    </p>
                    <p class="text-sm font-semibold leading-relaxed text-gray-800">{{ $q->question_text }}</p>
                </div>
            </div>
            <div class="mb-3 space-y-1.5 sm:ml-11">
                @foreach(['A','B','C','D','E'] as $opt)
                @php
                    $optText = $q->{'option_' . strtolower($opt)};
                    $isCorrect = $opt === $result['correct'];
                    $isWrongPick = $opt === $result['given'] && ! $isCorrect;
                @endphp
                <div class="flex items-start gap-2 rounded-lg px-3 py-2 text-sm
                    {{ $isCorrect ? 'bg-green-50 font-semibold text-green-800' : ($isWrongPick ? 'bg-red-50 text-red-700' : 'text-gray-600') }}">
                    <span class="shrink-0 font-bold">{{ $opt }}.</span>
                    <span class="min-w-0 flex-1">{{ $optText }}</span>
                    @if($isCorrect)
                        <span class="inline-flex shrink-0 items-center gap-0.5 text-[11px] font-bold"><x-icon name="check" class="h-4 w-4" /> Kunci</span>
                    @elseif($isWrongPick)
                        <span class="inline-flex shrink-0 items-center gap-0.5 text-[11px] font-bold"><x-icon name="close" class="h-4 w-4" /> Jawabanmu</span>
                    @endif
                </div>
                @endforeach
            </div>
            @if($q->explanation)
            <div class="rounded-xl border border-gray-200 bg-surface px-4 py-3 sm:ml-11">
                <p class="mb-1 flex items-center gap-1 text-[11px] font-bold text-primary"><x-icon name="lightbulb" class="h-4 w-4" /> Pembahasan</p>
                <p class="text-xs leading-relaxed text-gray-700">{{ $q->explanation }}</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Actions --}}
    <div class="grid gap-3 sm:grid-cols-2">
        <a href="{{ $retryUrl }}"
           class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary py-3 text-center text-sm font-bold text-white transition hover:bg-primary-dark">
            <x-icon name="restart_alt" class="h-5 w-5" /> {{ $retryLabel }}
        </a>
        <a href="{{ route('siswa.uji-nalar.index') }}"
           class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-gray-200 bg-white py-3 text-center text-sm font-bold text-gray-700 transition hover:bg-gray-50">
            <x-icon name="arrow_back" class="h-5 w-5" /> Kembali ke Uji Nalar
        </a>
    </div>

</div>
</x-layouts.siswa>
