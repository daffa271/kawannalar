@if($slot->booking?->status === 'approved')
<a href="{{ route('mentor.teman-nalar.booking.meeting', $slot->booking->id) }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#0A52C4] px-3 py-1.5 text-xs font-bold text-white hover:bg-[#0843a1] transition btn-link-meet">
    🎥 Link Meet
</a>
<form action="{{ route('mentor.teman-nalar.booking.complete', $slot->booking->id) }}" method="POST" class="inline">
    @csrf
    @method('PATCH')
    <button type="submit" class="ml-1 inline-flex items-center justify-center rounded-lg bg-gray-200 px-3 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-300">Mentoring Selesai</button>
</form>
@elseif(in_array($slot->status, ['completed', 'expired']))
<span class="text-xs font-bold text-gray-400">Tidak aktif</span>
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
