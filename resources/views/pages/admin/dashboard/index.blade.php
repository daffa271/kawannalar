<x-layouts.admin title="Dashboard Admin — KawanNalar Control Center">
<div class="mx-auto max-w-7xl space-y-6 px-1 sm:px-0">

    {{-- ── HERO WELCOME BANNER ADMIN INOVATOR ───────────────────────── --}}
    <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#0F1F3D] via-[#0A52C4] to-[#1565D8] p-6 text-white shadow-lg sm:p-8">
        <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-8 right-32 h-32 w-32 rounded-full bg-white/5"></div>
        <div class="relative z-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-500/20 px-3 py-1 text-[11px] font-bold text-blue-200 border border-blue-400/30">
                        <x-icon name="admin_panel_settings" class="h-3.5 w-3.5" /> Admin System · 4 Inovator KawanNalar
                    </span>
                </div>
                <h1 class="mt-2 text-2xl font-extrabold leading-tight sm:text-3xl">
                    Innovation Control Center
                </h1>
                <p class="mt-1.5 text-xs text-blue-100 max-w-2xl leading-relaxed">
                    Pantau pertumbuhan ekosistem KawanNalar Magetan, moderasikan modul pembelajaran dari mahasiswa, dan verifikasi paket Uji Nalar secara real-time.
                </p>
            </div>
            <div class="flex flex-wrap gap-2.5 shrink-0">
                <a href="{{ route('admin.verification.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-[#F28C28] px-4 py-2.5 text-xs font-bold text-white shadow-md hover:bg-[#E07D1C] transition">
                    <x-icon name="verified" class="h-4 w-4" /> Verifikasi Mentor &amp; Modul
                    @if(($pendingMentorCount + $pendingModuleCount) > 0)
                    <span class="rounded-full bg-white px-2 py-0.5 text-[10px] font-extrabold text-[#F28C28]">
                        {{ $pendingMentorCount + $pendingModuleCount }}
                    </span>
                    @endif
                </a>
                <a href="{{ route('admin.quizzes.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-4 py-2.5 text-xs font-bold text-white hover:bg-white/20 transition">
                    <x-icon name="quiz" class="h-4 w-4" /> Moderasi Uji Nalar
                    @if($pendingQuizCount > 0)
                    <span class="rounded-full bg-amber-400 px-2 py-0.5 text-[10px] font-extrabold text-gray-900">
                        {{ $pendingQuizCount }}
                    </span>
                    @endif
                </a>
            </div>
        </div>
    </section>

    {{-- ── 4 STAT CARDS REVISITED ────────────────────────────────────── --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-400">Total Siswa</p>
                <span class="rounded-lg bg-blue-50 p-2 text-base text-[#0A52C4]"><x-icon name="school" class="my-0.5 block h-5 w-5" /></span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-[#0A52C4]">{{ number_format($totalStudents) }}</p>
            <p class="mt-0.5 text-[10px] text-gray-400">Siswa Magetan terdaftar</p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-400">Mentor Aktif</p>
                <span class="rounded-lg bg-emerald-50 p-2 text-base text-emerald-600"><x-icon name="co_present" class="my-0.5 block h-5 w-5" /></span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-emerald-600">{{ number_format($activeMentors) }}</p>
            <p class="mt-0.5 text-[10px] text-gray-400">Terverifikasi &amp; Siap Mengajar</p>
        </div>

        <div class="rounded-2xl border border-amber-100 bg-amber-50/50 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-amber-800">Pending Review</p>
                <span class="rounded-lg bg-amber-100 p-2 text-base text-amber-600"><x-icon name="hourglass_bottom" class="my-0.5 block h-5 w-5" /></span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-amber-600">
                {{ $pendingMentorCount + $pendingModuleCount + $pendingQuizCount }}
            </p>
            <p class="mt-0.5 text-[10px] text-amber-700 font-medium">Mentor, Modul &amp; Paket Soal</p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-400">Soal &amp; Modul Tayang</p>
                <span class="rounded-lg bg-indigo-50 p-2 text-base text-indigo-600"><x-icon name="library_books" class="my-0.5 block h-5 w-5" /></span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-indigo-600">
                {{ $approvedModuleCount + $approvedQuizCount }}
            </p>
            <p class="mt-0.5 text-[10px] text-gray-400">Materi Siap Belajar</p>
        </div>
    </div>

    {{-- ── DUA KOLOM MODERASI QUICK ACCESS ───────────────────────────── --}}
    <div class="grid gap-6 lg:grid-cols-12">

        {{-- Kolom Kiri: Antrean Verifikasi Mentor & Modul --}}
        <div class="space-y-5 lg:col-span-6">
            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-4 border-b border-gray-100 pb-3">
                    <div class="min-w-0 flex-1">
                        <h2 class="font-extrabold text-gray-900 text-sm sm:text-base flex flex-wrap items-center gap-x-2 gap-y-1">
                            <span class="inline-flex items-start gap-1.5"><x-icon name="verified" class="mt-0.5 h-4 w-4 text-[#0A52C4] sm:h-5 sm:w-5" />Pendaftaran Mentor Pending</span>
                            @if($pendingMentorCount > 0)
                            <span class="whitespace-nowrap rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-extrabold text-amber-700">
                                {{ $pendingMentorCount }} Baru
                            </span>
                            @endif
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">Tinjau berkas pendaftaran mahasiswa calon mentor</p>
                    </div>
                    <a href="{{ route('admin.verification.index') }}" class="inline-flex items-center shrink-0 whitespace-nowrap pt-0.5 text-xs font-bold text-[#0A52C4] hover:underline">
                        Lihat Semua <x-icon name="chevron_right" class="h-3.5 w-3.5" />
                    </a>
                </div>

                @if($recentMentors->isEmpty())
                <div class="flex items-center justify-center gap-1.5 rounded-xl border border-dashed border-gray-200 py-8 text-center text-xs text-gray-400">
                    <x-icon name="check_circle" class="h-3.5 w-3.5" /> Tidak ada pendaftaran mentor yang menggantung.
                </div>
                @else
                <div class="divide-y divide-gray-50">
                    @foreach($recentMentors as $mentor)
                    <div class="flex items-center justify-between gap-3 py-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-gray-800 truncate">{{ $mentor->name }}</p>
                            <p class="text-[10px] text-gray-400">
                                {{ $mentor->mentorProfile?->university ?? 'Perguruan Tinggi' }} · {{ $mentor->mentorProfile?->major ?? 'Jurusan' }}
                            </p>
                        </div>
                        @if ($mentor->mentorProfile?->ktm_path)
                        <x-ktm-preview-link :mentor="$mentor"
                           class="shrink-0 rounded-xl border border-[#0A52C4] px-3 py-1.5 text-[11px] font-bold text-[#0A52C4] hover:bg-[#EEF4FF] transition">
                            Review KTM
                        </x-ktm-preview-link>
                        @else
                        <a href="{{ route('admin.verification.index') }}"
                           class="shrink-0 rounded-xl border border-[#0A52C4] px-3 py-1.5 text-[11px] font-bold text-[#0A52C4] hover:bg-[#EEF4FF] transition">
                            Review Berkas
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </section>
        </div>

        {{-- Kolom Kanan: Antrean Uji Nalar Soal --}}
        <div class="space-y-5 lg:col-span-6">
            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-4 border-b border-gray-100 pb-3">
                    <div class="min-w-0 flex-1">
                        <h2 class="font-extrabold text-gray-900 text-sm sm:text-base flex flex-wrap items-center gap-x-2 gap-y-1">
                            <span class="inline-flex items-start gap-1.5"><x-icon name="quiz" class="mt-0.5 h-4 w-4 text-[#0A52C4] sm:h-5 sm:w-5" />Paket Soal Uji Nalar Pending</span>
                            @if($pendingQuizCount > 0)
                            <span class="whitespace-nowrap rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-extrabold text-amber-700">
                                {{ $pendingQuizCount }} Paket
                            </span>
                            @endif
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">Soal evaluasi yang disubmit mentor untuk disetujui</p>
                    </div>
                    <a href="{{ route('admin.quizzes.index') }}" class="inline-flex items-center shrink-0 whitespace-nowrap pt-0.5 text-xs font-bold text-[#0A52C4] hover:underline">
                        Lihat Semua <x-icon name="chevron_right" class="h-3.5 w-3.5" />
                    </a>
                </div>

                @if($recentQuizzes->isEmpty())
                <div class="flex items-center justify-center gap-1.5 rounded-xl border border-dashed border-gray-200 py-8 text-center text-xs text-gray-400">
                    <x-icon name="check_circle" class="h-3.5 w-3.5" /> Tidak ada paket soal yang menunggu review.
                </div>
                @else
                <div class="divide-y divide-gray-50">
                    @foreach($recentQuizzes as $quiz)
                    <div class="flex items-center justify-between gap-3 py-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-gray-800 truncate">{{ $quiz->title }}</p>
                            <p class="text-[10px] text-gray-400">
                                {{ $quiz->subject->name ?? '-' }} · Kelas {{ $quiz->class_level }} · Mentor: {{ $quiz->mentor->name ?? '-' }}
                            </p>
                        </div>
                        <a href="{{ route('admin.quizzes.index') }}"
                           class="shrink-0 rounded-xl bg-[#0A52C4] px-3 py-1.5 text-[11px] font-bold text-white hover:bg-[#0842A0] transition">
                            Periksa Soal
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </section>
        </div>

    </div>

    {{-- ── SYSTEM HEALTH & INNOVATION STATS ───────────────────────────── --}}
    <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
        <h2 class="font-extrabold text-gray-900 text-sm sm:text-base mb-4 flex items-center gap-1.5"><x-icon name="monitor_heart" class="h-4 w-4 text-[#0A52C4] sm:h-5 sm:w-5" /> System Health &amp; Platform Status</h2>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 text-xs">
            <div class="rounded-xl border border-gray-100 bg-gray-50/70 p-3.5">
                <p class="text-gray-400 font-medium">Database Engine</p>
                <p class="mt-1 font-extrabold text-gray-800 flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span> SQLite Ready
                </p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50/70 p-3.5">
                <p class="text-gray-400 font-medium">Uji Nalar Engine</p>
                <p class="mt-1 font-extrabold text-gray-800 flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Operational
                </p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50/70 p-3.5">
                <p class="text-gray-400 font-medium">Ruang Nalar Storage</p>
                <p class="mt-1 font-extrabold text-gray-800 flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span> Inline Preview Active
                </p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50/70 p-3.5">
                <p class="text-gray-400 font-medium">Framework &amp; Styling</p>
                <p class="mt-1 font-extrabold text-gray-800 flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span> Laravel 11 + Tailwind v4
                </p>
            </div>
        </div>
    </section>

</div>
<x-module-preview-modal label="Pratinjau KTM" />
</x-layouts.admin>