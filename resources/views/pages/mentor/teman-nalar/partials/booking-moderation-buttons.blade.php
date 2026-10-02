{{-- Setujui / Tolak booking siswa lewat pop-up konfirmasi (<x-moderation-modal> di halaman induk). --}}
<x-moderation-button
    :action="route('mentor.teman-nalar.booking.approve', $booking->id)"
    title="Setujui booking ini?"
    :subject="($booking->student->name ?? 'Siswa').' · '.$booking->topic"
    note="Siswa akan mendapat akses link meeting dan slot menjadi terisi."
    :class="$approveClass">
    ✅ Setujui
</x-moderation-button>
<x-moderation-button
    mode="reject"
    :action="route('mentor.teman-nalar.booking.reject', $booking->id)"
    title="Tolak booking ini?"
    :subject="($booking->student->name ?? 'Siswa').' · '.$booking->topic"
    note="Slot akan kembali tersedia untuk siswa lain."
    audience="siswa di halaman Booking Saya"
    :class="$rejectClass">
    ❌ Tolak
</x-moderation-button>
