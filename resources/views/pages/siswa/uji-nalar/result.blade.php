<x-layouts.siswa title="Hasil Quiz — Uji Nalar KawanNalar">
<div class="mx-auto max-w-3xl px-1 sm:px-0 space-y-6">

    {{-- Score Card --}}
    <div class="rounded-2xl overflow-hidden shadow-lg">
        <div class="bg-gradient-to-br from-[#0A52C4] to-[#1E40AF] p-6 text-white text-center">
            <p class="text-xs font-bold uppercase tracking-widest text-blue-200 mb-2">Hasil Quiz</p>
            <h1 class="text-2xl font-extrabold sm:text-3xl mb-1">{{ $quiz->title }}</h1>
            <p class="text-blue-100 text-sm">{{ $quiz->subject->name ?? '' }} · Kelas {{ $quiz->class_level }}</p>
        </div>
        <div class="bg-white p-6">
            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="rounded-2xl {{ $score >= 70 ? 'bg-green-50' : 'bg-red-50' }} p-4">
                    <p class="text-3xl font-extrabold {{ $score >= 70 ? 'text-[#16A34A]' : 'text-[#DC2626]' }}">{{ $score }}</p>
                    <p class="text-xs text-gray-500 mt-1">Nilai</p>
                </div>
                <div class="rounded-2xl bg-blue-50 p-4">
                    <p class="text-3xl font-extrabold text-[#2563EB]">{{ $correctCount }}/{{ $total }}</p>
                    <p class="text-xs text-gray-500 mt-1">Benar</p>
                </div>
                <div class="rounded-2xl bg-[#FFF8F0] p-4">
                    <p class="text-3xl font-extrabold text-[#F28C28]">+{{ $xpGained }}</p>
                    <p class="text-xs text-gray-500 mt-1">XP Didapat</p>
                </div>
            </div>
            @if($score >= 70)
            <div class="mt-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-center text-sm font-semibold text-green-700">
                🎉 Selamat! Kamu lulus paket soal ini!
            </div>
            @else
            <div class="mt-4 rounded-xl bg-orange-50 border border-orange-200 px-4 py-3 text-center text-sm font-semibold text-orange-700">
                💪 Jangan menyerah! Coba lagi untuk hasil lebih baik.
            </div>
            @endif
        </div>
    </div>

    {{-- Pembahasan --}}
    <div class="space-y-4">
        <h2 class="font-extrabold text-gray-900 text-base">📖 Pembahasan Jawaban</h2>
        @foreach($results as $i => $result)
        @php $q = $result['question']; @endphp
        <div class="rounded-2xl border-2 {{ $result['is_correct'] ? 'border-green-200 bg-green-50/50' : 'border-red-200 bg-red-50/50' }} p-5">
            <div class="flex items-start gap-3 mb-3">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $result['is_correct'] ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} text-sm font-bold">
                    {{ $result['is_correct'] ? '✓' : '✕' }}
                </span>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider {{ $result['is_correct'] ? 'text-green-600' : 'text-red-600' }} mb-1">
                        Soal {{ $i + 1 }} — {{ $result['is_correct'] ? 'Benar' : 'Salah' }}
                    </p>
                    <p class="text-sm font-semibold text-gray-800 leading-relaxed">{{ $q->question_text }}</p>
                </div>
            </div>
            <div class="ml-11 space-y-1.5 mb-3">
                @foreach(['A','B','C','D','E'] as $opt)
                @php
                    $optText = $q->{'option_' . strtolower($opt)};
                    $isCorrect = $opt === $result['correct'];
                    $isGiven   = $opt === $result['given'];
                @endphp
                <div class="flex items-start gap-2 rounded-lg px-3 py-2 text-sm
                    {{ $isCorrect ? 'bg-green-100 text-green-800 font-semibold' : ($isGiven && !$isCorrect ? 'bg-red-100 text-red-800' : 'text-gray-600') }}">
                    <span class="font-bold shrink-0">{{ $opt }}.</span>
                    <span>{{ $optText }}</span>
                    @if($isCorrect)<span class="ml-auto shrink-0">✓</span>@endif
                    @if($isGiven && !$isCorrect)<span class="ml-auto shrink-0">✕</span>@endif
                </div>
                @endforeach
            </div>
            @if($q->explanation)
            <div class="ml-11 rounded-xl bg-white border border-gray-200 px-4 py-3">
                <p class="text-[11px] font-bold text-[#0A52C4] mb-1">💡 Pembahasan</p>
                <p class="text-xs text-gray-700 leading-relaxed">{{ $q->explanation }}</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Actions --}}
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('siswa.uji-nalar.show', $quiz) }}"
           class="flex-1 rounded-xl bg-[#0A52C4] py-3 text-center text-sm font-bold text-white hover:bg-[#0842A0] transition">
            🔄 Ulangi Quiz
        </a>
        <a href="{{ route('siswa.uji-nalar.index') }}"
           class="flex-1 rounded-xl border border-gray-200 py-3 text-center text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
            ← Kembali ke Uji Nalar
        </a>
    </div>

</div>
</x-layouts.siswa>
