<x-layouts.mentor title="Ruang Nalar Mentor — KawanNalar">
    <div x-data="{ previewUrl: null, showPreview: false }" class="space-y-8 pb-24">
        @if(auth()->user()->is_suspended)
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700 flex items-center gap-3">
            <span class="text-xl">⚠️</span>
            <span>Akun Anda sedang ditangguhkan oleh Admin. Silakan hubungi dukungan KawanNalar.</span>
        </div>
        @endif

        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-bold text-[#0A52C4]">Ruang Nalar Mentor</p>
                <h1 class="mt-1 text-2xl font-extrabold text-gray-900 lg:text-3xl">Pantau dan Bagikan Materi</h1>
                <p class="mt-2 max-w-2xl text-sm text-gray-500">Lihat materi yang sudah tayang dan pantau proses review catatan yang kamu kirim.</p>
            </div>
            @if(auth()->user()->is_suspended)
            <button disabled class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-200 px-5 py-3 text-sm font-bold text-gray-400 cursor-not-allowed">
                <span class="text-lg leading-none">+</span> Unggah Materi
            </button>
            @else
            <a href="{{ route('mentor.ruang-nalar.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F28C28] px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-[#E07D1C]"><span class="text-lg leading-none">+</span> Unggah Materi</a>
            @endif
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
            @if ($myModules->isEmpty())
            <div class="px-6 py-12 text-center">
                <p class="font-bold text-gray-700">Belum ada materi yang diunggah</p>
                @if(auth()->user()->is_suspended)
                <button disabled class="mt-3 inline-block text-sm font-bold text-gray-400 cursor-not-allowed">Unggah materi pertama</button>
                @else
                <a href="{{ route('mentor.ruang-nalar.create') }}" class="mt-3 inline-block text-sm font-bold text-[#0A52C4] hover:underline">Unggah materi pertama</a>
                @endif
            </div>
            @else
            <div class="divide-y divide-gray-100">
                @foreach ($myModules as $module)
                <div class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="h-10 w-10 shrink-0 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-gray-900">{{ $module->title }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $module->subject }} • {{ $module->grade }} • {{ $module->download_count }}x Diunduh • {{ $module->created_at?->format('d M Y') }}</p>
                            
                            <div class="mt-2 flex items-center gap-2">
                                @if($module->status === 'approved')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-0.5 text-[10px] font-bold text-green-700 border border-green-200">✓ Disetujui - Tayang</span>
                                @elseif($module->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-0.5 text-[10px] font-bold text-red-700 border border-red-200">Ditolak</span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2.5 py-0.5 text-[10px] font-bold text-yellow-700 border border-yellow-200">Pending Admin</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button" @click="previewUrl = '{{ route('mentor.ruang-nalar.preview', $module->id) }}'; showPreview = true" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-50 transition">
                            👁️ Pratinjau
                        </button>
                        <a href="{{ route('mentor.ruang-nalar.download', $module->id) }}" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#0A52C4] px-3 py-1.5 text-xs font-bold text-white hover:bg-[#0843a1] transition">
                            📥 Unduh File
                        </a>
                        <button class="p-1.5 text-gray-400 hover:text-gray-600 rounded-lg transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg></button>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </section>

        {{-- Modal Pratinjau Alpine.js --}}
        <div x-show="showPreview" 
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-sm"
            style="display:none;" x-cloak>
            
            <div @click.outside="showPreview = false; previewUrl = null" class="relative w-full max-w-4xl h-[85vh] rounded-2xl bg-white shadow-2xl flex flex-col overflow-hidden">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 bg-white shrink-0">
                    <h2 class="text-base font-extrabold text-gray-900">Pratinjau Modul</h2>
                    <button @click="showPreview = false; previewUrl = null" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                
                <div class="flex-1 w-full bg-gray-100">
                    <template x-if="showPreview && previewUrl">
                        <iframe :src="previewUrl" class="w-full h-full border-0" title="Pratinjau Dokumen"></iframe>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-layouts.mentor>