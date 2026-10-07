{{-- Tab "Paket Soal": paket soal Uji Nalar milik mentor. --}}
<section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
    <div class="mb-6 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div class="flex items-start gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                <x-icon name="quiz" class="h-5 w-5" />
            </span>
            <div>
                <h2 class="text-base font-extrabold text-gray-900 sm:text-lg">Uji Nalar: Kelola &amp; Buat Soal</h2>
                <p class="mt-0.5 text-xs text-gray-400">Buat paket soal preset 5, 10, atau 15 soal untuk siswa Magetan.@if($quizTotal > $myQuizzes->count()) Menampilkan {{ $myQuizzes->count() }} terbaru dari {{ $quizTotal }}.@endif</p>
            </div>
        </div>
        @include('pages.mentor.dashboard.partials.create-button', ['href' => route('mentor.uji-nalar.create'), 'icon' => 'add', 'label' => 'Buat Soal Baru'])
    </div>

    @if($myQuizzes->isEmpty())
    <div class="rounded-xl border border-dashed border-gray-200 py-12 text-center">
        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
            <x-icon name="edit_note" class="h-6 w-6" />
        </span>
        <p class="mt-3 text-sm font-bold text-gray-700">Belum ada paket soal</p>
        <p class="mt-1 text-xs text-gray-400">Mulai buat paket soal pertamamu!</p>
    </div>
    @else
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($myQuizzes as $quiz)
        <a href="{{ route('mentor.uji-nalar.show', $quiz) }}" class="group flex flex-col gap-3 rounded-xl border border-gray-100 p-4 shadow-sm transition hover:border-primary/30 hover:bg-primary/5">
            <div class="flex items-center justify-between gap-2">
                <span class="truncate rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-bold text-primary">{{ $quiz->subject->name ?? '-' }}</span>
                @include('pages.mentor.dashboard.partials.status-badge', ['status' => $quiz->status])
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-extrabold leading-snug text-gray-900 group-hover:text-primary">{{ $quiz->title }}</h3>
                <p class="mt-1 text-xs text-gray-400">Kelas {{ $quiz->class_level }} · {{ $quiz->total_questions }} Soal</p>
            </div>
            <div class="flex items-center justify-between border-t border-gray-100 pt-2 text-xs">
                <span class="text-[10px] text-gray-400">{{ $quiz->created_at?->format('d M Y') }}</span>
                <span class="inline-flex items-center font-bold text-primary">Detail Soal <x-icon name="chevron_right" class="h-4 w-4" /></span>
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-4">
        @include('pages.mentor.dashboard.partials.see-all', [
            'href' => route('mentor.uji-nalar.index'),
            'label' => 'Lihat semua paket soal',
            'total' => $quizTotal,
            'shown' => $myQuizzes->count(),
            'always' => true,
        ])
    </div>
    @endif
</section>
