<x-layouts.admin title="Moderasi Paket Soal — Admin Hub">
<div class="mx-auto max-w-7xl space-y-6 px-1 sm:px-0" x-data="{ rejectModal: false, targetQuizId: null, targetQuizTitle: '' }">
    <div>
        <p class="text-xs font-semibold uppercase tracking-wider text-[#0A52C4]">Admin Moderation Hub</p>
        <h1 class="mt-1 text-2xl font-extrabold text-gray-900 sm:text-3xl">Moderasi Paket Soal Uji Nalar</h1>
        <p class="mt-1 text-sm text-gray-500">Tinjau dan beri persetujuan paket soal yang disubmit oleh mentor.</p>
    </div>

    @if(session('status'))
    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
        ✓ {{ session('status') }}
    </div>
    @endif

    {{-- ── PAKET SOAL PENDING ─────────────────────────────────────── --}}
    <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-gray-900 text-base flex items-center gap-2">
                ⏳ Menunggu Persetujuan
                <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-extrabold text-amber-700">
                    {{ $pendingQuizzes->count() }}
                </span>
            </h2>
        </div>

        @if($pendingQuizzes->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-200 py-10 text-center text-xs text-gray-400">
            Tidak ada paket soal yang menunggu moderasi.
        </div>
        @else
        <div class="space-y-4">
            @foreach($pendingQuizzes as $quiz)
            <div x-data="{ expanded: false }" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm space-y-3">
                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="rounded-full bg-[#0A52C4]/10 px-2 py-0.5 text-[10px] font-bold text-[#0A52C4]">
                                {{ $quiz->subject->name ?? '-' }}
                            </span>
                            <span class="text-xs text-gray-400">Kelas {{ $quiz->class_level }} · {{ $quiz->total_questions }} Soal</span>
                            <span class="text-xs text-gray-400">· Mentor: <strong class="text-gray-700">{{ $quiz->mentor->name ?? '-' }}</strong></span>
                        </div>
                        <h3 class="font-extrabold text-gray-900 text-base">{{ $quiz->title }}</h3>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <button @click="expanded = !expanded" type="button"
                                class="rounded-xl border border-gray-200 px-3 py-2 text-xs font-bold text-gray-600 hover:bg-gray-50 transition">
                            <span x-text="expanded ? 'Sembunyikan Soal' : 'Preview Soal'"></span>
                        </button>

                        <form method="POST" action="{{ route('admin.quizzes.approve', $quiz) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-xl bg-green-600 px-4 py-2 text-xs font-bold text-white hover:bg-green-700 transition shadow-sm">
                                ✓ Setujui
                            </button>
                        </form>

                        <button @click="targetQuizId = {{ $quiz->id }}; targetQuizTitle = '{{ addslashes($quiz->title) }}'; rejectModal = true;"
                                type="button"
                                class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-100 transition">
                            ✕ Tolak
                        </button>
                    </div>
                </div>

                {{-- Accordion Questions Preview --}}
                <div x-show="expanded" x-cloak class="mt-3 border-t border-gray-100 pt-3 space-y-3">
                    <p class="text-xs font-bold text-gray-700">Daftar {{ $quiz->questions->count() }} Soal:</p>
                    @foreach($quiz->questions as $i => $q)
                    <div class="rounded-lg bg-gray-50 p-3 text-xs space-y-1.5">
                        <p class="font-bold text-gray-800">{{ $i+1 }}. {{ $q->question_text }}</p>
                        <div class="grid grid-cols-2 gap-1 text-gray-600 pl-4">
                            <span>A. {{ $q->option_a }}</span>
                            <span>B. {{ $q->option_b }}</span>
                            <span>C. {{ $q->option_c }}</span>
                            <span>D. {{ $q->option_d }}</span>
                            <span class="col-span-2">E. {{ $q->option_e }}</span>
                        </div>
                        <p class="text-[11px] font-bold text-green-700">Kunci Benar: {{ $q->correct_answer }}</p>
                        @if($q->explanation)
                        <p class="text-[11px] text-gray-500">Pembahasan: {{ $q->explanation }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </section>

    {{-- ── PAKET SOAL APPROVED (RIWAYAT) ──────────────────────────── --}}
    <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6 space-y-4">
        <h2 class="font-extrabold text-gray-900 text-base">✓ Riwayat Paket Soal Disetujui</h2>

        @if($approvedQuizzes->isEmpty())
        <p class="text-xs text-gray-400">Belum ada paket soal yang disetujui.</p>
        @else
        <div class="divide-y divide-gray-100">
            @foreach($approvedQuizzes as $quiz)
            <div class="flex items-center justify-between py-3">
                <div>
                    <p class="text-xs font-bold text-gray-800">{{ $quiz->title }}</p>
                    <p class="text-[10px] text-gray-400">
                        {{ $quiz->subject->name ?? '-' }} · Kelas {{ $quiz->class_level }} · Mentor: {{ $quiz->mentor->name ?? '-' }}
                    </p>
                </div>
                <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-[10px] font-bold text-green-700">Aktif Tayang</span>
            </div>
            @endforeach
        </div>
        @endif
    </section>

    {{-- Modal Tolak --}}
    <div x-show="rejectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-[#0F1F3D]/50 p-4">
        <div @click.outside="rejectModal = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <h3 class="font-extrabold text-gray-900 text-base">Tolak Paket Soal</h3>
            <p class="text-xs text-gray-500 mt-1" x-text="targetQuizTitle"></p>

            <form :action="`/admin/quizzes/${targetQuizId}/reject`" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Alasan Penolakan</label>
                    <textarea name="reason" rows="3" required placeholder="Tuliskan catatan perbaikan untuk mentor..."
                              class="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-xs focus:border-red-500 focus:outline-none"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="rejectModal = false" class="rounded-xl border border-gray-200 px-4 py-2 text-xs font-bold text-gray-600">Batal</button>
                    <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white hover:bg-red-700">Tolak Paket Soal</button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-layouts.admin>
