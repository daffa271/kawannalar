{{-- Permintaan booking siswa yang menunggu persetujuan. Setujui/Tolak lewat <x-moderation-modal> di index. --}}
<section class="space-y-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between gap-2 border-b border-gray-100 pb-2">
        <h3 class="flex items-center gap-1.5 text-sm font-extrabold text-gray-900">
            <x-icon name="inbox" class="h-5 w-5 text-primary" /> Permintaan Mentoring Baru
        </h3>
        @if($statPending > 0)
        <span class="shrink-0 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-extrabold text-amber-800">
            {{ $statPending }} Baru
        </span>
        @endif
    </div>

    @forelse($pendingBookings as $booking)
    <div class="space-y-2 rounded-xl border border-gray-100 p-3 text-xs">
        <div class="flex items-start justify-between gap-2">
            <span class="font-bold text-gray-800">{{ $booking->student->name ?? '-' }}</span>
            <span class="truncate text-right text-[10px] text-gray-400">{{ $booking->student?->studentProfile?->school }}</span>
        </div>
        <p class="text-[11px] text-gray-500">Topik: {{ $booking->topic }}</p>
        @if($booking->slot)
        <p class="flex items-center gap-1 text-[11px] font-semibold text-gray-500">
            <x-icon name="schedule" class="h-3.5 w-3.5" />
            {{ \Carbon\Carbon::parse($booking->slot->date)->translatedFormat('l, d M Y') }} · {{ substr($booking->slot->start_time, 0, 5) }} WIB
        </p>
        @endif
        @if($booking->message)
        <p class="text-[11px] italic text-gray-400">&ldquo;{{ $booking->message }}&rdquo;</p>
        @endif
        <div class="flex gap-2 pt-1">
            @if($suspended)
                <button disabled class="flex-1 cursor-not-allowed rounded-lg bg-gray-200 py-1.5 text-center text-[11px] font-bold text-gray-400">Setujui</button>
                <button disabled class="flex-1 cursor-not-allowed rounded-lg border border-gray-100 py-1.5 text-center text-[11px] font-bold text-gray-400">Tolak</button>
            @else
                @include('pages.mentor.teman-nalar.partials.booking-moderation-buttons', [
                    'approveClass' => 'flex-1 rounded-lg bg-green-600 py-1.5 font-bold text-white text-[11px] hover:bg-green-700',
                    'rejectClass' => 'flex-1 rounded-lg border border-red-200 py-1.5 font-bold text-red-600 text-[11px] hover:bg-red-50',
                ])
            @endif
        </div>
    </div>
    @empty
    <p class="py-6 text-center text-xs text-gray-400">Belum ada pengajuan bimbingan baru dari siswa.</p>
    @endforelse

    @include('pages.mentor.dashboard.partials.see-all', [
        'href' => route('mentor.teman-nalar.index'),
        'label' => 'Lihat semua permintaan',
        'total' => $statPending,
        'shown' => $pendingBookings->count(),
    ])
</section>
