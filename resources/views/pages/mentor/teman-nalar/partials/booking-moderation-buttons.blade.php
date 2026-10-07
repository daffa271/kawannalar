{{-- Setujui / Tolak booking siswa lewat pop-up konfirmasi (<x-moderation-modal> di halaman induk). --}}
<x-moderation-button
    :action="route('mentor.teman-nalar.booking.approve', $booking->id)"
    title="Setujui booking ini?"
    :subject="($booking->student->name ?? 'Siswa').' · '.$booking->topic"
    note="Siswa akan menerima email konfirmasi beserta akses link meeting, dan slot menjadi terisi."
    :class="$approveClass">
    <span class="inline-flex items-center justify-center gap-1"><x-icon name="check" class="h-4 w-4" /> Setujui</span>
</x-moderation-button>
<x-moderation-button
    mode="reject"
    :action="route('mentor.teman-nalar.booking.reject', $booking->id)"
    title="Tolak booking ini?"
    :subject="($booking->student->name ?? 'Siswa').' · '.$booking->topic"
    note="Slot akan kembali tersedia untuk siswa lain."
    audience="siswa di halaman Booking Saya dan dikirim lewat email"
    :class="$rejectClass">
    <span class="inline-flex items-center justify-center gap-1"><x-icon name="close" class="h-4 w-4" /> Tolak</span>
</x-moderation-button>
