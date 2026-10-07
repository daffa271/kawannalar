{{-- Navigasi tab. HP: 3 kolom sama lebar dengan label pendek; sm ke atas: label penuh. --}}
@php
    $tabs = [
        ['key' => 'slot', 'icon' => 'calendar_month', 'short' => 'Slot 1-on-1', 'full' => 'Kelola Slot 1-on-1', 'badge' => null],
        ['key' => 'soal', 'icon' => 'quiz', 'short' => 'Paket Soal', 'full' => 'Buat Paket Soal (Uji Nalar)', 'badge' => $pendingCount],
        ['key' => 'modul', 'icon' => 'menu_book', 'short' => 'Modul', 'full' => 'Upload Modul', 'badge' => null],
    ];
@endphp

<div class="border-b border-gray-200">
    <nav class="-mb-px grid grid-cols-3 sm:flex sm:space-x-6" role="tablist" aria-label="Bagian dashboard">
        @foreach($tabs as $tab)
        <button type="button" role="tab" @click="activeTab = '{{ $tab['key'] }}'"
                :aria-selected="(activeTab === '{{ $tab['key'] }}').toString()"
                :class="activeTab === '{{ $tab['key'] }}' ? 'border-primary text-primary font-extrabold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                class="inline-flex items-center justify-center gap-1.5 border-b-2 px-1 py-3 text-xs transition sm:whitespace-nowrap sm:text-sm">
            <x-icon :name="$tab['icon']" class="h-4 w-4" />
            <span class="sm:hidden">{{ $tab['short'] }}</span>
            <span class="hidden sm:inline">{{ $tab['full'] }}</span>
            @if($tab['badge'])
            <span class="rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold text-amber-800">{{ $tab['badge'] }}<span class="hidden sm:inline"> Pending</span></span>
            @endif
        </button>
        @endforeach
    </nav>
</div>
