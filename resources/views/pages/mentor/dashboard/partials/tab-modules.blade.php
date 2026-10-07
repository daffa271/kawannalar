{{-- Tab "Modul": modul Ruang Nalar milik mentor. Pratinjau & unduh ada di halaman Ruang Nalar. --}}
<section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
    <div class="mb-6 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div class="flex items-start gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                <x-icon name="menu_book" class="h-5 w-5" />
            </span>
            <div>
                <h2 class="text-base font-extrabold text-gray-900 sm:text-lg">Upload &amp; Kelola Modul Pembelajaran</h2>
                <p class="mt-0.5 text-xs text-gray-400">Bagikan catatan atau materi latihan untuk siswa di Ruang Nalar.@if($moduleTotal > $myModules->count()) Menampilkan {{ $myModules->count() }} terbaru dari {{ $moduleTotal }}.@endif</p>
            </div>
        </div>
        @include('pages.mentor.dashboard.partials.create-button', ['href' => route('mentor.ruang-nalar.create'), 'icon' => 'upload_file', 'label' => 'Upload Modul Baru'])
    </div>

    @if($myModules->isEmpty())
    <div class="rounded-xl border border-dashed border-gray-200 py-12 text-center">
        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
            <x-icon name="upload_file" class="h-6 w-6" />
        </span>
        <p class="mt-3 text-sm font-bold text-gray-700">Belum ada modul yang diunggah</p>
        <p class="mt-1 text-xs text-gray-400">Modul tayang di Ruang Nalar setelah disetujui Admin.</p>
    </div>
    @else
    <div class="divide-y divide-gray-100">
        @foreach($myModules as $module)
        <div class="flex items-start justify-between gap-3 py-4">
            <div class="flex min-w-0 items-start gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-gray-500">
                    <x-icon name="description" class="h-5 w-5" />
                </span>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-gray-800">{{ $module->title }}</p>
                    <p class="mt-0.5 text-xs text-gray-400">{{ $module->subject }} · {{ $module->grade }} · {{ number_format($module->download_count) }} diunduh</p>
                </div>
            </div>
            @include('pages.mentor.dashboard.partials.status-badge', ['status' => $module->status])
        </div>
        @endforeach
    </div>
    <div class="mt-4">
        @include('pages.mentor.dashboard.partials.see-all', [
            'href' => route('mentor.ruang-nalar.index'),
            'label' => 'Lihat semua modul, pratinjau & unduh',
            'total' => $moduleTotal,
            'shown' => $myModules->count(),
            'always' => true,
        ])
    </div>
    @endif
</section>
