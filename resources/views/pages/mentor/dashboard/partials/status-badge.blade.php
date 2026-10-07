{{-- Badge status moderasi (paket soal & modul). Warna status + ikon + teks. Butuh: $status. --}}
@if($status === 'approved')
<span class="inline-flex shrink-0 items-center gap-1 whitespace-nowrap rounded-full border border-green-200 bg-green-50 px-2 py-0.5 text-[10px] font-bold text-green-700">
    <x-icon name="check_circle" class="h-3.5 w-3.5" /> Disetujui
</span>
@elseif($status === 'rejected')
<span class="inline-flex shrink-0 items-center gap-1 whitespace-nowrap rounded-full border border-red-200 bg-red-50 px-2 py-0.5 text-[10px] font-bold text-red-700">
    <x-icon name="cancel" class="h-3.5 w-3.5" /> Ditolak
</span>
@else
<span class="inline-flex shrink-0 items-center gap-1 whitespace-nowrap rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-800">
    <x-icon name="schedule" class="h-3.5 w-3.5" /> Menunggu Review
</span>
@endif
