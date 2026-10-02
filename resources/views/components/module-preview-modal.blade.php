{{-- Pratinjau modul (PDF/gambar), dibuka lewat $dispatch('module-preview', {...}). Logika: public/js/module-preview.js --}}
<script src="{{ asset('js/module-preview.js') }}?v={{ filemtime(public_path('js/module-preview.js')) }}"></script>

<div x-data="modulePreview()"
    @module-preview.window="show($event.detail)"
    @keydown.escape.window="open && close()"
    x-show="open"
    class="fixed inset-0 z-[70] flex items-stretch justify-center bg-[#0F1F3D]/80 sm:items-center sm:p-6"
    role="dialog" aria-modal="true" :aria-label="'Pratinjau ' + title"
    style="display:none;" x-cloak>

    <div @click.outside="close()" class="flex h-full w-full flex-col overflow-hidden bg-white sm:h-[85vh] sm:max-w-3xl sm:rounded-2xl">
        <div class="flex items-center gap-2 border-b border-gray-100 px-4 py-3">
            <div class="min-w-0 flex-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-[#0A52C4]">Pratinjau Modul</p>
                <p class="truncate text-sm font-extrabold text-gray-900" x-text="title"></p>
            </div>
            <a :href="download"
                class="inline-flex shrink-0 items-center gap-1.5 rounded-lg bg-[#F28C28] px-3 py-2 text-xs font-bold text-white transition hover:bg-[#E07D1C]">
                <x-icon name="download" class="h-4 w-4" /> Unduh
            </a>
            <button type="button" @click="close()" aria-label="Tutup pratinjau"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-gray-50">
                <x-icon name="close" class="h-5 w-5" />
            </button>
        </div>

        <div class="flex-1 overflow-y-auto bg-gray-100 p-3 sm:p-4">
            <p x-show="state === 'loading'" class="py-16 text-center text-xs font-semibold text-gray-500">Memuat pratinjau…</p>

            <div x-show="state === 'error'" class="px-4 py-16 text-center">
                <p class="text-sm font-bold text-gray-700">Pratinjau tidak dapat ditampilkan.</p>
                <p class="mt-1 text-xs text-gray-500">Buka di tab baru atau unduh filenya.</p>
                <a :href="url" target="_blank" rel="noopener"
                    class="mt-4 inline-flex items-center gap-1.5 rounded-lg border border-[#0A52C4]/30 bg-white px-3 py-2 text-xs font-bold text-[#0A52C4]">
                    <x-icon name="open_in_new" class="h-4 w-4" /> Buka di tab baru
                </a>
            </div>

            <div x-ref="pages" x-show="type === 'pdf' && state !== 'error'" class="mx-auto max-w-3xl space-y-3"></div>

            <template x-if="open && type === 'image'">
                {{-- x-on:error, bukan @error: "@error" adalah direktif Blade --}}
                <img :src="url" :alt="title" x-on:load="state = 'ready'" x-on:error="state = 'error'" x-show="state === 'ready'"
                    class="mx-auto max-w-full rounded-lg bg-white object-contain shadow">
            </template>

            <p x-show="type === 'pdf' && state === 'ready' && pageCount > shown"
                class="mt-3 text-center text-[11px] text-gray-500"
                x-text="`Menampilkan ${shown} dari ${pageCount} halaman. Unduh untuk membaca seluruhnya.`"></p>
        </div>
    </div>
</div>
