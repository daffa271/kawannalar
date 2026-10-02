@if($slot->status === 'completed')
<span class="inline-flex items-center gap-1 rounded-full bg-gray-200 px-2.5 py-0.5 text-[11px] font-bold text-gray-600">Selesai</span>
@elseif($slot->status === 'expired')
<span class="inline-flex items-center gap-1 rounded-full bg-gray-200 px-2.5 py-0.5 text-[11px] font-bold text-gray-600">Kedaluwarsa</span>
@elseif($slot->booking?->status === 'pending')
<span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-2.5 py-0.5 text-[11px] font-bold text-yellow-700">Menunggu Konfirmasi</span>
<div class="text-xs text-gray-500 mt-1 font-semibold">{{ $slot->booking->student?->name }}</div>
@elseif($slot->booking?->status === 'approved' || $slot->status === 'terisi')
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
