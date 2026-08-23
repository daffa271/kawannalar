{{--
    Landing Page — KawanNalar.
    Menyatukan semua section dalam urutan yang sudah ditentukan.
    Ganti urutan section cukup dengan comment/uncomment baris @include di bawah.
--}}
<x-layouts.guest title="KawanNalar — Belajar Bersama, Raih Impian">
    @include('landing.sections.hero')
    @include('landing.sections.value-props')
    @include('landing.sections.features')
    @include('landing.sections.stats')
    @include('landing.sections.testimonials')
    @include('landing.sections.cta-banner')
</x-layouts.guest>
