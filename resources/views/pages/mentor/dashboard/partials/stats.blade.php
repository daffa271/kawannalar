{{-- 4 kartu statistik. Ikon seragam biru; angka gelap, amber hanya bila ada permintaan menunggu. --}}
@php
    $stats = [
        ['icon' => 'event_available', 'label' => 'Sesi Disetujui', 'value' => $statApproved, 'unit' => 'Sesi', 'note' => 'Mentoring 1-on-1', 'alert' => false],
        ['icon' => 'inbox', 'label' => 'Permintaan Masuk', 'value' => $statPending, 'unit' => 'Pending', 'note' => 'Menunggu persetujuan', 'alert' => $statPending > 0],
        ['icon' => 'menu_book', 'label' => 'Modul Tayang', 'value' => $moduleTayang, 'unit' => 'Modul', 'note' => 'Di Ruang Nalar', 'alert' => false],
        ['icon' => 'calendar_month', 'label' => 'Slot Tersedia', 'value' => $statSlotFree, 'unit' => 'Slot', 'note' => 'Belum dipesan siswa', 'alert' => false],
    ];
@endphp

<div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
    @foreach($stats as $stat)
    <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-5">
        <div class="flex items-start justify-between gap-2">
            <p class="text-xs font-semibold text-gray-500">{{ $stat['label'] }}</p>
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                <x-icon :name="$stat['icon']" class="h-5 w-5" />
            </span>
        </div>
        <p class="mt-2 text-2xl font-extrabold {{ $stat['alert'] ? 'text-amber-700' : 'text-gray-900' }}">
            {{ $stat['value'] }} <span class="text-xs font-semibold text-gray-400">{{ $stat['unit'] }}</span>
        </p>
        <p class="mt-0.5 text-[11px] text-gray-400">{{ $stat['note'] }}</p>
    </div>
    @endforeach
</div>
