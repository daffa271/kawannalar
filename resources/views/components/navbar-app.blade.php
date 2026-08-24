@php
$user = auth()->user();
$name = $user?->name ?? 'Pengguna';
$initials = collect(explode(' ', trim($name)))->filter()->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->join('');
$role = $user?->role ?? 'siswa';
$studentXp = $user?->xp ?? $user?->studentProfile?->xp ?? 1250;
$studentGrade = $user?->studentProfile?->grade ?? '';
$mentorUniversity = $user?->mentorProfile?->university ?? 'Mentor';
$profileSubtitle = match ($role) {
    'mentor' => $mentorUniversity,
    'admin' => 'Inovator KawanNalar',
    default => $studentGrade ? 'Siswa ' . trim($studentGrade) : 'Siswa',
};
$roleBadge = match ($role) {
    'mentor' => '<span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200/60 bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold text-emerald-800"><span>👨‍🏫</span> Mentor Verified</span>',
    'admin' => '<span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200/60 bg-blue-50 px-2.5 py-1 text-[10px] font-semibold text-blue-800"><span>⚡</span> Admin System</span>',
    default => '<span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200/60 bg-amber-50 px-2.5 py-1 text-[10px] font-bold text-amber-900 shadow-sm"><span>🏆</span> ' . number_format($studentXp) . ' XP</span>',
};
@endphp

<nav x-data="{ profileOpen: false, notifOpen: false }" class="fixed inset-x-0 top-0 z-40 h-20 border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur-md">
    <div class="mx-auto flex h-full w-full max-w-7xl items-center justify-between gap-4 px-6 md:px-8">
        <div class="flex items-center gap-2 md:gap-3">
            <button @click="$dispatch('toggle-sidebar')" class="md:hidden flex min-h-[40px] min-w-[40px] items-center justify-center rounded-xl p-2 text-slate-700 transition hover:bg-slate-100" aria-label="Buka menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="{{ route('dashboard') }}" class="flex shrink-0 items-center gap-2 md:gap-3">
                <img src="{{ asset('images/logokawannalar.jpeg') }}" alt="Logo KawanNalar" class="h-9 w-9 rounded-xl object-cover md:h-10 md:w-10">
                <span class="hidden text-lg font-extrabold tracking-[-0.03em] text-[#0A52C4] sm:inline md:text-xl">Kawan<span class="text-[#F28C28]">Nalar</span></span>
            </a>
        </div>

        <div class="hidden flex-1 justify-center lg:flex">
            <label class="sr-only" for="app-search">Cari</label>
            <div class="relative w-full max-w-md xl:max-w-lg">
                <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
                <input id="app-search" type="search" placeholder="Cari modul, mentor, bank soal, atau beasiswa..." class="w-full rounded-full border border-slate-200/80 bg-slate-50 py-2.5 pl-11 pr-4 text-sm font-medium text-slate-800 placeholder:text-slate-400 transition-all focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100/50" />
            </div>
        </div>

        <div class="ml-auto flex items-center gap-2 md:gap-3 lg:gap-4">
            @if ($role === 'siswa')
            <div class="hidden sm:flex items-center gap-1.5 rounded-full border border-amber-200/60 bg-amber-50 px-2.5 py-1.5 text-amber-900 shadow-sm sm:px-3.5">
                <span class="text-sm md:text-base">🏆</span>
                <span class="text-[11px] font-bold md:text-sm">{{ number_format($studentXp) }} XP</span>
            </div>
            @elseif ($role === 'mentor')
            <div class="hidden sm:flex items-center gap-1.5 rounded-full border border-emerald-200/60 bg-emerald-50 px-2.5 py-1.5 text-emerald-800 shadow-sm">
                <span>👨‍🏫</span>
                <span class="text-[11px] font-semibold">Mentor Verified</span>
            </div>
            @else
            <div class="hidden sm:flex items-center gap-1.5 rounded-full border border-blue-200/60 bg-blue-50 px-2.5 py-1.5 text-blue-800 shadow-sm">
                <span>⚡</span>
                <span class="text-[11px] font-semibold">Admin System</span>
            </div>
            @endif

            <div class="relative">
                <button @click="notifOpen = !notifOpen" class="relative flex min-h-[40px] min-w-[40px] items-center justify-center rounded-full p-2.5 text-slate-600 transition hover:bg-slate-100" aria-label="Notifikasi">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 0 0-4-5.7V5a2 2 0 1 0-4 0v.3C7.7 6.2 6 8.4 6 11v3.2c0 .5-.2 1.1-.6 1.4L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute -right-0.5 -top-0.5 flex h-[18px] min-w-[18px] items-center justify-center rounded-full border-2 border-white bg-rose-500 px-1 text-[10px] font-bold text-white">3</span>
                </button>

                <div x-show="notifOpen" x-cloak @click.away="notifOpen = false" class="absolute right-0 mt-2 w-72 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-xl">
                    <div class="border-b border-slate-100 px-4 py-3 text-sm font-bold text-slate-800">Notifikasi <span class="float-right text-xs text-[#0A52C4]">3 baru</span></div>
                    <div class="divide-y divide-slate-100">
                        <p class="px-4 py-3 text-xs text-slate-600">Mentor baru di Ruang Nalar</p>
                        <p class="px-4 py-3 text-xs text-slate-600">Tryout UTBK tersedia!</p>
                        <p class="px-4 py-3 text-xs text-slate-600">+50 XP dari quiz hari ini</p>
                    </div>
                </div>
            </div>

            <span class="hidden h-8 w-px bg-slate-200 sm:block"></span>

            <div class="relative">
                <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 rounded-full p-1.5 transition hover:bg-slate-100">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white ring-2 ring-blue-600/20">{{ $initials }}</span>

                    <span class="hidden text-left xl:flex xl:flex-col xl:leading-tight">
                        <span class="text-sm font-bold text-slate-800">{{ $name }}</span>
                        <span class="text-xs font-medium text-slate-500">{{ $profileSubtitle }}</span>
                    </span>

                    <svg class="hidden h-4 w-4 text-slate-400 sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="profileOpen" x-cloak @click.away="profileOpen = false" class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-xl">
                    <div class="border-b border-slate-100 px-4 py-3">
                        <p class="truncate text-sm font-bold text-slate-800">{{ $name }}</p>
                        <p class="truncate text-xs text-slate-500 mb-2">{{ $user?->email }}</p>
                        <div class="mt-1.5">
                            {!! $roleBadge !!}
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                        @csrf
                        <button class="w-full px-4 py-3 text-left text-sm font-semibold text-red-500 transition hover:bg-red-50">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>