@php
$user = auth()->user();
$name = $user?->name ?? 'Pengguna';
$firstName = explode(' ', trim($name))[0];
$role = $user?->role ?? 'siswa';
$isStudent = $role === 'siswa';

$studentMainItems = [
    [
        'label' => 'Dashboard Utama',
        'route' => 'dashboard.siswa',
        'icon'  => 'M3 12l2-2 7-7 7 7M5 10v10h14V10M9 21v-6h6v6',
        'badge' => null,
    ],
];

$studentPilarItems = [
    [
        'label' => 'Ruang Nalar',
        'route' => 'siswa.ruang-nalar.index',
        'icon'  => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        'badge' => 'Modul',
    ],
    [
        'label' => 'Teman Nalar',
        'route' => 'siswa.teman-nalar.index',
        'icon'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'badge' => null,
    ],
    [
        'label' => 'Nalar Diskusi',
        'route' => null,
        'icon'  => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
        'badge' => null,
    ],
    [
        'label' => 'NalarBot AI',
        'route' => null,
        'icon'  => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'badge' => 'AI',
    ],
    [
        'label' => 'Uji Nalar',
        'route' => 'siswa.uji-nalar.index',
        'icon'  => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
        'badge' => 'Tryout',
    ],
    [
        'label' => 'Jejak Nalar',
        'route' => null,
        'icon'  => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
        'badge' => null,
    ],
    [
        'label' => 'Kabar Nalar',
        'route' => null,
        'icon'  => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z',
        'badge' => null,
    ],
];

$studentUtilityItems = [
    [
        'label' => 'Nalar Focus',
        'route' => null,
        'icon'  => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'badge' => 'Timer',
    ],
];

$mentorItems = [
    [
        'label' => 'Dashboard Utama',
        'route' => 'dashboard.mentor',
        'icon'  => 'M3 12l2-2 7-7 7 7M5 10v10h14V10M9 21v-6h6v6',
        'badge' => null,
    ],
    [
        'label' => 'Upload Modul',
        'route' => 'mentor.ruang-nalar.index',
        'icon'  => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        'badge' => null,
    ],
    [
        'label' => 'Buat Soal (Uji Nalar)',
        'route' => 'mentor.uji-nalar.index',
        'icon'  => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
        'badge' => null,
    ],
    [
        'label' => 'Sesi Mentoring',
        'route' => 'mentor.teman-nalar.index',
        'icon'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'badge' => null,
    ],
];

$adminItems = [
    [
        'label' => 'Dashboard Utama',
        'route' => 'dashboard.admin',
        'icon'  => 'M3 12l2-2 7-7 7 7M5 10v10h14V10M9 21v-6h6v6',
        'badge' => null,
    ],
    [
        'label' => 'Verifikasi Konten',
        'route' => 'admin.verification.index',
        'icon'  => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
        'badge' => null,
    ],
    [
        'label' => 'Moderasi Paket Soal',
        'route' => 'admin.quizzes.index',
        'icon'  => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        'badge' => null,
    ],
    [
        'label' => 'Data Siswa',
        'route' => 'admin.users.siswa',
        'icon'  => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        'badge' => null,
    ],
    [
        'label' => 'Data Mentor',
        'route' => 'admin.users.mentor',
        'icon'  => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'badge' => null,
    ],
];

$items = match($role) {
    'mentor' => $mentorItems,
    'admin' => $adminItems,
    default => [],
};
@endphp

{{-- Mobile overlay backdrop --}}
<div x-data="{ open: false }" @toggle-sidebar.window="open = !open">
    <div x-show="open" @click="$dispatch('toggle-sidebar')"
        x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-20 bg-black/40 lg:hidden" style="display:none"></div>
</div>

<aside x-data="{ open: false }" @toggle-sidebar.window="open = !open"
    class="fixed bottom-0 left-0 top-20 z-30 flex w-72 -translate-x-full flex-col justify-between border-r border-gray-200/80 bg-white/95 backdrop-blur-md shadow-sm transition-transform duration-300 lg:translate-x-0"
    :class="open ? 'translate-x-0' : '-translate-x-full'">
    <div class="flex h-full flex-col">
        {{-- Profile Card Header --}}
        <div class="border-b border-gray-100/80 px-4 py-4.5">
            <div class="flex items-center gap-3">
                <div class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#0A52C4] to-[#16418C] text-base font-extrabold text-white ring-4 ring-[#0A52C4]/10 shadow-sm">
                    {{ strtoupper(substr($firstName, 0, 1)) }}
                    <span class="absolute -bottom-0.5 -right-0.5 flex h-4.5 w-4.5 items-center justify-center rounded-full border-2 border-white bg-[#F28C28] text-[9px] font-bold text-white shadow-xs">✏️</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate font-extrabold text-gray-900 text-sm leading-tight">{{ $firstName }}</p>
                    <p class="truncate text-[11px] text-gray-500 mt-0.5">
                        @if ($role === 'siswa')
                            {{ $user?->studentProfile?->school ?? 'Siswa KawanNalar' }}
                        @elseif ($role === 'mentor')
                            {{ $user?->mentorProfile?->university ?? 'Mentor KawanNalar' }}
                        @else
                            Inovator KawanNalar
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="mt-3.5 inline-flex items-center gap-1.5 rounded-lg border border-[#0A52C4]/20 bg-[#0A52C4]/5 px-3 py-1.5 text-xs font-bold text-[#0A52C4] hover:bg-[#0A52C4]/10 hover:border-[#0A52C4]/30 transition-all duration-200">
                <span>
                    @if ($role === 'siswa')
                        {{ $user?->studentProfile?->grade ?? 'Lengkapi profil' }}
                    @elseif ($role === 'mentor')
                        Verified Mentor
                    @else
                        Admin System
                    @endif
                </span>
                <span class="text-[10px] font-extrabold">›</span>
            </a>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-4">
            @if ($isStudent)
                {{-- Dashboard Utama --}}
                <div>
                    <ul class="space-y-1">
                        @foreach ($studentMainItems as $item)
                        @php($active = $item['route'] && request()->routeIs($item['route']))
                        <li>
                            <a href="{{ $item['route'] ? route($item['route']) : '#' }}"
                                class="group relative flex items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ $active ? 'bg-[#0A52C4] text-white shadow-md shadow-[#0A52C4]/20 font-semibold' : 'text-gray-700 hover:bg-[#0A52C4]/8 hover:text-[#0A52C4] hover:translate-x-0.5' }}">
                                @if ($active)
                                <span class="absolute -left-4 top-1/2 h-7 w-1.5 -translate-y-1/2 rounded-r-full bg-[#F28C28]"></span>
                                @endif
                                <div class="flex items-center gap-3">
                                    <svg class="h-5 w-5 shrink-0 transition-colors {{ $active ? 'text-white' : 'text-gray-400 group-hover:text-[#0A52C4]' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}" />
                                    </svg>
                                    <span>{{ $item['label'] }}</span>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Pilar Belajar (7 Fitur Utama) --}}
                <div>
                    <div class="mb-2 px-3 flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#0A52C4]">Pilar Belajar</span>
                        <span class="text-[9px] font-bold rounded-full bg-[#0A52C4]/10 text-[#0A52C4] px-1.5 py-0.2">7 Fitur</span>
                    </div>
                    <ul class="space-y-1">
                        @foreach ($studentPilarItems as $item)
                        @php($active = $item['route'] && request()->routeIs($item['route']))
                        <li>
                            <a href="{{ $item['route'] ? route($item['route']) : '#' }}"
                                class="group relative flex items-center justify-between gap-2 rounded-xl px-3 py-2 text-sm font-medium transition-all duration-200 {{ $active ? 'bg-[#0A52C4] text-white shadow-md shadow-[#0A52C4]/20 font-semibold' : 'text-gray-700 hover:bg-[#0A52C4]/8 hover:text-[#0A52C4] hover:translate-x-0.5' }}">
                                @if ($active)
                                <span class="absolute -left-4 top-1/2 h-7 w-1.5 -translate-y-1/2 rounded-r-full bg-[#F28C28]"></span>
                                @endif
                                <div class="flex items-center gap-3 min-w-0">
                                    <svg class="h-5 w-5 shrink-0 transition-colors {{ $active ? 'text-white' : 'text-gray-400 group-hover:text-[#0A52C4]' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}" />
                                    </svg>
                                    <span class="truncate">{{ $item['label'] }}</span>
                                </div>
                                @if($item['badge'])
                                <span class="shrink-0 px-1.5 py-0.5 text-[9px] font-bold rounded-md {{ $active ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-[#0A52C4]/10 group-hover:text-[#0A52C4]' }}">
                                    {{ $item['badge'] }}
                                </span>
                                @endif
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Utilitas (Nalar Focus) --}}
                <div>
                    <div class="mb-2 px-3 flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#0A52C4]">Utilitas</span>
                    </div>
                    <ul class="space-y-1">
                        @foreach ($studentUtilityItems as $item)
                        @php($active = $item['route'] && request()->routeIs($item['route']))
                        <li>
                            <a href="{{ $item['route'] ? route($item['route']) : '#' }}"
                                class="group relative flex items-center justify-between gap-2 rounded-xl px-3 py-2 text-sm font-medium transition-all duration-200 {{ $active ? 'bg-[#0A52C4] text-white shadow-md shadow-[#0A52C4]/20 font-semibold' : 'text-gray-700 hover:bg-[#0A52C4]/8 hover:text-[#0A52C4] hover:translate-x-0.5' }}">
                                @if ($active)
                                <span class="absolute -left-4 top-1/2 h-7 w-1.5 -translate-y-1/2 rounded-r-full bg-[#F28C28]"></span>
                                @endif
                                <div class="flex items-center gap-3 min-w-0">
                                    <svg class="h-5 w-5 shrink-0 transition-colors {{ $active ? 'text-white' : 'text-gray-400 group-hover:text-[#0A52C4]' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}" />
                                    </svg>
                                    <span class="truncate">{{ $item['label'] }}</span>
                                </div>
                                @if($item['badge'])
                                <span class="shrink-0 px-1.5 py-0.5 text-[9px] font-bold rounded-md {{ $active ? 'bg-white/20 text-white' : 'bg-[#FFC000]/15 text-[#D49B00] group-hover:bg-[#FFC000]/25' }}">
                                    {{ $item['badge'] }}
                                </span>
                                @endif
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

            @else
                {{-- Mentor / Admin items --}}
                <div class="mb-2 px-3">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-[#0A52C4]">Menu Utama</p>
                </div>
                <ul class="space-y-1">
                    @foreach ($items as $item)
                    @php($active = $item['route'] && request()->routeIs($item['route']))
                    <li>
                        <a href="{{ $item['route'] ? route($item['route']) : '#' }}"
                            class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ $active ? 'bg-[#0A52C4] text-white shadow-md shadow-[#0A52C4]/20 font-semibold' : 'text-gray-700 hover:bg-[#0A52C4]/8 hover:text-[#0A52C4] hover:translate-x-0.5' }}">
                            @if ($active)
                            <span class="absolute -left-4 top-1/2 h-7 w-1.5 -translate-y-1/2 rounded-r-full bg-[#F28C28]"></span>
                            @endif
                            <svg class="h-5 w-5 shrink-0 transition-colors {{ $active ? 'text-white' : 'text-gray-400 group-hover:text-[#0A52C4]' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}" />
                            </svg>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            @endif
        </nav>

        {{-- Footer Profile Level / Gamification Card --}}
        <div class="mt-auto border-t border-gray-100/80 p-4">
            @if ($role === 'mentor')
            <div class="rounded-2xl border border-[#0A52C4]/20 bg-gradient-to-br from-[#EEF4FF] to-[#E5EFFF] p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-extrabold text-[#0A52C4]">Level 4 · Inspirator Aktif</p>
                    <span class="text-xs font-extrabold text-[#F28C28]">1,250 XP</span>
                </div>
                <p class="mt-1 text-[11px] text-gray-500 leading-snug">Terus aktif berbagi modul &amp; paket soal!</p>
                <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-white/80 shadow-inner">
                    <div class="h-full w-3/4 rounded-full bg-gradient-to-r from-[#0A52C4] to-[#F28C28]"></div>
                </div>
            </div>
            @elseif ($role === 'siswa')
            <div class="rounded-2xl border border-[#F28C28]/25 bg-gradient-to-br from-[#FFF9F2] to-[#FFF4E5] p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-extrabold text-gray-800">Lengkapi profilmu</p>
                    <span class="text-xs font-extrabold text-[#F28C28]">80%</span>
                </div>
                <p class="mt-1 text-[11px] text-gray-500 leading-snug">Dapatkan rekomendasi belajar yang lebih tepat.</p>
                <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-white/80 shadow-inner">
                    <div class="h-full w-4/5 rounded-full bg-[#F28C28]"></div>
                </div>
            </div>
            @else
            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-3.5 text-center">
                <p class="text-xs font-bold text-gray-700">Administrator System</p>
                <p class="text-[10px] text-gray-500 mt-0.5">KawanNalar Dashboard</p>
            </div>
            @endif
        </div>
    </div>
</aside>
