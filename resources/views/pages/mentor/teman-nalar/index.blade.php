<x-layouts.mentor>
<x-slot name="title">Kelola Sesi Mentoring — KawanNalar</x-slot>

<div x-data="{ tab: '1on1', showModal: false }" class="space-y-6 pb-24">
    @if(auth()->user()->is_suspended)
    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700 flex items-center gap-3">
        <span class="text-xl">⚠️</span>
        <span>Akun Anda sedang ditangguhkan oleh Admin. Silakan hubungi dukungan KawanNalar.</span>
    </div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Sesi Mentoring (Teman Nalar)</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola slot bimbingan privat 1-on-1 dan kelas live.</p>
        </div>
        @if(auth()->user()->is_suspended)
        <button disabled class="inline-flex items-center gap-2 rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-bold text-gray-400 cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Slot Baru
        </button>
        @else
        <button @click="showModal = true" class="inline-flex items-center gap-2 rounded-xl bg-[#FF6B00] px-4 py-2.5 text-sm font-bold text-white shadow hover:bg-[#E56000] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Slot Baru
        </button>
        @endif
    </div>

    @if(session('success'))
    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-bold text-green-800 flex items-center gap-2">
        ✅ {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-800 flex items-center gap-2">
        ❌ {{ session('error') }}
    </div>
    @endif

    @include('pages.mentor.teman-nalar.partials.header-stats')
    @include('pages.mentor.teman-nalar.partials.tab-navigation')

    <div x-show="tab === '1on1'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                @include('pages.mentor.teman-nalar.partials.slot-table')
            </div>
            <div class="lg:col-span-1">
                @include('pages.mentor.teman-nalar.partials.requests-card')
            </div>
        </div>
    </div>

    <div x-show="tab === 'live'" style="display:none;">
        @include('pages.mentor.teman-nalar.partials.live-class-list')
    </div>

    @include('pages.mentor.teman-nalar.partials.modal-add-slot')
</div>
</x-layouts.mentor>
