<x-layouts.mentor title="Uji Nalar — Kelola Paket Soal Mentor">
<div class="mx-auto max-w-7xl space-y-6 px-1 sm:px-0">
    @if(auth()->user()->is_suspended)
    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700 flex items-center gap-3">
        <x-icon name="warning" class="h-6 w-6" />
        <span>Akun Anda sedang ditangguhkan oleh Admin. Silakan hubungi dukungan KawanNalar.</span>
    </div>
    @endif

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-[#0A52C4]">Engine Evaluasi &amp; Gamifikasi</p>
            <h1 class="mt-1 text-2xl font-extrabold text-gray-900 sm:text-3xl">Kelola Paket Soal Uji Nalar</h1>
            <p class="mt-1 text-sm text-gray-500">Buat paket soal baru untuk latihan siswa. Soal akan diverifikasi admin sebelum tayang.</p>
        </div>
        @if(auth()->user()->is_suspended)
        <button disabled class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-200 px-5 py-3 text-sm font-bold text-gray-400 cursor-not-allowed">
            <x-icon name="add" class="h-5 w-5" /> Buat Paket Soal Baru
        </button>
        @else
        <a href="{{ route('mentor.uji-nalar.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-cta px-5 py-3 text-sm font-bold text-navy shadow-sm hover:bg-cta-dark transition">
            <x-icon name="add" class="h-5 w-5" /> Buat Paket Soal Baru
        </a>
        @endif
    </div>

    @if(session('status'))
    <div class="flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
        <x-icon name="check_circle" class="h-5 w-5" /> {{ session('status') }}
    </div>
    @endif

    {{-- Alur konten Uji Nalar --}}
    <div class="rounded-2xl border border-[#0A52C4]/15 bg-[#EEF4FF] p-4 sm:p-5">
        <p class="flex items-center gap-1.5 text-sm font-extrabold text-[#0A52C4]"><x-icon name="info" class="h-5 w-5" /> Kamu cukup membuat paket soal</p>
        <p class="mt-1 text-sm leading-relaxed text-gray-700">Bank Soal adalah sumber utama soal. Setelah disetujui Admin, soal dapat digunakan secara otomatis untuk Bank Soal, Flashcard, dan Nalar Kilat.</p>
        <ul class="mt-3 grid gap-2 text-xs text-gray-600 sm:grid-cols-2">
            <li class="flex items-start gap-2 rounded-xl bg-white/70 px-3 py-2"><x-icon name="style" class="mt-px h-4 w-4 text-[#0A52C4]" /> <span>Flashcard dibuat otomatis dari soal yang telah disetujui.</span></li>
            <li class="flex items-start gap-2 rounded-xl bg-white/70 px-3 py-2"><x-icon name="bolt" class="mt-px h-4 w-4 text-[#0A52C4]" /> <span>Nalar Kilat mengambil soal secara acak dari Bank Soal yang telah disetujui.</span></li>
        </ul>
    </div>

    {{-- Filter / Counter Bar --}}
    <div class="grid grid-cols-3 gap-2 sm:gap-4">
        <div class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm text-center sm:p-4">
            <p class="text-xs text-gray-400 font-medium">Total Paket Soal</p>
            <p class="mt-1 text-2xl font-extrabold text-gray-900">{{ $quizzes->count() }}</p>
        </div>
        <div class="rounded-2xl border border-green-100 bg-green-50 p-3 shadow-sm text-center sm:p-4">
            <p class="text-xs text-green-700 font-medium">Disetujui (Tayang)</p>
            <p class="mt-1 text-2xl font-extrabold text-green-700">{{ $quizzes->where('status', 'approved')->count() }}</p>
        </div>
        <div class="rounded-2xl border border-amber-100 bg-amber-50 p-3 shadow-sm text-center sm:p-4">
            <p class="text-xs text-amber-700 font-medium">Menunggu Moderasi</p>
            <p class="mt-1 text-2xl font-extrabold text-amber-700">{{ $quizzes->where('status', 'pending')->count() }}</p>
        </div>
    </div>

    {{-- Daftar Quiz --}}
    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
        <h2 class="font-extrabold text-gray-900 text-base mb-4">Daftar Paket Soal Saya</h2>

        @if($quizzes->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-200 py-12 text-center">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#0A52C4]/10 text-[#0A52C4]"><x-icon name="edit_note" class="h-6 w-6" /></span>
            <p class="mt-3 text-sm font-bold text-gray-700">Belum ada paket soal</p>
            <p class="text-xs text-gray-400 mt-1">Buat preset 5, 10, atau 15 soal untuk membantu siswa mengasah nalar.</p>
            @if(auth()->user()->is_suspended)
            <button disabled class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-gray-200 px-5 py-2.5 text-xs font-bold text-gray-400 cursor-not-allowed">
                <x-icon name="add" class="h-4 w-4" /> Buat Soal Sekarang
            </button>
            @else
            <a href="{{ route('mentor.uji-nalar.create') }}"
               class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-[#0A52C4] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#0842A0] transition">
                <x-icon name="add" class="h-4 w-4" /> Buat Soal Sekarang
            </a>
            @endif
        </div>
        @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($quizzes as $quiz)
            <div class="flex flex-col justify-between rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition hover:border-[#0A52C4]/30 hover:shadow-md">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="rounded-full bg-[#0A52C4]/10 px-2.5 py-1 text-[11px] font-bold text-[#0A52C4]">
                            {{ $quiz->subject->name ?? '-' }}
                        </span>
                        @include('pages.mentor.uji-nalar.partials.status-badge', ['status' => $quiz->status])
                    </div>

                    <h3 class="font-extrabold text-gray-900 text-base leading-snug">{{ $quiz->title }}</h3>
                    <p class="mt-1 text-xs text-gray-400">
                        Kelas {{ $quiz->class_level }} · {{ $quiz->total_questions }} Soal (Pilihan Ganda A–E)
                    </p>
                    @if($quiz->status === 'rejected' && $quiz->rejection_reason)
                    <p class="mt-3 line-clamp-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[11px] leading-relaxed text-red-700">
                        <span class="font-bold">Alasan ditolak:</span> {{ $quiz->rejection_reason }}
                    </p>
                    @endif
                </div>

                <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-3">
                    <span class="text-[11px] text-gray-400">{{ $quiz->created_at?->format('d M Y') }}</span>
                    <a href="{{ route('mentor.uji-nalar.show', $quiz) }}"
                       class="inline-flex items-center gap-1 text-xs font-bold text-[#0A52C4] hover:underline">
                        Lihat Detail Soal <x-icon name="chevron_right" class="h-4 w-4" />
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
</x-layouts.mentor>
