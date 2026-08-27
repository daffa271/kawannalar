<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @php
        $totalSlots = $slots->count();
        $availableSlots = $slots->whereIn('status', ['available', 'kosong'])->count();
        $pendingRequests = $bookings->where('status', 'pending')->count();
        $totalClasses = $liveClasses->count();
    @endphp
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Total Slot 1-on-1</p>
        <p class="mt-1 text-2xl font-extrabold text-gray-900">{{ $totalSlots }}</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Slot Kosong</p>
        <p class="mt-1 text-2xl font-extrabold text-green-600">{{ $availableSlots }}</p>
    </div>
    <div class="rounded-2xl border border-[#0A52C4]/30 bg-blue-50 p-4 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wide text-[#0A52C4]">Permintaan Baru</p>
        <p class="mt-1 text-2xl font-extrabold text-[#0A52C4]">{{ $pendingRequests }}</p>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Live Class Aktif</p>
        <p class="mt-1 text-2xl font-extrabold text-gray-900">{{ $totalClasses }}</p>
    </div>
</div>
