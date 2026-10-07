{{-- Status moderasi paket soal: warna status + ikon + teks. Butuh: $status. Opsional: $long (label panjang). --}}
@php($long = $long ?? false)
@if($status === 'approved')
<span class="inline-flex shrink-0 items-center gap-1 whitespace-nowrap rounded-full bg-green-100 px-2.5 py-0.5 text-[10px] font-extrabold text-green-700 {{ $long ? 'sm:text-xs' : '' }}">
    <x-icon name="check_circle" class="h-3.5 w-3.5" /> {{ $long ? 'Disetujui (Tayang)' : 'Disetujui' }}
</span>
@elseif($status === 'rejected')
<span class="inline-flex shrink-0 items-center gap-1 whitespace-nowrap rounded-full bg-red-100 px-2.5 py-0.5 text-[10px] font-extrabold text-red-700 {{ $long ? 'sm:text-xs' : '' }}">
    <x-icon name="cancel" class="h-3.5 w-3.5" /> {{ $long ? 'Ditolak Admin' : 'Ditolak' }}
</span>
@else
<span class="inline-flex shrink-0 items-center gap-1 whitespace-nowrap rounded-full bg-amber-100 px-2.5 py-0.5 text-[10px] font-extrabold text-amber-800 {{ $long ? 'sm:text-xs' : '' }}">
    <x-icon name="schedule" class="h-3.5 w-3.5" /> {{ $long ? 'Menunggu Moderasi' : 'Pending' }}
</span>
@endif
