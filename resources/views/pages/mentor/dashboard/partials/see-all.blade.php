{{--
    Tautan "Lihat semua" ke halaman fitur. Dashboard hanya ringkasan (5 teratas).
    Butuh: $href, $label, $total, $shown. Tampil hanya bila masih ada data yang tersembunyi
    atau $always = true.
--}}
@if(($always ?? false) || $total > $shown)
<a href="{{ $href }}"
   class="flex items-center justify-center gap-1 rounded-xl border border-primary/20 py-2.5 text-xs font-bold text-primary transition hover:bg-primary/5">
    {{ $label }}@if($total > $shown) ({{ $total }})@endif
    <x-icon name="chevron_right" class="h-4 w-4" />
</a>
@endif
