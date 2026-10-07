{{-- Tab "Slot 1-on-1": tabel slot (partial bersama Sesi Mentoring) + sidebar sesi & permintaan. --}}
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="space-y-5 lg:col-span-2">
        @include('pages.mentor.teman-nalar.partials.slot-table', [
            'slots' => $mySlots,
            'title' => 'Tabel Slot Mentoring 1-on-1',
            'subtitle' => $slotTotal > $mySlots->count()
                ? 'Menampilkan '.$mySlots->count().' slot teratas dari '.$slotTotal.' slot'
                : 'Jadwal waktu luang yang bisa dipesan oleh siswa',
            'showAddButton' => true,
        ])
        @include('pages.mentor.dashboard.partials.see-all', [
            'href' => route('mentor.teman-nalar.index'),
            'label' => 'Kelola semua slot di Sesi Mentoring',
            'total' => $slotTotal,
            'shown' => $mySlots->count(),
            'always' => $slotTotal > 0,
        ])
    </div>

    <aside class="space-y-5 lg:col-span-1">
        @include('pages.mentor.dashboard.partials.upcoming-sessions')
        @include('pages.mentor.dashboard.partials.booking-requests')
    </aside>
</div>
