{{-- Tautan KTM calon mentor. Klik biasa membuka pratinjau di halaman yang sama lewat <x-module-preview-modal>, bukan tab baru; href tetap ada sebagai cadangan. --}}
@props(['mentor'])

@php
    $ext = strtolower(pathinfo((string) $mentor->mentorProfile?->ktm_path, PATHINFO_EXTENSION));
    $preview = ['title' => $mentor->name, 'download' => '', 'type' => in_array($ext, ['jpg', 'jpeg', 'png']) ? 'image' : 'pdf'];
@endphp

<a href="{{ route('admin.mentors.ktm', $mentor->id) }}" x-data
    @click.prevent="$dispatch('module-preview', { ...@js($preview), url: $el.href })"
    {{ $attributes }}>{{ $slot }}</a>
