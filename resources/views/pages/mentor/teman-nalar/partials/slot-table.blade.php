{{-- Dipakai halaman Sesi Mentoring & Dashboard Mentor. Opsional: $title, $subtitle, $showAddButton. --}}
<div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <h2 class="font-extrabold text-gray-800 text-sm">{{ $title ?? 'Daftar Slot Waktu Luang' }}</h2>
            @isset($subtitle)
            <p class="text-xs text-gray-400 mt-0.5">{{ $subtitle }}</p>
            @endisset
        </div>
        @if($showAddButton ?? false)
            @if(auth()->user()->is_suspended)
            <button disabled type="button" class="shrink-0 rounded-xl bg-gray-200 px-4 py-2 text-xs font-bold text-gray-400 cursor-not-allowed">
                + Tambah Slot
            </button>
            @else
            <button @click="showModal = true" type="button" class="shrink-0 rounded-xl bg-[#F28C28] px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-[#E07D1C] transition">
                + Tambah Slot
            </button>
            @endif
        @endif
    </div>

    {{-- Desktop: tabel --}}
    <div class="hidden overflow-x-auto w-full md:block">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 pl-5 pr-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Tanggal</th>
                    <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Waktu</th>
                    <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Durasi</th>
                    <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Status</th>
                    <th class="px-3 py-3 text-right text-xs font-bold uppercase tracking-wide text-gray-500 pr-5">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($slots as $slot)
                <tr class="{{ in_array($slot->status, ['completed', 'expired']) ? 'bg-gray-100 text-gray-400' : 'hover:bg-gray-50' }} transition-colors">
                    <td class="py-4 pl-5 pr-3 text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($slot->date)->translatedFormat('l, d M Y') }}</td>
                    <td class="px-3 py-4 text-sm text-gray-600">{{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }} WIB</td>
                    <td class="px-3 py-4 text-sm text-gray-600">{{ $slot->duration ?? '45' }} Menit</td>
                    <td class="px-3 py-4">
                        @include('pages.mentor.teman-nalar.partials.slot-status')
                    </td>
                    <td class="px-3 py-4 text-right pr-5">
                        @include('pages.mentor.teman-nalar.partials.slot-actions')
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-sm text-gray-400 font-medium">
                        Belum ada slot waktu luang yang dibuat. Klik <strong>+ Tambah Slot</strong> untuk membuka jadwal bimbingan!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- HP: kartu per slot (status & aksi selalu terlihat) --}}
    <div class="divide-y divide-gray-100 md:hidden">
        @forelse($slots as $slot)
        <div class="space-y-3 px-5 py-4 {{ in_array($slot->status, ['completed', 'expired']) ? 'bg-gray-50' : '' }}">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-sm font-bold {{ in_array($slot->status, ['completed', 'expired']) ? 'text-gray-500' : 'text-gray-900' }}">{{ \Carbon\Carbon::parse($slot->date)->translatedFormat('l, d M Y') }}</p>
                    <p class="mt-0.5 text-xs text-gray-500">{{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }} WIB · {{ $slot->duration ?? '45' }} Menit</p>
                    @if($slot->topic)
                    <p class="mt-0.5 text-xs font-semibold text-[#0A52C4]">{{ $slot->topic }}</p>
                    @endif
                </div>
                <div class="shrink-0 text-right">
                    @include('pages.mentor.teman-nalar.partials.slot-status')
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @include('pages.mentor.teman-nalar.partials.slot-actions')
            </div>
        </div>
        @empty
        <p class="px-5 py-8 text-center text-sm text-gray-400 font-medium">
            Belum ada slot waktu luang yang dibuat. Klik <strong>+ Tambah Slot</strong> untuk membuka jadwal bimbingan!
        </p>
        @endforelse
    </div>
</div>
