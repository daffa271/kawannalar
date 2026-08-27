<x-layouts.siswa title="Ruang Nalar — Ruang Digital Berbagi Terverifikasi">
<div x-data="{
        uploadOpen: {{ $errors->any() ? 'true' : 'false' }},
        previewOpen: false,
        previewUrl: '',
        previewTitle: '',
        isImage: false,
        openPreview(url, title, image) {
            this.previewUrl   = url;
            this.previewTitle = title;
            this.isImage      = image;
            this.previewOpen  = true;
        },
        closePreview() {
            this.previewOpen = false;
            this.previewUrl  = '';
        }
     }"
     class="space-y-6 sm:space-y-8 min-w-0 w-full overflow-x-hidden">

    @if(auth()->user()->is_suspended)
    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700 flex items-center gap-3">
        <span class="text-xl">⚠️</span>
        <span>Akun Anda sedang ditangguhkan oleh Admin. Silakan hubungi dukungan KawanNalar.</span>
    </div>
    @endif

    {{-- ── 1. TOP HERO BANNER (Biru Halus) ──────────────────────────── --}}
    <section class="relative overflow-hidden rounded-2xl border border-[#0A52C4]/20 bg-gradient-to-r from-[#EEF4FF] via-[#E6F0FF] to-[#DCEBFF] p-4 sm:p-8 shadow-sm">
        <div class="absolute -right-12 -top-12 h-48 w-48 rounded-full bg-[#0A52C4]/5 pointer-events-none"></div>
        <div class="absolute -bottom-10 right-36 h-36 w-36 rounded-full bg-[#0A52C4]/5 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div class="space-y-2 max-w-2xl min-w-0">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#0A52C4]/10 px-3 py-1 text-[11px] sm:text-xs font-extrabold text-[#0A52C4]">
                    ✨ Komunitas Belajar Magetan
                </span>
                <h1 class="text-xl sm:text-3xl lg:text-4xl font-extrabold text-[#0A52C4] leading-tight break-words">
                    Ruang Nalar: Ruang Digital Berbagi Terverifikasi
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed break-words">
                    Akses ratusan ringkasan materi gratis dari sesama pelajar dan mentor alumni Magetan. Semua modul telah melalui tahap kurasi dan verifikasi admin.
                </p>

                {{-- Badges Stat & Gamification --}}
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 pt-1 sm:pt-2">
                    <span class="inline-flex items-center gap-1.5 rounded-xl bg-white px-2.5 py-1 text-[11px] sm:text-xs font-bold text-gray-700 shadow-sm border border-gray-100">
                        📚 <strong class="text-[#0A52C4]">{{ number_format($totalDownloads) }}</strong> Modul Diunduh
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-xl bg-amber-50 px-2.5 py-1 text-[11px] sm:text-xs font-bold text-amber-800 border border-amber-200">
                        ⭐ <strong>+10 XP</strong> Per Upload Disetujui
                    </span>
                </div>
            </div>

            {{-- CTA Button Orange --}}
            <div class="shrink-0 w-full sm:w-auto">
                @if(auth()->user()->is_suspended)
                <button type="button" disabled
                        class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-gray-200 px-5 py-3 text-xs sm:text-sm font-extrabold text-gray-400 cursor-not-allowed">
                    <span class="text-base sm:text-lg leading-none">⚡</span> Unggah Catatan Kamu (Ditangguhkan)
                </button>
                @else
                <button type="button" @click="uploadOpen = true"
                        class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-[#FF7A00] px-5 py-3 text-xs sm:text-sm font-extrabold text-white shadow-md hover:bg-[#E06C00] transition transform hover:-translate-y-0.5">
                    <span class="text-base sm:text-lg leading-none">⚡</span> Unggah Catatan Kamu (+10 XP)
                </button>
                @endif
            </div>
        </div>
    </section>

    {{-- Notification Alert --}}
    @if (session('status'))
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-3.5 sm:p-4 text-xs sm:text-sm font-bold text-emerald-800 shadow-sm flex items-center gap-2">
        <span class="text-base shrink-0">✓</span>
        <span class="break-words">{{ session('status') }}</span>
    </div>
    @endif

    {{-- ── 2. SEARCH & FILTER BAR (Interaktif) ─────────────────────── --}}
    <section class="rounded-2xl border border-gray-100 bg-white p-3.5 sm:p-5 shadow-sm space-y-3 sm:space-y-4 min-w-0 overflow-hidden">
        <form method="GET" action="{{ route('siswa.ruang-nalar.index') }}" class="space-y-3 sm:space-y-4">

            {{-- Search Bar Input --}}
            <div class="flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1 min-w-0">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none text-xs">
                        🔍
                    </span>
                    <input type="text" id="q" name="q" value="{{ request('q') }}"
                           placeholder="Cari rumus mtk, modul biologi, atau ringkasan TPS..."
                           class="w-full rounded-xl border border-gray-200 bg-gray-50/70 pl-8 pr-3 py-2 text-xs text-gray-800 placeholder-gray-400 focus:border-[#0A52C4] focus:bg-white focus:outline-none transition">
                </div>
                <button type="submit"
                        class="w-full sm:w-auto shrink-0 rounded-xl bg-[#0A52C4] px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-[#0842A0] transition">
                    Cari Modul
                </button>
            </div>

            {{-- Filter Chips Horizontal Scrollable --}}
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs whitespace-nowrap max-w-full scrollbar-none">
                <span class="font-bold text-gray-400 text-[10px] sm:text-[11px] shrink-0 uppercase tracking-wider pr-1">Mapel:</span>
                @php
                    $activeSubject = request('subject');
                    $presetMapel = ['Matematika', 'Biologi', 'Fisika', 'Kimia', 'UTBK TPS', 'Bahasa Inggris'];
                @endphp

                <a href="{{ route('siswa.ruang-nalar.index', array_merge(request()->except('subject'), ['page' => 1])) }}"
                   class="rounded-full px-3 py-1 font-extrabold text-[11px] sm:text-xs transition border shrink-0 {{ !$activeSubject ? 'bg-[#0A52C4] text-white border-[#0A52C4]' : 'bg-gray-100 text-gray-600 border-transparent hover:bg-gray-200' }}">
                    Semua Mapel
                </a>

                @foreach ($presetMapel as $mapel)
                <a href="{{ route('siswa.ruang-nalar.index', array_merge(request()->except('subject'), ['subject' => $mapel, 'page' => 1])) }}"
                   class="rounded-full px-3 py-1 font-bold text-[11px] sm:text-xs transition border shrink-0 {{ $activeSubject === $mapel ? 'bg-[#0A52C4] text-white border-[#0A52C4]' : 'bg-gray-100 text-gray-600 border-transparent hover:bg-gray-200' }}">
                    {{ $mapel }}
                </a>
                @endforeach
            </div>

            {{-- Dropdown Filters (Row 2) --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-2 border-t border-gray-100">
                <div>
                    <label for="grade" class="block text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tingkat Kelas</label>
                    <select id="grade" name="grade" onchange="this.form.submit()"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/70 px-2.5 py-1.5 text-xs text-gray-700 font-semibold focus:border-[#0A52C4] focus:outline-none">
                        <option value="">Semua Kelas</option>
                        @foreach (['Kelas 10', 'Kelas 11', 'Kelas 12', 'UTBK'] as $g)
                        <option value="{{ $g }}" @selected(request('grade') === $g)>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sort" class="block text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Urutkan</label>
                    <select id="sort" name="sort" onchange="this.form.submit()"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/70 px-2.5 py-1.5 text-xs text-gray-700 font-semibold focus:border-[#0A52C4] focus:outline-none">
                        <option value="latest" @selected(request('sort') === 'latest' || !request('sort'))>Terbaru</option>
                        <option value="popular" @selected(request('sort') === 'popular')>Terpopuler</option>
                    </select>
                </div>

                @if(request('q') || request('subject') || request('grade') || request('sort'))
                <div class="col-span-2 sm:col-span-2 flex items-end justify-end">
                    <a href="{{ route('siswa.ruang-nalar.index') }}"
                       class="text-[11px] font-bold text-red-500 hover:underline py-1.5">
                        ✕ Reset Filter
                    </a>
                </div>
                @endif
            </div>

        </form>
    </section>

    {{-- ── 3. GRID KATALOG MODUL (3 Kolom Desktop, 1 Kolom Mobile) ─────── --}}
    <section class="space-y-4 min-w-0">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-gray-900 text-sm sm:text-lg">Katalog Modul &amp; Catatan Terverifikasi</h2>
            <span class="text-xs text-gray-400 font-medium">{{ $modules->total() }} materi</span>
        </div>

        @if ($modules->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 sm:p-12 text-center shadow-sm">
            <span class="text-3xl sm:text-4xl">📚</span>
            <p class="mt-3 font-extrabold text-gray-800 text-sm sm:text-base">Modul belum ditemukan</p>
            <p class="mt-1 text-xs text-gray-400 max-w-md mx-auto">
                Belum ada modul yang sesuai dengan filter pencarianmu. Coba ganti kata kunci atau pilih semua mapel.
            </p>
            <a href="{{ route('siswa.ruang-nalar.index') }}"
               class="mt-4 inline-flex items-center gap-2 rounded-xl bg-[#0A52C4] px-4 py-2 text-xs font-bold text-white">
                Tampilkan Semua Modul
            </a>
        </div>
        @else
        <div class="grid gap-4 sm:gap-5 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($modules as $module)
            @php
                $ext = strtolower(pathinfo($module->file_path, PATHINFO_EXTENSION));
                $isImageFile = in_array($ext, ['jpg','jpeg','png']);
            @endphp
            <article class="flex flex-col rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md min-w-0">

                {{-- Header Card Badges --}}
                <div class="flex items-center justify-between gap-2 mb-2.5">
                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider
                        {{ $ext === 'pdf' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-[#0A52C4]' }}">
                        📄 {{ strtoupper($ext) }}
                    </span>
                    <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-[10px] font-bold text-gray-600">
                        {{ $module->grade }}
                    </span>
                </div>

                {{-- Content Title & Description --}}
                <div class="space-y-1 flex-1 min-w-0">
                    <span class="inline-block text-[11px] font-bold text-[#0A52C4]">
                        {{ $module->subject }}
                    </span>
                    <h3 class="text-sm sm:text-base font-extrabold text-gray-900 leading-snug line-clamp-2 break-words">
                        {{ $module->title }}
                    </h3>
                    <p class="text-xs text-gray-500 line-clamp-3 leading-relaxed break-words">
                        {{ $module->description ?: 'Ringkasan materi dan latihan soal pilihan terverifikasi KawanNalar.' }}
                    </p>
                </div>

                {{-- Author Info Box --}}
                <div class="mt-3.5 rounded-xl bg-gray-50/80 p-2.5 sm:p-3 space-y-2 min-w-0">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="flex h-7 w-7 sm:h-8 sm:w-8 shrink-0 items-center justify-center rounded-full bg-[#0A52C4]/10 text-xs font-extrabold text-[#0A52C4]">
                            {{ strtoupper(substr($module->uploader?->name ?? 'M', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-extrabold text-gray-800 truncate flex items-center gap-1">
                                Kak {{ $module->uploader?->name ?? 'Mentor KawanNalar' }}
                                <span class="text-amber-500 text-[10px]" title="Mentor Verified">✓</span>
                            </p>
                            <p class="text-[10px] text-gray-400 truncate">
                                {{ $module->uploader?->mentorProfile?->university ?? ($module->uploader?->role === 'mentor' ? 'Teknik Informatika PENS' : 'Pelajar Magetan') }}
                            </p>
                        </div>
                    </div>

                    {{-- Metadata Stats --}}
                    <div class="flex items-center justify-between border-t border-gray-200/60 pt-2 text-[10px] text-gray-500 font-semibold">
                        <span>📥 {{ number_format($module->download_count) }}x Diunduh</span>
                        <span>📅 {{ $module->created_at?->format('d M Y') }}</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-3.5 flex gap-2 pt-1">
                    <button type="button"
                            @click="openPreview('{{ route('siswa.ruang-nalar.preview', $module) }}', @js($module->title), {{ $isImageFile ? 'true' : 'false' }})"
                            class="flex-1 inline-flex items-center justify-center gap-1 rounded-xl border border-[#0A52C4] bg-white py-2 text-xs font-bold text-[#0A52C4] hover:bg-[#EEF4FF] transition">
                        👁️ Pratinjau
                    </button>
                    <a href="{{ route('siswa.ruang-nalar.download', $module) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1 rounded-xl bg-[#FF7A00] py-2 text-xs font-bold text-white hover:bg-[#E06C00] shadow-sm transition">
                        📥 Unduh File
                    </a>
                </div>

            </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pt-3">{{ $modules->links() }}</div>
        @endif
    </section>

    {{-- ── 4. SECTION STATUS UNGGAHAN MATERIAL KAMU ────────────────── --}}
    @if ($myModules->isNotEmpty())
    <section class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-6 shadow-sm space-y-3 min-w-0">
        <div>
            <h2 class="font-extrabold text-gray-900 text-sm sm:text-lg">Status Unggahan Material Kamu</h2>
            <p class="text-[11px] sm:text-xs text-gray-400 mt-0.5">Admin akan meninjau catatan sebelum dipublikasikan untuk siswa Magetan.</p>
        </div>

        <div class="divide-y divide-gray-100 border-t border-gray-100">
            @foreach ($myModules as $myMod)
            @php
                $myExt = strtolower(pathinfo($myMod->file_path, PATHINFO_EXTENSION));
            @endphp
            <div class="py-3.5 space-y-2">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-xs font-extrabold text-gray-600">
                            {{ strtoupper($myExt) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm font-bold text-gray-800 truncate">{{ $myMod->title }}</p>
                            <p class="text-[10px] sm:text-xs text-gray-400 mt-0.5 truncate">
                                {{ $myMod->subject }} · {{ $myMod->grade }} · {{ $myMod->created_at?->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    {{-- Multi-Warna Status Badge --}}
                    <div class="shrink-0 self-start sm:self-auto">
                        @if($myMod->status === 'approved')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] sm:text-xs font-extrabold text-emerald-800 border border-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Approved / Tayang Publik (+10 XP)
                        </span>
                        @elseif($myMod->status === 'rejected')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-2.5 py-0.5 text-[10px] sm:text-xs font-extrabold text-red-700 border border-red-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Ditolak Admin
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-0.5 text-[10px] sm:text-xs font-extrabold text-amber-800 border border-amber-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Pending Review Admin
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Expanded Alert Box (Jika Ditolak) --}}
                @if($myMod->status === 'rejected')
                <div class="rounded-xl border border-red-200 bg-red-50 p-3 text-[11px] text-red-700 space-y-1">
                    <p class="font-bold flex items-center gap-1 text-red-800">
                        📌 Catatan Revisi Admin:
                    </p>
                    <p class="leading-relaxed">
                        Gambar atau modul terlalu buram / format tidak lengkap. Harap lakukan upload ulang catatan dengan foto atau file PDF yang lebih jernih dan terbaca.
                    </p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ── 5. MODAL 1: FORM UNGGAH MODUL/CATATAN RINGKAS ─────────────── --}}
    <div x-show="uploadOpen" x-cloak @keydown.escape.window="uploadOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-[#0F1F3D]/60 backdrop-blur-xs p-3 sm:p-4 overflow-y-auto"
         role="dialog" aria-modal="true">
        <div @click.outside="uploadOpen = false"
             class="w-full max-w-xl rounded-2xl bg-white shadow-2xl flex flex-col max-h-[85vh] my-auto overflow-hidden">

            {{-- Header (Fixed Top) --}}
            <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3.5 sm:px-6 shrink-0 bg-white">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#0A52C4]">Ruang Nalar</span>
                    <h2 class="text-base sm:text-xl font-extrabold text-gray-900 leading-tight">Unggah Catatan / Modul Ringkas</h2>
                </div>
                <button type="button" @click="uploadOpen = false"
                        class="flex h-8 w-8 items-center justify-center rounded-xl border border-gray-200 text-xl text-gray-400 hover:bg-gray-100 transition">
                    &times;
                </button>
            </div>

            {{-- Form Body (Scrollable Middle) --}}
            <form method="POST" action="{{ route('siswa.ruang-nalar.store') }}" enctype="multipart/form-data"
                  class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-3.5 text-xs">
                @csrf

                {{-- Note Box XP Gamification --}}
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-2.5 sm:p-3 text-[11px] sm:text-xs text-amber-900 flex items-center gap-2">
                    <span class="text-base shrink-0">⭐</span>
                    <p class="font-semibold leading-tight">
                        Setiap unggahan catatan yang disetujui admin akan mendapat <strong class="text-[#FF7A00]">+10 XP Gamifikasi</strong>!
                    </p>
                </div>

                <div>
                    <label for="modal-title" class="block font-bold text-gray-700 mb-1">Judul Modul / Catatan Ringkas</label>
                    <input id="modal-title" name="title" value="{{ old('title') }}" required
                           class="w-full rounded-xl border border-gray-200 bg-gray-50/70 p-2.5 text-xs text-gray-800 focus:border-[#0A52C4] focus:bg-white focus:outline-none"
                           placeholder="Contoh: Ringkasan Rumus Trigonometri &amp; Trik Praktis Kelas 10">
                    <x-input-error :messages="$errors->get('title')" class="mt-1 text-red-500" />
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label for="modal-subject" class="block font-bold text-gray-700 mb-1">Mata Pelajaran</label>
                        <select id="modal-subject" name="subject" required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50/70 p-2.5 text-xs text-gray-800 focus:border-[#0A52C4] focus:bg-white focus:outline-none">
                            <option value="">Pilih Mata Pelajaran</option>
                            <option>Matematika</option>
                            <option>Fisika</option>
                            <option>Kimia</option>
                            <option>Biologi</option>
                            <option>UTBK TPS</option>
                            <option>Bahasa Inggris</option>
                            <option>Penalaran Umum</option>
                        </select>
                        <x-input-error :messages="$errors->get('subject')" class="mt-1 text-red-500" />
                    </div>

                    <div>
                        <label for="modal-grade" class="block font-bold text-gray-700 mb-1">Tingkat Kelas</label>
                        <select id="modal-grade" name="grade" required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50/70 p-2.5 text-xs text-gray-800 focus:border-[#0A52C4] focus:bg-white focus:outline-none">
                            <option value="">Pilih Target Kelas</option>
                            <option>Kelas 10</option>
                            <option>Kelas 11</option>
                            <option>Kelas 12</option>
                            <option>UTBK</option>
                        </select>
                        <x-input-error :messages="$errors->get('grade')" class="mt-1 text-red-500" />
                    </div>
                </div>

                <div>
                    <label for="modal-description" class="block font-bold text-gray-700 mb-1">Deskripsi Singkat (Opsional)</label>
                    <textarea id="modal-description" name="description" rows="2"
                              class="w-full rounded-xl border border-gray-200 bg-gray-50/70 p-2.5 text-xs text-gray-800 focus:border-[#0A52C4] focus:bg-white focus:outline-none"
                              placeholder="Jelaskan isi pokok catatan atau topik yang dibahas...">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1 text-red-500" />
                </div>

                {{-- Drag & Drop Upload Area --}}
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Upload File Catatan (PDF, PNG, JPG)</label>
                    <div class="relative rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50/60 p-4 sm:p-5 text-center hover:border-[#0A52C4] transition">
                        <input id="modal-file" type="file" name="file" required accept="application/pdf,image/png,image/jpeg,.pdf,.png,.jpg,.jpeg"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <span class="text-2xl sm:text-3xl">📁</span>
                        <p class="mt-1.5 text-xs font-bold text-gray-700">Pilih atau Seret File ke Sini</p>
                        <p class="mt-0.5 text-[10px] text-gray-400">Format PDF, PNG, JPG atau JPEG (Maks. 25 MB)</p>
                    </div>
                    <x-input-error :messages="$errors->get('file')" class="mt-1 text-red-500" />
                </div>

                {{-- Action Buttons (Inside Form) --}}
                <div class="flex justify-end gap-2 border-t border-gray-100 pt-3.5 mt-2">
                    <button type="button" @click="uploadOpen = false"
                            class="rounded-xl border border-gray-200 px-4 py-2 font-bold text-gray-600 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="rounded-xl bg-[#FF7A00] px-4 sm:px-5 py-2 font-extrabold text-white hover:bg-[#E06C00] shadow-sm transition">
                        Kirim untuk Moderasi Admin
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── 5. MODAL 2: PRATINJAU FILE MODUL (PDF / IMAGE) ─────────────── --}}
    <div x-show="previewOpen" x-cloak @keydown.escape.window="closePreview()"
         class="fixed inset-0 z-50 flex flex-col bg-[#0F1F3D]/80 backdrop-blur-xs"
         role="dialog" aria-modal="true">
        {{-- Preview Header --}}
        <div class="flex items-center justify-between gap-3 bg-white px-4 py-3 shadow-md sm:px-6">
            <div class="min-w-0">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#0A52C4]">Pratinjau Modul Ruang Nalar</span>
                <p class="truncate text-xs sm:text-sm font-extrabold text-gray-900" x-text="previewTitle"></p>
            </div>
            <div class="flex shrink-0 items-center gap-2">
                <a :href="previewUrl.replace('/preview', '/download')"
                   class="inline-flex items-center gap-1.5 rounded-xl bg-[#FF7A00] px-3.5 py-1.5 text-xs font-bold text-white hover:bg-[#E06C00] transition shadow-sm">
                    📥 Unduh File
                </a>
                <button @click="closePreview()" aria-label="Tutup Pratinjau"
                        class="flex h-8 w-8 items-center justify-center rounded-xl border border-gray-200 text-xl font-bold text-gray-500 hover:bg-gray-100 transition">
                    &times;
                </button>
            </div>
        </div>

        {{-- Preview Body --}}
        <div class="flex-1 overflow-hidden bg-gray-900/60 p-2 sm:p-4">
            <template x-if="!isImage">
                <iframe :src="previewUrl" class="h-full w-full rounded-2xl border-0 bg-white shadow-2xl" loading="lazy" title="Preview materi PDF"></iframe>
            </template>
            <template x-if="isImage">
                <div class="flex h-full items-center justify-center overflow-auto p-2 sm:p-4">
                    <img :src="previewUrl" class="max-h-full max-w-full rounded-2xl object-contain shadow-2xl" alt="Preview gambar modul">
                </div>
            </template>
        </div>
    </div>

</div>
</x-layouts.siswa>