{{-- Sesi bimbingan yang sudah disetujui, dari jadwal terdekat (maks. 5). --}}
<section class="space-y-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between gap-2 border-b border-gray-100 pb-2">
        <h3 class="flex items-center gap-1.5 text-sm font-extrabold text-gray-900">
            <x-icon name="schedule" class="h-5 w-5 text-primary" /> Sesi Bimbingan Mendatang
        </h3>
        <span class="shrink-0 rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-extrabold text-primary">
            {{ $statApproved }} Terjadwal
        </span>
    </div>

    @forelse($upcomingSessions as $session)
    <div class="space-y-1.5 rounded-xl border border-gray-100 bg-surface p-3 text-xs">
        <div class="flex items-start justify-between gap-2">
            <span class="font-bold text-gray-900">{{ $session->student->name ?? '-' }}</span>
            <span class="truncate text-right text-[10px] text-gray-400">{{ $session->student?->studentProfile?->school }}</span>
        </div>
        <p class="text-[11px] text-gray-600">Topik: {{ $session->topic }}</p>
        @if($session->slot)
        <p class="flex items-center gap-1 text-[11px] font-semibold text-primary">
            <x-icon name="calendar_month" class="h-3.5 w-3.5" />
            {{ \Carbon\Carbon::parse($session->slot->date)->translatedFormat('d M Y') }} · {{ substr($session->slot->start_time, 0, 5) }} WIB
        </p>
        @endif
        @if($session->slot?->meeting_link)
        <a href="{{ route('mentor.teman-nalar.booking.meeting', $session->id) }}" target="_blank" rel="noopener"
           class="mt-1 flex w-full items-center justify-center gap-1.5 rounded-xl bg-cta py-2 text-[11px] font-bold text-navy transition hover:bg-cta-dark">
            <x-icon name="videocam" class="h-4 w-4" /> Masuk Google Meet
        </a>
        @endif
    </div>
    @empty
    <p class="py-6 text-center text-xs text-gray-400">Belum ada jadwal bimbingan mendatang.</p>
    @endforelse

    @include('pages.mentor.dashboard.partials.see-all', [
        'href' => route('mentor.teman-nalar.index'),
        'label' => 'Lihat semua sesi',
        'total' => $statApproved,
        'shown' => $upcomingSessions->count(),
    ])
</section>
