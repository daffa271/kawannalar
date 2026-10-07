<x-layouts.mentor title="Ruang Nalar Mentor — KawanNalar">
    <div x-data class="space-y-8 pb-24">
        @if(auth()->user()->is_suspended)
        <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700">
            <x-icon name="warning" class="h-6 w-6" />
            <span>Akun Anda sedang ditangguhkan oleh Admin. Silakan hubungi dukungan KawanNalar.</span>
        </div>
        @endif

        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-bold text-primary">Ruang Nalar Mentor</p>
                <h1 class="mt-1 text-2xl font-extrabold text-gray-900 lg:text-3xl">Pantau dan Bagikan Materi</h1>
                <p class="mt-2 max-w-2xl text-sm text-gray-500">Lihat materi yang sudah tayang dan pantau proses review catatan yang kamu kirim.</p>
            </div>
            @if(auth()->user()->is_suspended)
            <button disabled class="inline-flex cursor-not-allowed items-center justify-center gap-2 rounded-xl bg-gray-200 px-5 py-3 text-sm font-bold text-gray-400">
                <x-icon name="add" class="h-5 w-5" /> Unggah Materi
            </button>
            @else
            <a href="{{ route('mentor.ruang-nalar.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-cta px-5 py-3 text-sm font-bold text-navy shadow-sm transition hover:bg-cta-dark">
                <x-icon name="add" class="h-5 w-5" /> Unggah Materi
            </a>
            @endif
        </div>

        @if (session('status'))
        <div class="flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            <x-icon name="check_circle" class="h-5 w-5" /> {{ session('status') }}
        </div>
        @endif

        <div class="grid grid-cols-3 gap-2 sm:gap-4">
            <div class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm sm:p-5">
                <p class="text-[11px] text-gray-400 sm:text-xs">Materi tayang</p>
                <p class="mt-1 text-2xl font-extrabold text-primary sm:mt-2">{{ $publishedModules->total() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm sm:p-5">
                <p class="text-[11px] text-gray-400 sm:text-xs">Unggahan saya</p>
                <p class="mt-1 text-2xl font-extrabold text-gray-900 sm:mt-2">{{ $myModules->count() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm sm:p-5">
                <p class="text-[11px] text-gray-400 sm:text-xs">Menunggu review</p>
                <p class="mt-1 text-2xl font-extrabold text-amber-700 sm:mt-2">{{ $myModules->where('status', 'pending')->count() }}</p>
            </div>
        </div>

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-5">
                <h2 class="font-extrabold text-gray-900">Status Unggahan Saya</h2>
                <p class="mt-1 text-xs text-gray-400">Admin akan meninjau setiap materi sebelum dibagikan kepada siswa.</p>
            </div>
            @if ($myModules->isEmpty())
            <div class="px-6 py-12 text-center">
                <p class="font-bold text-gray-700">Belum ada materi yang diunggah</p>
                @if(auth()->user()->is_suspended)
                <button disabled class="mt-3 inline-block cursor-not-allowed text-sm font-bold text-gray-400">Unggah materi pertama</button>
                @else
                <a href="{{ route('mentor.ruang-nalar.create') }}" class="mt-3 inline-block text-sm font-bold text-primary hover:underline">Unggah materi pertama</a>
                @endif
            </div>
            @else
            <div class="divide-y divide-gray-100">
                @foreach ($myModules as $module)
                @php
                    $ext = strtolower(pathinfo($module->file_path, PATHINFO_EXTENSION));
                    $preview = [
                        'title' => $module->title,
                        'url' => route('mentor.ruang-nalar.preview', $module->id),
                        'download' => route('mentor.ruang-nalar.download', $module->id),
                        'type' => in_array($ext, ['jpg', 'jpeg', 'png']) ? 'image' : 'pdf',
                    ];
                @endphp
                <div class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-1 items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <x-icon name="description" class="h-5 w-5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-extrabold text-gray-900">{{ $module->title }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $module->subject }} · {{ $module->grade }} · {{ $module->download_count }}x Diunduh · {{ $module->created_at?->format('d M Y') }}</p>

                            {{-- Status moderasi: warna status + ikon + teks --}}
                            <div class="mt-2 flex items-center gap-2">
                                @if($module->status === 'approved')
                                    <span class="inline-flex items-center gap-1 rounded-full border border-green-200 bg-green-50 px-2.5 py-0.5 text-[10px] font-bold text-green-700"><x-icon name="check_circle" class="h-3.5 w-3.5" /> Disetujui · Tayang</span>
                                @elseif($module->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 rounded-full border border-red-200 bg-red-50 px-2.5 py-0.5 text-[10px] font-bold text-red-700"><x-icon name="cancel" class="h-3.5 w-3.5" /> Ditolak</span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-[10px] font-bold text-amber-800"><x-icon name="schedule" class="h-3.5 w-3.5" /> Menunggu Review Admin</span>
                                @endif
                            </div>
                            @if($module->status === 'rejected')
                            <p class="mt-2 break-words rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[11px] leading-relaxed text-red-700">
                                <span class="font-bold text-red-800">Alasan ditolak Admin:</span>
                                {{ $module->rejection_reason ?: 'Admin tidak mencantumkan alasan penolakan.' }}
                            </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button type="button" @click="$dispatch('module-preview', @js($preview))"
                            class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-bold text-gray-700 transition hover:bg-gray-50 sm:flex-none">
                            <x-icon name="visibility" class="h-4 w-4" /> Pratinjau
                        </button>
                        <a href="{{ $preview['download'] }}"
                            class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-primary px-3 py-1.5 text-xs font-bold text-white transition hover:bg-primary-dark sm:flex-none">
                            <x-icon name="download" class="h-4 w-4" /> Unduh File
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </section>

        {{-- Materi yang sudah tayang di Ruang Nalar (semua pengunggah) --}}
        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-5">
                <h2 class="font-extrabold text-gray-900">Materi Tayang di Ruang Nalar</h2>
                <p class="mt-1 text-xs text-gray-400">Materi yang sudah disetujui Admin dan bisa diakses siswa.</p>
            </div>
            @if ($publishedModules->isEmpty())
            <p class="px-6 py-12 text-center text-sm text-gray-400">Belum ada materi yang tayang.</p>
            @else
            <div class="divide-y divide-gray-100">
                @foreach ($publishedModules as $module)
                @php
                    $ext = strtolower(pathinfo($module->file_path, PATHINFO_EXTENSION));
                    $preview = [
                        'title' => $module->title,
                        'url' => route('mentor.ruang-nalar.preview', $module->id),
                        'download' => route('mentor.ruang-nalar.download', $module->id),
                        'type' => in_array($ext, ['jpg', 'jpeg', 'png']) ? 'image' : 'pdf',
                    ];
                @endphp
                <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex min-w-0 items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-gray-500">
                            <x-icon :name="$preview['type'] === 'image' ? 'image' : 'picture_as_pdf'" class="h-5 w-5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-extrabold text-gray-900">{{ $module->title }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $module->subject }} · {{ $module->grade }} · oleh {{ $module->uploader?->name ?? 'KawanNalar' }} · {{ number_format($module->download_count) }}x Diunduh</p>
                        </div>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <button type="button" @click="$dispatch('module-preview', @js($preview))"
                            class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-bold text-gray-700 transition hover:bg-gray-50 sm:flex-none">
                            <x-icon name="visibility" class="h-4 w-4" /> Pratinjau
                        </button>
                        <a href="{{ $preview['download'] }}"
                            class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-primary px-3 py-1.5 text-xs font-bold text-white transition hover:bg-primary-dark sm:flex-none">
                            <x-icon name="download" class="h-4 w-4" /> Unduh
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @if ($publishedModules->hasPages())
            <div class="border-t border-gray-100 px-5 py-4">{{ $publishedModules->links() }}</div>
            @endif
            @endif
        </section>
    </div>

    {{-- Pratinjau PDF / gambar (PDF.js, tetap tampil di HP) --}}
    <x-module-preview-modal />
</x-layouts.mentor>
