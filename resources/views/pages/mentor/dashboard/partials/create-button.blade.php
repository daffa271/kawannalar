{{-- Tombol aksi utama tab (oranye + teks navy), nonaktif saat akun ditangguhkan. Butuh: $href, $icon, $label. --}}
@if($suspended)
<button disabled type="button" class="inline-flex shrink-0 cursor-not-allowed items-center justify-center gap-1.5 rounded-xl bg-gray-200 px-5 py-2.5 text-xs font-bold text-gray-400">
    <x-icon :name="$icon" class="h-4 w-4" /> {{ $label }}
</button>
@else
<a href="{{ $href }}" class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl bg-cta px-5 py-2.5 text-xs font-bold text-navy shadow-sm transition hover:bg-cta-dark">
    <x-icon :name="$icon" class="h-4 w-4" /> {{ $label }}
</a>
@endif
