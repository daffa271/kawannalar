<x-layouts.siswa title="Ruang Nalar — Ruang Digital Berbagi Terverifikasi">
    <div x-data="{
        uploadOpen: {{ $errors->any() ? 'true' : 'false' }},
        fileName: '',
        fileSize: '',
        fileTooLarge: false,
        selectFile(event) {
            const file = event.target.files[0];
            this.fileName = file ? file.name : '';
            this.fileSize = file ? this.formatFileSize(file.size) : '';
            this.fileTooLarge = file ? file.size > 25 * 1024 * 1024 : false;
        },
        formatFileSize(bytes) {
            return bytes >= 1024 * 1024
                ? `${(bytes / (1024 * 1024)).toFixed(2)} MB`
                : `${Math.max(1, Math.round(bytes / 1024))} KB`;
        }
     }"
        class="space-y-6 sm:space-y-8 min-w-0 w-full overflow-x-hidden">

        @if(auth()->user()->is_suspended)
        <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700">
            <x-icon name="warning" class="h-6 w-6" />
            <span>Akun Anda sedang ditangguhkan oleh Admin. Silakan hubungi dukungan KawanNalar.</span>
        </div>
        @endif

        {{-- ── 1. TOP HERO BANNER (Biru Halus) ──────────────────────────── --}}
        <section class="relative overflow-hidden rounded-2xl border border-primary/20 bg-gradient-to-r from-primary/5 via-primary/10 to-primary/15 p-4 shadow-sm sm:p-8">
            <div class="pointer-events-none absolute -right-12 -top-12 h-48 w-48 rounded-full bg-primary/5"></div>
            <div class="pointer-events-none absolute -bottom-10 right-36 h-36 w-36 rounded-full bg-primary/5"></div>

            <div class="relative z-10 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div class="min-w-0 max-w-2xl space-y-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-[11px] font-extrabold text-primary sm:text-xs">
                        <x-icon name="groups" class="h-4 w-4" /> Komunitas Belajar Magetan
                    </span>
                    <h1 class="break-words text-xl font-extrabold leading-tight text-primary sm:text-3xl lg:text-4xl">
                        Ruang Nalar: Ruang Digital Berbagi Terverifikasi
                    </h1>
                    <p class="break-words text-xs leading-relaxed text-gray-600 sm:text-sm">
                        Akses ratusan ringkasan materi gratis dari sesama pelajar dan mentor alumni Magetan. Semua modul telah melalui tahap kurasi dan verifikasi admin.
                    </p>

                    {{-- Statistik & gamifikasi --}}
                    <div class="flex flex-wrap items-center gap-2 pt-1 sm:gap-3 sm:pt-2">
                        <span class="inline-flex items-center gap-1.5 rounded-xl border border-gray-100 bg-white px-2.5 py-1 text-[11px] font-bold text-gray-700 shadow-sm sm:text-xs">
                            <x-icon name="download" class="h-4 w-4 text-primary" />
                            <strong class="text-primary">{{ number_format($totalDownloads) }}</strong> Modul Diunduh
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-xl border border-cta/30 bg-cta/10 px-2.5 py-1 text-[11px] font-bold text-navy sm:text-xs">
                            <x-icon name="bolt" class="h-4 w-4 text-cta-dark" />
                            <strong>+{{ \App\Models\Module::APPROVAL_XP }} XP</strong> Per Upload Disetujui
                        </span>
                    </div>
                </div>

                {{-- CTA: aksi utama halaman (oranye + teks navy) --}}
                <div class="w-full shrink-0 sm:w-auto">
                    @if(auth()->user()->is_suspended)
                    <button type="button" disabled
                        class="inline-flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-xl bg-gray-200 px-5 py-3 text-xs font-extrabold text-gray-400 sm:w-auto sm:text-sm">
                        <x-icon name="upload_file" class="h-5 w-5" /> Unggah Catatan Kamu (Ditangguhkan)
                    </button>
                    @else
                    <button type="button" @click="uploadOpen = true"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-cta px-5 py-3 text-xs font-extrabold text-navy shadow-sm transition hover:-translate-y-0.5 hover:bg-cta-dark sm:w-auto sm:text-sm">
                        <x-icon name="upload_file" class="h-5 w-5" /> Unggah Catatan Kamu (+{{ \App\Models\Module::APPROVAL_XP }} XP)
                    </button>
                    @endif
                </div>
            </div>
        </section>

        {{-- Notifikasi --}}
        @if (session('status'))
        <div class="flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 p-3.5 text-xs font-bold text-emerald-800 shadow-sm sm:p-4 sm:text-sm">
            <x-icon name="check_circle" class="h-5 w-5" />
            <span class="break-words">{{ session('status') }}</span>
        </div>
        @endif

        {{-- ── 2. SEARCH & FILTER BAR ───────────────────────────────────── --}}
        <section class="min-w-0 space-y-3 overflow-hidden rounded-2xl border border-gray-100 bg-white p-3.5 shadow-sm sm:space-y-4 sm:p-5">
            <form method="GET" action="{{ route('siswa.ruang-nalar.index') }}" class="space-y-3 sm:space-y-4">
                @if(request('subject'))
                <input type="hidden" name="subject" value="{{ request('subject') }}">
                @endif

                {{-- Pencarian --}}
                <div class="flex flex-col gap-2 sm:flex-row">
                    <div class="relative min-w-0 flex-1">
                        <label for="q" class="sr-only">Cari modul</label>
                        <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                        <input type="text" id="q" name="q" value="{{ request('q') }}"
                            placeholder="Cari rumus mtk, modul biologi, atau ringkasan TPS..."
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/70 py-2 pl-9 pr-3 text-xs text-gray-800 placeholder-gray-400 transition focus:border-primary focus:bg-white focus:outline-none">
                    </div>
                    <button type="submit"
                        class="inline-flex w-full shrink-0 items-center justify-center gap-1.5 rounded-xl bg-primary px-5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-primary-dark sm:w-auto">
                        <x-icon name="search" class="h-4 w-4" /> Cari Modul
                    </button>
                </div>

                {{-- Chip mapel: dibuat dari mapel yang benar-benar ada di katalog --}}
                @php $activeSubject = request('subject'); @endphp
                <div class="scrollbar-none flex max-w-full items-center gap-1.5 overflow-x-auto whitespace-nowrap pb-1 text-xs">
                    <span class="shrink-0 pr-1 text-[10px] font-bold uppercase tracking-wider text-gray-400 sm:text-[11px]">Mapel:</span>

                    <a href="{{ route('siswa.ruang-nalar.index', array_merge(request()->except('subject', 'page'))) }}"
                        class="shrink-0 rounded-full border px-3 py-1 text-[11px] font-extrabold transition sm:text-xs {{ !$activeSubject ? 'border-primary bg-primary text-white' : 'border-transparent bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Semua Mapel
                    </a>

                    @foreach ($subjects as $mapel)
                    <a href="{{ route('siswa.ruang-nalar.index', array_merge(request()->except('subject', 'page'), ['subject' => $mapel])) }}"
                        class="shrink-0 rounded-full border px-3 py-1 text-[11px] font-bold transition sm:text-xs {{ $activeSubject === $mapel ? 'border-primary bg-primary text-white' : 'border-transparent bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ $mapel }}
                    </a>
                    @endforeach
                </div>

                {{-- Filter dropdown --}}
                <div class="grid grid-cols-2 gap-2.5 border-t border-gray-100 pt-2 sm:grid-cols-4">
                    <div>
                        <label for="grade" class="mb-1 block text-[9px] font-bold uppercase tracking-wider text-gray-400 sm:text-[10px]">Tingkat Kelas</label>
                        <select id="grade" name="grade" onchange="this.form.submit()"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/70 px-2.5 py-1.5 text-xs font-semibold text-gray-700 focus:border-primary focus:outline-none">
                            <option value="">Semua Kelas</option>
                            @foreach (\App\Models\Module::GRADES as $g)
                            <option value="{{ $g }}" @selected(request('grade') === $g)>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sort" class="mb-1 block text-[9px] font-bold uppercase tracking-wider text-gray-400 sm:text-[10px]">Urutkan</label>
                        <select id="sort" name="sort" onchange="this.form.submit()"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/70 px-2.5 py-1.5 text-xs font-semibold text-gray-700 focus:border-primary focus:outline-none">
                            <option value="latest" @selected(request('sort') === 'latest' || !request('sort'))>Terbaru</option>
                            <option value="popular" @selected(request('sort') === 'popular')>Terpopuler</option>
                        </select>
                    </div>

                    @if(request('q') || request('subject') || request('grade') || request('sort'))
                    <div class="col-span-2 flex items-end justify-end">
                        <a href="{{ route('siswa.ruang-nalar.index') }}"
                            class="inline-flex items-center gap-1 py-1.5 text-[11px] font-bold text-gray-500 hover:text-primary">
                            <x-icon name="close" class="h-4 w-4" /> Reset filter
                        </a>
                    </div>
                    @endif
                </div>
            </form>
        </section>

        {{-- ── 3. GRID KATALOG MODUL ─────────────────────────────────────── --}}
        <section class="min-w-0 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-extrabold text-gray-900 sm:text-lg">Katalog Modul &amp; Catatan Terverifikasi</h2>
                <span class="text-xs font-medium text-gray-400">{{ $modules->total() }} materi</span>
            </div>

            @if ($modules->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center shadow-sm sm:p-12">
                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                    <x-icon name="search_off" class="h-6 w-6" />
                </span>
                <p class="mt-3 text-sm font-extrabold text-gray-800 sm:text-base">Modul belum ditemukan</p>
                <p class="mx-auto mt-1 max-w-md text-xs text-gray-400">
                    Belum ada modul yang sesuai dengan filter pencarianmu. Coba ganti kata kunci atau pilih semua mapel.
                </p>
                <a href="{{ route('siswa.ruang-nalar.index') }}"
                    class="mt-4 inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-bold text-white hover:bg-primary-dark">
                    Tampilkan Semua Modul
                </a>
            </div>
            @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3">
                @foreach ($modules as $module)
                @php
                    $ext = strtolower(pathinfo($module->file_path, PATHINFO_EXTENSION));
                    $isImageFile = in_array($ext, ['jpg', 'jpeg', 'png']);
                    $uploader = $module->uploader;
                    $isMentor = $uploader?->role === 'mentor';
                    $uploaderName = $uploader?->name ?? 'KawanNalar';
                    $uploaderMeta = $isMentor
                        ? ($uploader->mentorProfile?->university ?: 'Mentor KawanNalar')
                        : ($uploader?->studentProfile?->school ?: 'Pelajar Magetan');
                    $preview = [
                        'title' => $module->title,
                        'url' => route('siswa.ruang-nalar.preview', $module),
                        'download' => route('siswa.ruang-nalar.download', $module),
                        'type' => $isImageFile ? 'image' : 'pdf',
                    ];
                @endphp
                <article class="flex min-w-0 flex-col rounded-2xl border border-gray-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">

                    {{-- Badge tipe file (netral) & kelas --}}
                    <div class="mb-2.5 flex items-center justify-between gap-2">
                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-700">
                            <x-icon :name="$isImageFile ? 'image' : 'picture_as_pdf'" class="h-3.5 w-3.5" /> {{ strtoupper($ext) }}
                        </span>
                        <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-[10px] font-bold text-gray-600">
                            {{ $module->grade }}
                        </span>
                    </div>

                    {{-- Judul & deskripsi --}}
                    <div class="min-w-0 flex-1 space-y-1">
                        <span class="inline-block text-[11px] font-bold text-primary">{{ $module->subject }}</span>
                        <h3 class="line-clamp-2 break-words text-sm font-extrabold leading-snug text-gray-900 sm:text-base">
                            {{ $module->title }}
                        </h3>
                        <p class="line-clamp-3 break-words text-xs leading-relaxed text-gray-500">
                            {{ $module->description ?: 'Ringkasan materi dan latihan soal pilihan terverifikasi KawanNalar.' }}
                        </p>
                    </div>

                    {{-- Pengunggah: mentor = "Kak" + lencana, siswa = nama + sekolah --}}
                    <div class="mt-3.5 min-w-0 space-y-2 rounded-xl bg-gray-50/80 p-2.5 sm:p-3">
                        <div class="flex min-w-0 items-center gap-2">
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary/10 text-xs font-extrabold text-primary sm:h-8 sm:w-8">
                                {{ strtoupper(substr($uploaderName, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="flex items-center gap-1 truncate text-xs font-extrabold text-gray-800">
                                    <span class="truncate">{{ $isMentor ? 'Kak '.$uploaderName : $uploaderName }}</span>
                                    @if($isMentor)
                                    <x-icon name="verified" class="h-3.5 w-3.5 text-primary" />
                                    <span class="sr-only">Mentor terverifikasi</span>
                                    @endif
                                </p>
                                <p class="truncate text-[10px] text-gray-400">{{ $isMentor ? 'Mentor · ' : '' }}{{ $uploaderMeta }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t border-gray-200/60 pt-2 text-[10px] font-semibold text-gray-500">
                            <span class="inline-flex items-center gap-1"><x-icon name="download" class="h-3.5 w-3.5" /> {{ number_format($module->download_count) }}x Diunduh</span>
                            <span class="inline-flex items-center gap-1"><x-icon name="calendar_month" class="h-3.5 w-3.5" /> {{ $module->created_at?->format('d M Y') }}</span>
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="mt-3.5 flex gap-2 pt-1">
                        <button type="button" @click="$dispatch('module-preview', @js($preview))"
                            class="inline-flex flex-1 items-center justify-center gap-1 rounded-xl border border-primary bg-white py-2 text-xs font-bold text-primary transition hover:bg-primary/5">
                            <x-icon name="visibility" class="h-4 w-4" /> Pratinjau
                        </button>
                        <a href="{{ $preview['download'] }}"
                            class="inline-flex flex-1 items-center justify-center gap-1 rounded-xl bg-cta py-2 text-xs font-bold text-navy shadow-sm transition hover:bg-cta-dark">
                            <x-icon name="download" class="h-4 w-4" /> Unduh File
                        </a>
                    </div>
                </article>
                @endforeach
            </div>

            <div class="pt-3">{{ $modules->links() }}</div>
            @endif
        </section>

        {{-- ── 4. STATUS UNGGAHAN MATERIAL KAMU ─────────────────────────── --}}
        @if ($myModules->isNotEmpty())
        <section class="min-w-0 space-y-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-6">
            <div>
                <h2 class="text-sm font-extrabold text-gray-900 sm:text-lg">Status Unggahan Material Kamu</h2>
                <p class="mt-0.5 text-[11px] text-gray-400 sm:text-xs">Admin akan meninjau catatan sebelum dipublikasikan untuk siswa Magetan.</p>
            </div>

            <div class="divide-y divide-gray-100 border-t border-gray-100">
                @foreach ($myModules as $myMod)
                @php $myExt = strtolower(pathinfo($myMod->file_path, PATHINFO_EXTENSION)); @endphp
                <div class="space-y-2 py-3.5">
                    <div class="flex flex-col justify-between gap-2.5 sm:flex-row sm:items-center">
                        <div class="flex min-w-0 items-center gap-2.5">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-[10px] font-extrabold text-gray-600">
                                {{ strtoupper($myExt) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-xs font-bold text-gray-800 sm:text-sm">{{ $myMod->title }}</p>
                                <p class="mt-0.5 truncate text-[10px] text-gray-400 sm:text-xs">
                                    {{ $myMod->subject }} · {{ $myMod->grade }} · {{ $myMod->created_at?->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        {{-- Status moderasi: hijau / merah / amber + ikon + teks --}}
                        <div class="shrink-0 self-start sm:self-auto">
                            @if($myMod->status === 'approved')
                            <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[10px] font-extrabold text-emerald-800 sm:text-xs">
                                <x-icon name="check_circle" class="h-3.5 w-3.5" /> Disetujui · Tayang (+{{ \App\Models\Module::APPROVAL_XP }} XP)
                            </span>
                            @elseif($myMod->status === 'rejected')
                            <span class="inline-flex items-center gap-1 rounded-full border border-red-200 bg-red-50 px-2.5 py-0.5 text-[10px] font-extrabold text-red-700 sm:text-xs">
                                <x-icon name="cancel" class="h-3.5 w-3.5" /> Ditolak Admin
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-[10px] font-extrabold text-amber-800 sm:text-xs">
                                <x-icon name="schedule" class="h-3.5 w-3.5" /> Menunggu Review Admin
                            </span>
                            @endif
                        </div>
                    </div>

                    @if($myMod->status === 'rejected')
                    <div class="space-y-1 rounded-xl border border-red-200 bg-red-50 p-3 text-[11px] text-red-700">
                        <p class="flex items-center gap-1 font-bold text-red-800">
                            <x-icon name="info" class="h-4 w-4" /> Catatan Revisi Admin:
                        </p>
                        <p class="break-words leading-relaxed">
                            {{ $myMod->rejection_reason ?: 'Admin tidak mencantumkan alasan penolakan.' }}
                        </p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ── 5. MODAL UNGGAH MODUL / CATATAN RINGKAS ──────────────────── --}}
        <div x-show="uploadOpen" x-cloak @keydown.escape.window="uploadOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-navy/60 p-3 backdrop-blur-xs sm:p-4"
            role="dialog" aria-modal="true" aria-labelledby="upload-title">
            <div @click.outside="uploadOpen = false"
                class="my-auto flex max-h-[85vh] w-full max-w-xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">

                <div class="flex shrink-0 items-center justify-between border-b border-gray-100 bg-white px-4 py-3.5 sm:px-6">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-primary">Ruang Nalar</span>
                        <h2 id="upload-title" class="text-base font-extrabold leading-tight text-gray-900 sm:text-xl">Unggah Catatan / Modul Ringkas</h2>
                    </div>
                    <button type="button" @click="uploadOpen = false" aria-label="Tutup"
                        class="flex h-8 w-8 items-center justify-center rounded-xl border border-gray-200 text-gray-400 transition hover:bg-gray-100">
                        <x-icon name="close" class="h-5 w-5" />
                    </button>
                </div>

                <form method="POST" action="{{ route('siswa.ruang-nalar.store') }}" enctype="multipart/form-data"
                    class="flex-1 space-y-3.5 overflow-y-auto p-4 text-xs sm:p-6">
                    @csrf

                    <div class="flex items-center gap-2 rounded-xl border border-cta/30 bg-cta/10 p-2.5 text-[11px] text-navy sm:p-3 sm:text-xs">
                        <x-icon name="bolt" class="h-5 w-5 text-cta-dark" />
                        <p class="font-semibold leading-tight">
                            Setiap unggahan catatan yang disetujui admin akan mendapat <strong>+{{ \App\Models\Module::APPROVAL_XP }} XP Gamifikasi</strong>!
                        </p>
                    </div>

                    <div>
                        <label for="modal-title" class="mb-1 block font-bold text-gray-700">Judul Modul / Catatan Ringkas</label>
                        <input id="modal-title" name="title" value="{{ old('title') }}" required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/70 p-2.5 text-xs text-gray-800 focus:border-primary focus:bg-white focus:outline-none"
                            placeholder="Contoh: Ringkasan Rumus Trigonometri &amp; Trik Praktis Kelas 10">
                        <x-input-error :messages="$errors->get('title')" class="mt-1 text-red-500" />
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label for="modal-subject" class="mb-1 block font-bold text-gray-700">Mata Pelajaran</label>
                            <select id="modal-subject" name="subject" required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50/70 p-2.5 text-xs text-gray-800 focus:border-primary focus:bg-white focus:outline-none">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach (\App\Models\Module::SUBJECTS as $subject)
                                <option value="{{ $subject }}" @selected(old('subject') === $subject)>{{ $subject }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('subject')" class="mt-1 text-red-500" />
                        </div>

                        <div>
                            <label for="modal-grade" class="mb-1 block font-bold text-gray-700">Tingkat Kelas</label>
                            <select id="modal-grade" name="grade" required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50/70 p-2.5 text-xs text-gray-800 focus:border-primary focus:bg-white focus:outline-none">
                                <option value="">Pilih Target Kelas</option>
                                @foreach (\App\Models\Module::GRADES as $grade)
                                <option value="{{ $grade }}" @selected(old('grade') === $grade)>{{ $grade }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('grade')" class="mt-1 text-red-500" />
                        </div>
                    </div>

                    <div>
                        <label for="modal-description" class="mb-1 block font-bold text-gray-700">Deskripsi Singkat (Opsional)</label>
                        <textarea id="modal-description" name="description" rows="2"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/70 p-2.5 text-xs text-gray-800 focus:border-primary focus:bg-white focus:outline-none"
                            placeholder="Jelaskan isi pokok catatan atau topik yang dibahas...">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1 text-red-500" />
                    </div>

                    {{-- Area unggah file --}}
                    <div>
                        <label for="modal-file" class="mb-1 block font-bold text-gray-700">Upload File Catatan (PDF, PNG, JPG)</label>
                        <div class="relative rounded-2xl border-2 border-dashed bg-gray-50/60 p-4 text-center transition sm:p-5"
                            :class="fileTooLarge ? 'border-red-400 bg-red-50/60' : 'border-gray-200 hover:border-primary'">
                            <input id="modal-file" type="file" name="file" required accept="application/pdf,image/png,image/jpeg,.pdf,.png,.jpg,.jpeg"
                                @change="selectFile($event)"
                                class="absolute inset-0 h-full w-full cursor-pointer opacity-0">
                            <div x-show="!fileName">
                                <x-icon name="cloud_upload" class="mx-auto h-8 w-8 text-primary" />
                                <p class="mt-1.5 text-xs font-bold text-gray-700">Pilih atau Seret File ke Sini</p>
                                <p class="mt-0.5 text-[10px] text-gray-400">Format PDF, PNG, JPG atau JPEG (Maks. 25 MB)</p>
                            </div>
                            <div x-show="fileName" x-cloak>
                                <x-icon name="task_alt" class="mx-auto h-8 w-8 text-emerald-600" x-show="!fileTooLarge" />
                                <x-icon name="warning" class="mx-auto h-8 w-8 text-red-600" x-show="fileTooLarge" x-cloak />
                                <p class="mt-1.5 break-all text-xs font-bold" :class="fileTooLarge ? 'text-red-700' : 'text-emerald-700'" x-text="fileName"></p>
                                <p class="mt-0.5 text-[10px]" :class="fileTooLarge ? 'font-bold text-red-600' : 'text-gray-500'"
                                    x-text="fileTooLarge ? `Ukuran ${fileSize}. File melebihi batas 25 MB.` : `File siap diunggah (${fileSize})`"></p>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('file')" class="mt-1 text-red-500" />
                    </div>

                    <div class="mt-2 flex justify-end gap-2 border-t border-gray-100 pt-3.5">
                        <button type="button" @click="uploadOpen = false"
                            class="rounded-xl border border-gray-200 px-4 py-2 font-bold text-gray-600 transition hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" :disabled="fileTooLarge"
                            :class="fileTooLarge ? 'cursor-not-allowed bg-gray-200 text-gray-400 shadow-none' : 'bg-cta text-navy shadow-sm hover:bg-cta-dark'"
                            class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2 font-extrabold transition sm:px-5">
                            <x-icon name="send" class="h-4 w-4" /> Kirim untuk Moderasi Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- Pratinjau PDF / gambar (PDF.js, tetap tampil di HP) --}}
    <x-module-preview-modal />
</x-layouts.siswa>
