{{-- Tombol Setujui / Tolak: membuka <x-moderation-modal> alih-alih langsung mengirim form. --}}
@props(['mode' => 'approve', 'action', 'title', 'subject' => '', 'note' => '', 'audience' => ''])

<button type="button" x-data
    @click="$dispatch('moderation', @js(compact('mode', 'action', 'title', 'subject', 'note', 'audience')))"
    {{ $attributes }}>{{ $slot }}</button>
