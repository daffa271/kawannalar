{{-- Permintaan Booking dari Siswa (Hanya Status Pending) --}}
<div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden flex flex-col h-full">

    {{-- Card Header --}}
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-blue-50/50 shrink-0">
        <h2 class="font-extrabold text-[#0A52C4] text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Permintaan Booking
        </h2>
        @if($bookings->count() > 0)
            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white animate-pulse">
                {{ $bookings->count() }}
            </span>
        @endif
    </div>

    {{-- Card Body --}}
    <div class="p-4 space-y-4 flex-1 overflow-y-auto max-h-[600px]">
        @forelse($bookings as $booking)
        <div class="rounded-xl border border-blue-200 bg-blue-50/30 p-4">

            {{-- Student Info --}}
            <div class="flex items-start gap-3">
                <div class="h-10 w-10 shrink-0 rounded-full bg-gradient-to-br from-[#0A52C4] to-blue-400 flex items-center justify-center text-white font-extrabold text-sm">
                    {{ strtoupper(substr($booking->student->name ?? 'S', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ $booking->student->name ?? 'Siswa' }}</p>
                    <p class="text-xs text-gray-500 truncate">
                        {{ $booking->student->studentProfile->school ?? $booking->student->school_name ?? 'Sekolah tidak diketahui' }}
                    </p>
                </div>
                <span class="rounded bg-yellow-100 px-2 py-0.5 text-[10px] font-bold text-yellow-700 shrink-0">Pending</span>
            </div>

            {{-- Booking Details --}}
            <div class="mt-3 space-y-1.5 rounded-lg bg-white p-3 border border-gray-100 text-xs">
                <div class="flex items-start gap-2">
                    <span class="font-semibold text-gray-500 w-14 shrink-0">Topik:</span>
                    <span class="text-gray-900 font-bold">{{ $booking->topic ?? '-' }}</span>
                </div>
                @if($booking->slot)
                <div class="flex items-start gap-2">
                    <span class="font-semibold text-gray-500 w-14 shrink-0">Jadwal:</span>
                    <span class="text-gray-700">
                        {{ \Carbon\Carbon::parse($booking->slot->date)->translatedFormat('d M Y') }},
                        {{ substr($booking->slot->start_time, 0, 5) }} – {{ substr($booking->slot->end_time, 0, 5) }} WIB
                    </span>
                </div>
                @endif
                @if($booking->message)
                <div class="flex items-start gap-2 pt-2 border-t border-gray-100 mt-2">
                    <span class="font-semibold text-gray-500 w-14 shrink-0">Pesan:</span>
                    <span class="text-gray-600 italic">"{{ $booking->message }}"</span>
                </div>
                @endif
            </div>

            {{-- Approve / Reject Buttons --}}
            <div class="mt-3 flex gap-2">
                @if(auth()->user()->is_suspended)
                    <button disabled class="flex-1 rounded-lg bg-gray-200 py-2 text-xs font-bold text-gray-400 cursor-not-allowed">
                        ✅ Setujui
                    </button>
                    <button disabled class="flex-1 rounded-lg border border-gray-150 bg-gray-100 py-2 text-xs font-bold text-gray-400 cursor-not-allowed">
                        ❌ Tolak
                    </button>
                @else
                    <form action="{{ route('mentor.teman-nalar.booking.approve', $booking->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-full rounded-lg bg-green-500 py-2 text-xs font-bold text-white hover:bg-green-600 active:scale-95 transition">
                            ✅ Setujui
                        </button>
                    </form>
                    <form action="{{ route('mentor.teman-nalar.booking.reject', $booking->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-full rounded-lg border border-red-200 bg-red-50 py-2 text-xs font-bold text-red-600 hover:bg-red-100 active:scale-95 transition">
                            ❌ Tolak
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @empty
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm font-bold text-gray-500">Tidak ada permintaan baru</p>
            <p class="text-xs text-gray-400 mt-1">Semua booking sudah ditangani.</p>
        </div>
        @endforelse
    </div>
</div>
