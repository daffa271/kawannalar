<x-layouts.mentor title="Ruang Nalar Mentor — KawanNalar">
    <div class="space-y-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-bold text-[#0A52C4]">Ruang Nalar Mentor</p>
                <h1 class="mt-1 text-2xl font-extrabold text-gray-900 lg:text-3xl">Pantau dan Bagikan Materi</h1>
                <p class="mt-2 max-w-2xl text-sm text-gray-500">Lihat materi yang sudah tayang dan pantau proses review catatan yang kamu kirim.</p>
            </div>
            <a href="{{ route('mentor.ruang-nalar.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F28C28] px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-[#E07D1C]"><span class="text-lg leading-none">+</span> Unggah Materi</a>
        </div>

        @if (session('status'))<div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">{{ session('status') }}</div>@endif

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs text-gray-400">Materi tayang</p>
                <p class="mt-2 text-2xl font-extrabold text-[#0A52C4]">{{ $publishedModules->total() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs text-gray-400">Unggahan saya</p>
                <p class="mt-2 text-2xl font-extrabold text-[#F28C28]">{{ $myModules->count() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs text-gray-400">Menunggu review</p>
                <p class="mt-2 text-2xl font-extrabold text-amber-500">{{ $myModules->where('status', 'pending')->count() }}</p>
            </div>
        </div>

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-5">
                <h2 class="font-extrabold text-gray-900">Status Unggahan Saya</h2>
                <p class="mt-1 text-xs text-gray-400">Admin akan meninjau setiap materi sebelum dibagikan kepada siswa.</p>
            </div>
            @if ($myModules->isEmpty())<div class="px-6 py-12 text-center">
                <p class="font-bold text-gray-700">Belum ada materi yang diunggah</p><a href="{{ route('mentor.ruang-nalar.create') }}" class="mt-3 inline-block text-sm font-bold text-[#0A52C4] hover:underline">Unggah materi pertama</a>
            </div>@else
            <div class="divide-y divide-gray-100">@foreach ($myModules as $module)<div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $module->title }}</p>
                        <p class="mt-1 text-xs text-gray-400">{{ $module->subject }} · {{ $module->created_at?->format('d M Y') }}</p>
                    </div><span class="w-fit rounded-full px-3 py-1 text-xs font-bold {{ $module->status === 'approved' ? 'bg-green-50 text-green-700' : ($module->status === 'rejected' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') }}">{{ $module->status === 'approved' ? 'Disetujui · Tayang' : ($module->status === 'rejected' ? 'Ditolak Admin' : 'Menunggu Review') }}</span>
                </div>@endforeach</div>@endif
        </section>

        <section>
            <div class="mb-4 flex items-end justify-between">
                <div>
                    <h2 class="font-extrabold text-gray-900">Materi Terverifikasi</h2>
                    <p class="mt-1 text-xs text-gray-400">Koleksi materi yang saat ini dapat diakses semua siswa.</p>
                </div>
            </div>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">@forelse ($publishedModules as $module)<article class="flex flex-col rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3"><span class="rounded-full bg-[#0A52C4]/10 px-2.5 py-1 text-[11px] font-bold text-[#0A52C4]">{{ $module->subject }}</span><span class="text-xs text-gray-400">{{ $module->grade }}</span></div>
                    <h3 class="mt-4 text-base font-extrabold text-gray-900">{{ $module->title }}</h3>
                    <p class="mt-2 line-clamp-2 flex-1 text-sm text-gray-500">{{ $module->description ?: 'Materi pembelajaran terverifikasi.' }}</p>
                    <div class="mt-5 border-t border-gray-100 pt-4">
                        <p class="text-xs text-gray-400">Oleh {{ $module->uploader?->name ?? 'KawanNalar' }} · {{ number_format($module->download_count) }} download</p>
                    </div>
                </article>@empty<div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center text-sm text-gray-400 md:col-span-2 xl:col-span-3">Belum ada materi terverifikasi.</div>@endforelse</div>
            <div class="mt-5">{{ $publishedModules->links() }}</div>
        </section>
    </div>
</x-layouts.mentor>