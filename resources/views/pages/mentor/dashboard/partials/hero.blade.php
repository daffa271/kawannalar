{{-- Hero sambutan + aksi cepat. Data profil hanya dari mentorProfile (tanpa teks contoh). --}}
@php
    $firstName = explode(' ', trim($mentor->name))[0];
    $profileLine = collect([
        $profile?->major,
        $profile?->university,
        $profile?->high_school ? 'Alumni '.$profile->high_school : null,
    ])->filter()->implode(' · ');
@endphp

<section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary to-primary-dark p-6 text-white shadow-lg sm:p-8">
    <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/5"></div>
    <div class="absolute -bottom-8 right-32 h-32 w-32 rounded-full bg-white/5"></div>

    <div class="relative z-10 flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
        <div class="min-w-0">
            <p class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-white/75">
                <x-icon name="verified" class="h-4 w-4" /> Dashboard Mentor Terverifikasi
            </p>
            <h1 class="mt-2 text-xl font-extrabold leading-tight sm:text-2xl md:text-3xl">
                Selamat Datang, Kak {{ $firstName }}!
            </h1>
            @if($profileLine !== '')
            <p class="mt-1.5 text-sm leading-relaxed text-white/80">{{ $profileLine }}</p>
            @else
            <a href="{{ route('profile.edit') }}" class="mt-1.5 inline-flex items-center gap-1 text-sm font-semibold text-white/90 underline-offset-2 hover:underline">
                Lengkapi profil mentor kamu <x-icon name="chevron_right" class="h-4 w-4" />
            </a>
            @endif
        </div>

        <div class="flex shrink-0 flex-wrap gap-2">
            @if($suspended)
                <button disabled type="button" class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl bg-white/20 px-5 py-2.5 text-sm font-bold text-white/60">
                    <x-icon name="add" class="h-5 w-5" /> Tambah Slot Waktu Luang
                </button>
                <button disabled type="button" class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl border border-white/20 px-4 py-2.5 text-sm font-bold text-white/60">
                    <x-icon name="edit_note" class="h-5 w-5" /> Buat Soal
                </button>
            @else
                {{-- Aksi utama: oranye + teks navy --}}
                <button @click="showModal = true" type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-cta px-5 py-2.5 text-sm font-bold text-navy shadow-sm transition hover:bg-cta-dark">
                    <x-icon name="add" class="h-5 w-5" /> Tambah Slot Waktu Luang
                </button>
                <a href="{{ route('mentor.uji-nalar.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-white/20">
                    <x-icon name="edit_note" class="h-5 w-5" /> Buat Soal
                </a>
            @endif
        </div>
    </div>
</section>
