<x-layouts.mentor>
    <x-slot name="title">Kelola Sesi Mentoring — KawanNalar</x-slot>

    {{-- Modal langsung terbuka bila penyimpanan sesi ditolak, agar pesan error terlihat --}}
    <div x-data="{ tab: '1on1', showModal: {{ $errors->any() ? 'true' : 'false' }} }" class="space-y-8 pb-24">
        @if(auth()->user()->is_suspended)
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700 flex items-center gap-3">
            <x-icon name="warning" class="h-6 w-6" />
            <span>Akun Anda sedang ditangguhkan oleh Admin. Silakan hubungi dukungan KawanNalar.</span>
        </div>
        @endif

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Sesi Mentoring (Teman Nalar)</h1>
                <p class="mt-1 text-sm text-gray-500">Kelola sesi bimbingan private dan Belajar Bersama.</p>
            </div>
            @if(auth()->user()->is_suspended)
            <button disabled class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-bold text-gray-400 cursor-not-allowed">
                <x-icon name="add" class="h-5 w-5" /> Buat Slot Baru
            </button>
            @else
            {{-- Aksi utama: oranye brand + teks navy (sama dengan landing & dashboard) --}}
            <button @click="showModal = true" class="inline-flex items-center justify-center gap-2 rounded-xl bg-cta px-4 py-2.5 text-sm font-bold text-navy shadow-sm hover:bg-cta-dark transition">
                <x-icon name="add" class="h-5 w-5" /> Buat Slot Baru
            </button>
            @endif
        </div>

        @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-bold text-green-800 flex items-center gap-2">
            <x-icon name="check_circle" class="h-5 w-5" /> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-800 flex items-center gap-2">
            <x-icon name="cancel" class="h-5 w-5" /> {{ session('error') }}
        </div>
        @endif

        <x-moderation-modal />

        @include('pages.mentor.teman-nalar.partials.header-stats')
        @include('pages.mentor.teman-nalar.partials.tab-navigation')

        <div x-show="tab === '1on1'" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    @include('pages.mentor.teman-nalar.partials.slot-table')
                </div>
                <div class="lg:col-span-1">
                    @include('pages.mentor.teman-nalar.partials.requests-card')
                </div>
            </div>
        </div>

        <div x-show="tab === 'live'">
            @include('pages.mentor.teman-nalar.partials.live-class-list')
        </div>

        @include('pages.mentor.teman-nalar.partials.modal-add-slot')
    </div>
</x-layouts.mentor>