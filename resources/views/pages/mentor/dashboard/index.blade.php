<x-layouts.mentor title="Dashboard Mentor — KawanNalar">
@php
    $firstName = explode(' ', trim($mentor->name))[0];
    $profile   = $mentor->mentorProfile;
@endphp

<div x-data="{ activeTab: 'slot', showModal: false }" class="mx-auto max-w-7xl space-y-6 px-1 sm:px-0">

    @if(auth()->user()->is_suspended)
    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700 flex items-center gap-3">
        <span class="text-xl">⚠️</span>
        <span>Akun Anda sedang ditangguhkan oleh Admin. Silakan hubungi dukungan KawanNalar.</span>
    </div>
    @endif

    {{-- ── HERO WELCOME BANNER ───────────────────────────────────────── --}}
    <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#0A52C4] via-[#1565D8] to-[#0D3E96] p-6 text-white shadow-lg sm:p-8">
        <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-8 right-32 h-32 w-32 rounded-full bg-white/5"></div>
        <div class="relative z-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-200">Dashboard Mentor Verified 👨‍🏫</p>
                <h1 class="mt-2 text-xl font-extrabold leading-tight sm:text-2xl md:text-3xl">
                    Selamat Datang, Kak {{ $firstName }}! 👋
                </h1>
                <p class="mt-1.5 text-sm leading-relaxed text-blue-100">
                    {{ $profile?->major ?? 'D4 Teknik Informatika' }}
                    @if($profile?->university) · {{ $profile->university }} @else · PENS Surabaya @endif
                    @if($profile?->high_school) · Alumni {{ $profile->high_school }} @else · Alumni SMAN 1 Magetan @endif
                </p>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                @if(auth()->user()->is_suspended)
                    <button disabled type="button"
                            class="inline-flex items-center gap-2 rounded-xl bg-gray-300 px-5 py-2.5 text-sm font-bold text-gray-500 shadow-none cursor-not-allowed">
                        + Tambah Slot Waktu Luang
                    </button>
                    <button disabled type="button"
                            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-100 px-4 py-2.5 text-sm font-bold text-gray-400 cursor-not-allowed">
                        ✏️ Buat Soal
                    </button>
                @else
                    <button @click="showModal = true" type="button"
                            class="inline-flex items-center gap-2 rounded-xl bg-[#F28C28] px-5 py-2.5 text-sm font-bold text-white shadow-md hover:bg-[#E07D1C] transition">
                        + Tambah Slot Waktu Luang
                    </button>
                    <a href="{{ route('mentor.uji-nalar.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-4 py-2.5 text-sm font-bold text-white hover:bg-white/20 transition">
                        ✏️ Buat Soal
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- ── 4 STAT CARDS (real data) ───────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-400">Sesi Disetujui</p>
                <span class="rounded-lg bg-blue-50 p-2 text-base">🎯</span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-[#0A52C4]">{{ $statApproved }} Sesi</p>
            <p class="mt-0.5 text-[10px] text-gray-400">Mentoring 1-on-1</p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-400">Permintaan Masuk</p>
                <span class="rounded-lg bg-amber-50 p-2 text-base">📥</span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-amber-500">{{ $statPending }} <span class="text-xs font-normal text-gray-400">Pending</span></p>
            <p class="mt-0.5 text-[10px] text-gray-400">Menunggu persetujuan</p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-400">Modul Tayang</p>
                <span class="rounded-lg bg-emerald-50 p-2 text-base">📚</span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-emerald-600">{{ $moduleTayang }} Modul</p>
            <p class="mt-0.5 text-[10px] text-gray-400">Di Perpustakaan Catatan</p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-400">Slot Tersedia</p>
                <span class="rounded-lg bg-indigo-50 p-2 text-base">📅</span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-indigo-600">{{ $statSlotFree }} Slot</p>
            <p class="mt-0.5 text-[10px] text-gray-400">Belum dipesan siswa</p>
        </div>
    </div>

    {{-- ── TAB SELECTOR INTERAKTIF ──────────────────────────────────── --}}
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-6 overflow-x-auto">
            <button @click="activeTab = 'slot'"
                    :class="activeTab === 'slot' ? 'border-[#0A52C4] text-[#0A52C4] font-extrabold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                    class="whitespace-nowrap border-b-2 py-3 px-1 text-sm transition">
                📅 Kelola Slot 1-on-1
            </button>

            <button @click="activeTab = 'soal'"
                    :class="activeTab === 'soal' ? 'border-[#0A52C4] text-[#0A52C4] font-extrabold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                    class="whitespace-nowrap border-b-2 py-3 px-1 text-sm transition">
                ⚡ Buat Paket Soal (Uji Nalar)
                @if($pendingCount > 0)
                <span class="ml-1.5 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-700">{{ $pendingCount }} Pending</span>
                @endif
            </button>

            <button @click="activeTab = 'modul'"
                    :class="activeTab === 'modul' ? 'border-[#0A52C4] text-[#0A52C4] font-extrabold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                    class="whitespace-nowrap border-b-2 py-3 px-1 text-sm transition">
                📚 Upload Modul
            </button>
        </nav>
    </div>

    {{-- ── TAB CONTENT 1: KELOLA SLOT 1-ON-1 (real data) ───────────── --}}
    <div x-show="activeTab === 'slot'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Tabel Slot Mentoring (2 Kolom) --}}
        <div class="space-y-5 lg:col-span-2">
            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="font-extrabold text-gray-900 text-base">Tabel Slot Mentoring 1-on-1</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Jadwal waktu luang yang bisa dipesan oleh siswa</p>
                    </div>
                    @if(auth()->user()->is_suspended)
                        <button disabled type="button"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-gray-200 px-3.5 py-2 text-xs font-bold text-gray-400 cursor-not-allowed">
                            + Tambah Slot
                        </button>
                    @else
                        <button @click="showModal = true" type="button"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-[#F28C28] px-3.5 py-2 text-xs font-bold text-white hover:bg-[#E07D1C] transition">
                            + Tambah Slot
                        </button>
                    @endif
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-gray-100 bg-[#F8FAFC] text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-3.5 rounded-l-xl">Hari &amp; Tanggal</th>
                                <th class="py-3 px-3.5">Jam</th>
                                <th class="py-3 px-3.5">Durasi</th>
                                <th class="py-3 px-3.5">Status</th>
                                <th class="py-3 px-3.5 rounded-r-xl text-right">Aksi / Meet</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($mySlots as $slot)
                            @php
                                $hasBooking = $slot->booking && $slot->booking->student;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3.5 px-3.5 font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($slot->date)->translatedFormat('l, d M Y') }}
                                </td>
                                <td class="py-3.5 px-3.5 text-gray-600 font-semibold">
                                    {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }} WIB
                                </td>
                                <td class="py-3.5 px-3.5 text-gray-500">{{ $slot->duration ?? '45' }} Menit</td>
                                <td class="py-3.5 px-3.5">
                                    @if($slot->status === 'terisi' && $hasBooking)
                                        <div class="flex flex-col gap-0.5">
                                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-0.5 text-[10px] font-extrabold text-[#0A52C4]">
                                                <span class="h-1.5 w-1.5 rounded-full bg-[#0A52C4]"></span> Terisi
                                            </span>
                                            <p class="text-[11px] font-semibold text-gray-700">
                                                {{ $slot->booking->student->name }}
                                            </p>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-[10px] font-bold text-green-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span> Kosong (Tersedia)
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3.5 text-right">
                                    @if($slot->status === 'terisi' && $slot->meeting_link)
                                        <a href="{{ $slot->meeting_link }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 rounded-xl border border-[#0A52C4] px-3 py-1.5 text-xs font-bold text-[#0A52C4] hover:bg-[#EEF4FF] transition">
                                            🎥 Link Meet
                                        </a>
                                    @else
                                        @if(auth()->user()->is_suspended)
                                            <span class="text-xs font-semibold text-gray-300 cursor-not-allowed">Hapus Slot</span>
                                        @else
                                            <form action="{{ route('mentor.teman-nalar.slot.destroy', $slot->id) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-xs font-semibold text-red-500 hover:underline"
                                                        onclick="return confirm('Hapus slot ini?')">Hapus Slot</button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-sm text-gray-400">
                                    Belum ada slot waktu luang. Klik <strong>+ Tambah Slot</strong> untuk memulai.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        {{-- Sidebar Kanan: Sesi Terdekat & Permintaan Baru --}}
        <aside class="space-y-5 lg:col-span-1">

            {{-- ■ Card: Sesi Bimbingan Terdekat --}}
            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                    <h3 class="font-extrabold text-gray-900 text-sm">⏰ Sesi Bimbingan Mendatang</h3>
                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-extrabold text-green-700">
                        {{ $upcomingSessions->count() }} Aktif
                    </span>
                </div>

                @forelse($upcomingSessions as $session)
                <div class="rounded-xl border border-[#F28C28]/20 bg-[#FFF8F0] p-3 text-xs space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-gray-900">{{ $session->student->name ?? '-' }}</span>
                        <span class="text-[10px] text-gray-400">
                            {{ $session->student->studentProfile->school ?? '' }}
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-600">Topik: {{ $session->topic }}</p>
                    @if($session->slot)
                    <p class="text-[11px] text-[#F28C28] font-semibold">
                        📅 {{ \Carbon\Carbon::parse($session->slot->date)->translatedFormat('d M Y') }}
                        · {{ substr($session->slot->start_time, 0, 5) }} WIB
                    </p>
                    @endif
                    @if($session->slot?->meeting_link)
                    <a href="{{ $session->slot->meeting_link }}" target="_blank"
                       class="mt-1 flex w-full items-center justify-center gap-1.5 rounded-xl bg-[#F28C28] py-2 text-[11px] font-bold text-white hover:bg-[#E07D1C] transition">
                        🎥 Masuk Google Meet
                    </a>
                    @endif
                </div>
                @empty
                <div class="py-6 text-center text-xs text-gray-400">
                    Belum ada jadwal bimbingan mendatang.
                </div>
                @endforelse
            </section>

            {{-- ■ Card: Permintaan Mentoring Baru --}}
            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                    <h3 class="font-extrabold text-gray-900 text-sm">📥 Permintaan Mentoring Baru</h3>
                    @if($pendingBookings->count() > 0)
                    <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-extrabold text-[#0A52C4]">
                        {{ $pendingBookings->count() }} Baru
                    </span>
                    @endif
                </div>

                @forelse($pendingBookings as $booking)
                <div class="rounded-xl border border-gray-100 p-3 text-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-gray-800">{{ $booking->student->name ?? '-' }}</span>
                        <span class="text-[10px] text-gray-400">
                            {{ $booking->student->studentProfile->school ?? '' }}
                        </span>
                    </div>
                    <p class="text-gray-500 text-[11px]">Topik: {{ $booking->topic }}</p>
                    @if($booking->slot)
                    <p class="text-[11px] text-gray-400 font-semibold">
                        ⏰ {{ \Carbon\Carbon::parse($booking->slot->date)->translatedFormat('l, d M Y') }}
                        · {{ substr($booking->slot->start_time, 0, 5) }} WIB
                    </p>
                    @endif
                    @if($booking->message)
                    <p class="italic text-gray-400 text-[11px]">&ldquo;{{ $booking->message }}&rdquo;</p>
                    @endif
                    <div class="flex gap-2 pt-1">
                        @if(auth()->user()->is_suspended)
                            <button disabled class="flex-1 rounded-lg bg-gray-200 py-1.5 text-center font-bold text-gray-400 text-[11px] cursor-not-allowed">Setujui</button>
                            <button disabled class="flex-1 rounded-lg border border-gray-100 py-1.5 text-center font-bold text-gray-400 text-[11px] cursor-not-allowed">Tolak</button>
                        @else
                            <form action="{{ route('mentor.teman-nalar.booking.approve', $booking->id) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full rounded-lg bg-green-600 py-1.5 font-bold text-white text-[11px] hover:bg-green-700">
                                    ✅ Setujui
                                </button>
                            </form>
                            <form action="{{ route('mentor.teman-nalar.booking.reject', $booking->id) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full rounded-lg border border-red-200 py-1.5 font-bold text-red-600 text-[11px] hover:bg-red-50">
                                    ❌ Tolak
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="py-6 text-center text-xs text-gray-400">
                    Belum ada pengajuan bimbingan baru dari siswa.
                </div>
                @endforelse
            </section>
        </aside>
    </div>

    {{-- ── TAB CONTENT 2: UJI NALAR (PAKET SOAL MENTOR) ─────────────── --}}
    <div x-show="activeTab === 'soal'" class="space-y-6">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center mb-6">
                <div>
                    <h2 class="font-extrabold text-gray-900 text-lg">⚡ Fitur Uji Nalar: Kelola &amp; Buat Soal</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Buat paket soal preset 5, 10, atau 15 soal untuk siswa Magetan.</p>
                </div>
                @if(auth()->user()->is_suspended)
                    <button disabled type="button"
                       class="inline-flex items-center gap-2 rounded-xl bg-gray-200 px-5 py-2.5 text-xs font-bold text-gray-400 cursor-not-allowed">
                        + Buat Soal Baru
                    </button>
                @else
                    <a href="{{ route('mentor.uji-nalar.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-[#F28C28] px-5 py-2.5 text-xs font-bold text-white shadow hover:bg-[#E07D1C] transition">
                        + Buat Soal Baru
                    </a>
                @endif
            </div>

            @if($myQuizzes->isEmpty())
            <div class="rounded-xl border border-dashed border-gray-200 py-12 text-center">
                <span class="text-3xl">✏️</span>
                <p class="mt-2 text-sm font-bold text-gray-700">Belum ada paket soal</p>
                <p class="text-xs text-gray-400 mt-1">Mulai buat paket soal pertamamu!</p>
                <a href="{{ route('mentor.uji-nalar.create') }}"
                   class="mt-4 inline-flex items-center gap-2 rounded-xl bg-[#0A52C4] px-5 py-2.5 text-xs font-bold text-white transition">
                    + Buat Paket Soal
                </a>
            </div>
            @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($myQuizzes as $quiz)
                <div class="rounded-xl border border-gray-100 p-4 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="rounded-full bg-[#0A52C4]/10 px-2 py-0.5 text-[10px] font-bold text-[#0A52C4]">
                            {{ $quiz->subject->name ?? '-' }}
                        </span>
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold whitespace-nowrap
                            {{ $quiz->status === 'approved' ? 'bg-green-100 text-green-700' : ($quiz->status === 'rejected' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-700') }}">
                            {{ $quiz->status === 'approved' ? '✓ Disetujui' : ($quiz->status === 'rejected' ? '✕ Ditolak' : '⏳ Pending') }}
                        </span>
                    </div>

                    <h3 class="font-extrabold text-gray-900 text-sm leading-snug">{{ $quiz->title }}</h3>
                    <p class="text-xs text-gray-400">Kelas {{ $quiz->class_level }} · {{ $quiz->total_questions }} Soal</p>

                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between text-xs">
                        <span class="text-gray-400 text-[10px]">{{ $quiz->created_at?->format('d M Y') }}</span>
                        <a href="{{ route('mentor.uji-nalar.show', $quiz) }}" class="font-bold text-[#0A52C4] hover:underline">Detail Soal ›</a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- ── TAB CONTENT 3: UPLOAD MODUL ──────────────────────────────── --}}
    <div x-show="activeTab === 'modul'" class="space-y-6">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center mb-6">
                <div>
                    <h2 class="font-extrabold text-gray-900 text-lg">📚 Upload &amp; Kelola Modul Pembelajaran</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Bagikan catatan atau materi latihan untuk siswa di Ruang Nalar.</p>
                </div>
                @if(auth()->user()->is_suspended)
                    <button disabled type="button"
                       class="inline-flex items-center gap-2 rounded-xl bg-gray-200 px-5 py-2.5 text-xs font-bold text-gray-400 cursor-not-allowed">
                        + Upload Modul Baru
                    </button>
                @else
                    <a href="{{ route('mentor.ruang-nalar.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-[#F28C28] px-5 py-2.5 text-xs font-bold text-white shadow hover:bg-[#E07D1C] transition">
                        + Upload Modul Baru
                    </a>
                @endif
            </div>

            @if($myModules->isEmpty())
            <div class="rounded-xl border border-dashed border-gray-200 py-12 text-center text-xs text-gray-400">
                Belum ada modul yang diunggah.
            </div>
            @else
            <div class="divide-y divide-gray-100">
                @foreach($myModules as $module)
                <div class="flex items-center justify-between py-4">
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $module->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $module->subject }} · {{ $module->grade }} · {{ number_format($module->download_count) }} diunduh</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-bold whitespace-nowrap
                        {{ $module->status === 'approved' ? 'bg-green-100 text-green-700' : ($module->status === 'rejected' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-700') }}">
                        {{ $module->status === 'approved' ? 'Disetujui' : ($module->status === 'rejected' ? 'Ditolak' : 'Pending Review') }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    @include('pages.mentor.teman-nalar.partials.modal-add-slot')
</div>
</x-layouts.mentor>