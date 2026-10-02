<x-layouts.siswa title="Dashboard Siswa — KawanNalar">
    @php
    $profile = $student->studentProfile;
    $firstName = explode(' ', trim($student->name))[0];
    @endphp

    {{-- Suspended Banner --}}
    @if(auth()->user()->is_suspended)
    <div class="mb-4 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700">
        <x-icon name="warning" class="h-6 w-6" />
        <span>Akun Anda sedang ditangguhkan oleh Admin. Silakan hubungi dukungan KawanNalar.</span>
    </div>
    @endif

    {{-- Main Grid: 1-col on mobile, 12-col on lg+ --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-12 lg:gap-6">

        {{-- ══════════ KOLOM UTAMA (8/12) ══════════ --}}
        <div class="space-y-5 lg:col-span-8">

            {{-- ── Hero / Selamat Datang ── --}}
            <section class="relative overflow-hidden rounded-2xl bg-[#0A52C4] p-5 text-white shadow-lg sm:p-7">
                <div class="absolute -right-12 -top-20 h-60 w-60 rounded-full border-[35px] border-white/5"></div>
                <div class="relative z-10">
                    <p class="text-[11px] font-semibold text-blue-100 sm:text-xs">
                        {{ $profile?->target_university ? 'Target: '.$profile->target_university : 'Perjalanan belajarmu dimulai di sini' }}
                    </p>
                    <h1 class="mt-1.5 text-lg font-extrabold leading-tight sm:text-2xl">
                        Selamat Datang Kembali, {{ $firstName }}!
                    </h1>
                    <p class="mt-1.5 text-[11px] leading-relaxed text-blue-100 sm:text-sm">
                        Tetap semangat, terus melangkah menuju kampus impianmu!<br>
                        Target: <strong class="text-white">{{ $profile?->target_major ?? 'Tentukan target jurusan' }}{{ $profile?->target_university ? ' · '.$profile->target_university : '' }}</strong>
                    </p>
                    {{-- Stats Row --}}
                    <div class="mt-4 grid grid-cols-2 gap-2 sm:gap-3">
                        <div class="rounded-xl border border-white/10 bg-white/10 p-2.5 text-center sm:p-3.5 sm:text-left">
                            <x-icon name="trophy" class="mx-auto h-5 w-5 text-[#FFC000] sm:mx-0" />
                            <p class="mt-1 text-[10px] text-blue-100">Poin</p>
                            <p class="truncate text-xs font-extrabold sm:text-sm">{{ number_format($xp) }} XP</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/10 p-2.5 text-center sm:p-3.5 sm:text-left">
                            <x-icon name="quiz" class="mx-auto h-5 w-5 text-blue-100 sm:mx-0" />
                            <p class="mt-1 text-[10px] text-blue-100">Latihan Uji Nalar</p>
                            <p class="text-xs font-extrabold sm:text-sm">{{ number_format($quizCount) }} Kuis</p>
                        </div>
                    </div>
                </div>
                <x-icon name="school" class="absolute bottom-3 right-6 hidden h-24 w-24 text-white/10 sm:block" />
            </section>

            {{-- ── Sesi Mentoring Mendatang ── --}}
            <section class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">Sesi Mentoring Mendatang</h2>
                        <p class="mt-0.5 text-[11px] text-gray-400">Teman Nalar membantu langkahmu lebih terarah.</p>
                    </div>
                    <a href="{{ route('siswa.teman-nalar.index') }}" class="shrink-0 text-xs font-bold text-[#0A52C4]">Lihat Semua ›</a>
                </div>

                @if ($upcomingMentoring)
                <div class="mt-4 rounded-xl border border-gray-100 bg-[#F8FAFF] p-3 sm:p-4">
                    {{-- Mentor Info Row --}}
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#0A52C4] text-sm font-bold text-white">
                            {{ strtoupper(substr($upcomingMentoring->mentor->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-gray-900 text-sm leading-tight">
                                Kak {{ $upcomingMentoring->mentor->name }}
                                @if($upcomingMentoring->status === 'approved')
                                    <span class="text-green-500 text-xs">● Disetujui</span>
                                @else
                                    <span class="text-yellow-500 text-xs">● Pending</span>
                                @endif
                            </p>
                            <p class="mt-0.5 text-[11px] text-gray-500">
                                {{ $upcomingMentoring->mentor->mentorProfile?->university ?? 'PTN' }} · {{ $upcomingMentoring->mentor->mentorProfile?->major ?? 'Jurusan' }}
                            </p>
                            {{-- Date & badges --}}
                            <div class="mt-2 flex flex-wrap items-center gap-1.5">
                                <span class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2 py-0.5 text-[11px] font-semibold text-[#0A52C4]">
                                    <x-icon name="calendar_month" class="h-3.5 w-3.5" />
                                    {{ \Carbon\Carbon::parse($upcomingMentoring->slot->date)->isToday() ? 'Hari ini' : \Carbon\Carbon::parse($upcomingMentoring->slot->date)->translatedFormat('D, d M Y') }}, {{ substr($upcomingMentoring->slot->start_time, 0, 5) }} WIB
                                </span>
                                @if($upcomingMentoring->status === 'pending')
                                    <span class="rounded-md bg-yellow-100 px-2 py-0.5 text-[11px] font-semibold text-yellow-700">Menunggu Persetujuan</span>
                                @else
                                    <span class="rounded-md bg-green-100 px-2 py-0.5 text-[11px] font-semibold text-green-700">Disetujui</span>
                                @endif
                                <span class="rounded-md bg-[#FFF3E5] px-2 py-0.5 text-[11px] font-semibold text-[#C26A13]">{{ $upcomingMentoring->topic }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- Action Buttons (always full-width on mobile) --}}
                    <div class="mt-3 flex gap-2">
                        @if(! auth()->user()->is_suspended && $upcomingMentoring->status === 'approved' && $upcomingMentoring->slot->meeting_link)
                            <a href="{{ $upcomingMentoring->slot->meeting_link }}" target="_blank" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-[#F28C28] py-2.5 text-xs font-bold text-white hover:bg-[#E07D1C] transition">
                                <x-icon name="videocam" class="h-4 w-4" /> Masuk Meet
                            </a>
                        @else
                            <button disabled class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-gray-200 py-2.5 text-xs font-bold text-gray-400 cursor-not-allowed">
                                <x-icon name="videocam" class="h-4 w-4" /> Masuk Meet
                            </button>
                        @endif
                        <a href="{{ route('siswa.teman-nalar.index') }}" class="flex-1 rounded-xl border border-[#0A52C4]/25 py-2.5 text-xs font-bold text-[#0A52C4] text-center hover:bg-[#0A52C4]/5 transition">Lihat Detail</a>
                    </div>
                </div>
                @else
                <div class="mt-4 rounded-xl border border-dashed border-gray-200 px-4 py-8 text-center">
                    <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-[#EEF4FF] text-[#0A52C4]">
                        <x-icon name="event_available" class="h-6 w-6" />
                    </span>
                    <p class="mt-3 text-sm font-bold text-gray-700">Belum ada sesi terjadwal</p>
                    <p class="mt-1 text-xs text-gray-400">Booking bimbingan dengan mentor pilihanmu.</p>
                    <a href="{{ route('siswa.teman-nalar.index') }}" class="mt-3 inline-block rounded-xl bg-[#0A52C4] px-4 py-2 text-xs font-bold text-white hover:bg-[#0843a1] transition">Cari Mentor →</a>
                </div>
                @endif
            </section>

            {{-- ── Nalar Kilat ── --}}
            <section class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">Nalar Kilat</h2>
                        <p class="mt-0.5 text-[11px] text-gray-400">Latihan singkat setiap hari bikin kamu makin siap.</p>
                    </div>
                    <a href="{{ route('siswa.uji-nalar.index') }}#nalar-kilat" class="shrink-0 text-xs font-bold text-[#0A52C4]">Lihat Semua ›</a>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-2.5">
                    <a href="{{ route('siswa.uji-nalar.kilat', ['jumlah' => 5]) }}" class="rounded-xl border border-green-100 bg-green-50 px-2 py-3.5 text-center transition hover:border-green-300 hover:bg-green-100/50">
                        <p class="text-sm font-extrabold text-gray-800">5 Soal</p>
                        <p class="mt-0.5 text-[10px] text-gray-500">± 5 Mnt</p>
                    </a>
                    <a href="{{ route('siswa.uji-nalar.kilat', ['jumlah' => 10]) }}" class="rounded-xl border border-[#FFC000]/30 bg-[#FFF9E8] px-2 py-3.5 text-center transition hover:border-[#FFC000]">
                        <p class="text-sm font-extrabold text-gray-800">10 Soal</p>
                        <p class="mt-0.5 text-[10px] text-gray-500">± 10 Mnt</p>
                    </a>
                    <a href="{{ route('siswa.uji-nalar.kilat', ['jumlah' => 15]) }}" class="rounded-xl border border-blue-100 bg-blue-50 px-2 py-3.5 text-center transition hover:border-blue-300 hover:bg-blue-100/50">
                        <p class="text-sm font-extrabold text-gray-800">15 Soal</p>
                        <p class="mt-0.5 text-[10px] text-gray-500">± 15 Mnt</p>
                    </a>
                </div>
            </section>

            {{-- ── Ruang Nalar Quick Feed: klik membuka pratinjau, unduh lewat tombol terpisah ── --}}
            <section x-data class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">Ruang Nalar Quick Feed</h2>
                        <p class="mt-0.5 text-[11px] text-gray-400">Materi populer untuk menemani belajarmu.</p>
                    </div>
                    <a href="{{ route('siswa.ruang-nalar.index') }}" class="shrink-0 text-xs font-bold text-[#0A52C4]">Lihat Semua ›</a>
                </div>
                <div class="mt-4 grid gap-3 grid-cols-1 sm:grid-cols-2">
                    @forelse ($popularModules as $module)
                    @php($extension = strtolower(pathinfo($module->file_path, PATHINFO_EXTENSION)))
                    @php($preview = ['title' => $module->title, 'url' => route('siswa.ruang-nalar.preview', $module), 'download' => route('siswa.ruang-nalar.download', $module), 'type' => in_array($extension, ['jpg', 'jpeg', 'png']) ? 'image' : 'pdf'])
                    <article class="flex flex-col rounded-xl border border-gray-100 p-3 transition hover:border-[#0A52C4]/20 hover:bg-[#F8FAFF]">
                        <button type="button" @click="$dispatch('module-preview', @js($preview))" class="flex min-w-0 gap-3 text-left">
                            <span class="flex h-14 w-11 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#0A52C4] to-[#16418C] text-[10px] font-extrabold tracking-wide text-white">{{ strtoupper($extension) }}</span>
                            <span class="min-w-0 flex-1">
                                <span class="line-clamp-2 text-xs font-bold text-gray-800 sm:text-sm">{{ $module->title }}</span>
                                <span class="mt-0.5 block truncate text-[10px] text-gray-400">oleh {{ $module->uploader?->name ?? 'Mentor KawanNalar' }}</span>
                                <span class="mt-1 inline-flex items-center gap-1 text-[10px] text-gray-500">
                                    <x-icon name="download" class="h-3.5 w-3.5" /> {{ number_format($module->download_count) }} Unduhan
                                </span>
                            </span>
                        </button>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <button type="button" @click="$dispatch('module-preview', @js($preview))"
                                class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-[#0A52C4]/30 bg-white py-2 text-xs font-bold text-[#0A52C4] transition hover:bg-[#EEF4FF]">
                                <x-icon name="visibility" class="h-4 w-4" /> Pratinjau
                            </button>
                            <a href="{{ $preview['download'] }}"
                                class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#F28C28] py-2 text-xs font-bold text-white transition hover:bg-[#E07D1C]">
                                <x-icon name="download" class="h-4 w-4" /> Unduh
                            </a>
                        </div>
                    </article>
                    @empty
                    <p class="col-span-full rounded-xl bg-[#F4F7FA] px-4 py-6 text-center text-xs text-gray-500">Belum ada modul populer.</p>
                    @endforelse
                </div>
            </section>

            {{-- ── Kabar Nalar (info beasiswa/lomba/PTN): fitur belum dibuka ── --}}
            <section class="flex flex-col gap-4 rounded-2xl border border-[#F28C28]/30 bg-[#FFF8E8] p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
                <div class="flex min-w-0 items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#C26A13]">
                        <x-icon name="campaign" class="h-5 w-5" />
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs font-extrabold text-gray-900 sm:text-sm">Kabar Nalar segera hadir</p>
                        <p class="mt-1 text-[11px] leading-relaxed text-gray-500">Info beasiswa, lomba, dan perguruan tinggi dari mentor, diverifikasi Admin sebelum tayang.</p>
                    </div>
                </div>
                <a href="{{ route('siswa.kabar-nalar') }}" class="w-full shrink-0 rounded-xl bg-[#F28C28] px-4 py-2.5 text-center text-xs font-bold text-white transition hover:bg-[#E07D1C] sm:w-auto">Lihat Kabar Nalar</a>
            </section>
        </div>

        {{-- ══════════ SIDEBAR KANAN (4/12) ══════════ --}}
        <aside class="space-y-5 lg:col-span-4">

            @include('pages.siswa.dashboard.partials.nalar-focus')

            {{-- ── NalarBot AI: fitur belum dibuka, semua pintasan menuju halaman Segera Hadir ── --}}
            <section class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">NalarBot AI</h2>
                    <span class="rounded-full bg-[#FFC000]/15 px-2 py-0.5 text-[10px] font-bold text-[#8A6500]">Segera hadir</span>
                </div>
                <p class="mt-0.5 text-[11px] text-gray-400">Asisten AI untuk minat bakat, peluang PTN, dan teman cerita.</p>
                <div class="mt-4 grid grid-cols-3 gap-2">
                    <a href="{{ route('siswa.nalarbot') }}" class="flex flex-col items-center gap-1.5 rounded-xl bg-green-50 px-1 py-3 text-center text-[11px] font-bold leading-tight text-gray-700 transition hover:bg-green-100">
                        <x-icon name="psychology" class="h-6 w-6 text-green-700" /> Minat Bakat
                    </a>
                    <a href="{{ route('siswa.nalarbot') }}" class="flex flex-col items-center gap-1.5 rounded-xl bg-blue-50 px-1 py-3 text-center text-[11px] font-bold leading-tight text-gray-700 transition hover:bg-blue-100">
                        <x-icon name="insights" class="h-6 w-6 text-[#0A52C4]" /> Peluang PTN
                    </a>
                    <a href="{{ route('siswa.nalarbot') }}" class="flex flex-col items-center gap-1.5 rounded-xl bg-pink-50 px-1 py-3 text-center text-[11px] font-bold leading-tight text-gray-700 transition hover:bg-pink-100">
                        <x-icon name="forum" class="h-6 w-6 text-pink-600" /> Curhat AI
                    </a>
                </div>
            </section>

            {{-- ── Magetan Champions ── --}}
            <section class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">Magetan Champions</h2>
                    <a href="{{ route('siswa.uji-nalar.index') }}#peringkat" class="text-xs font-bold text-[#0A52C4]">Peringkat ›</a>
                </div>
                <div class="mt-4 divide-y divide-gray-100">
                    @forelse ($leaderboard as $rank => $leader)
                    <div class="flex items-center gap-3 py-3 first:pt-0">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full {{ $rank === 0 ? 'bg-[#FFC000] text-white' : ($rank === 1 ? 'bg-gray-200 text-gray-700' : 'bg-[#D99159] text-white') }} text-xs font-extrabold">{{ $rank + 1 }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="truncate text-xs font-bold text-gray-800">{{ $leader['name'] }}</p>
                            <p class="truncate text-[10px] text-gray-400">{{ $leader['school'] }}</p>
                        </div>
                        <span class="shrink-0 text-xs font-extrabold text-[#F28C28]">{{ number_format($leader['xp']) }} XP</span>
                    </div>
                    @empty
                    <p class="py-4 text-center text-xs text-gray-400">Belum ada peringkat. Kerjakan Uji Nalar untuk mengumpulkan XP.</p>
                    @endforelse
                </div>
                <a href="{{ route('siswa.uji-nalar.index') }}#peringkat" class="mt-4 inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-[#0A52C4]/15 py-2.5 text-xs font-bold text-[#0A52C4] hover:bg-[#0A52C4]/5">
                    <x-icon name="leaderboard" class="h-4 w-4" /> Lihat Peringkat Lengkap
                </a>
            </section>
        </aside>
    </div>

    <x-module-preview-modal />
</x-layouts.siswa>
