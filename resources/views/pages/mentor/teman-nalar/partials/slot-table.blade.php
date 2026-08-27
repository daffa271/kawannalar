<div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-extrabold text-gray-800 text-sm">Daftar Slot Waktu Luang</h2>
    </div>
    <div class="overflow-x-auto w-full">
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
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 pl-5 pr-3 text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($slot->date)->translatedFormat('l, d M Y') }}</td>
                    <td class="px-3 py-4 text-sm text-gray-600">{{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }} WIB</td>
                    <td class="px-3 py-4 text-sm text-gray-600">{{ $slot->duration ?? '45' }} Menit</td>
                    <td class="px-3 py-4">
                        @if($slot->status === 'terisi')
                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-0.5 text-[11px] font-bold text-[#0A52C4] badge-terisi">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#0A52C4]"></span> Terisi
                            </span>
                            @if($slot->booking && $slot->booking->student)
                                <div class="text-xs text-gray-500 mt-1 font-semibold">{{ $slot->booking->student->name }}</div>
                            @endif
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-[11px] font-bold text-green-700 badge-kosong">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span> Kosong (Tersedia)
                            </span>
                        @endif
                    </td>
                    <td class="px-3 py-4 text-right pr-5">
                        @if($slot->status === 'terisi')
                            <a href="{{ $slot->meeting_link }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#0A52C4] px-3 py-1.5 text-xs font-bold text-white hover:bg-[#0843a1] transition btn-link-meet">
                                🎥 Link Meet
                            </a>
                        @else
                            @if(auth()->user()->is_suspended)
                                <button disabled class="text-xs font-bold text-gray-400 cursor-not-allowed">
                                    Hapus Slot
                                </button>
                            @else
                                <form action="{{ route('mentor.teman-nalar.slot.destroy', $slot->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 transition btn-hapus-slot" onclick="return confirm('Hapus slot ini?')">
                                        Hapus Slot
                                    </button>
                                </form>
                            @endif
                        @endif
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
</div>
