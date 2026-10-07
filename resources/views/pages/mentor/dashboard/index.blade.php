{{--
    Dashboard Mentor — susunan halaman. Setiap bagian ada di partials/:
      hero · stats · tabs · tab-slot (upcoming-sessions, booking-requests) · tab-quizzes · tab-modules
    Data dari App\Http\Controllers\Mentor\DashboardController.
--}}
<x-layouts.mentor title="Dashboard Mentor — KawanNalar">
@php($suspended = (bool) $mentor->is_suspended)

<div x-data="{ activeTab: 'slot', showModal: {{ $errors->any() ? 'true' : 'false' }} }" class="mx-auto max-w-7xl space-y-6 px-1 sm:px-0">

    @if($suspended)
    <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700">
        <x-icon name="warning" class="h-6 w-6" />
        <span>Akun Anda sedang ditangguhkan oleh Admin. Silakan hubungi dukungan KawanNalar.</span>
    </div>
    @endif

    {{-- Pop-up konfirmasi Setujui / Tolak booking --}}
    <x-moderation-modal />

    @include('pages.mentor.dashboard.partials.hero')
    @include('pages.mentor.dashboard.partials.stats')
    @include('pages.mentor.dashboard.partials.tabs')

    <div x-show="activeTab === 'slot'">
        @include('pages.mentor.dashboard.partials.tab-slot')
    </div>
    <div x-show="activeTab === 'soal'" x-cloak>
        @include('pages.mentor.dashboard.partials.tab-quizzes')
    </div>
    <div x-show="activeTab === 'modul'" x-cloak>
        @include('pages.mentor.dashboard.partials.tab-modules')
    </div>

    {{-- Pop-up "Buat Sesi Baru" (dipakai bersama halaman Sesi Mentoring) --}}
    @include('pages.mentor.teman-nalar.partials.modal-add-slot')
</div>
</x-layouts.mentor>
