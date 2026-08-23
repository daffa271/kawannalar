@php
$user = auth()->user();
$name = $user?->name ?? 'Pengguna';
$firstName = explode(' ', trim($name))[0];
$role = $user?->role ?? 'siswa';
$isStudent = $role === 'siswa';
$items = $isStudent ? [
['label' => 'Dashboard Utama', 'route' => 'dashboard.siswa', 'icon' => 'M3 12l2-2 7-7 7 7M5 10v10h14V10M9 21v-6h6v6'],
['label' => 'Ruang Nalar', 'route' => 'siswa.ruang-nalar.index', 'icon' => 'M4 5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v15H6a2 2 0 0 0-2 2V5Zm0 15a2 2 0 0 1 2-2h13M8 7h7M8 11h7'],
['label' => 'Teman Nalar', 'route' => null, 'icon' => 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-8a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7-5a3 3 0 0 1 0 6m4 7v-2a4 4 0 0 0-3-3.9'],
['label' => 'Nalar Diskusi', 'route' => null, 'icon' => 'M21 11.5a8.4 8.4 0 0 1-9 8.5 9 9 0 0 1-4-.9L3 21l1.9-4A8.4 8.4 0 0 1 3 11.5 8.5 8.5 0 0 1 12 3a8.5 8.5 0 0 1 9 8.5Z'],
['label' => 'NalarBot AI', 'route' => null, 'icon' => 'M12 3v3m-7 6a7 7 0 1 0 14 0M5 12H2m20 0h-3M7.1 7.1 5 5m11.9 2.1L19 5M8 17h8m-6 3h4'],
['label' => 'Uji Nalar', 'route' => null, 'icon' => 'm12 3 2.1 4.3 4.7.7-3.4 3.3.8 4.7-4.2-2.2-4.2 2.2.8-4.7-3.4-3.3 4.7-.7L12 3Z'],
['label' => 'Jejak Nalar', 'route' => null, 'icon' => 'M5 20h14M7 20V9l5-3 5 3v11M9 9h6M12 3v3'],
['label' => 'Kabar Nalar', 'route' => null, 'icon' => 'M5 4h14v16H5zM8 8h8M8 12h8M8 16h5'],
] : ($role === 'mentor' ? [
['label' => 'Dashboard Utama', 'route' => 'dashboard.mentor', 'icon' => 'M3 12l2-2 7-7 7 7M5 10v10h14V10M9 21v-6h6v6'],
['label' => 'Ruang Nalar', 'route' => 'mentor.ruang-nalar.index', 'icon' => 'M4 5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v15H6a2 2 0 0 0-2 2V5Zm0 15a2 2 0 0 1 2-2h13M8 7h7M8 11h7'],
] : [[
'label' => 'Dashboard Utama',
'route' => 'dashboard.admin',
'icon' => 'M3 12l2-2 7-7 7 7M5 10v10h14V10M9 21v-6h6v6',
], [
'label' => 'Verifikasi Konten',
'route' => 'admin.verification.index',
'icon' => 'M9 12l2 2 4-4m5 2a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z',
]]);
@endphp

<aside x-data="{ open: false }" @toggle-sidebar.window="open = !open" class="fixed bottom-0 left-0 top-20 z-30 flex w-72 -translate-x-full flex-col justify-between border-r border-gray-200 bg-white shadow-sm transition-transform duration-300 lg:translate-x-0" :class="open ? 'translate-x-0' : '-translate-x-full'">
    <div class="flex h-full flex-col">
        <div class="border-b border-gray-100 px-4 py-5">
            <div class="flex items-center gap-3">
                <div class="relative flex h-12 w-12 items-center justify-center rounded-full bg-[#0A52C4] text-lg font-extrabold text-white ring-4 ring-[#0A52C4]/10">{{ strtoupper(substr($firstName, 0, 1)) }}<span class="absolute -bottom-0.5 -right-0.5 flex h-5 w-5 items-center justify-center rounded-full border-2 border-white bg-[#F28C28] text-[9px]">✎</span></div>
                <div class="min-w-0">
                    <p class="truncate font-extrabold text-gray-900">{{ $firstName }}</p>
                    <p class="truncate text-xs text-gray-500">{{ $user?->studentProfile?->school ?? ucfirst($role) }}</p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="mt-4 inline-flex rounded-lg border border-[#0A52C4]/20 px-3 py-1.5 text-xs font-bold text-[#0A52C4] hover:bg-[#0A52C4]/5">{{ $user?->studentProfile?->grade ?? 'Lengkapi profil' }} <span class="ml-2">›</span></a>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-5">
            <p class="mb-2 px-3 text-[10px] font-extrabold uppercase tracking-wider text-[#0A52C4]">{{ $isStudent ? 'Pilar Belajar' : 'Menu Utama' }}</p>
            <ul class="space-y-2">
                @foreach ($items as $item)
                @php($active = $item['route'] && request()->routeIs($item['route']))
                <li><a href="{{ $item['route'] ? route($item['route']) : '#' }}" class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $active ? 'bg-[#0A52C4] text-white shadow-sm' : 'text-gray-600 hover:bg-[#0A52C4]/5 hover:text-[#0A52C4]' }}">@if ($active)<span class="absolute -left-3 top-1/2 h-8 w-1 -translate-y-1/2 rounded-r-full bg-[#0A52C4]"></span>@endif<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $item['icon'] }}" />
                        </svg><span>{{ $item['label'] }}</span></a></li>
                @endforeach
            </ul>
            @if ($isStudent)
            <p class="mb-2 mt-6 px-3 text-[10px] font-extrabold uppercase tracking-wider text-[#0A52C4]">Utilitas</p>
            <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-[#0A52C4]/5 hover:text-[#0A52C4]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-width="1.6" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-15v6l3 2" />
                </svg>Nalar Focus</a>
            @endif
        </nav>

        <div class="mt-auto border-t border-gray-100 p-4">
            <div class="rounded-2xl border border-[#F28C28]/20 bg-[#FFF8E8] p-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-extrabold text-gray-800">Lengkapi profilmu</p><span class="text-xs font-extrabold text-[#F28C28]">80%</span>
                </div>
                <p class="mt-1 text-[11px] text-gray-500">Dapatkan rekomendasi belajar yang lebih tepat.</p>
                <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-white">
                    <div class="h-full w-4/5 rounded-full bg-[#F28C28]"></div>
                </div>
            </div>
        </div>
    </div>
</aside>