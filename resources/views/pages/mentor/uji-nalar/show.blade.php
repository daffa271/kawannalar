<x-layouts.mentor title="Detail Paket Soal — Mentor">
<div class="mx-auto max-w-5xl space-y-6 px-1 sm:px-0">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('mentor.uji-nalar.index') }}" class="text-xs font-bold text-[#0A52C4] hover:underline">‹ Kembali ke Kelola Soal</a>
            <h1 class="mt-1 text-2xl font-extrabold text-gray-900">{{ $quiz->title }}</h1>
            <p class="mt-0.5 text-xs text-gray-400">
                {{ $quiz->subject->name ?? '-' }} · Kelas {{ $quiz->class_level }} · {{ $quiz->total_questions }} Soal
            </p>
        </div>
        <span class="rounded-full px-3 py-1 text-xs font-extrabold
            {{ $quiz->status === 'approved' ? 'bg-green-100 text-green-700' : ($quiz->status === 'rejected' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-700') }}">
            {{ $quiz->status === 'approved' ? '✓ Disetujui (Tayang)' : ($quiz->status === 'rejected' ? '✕ Ditolak Admin' : '⏳ Menunggu Moderasi') }}
        </span>
    </div>

    {{-- Alert jika rejected --}}
    @if($quiz->status === 'rejected' && $quiz->rejection_reason)
    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-xs text-red-700">
        <p class="font-bold">Alasan Penolakan dari Admin:</p>
        <p class="mt-1">{{ $quiz->rejection_reason }}</p>
    </div>
    @endif

    {{-- Daftar Pertanyaan --}}
    <div class="space-y-4">
        @foreach($quiz->questions as $index => $q)
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                <span class="font-extrabold text-xs text-[#0A52C4]">Soal #{{ $index + 1 }}</span>
                <span class="text-[10px] font-bold text-gray-400">Kunci Jawaban: <span class="text-green-600 font-extrabold">{{ $q->correct_answer }}</span></span>
            </div>

            <p class="text-sm font-semibold text-gray-800 leading-relaxed">{{ $q->question_text }}</p>

            <div class="grid gap-2 sm:grid-cols-2 text-xs">
                @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d, 'E' => $q->option_e] as $optKey => $optVal)
                <div class="rounded-xl border p-2.5 flex items-center gap-2
                            {{ $q->correct_answer === $optKey ? 'border-green-300 bg-green-50 text-green-900 font-bold' : 'border-gray-100 bg-gray-50 text-gray-700' }}">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-extrabold
                                 {{ $q->correct_answer === $optKey ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                        {{ $optKey }}
                    </span>
                    <span class="truncate">{{ $optVal }}</span>
                </div>
                @endforeach
            </div>

            @if($q->explanation)
            <div class="mt-2 rounded-xl bg-[#EEF4FF] p-3 text-xs text-gray-700">
                <span class="font-bold text-[#0A52C4]">Pembahasan:</span> {{ $q->explanation }}
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
</x-layouts.mentor>
