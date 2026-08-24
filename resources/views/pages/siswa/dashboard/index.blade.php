<x-layouts.siswa title="Dashboard Siswa — KawanNalar">
    @php
    $profile = $student->studentProfile;
    $firstName = explode(' ', trim($student->name))[0];
    @endphp
    <div class="mx-auto max-w-7xl space-y-6 px-1 sm:px-0">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-8">
            <!-- Kolom Utama -->
            <div class="space-y-6 lg:col-span-8">
                <!-- Hero Section / Selamat Datang -->
                <section class="relative overflow-hidden rounded-2xl bg-[#0A52C4] p-5 text-white shadow-lg sm:p-8 md:p-10">
                    <div class="absolute -right-16 -top-24 h-72 w-72 rounded-full border-[40px] border-white/5"></div>
                    <div class="relative z-10 max-w-2xl">
                        <p class="text-xs font-semibold text-blue-100 sm:text-sm">
                            {{ $profile?->target_university ? 'Target: '.$profile->target_university : 'Perjalanan belajarmu dimulai di sini' }}
                        </p>
                        <h1 class="mt-2 text-xl font-extrabold leading-tight sm:text-2xl md:text-3xl">
                            Selamat Datang Kembali, {{ $firstName }}! 👋
                        </h1>
                        <p class="mt-2 text-xs leading-relaxed text-blue-100 sm:text-sm">
                            Tetap semangat, 120 Hari lagi menuju UTBK SNBT 2026!<br>
                            Target Kampus: <strong class="text-white">{{ $profile?->target_major ?? 'Tentukan target jurusan impianmu' }}{{ $profile?->target_university ? ' · '.$profile->target_university : '' }}</strong>
                        </p>
                        <!-- Grid Stats (Streak, Poin, Waktu Fokus) -->
                        <div class="mt-5 grid grid-cols-3 gap-2.5 sm:gap-4">
                            <div class="rounded-xl border border-white/10 bg-white/10 p-2.5 sm:p-3.5 text-center sm:text-left transition-all hover:bg-white/15">
                                <p class="text-base sm:text-lg">🔥</p>
                                <p class="mt-0.5 text-[10px] text-blue-100 sm:text-[11px]">Streak</p>
                                <p class="truncate text-xs font-extrabold sm:text-sm md:text-base">{{ $streakDays }} Hari</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/10 p-2.5 sm:p-3.5 text-center sm:text-left transition-all hover:bg-white/15">
                                <p class="text-base sm:text-lg">🏆</p>
                                <p class="mt-0.5 text-[10px] text-blue-100 sm:text-[11px]">Poin</p>
                                <p class="truncate text-xs font-extrabold sm:text-sm md:text-base">{{ number_format($xp) }} XP</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/10 p-2.5 sm:p-3.5 text-center sm:text-left transition-all hover:bg-white/15">
                                <p class="text-base sm:text-lg">⏱️</p>
                                <p class="mt-0.5 text-[10px] text-blue-100 sm:text-[11px]">Fokus</p>
                                <p class="truncate text-xs font-extrabold sm:text-sm md:text-base">{{ $focusHours }} Jam</p>
                            </div>
                        </div>
                    </div>
                    <div class="absolute bottom-0 right-8 hidden text-[90px] leading-none opacity-30 sm:block">🏫</div>
                </section>

                <!-- Sesi Mentoring Mendatang -->
                <section class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">Sesi Mentoring Mendatang</h2>
                            <p class="mt-0.5 text-[11px] sm:text-xs text-gray-400">Teman Nalar membantu langkahmu lebih terarah.</p>
                        </div>
                        <a href="#" class="text-xs font-bold text-[#0A52C4] shrink-0">Lihat Semua ›</a>
                    </div>
                    @if ($upcomingMentoring)
                    <div class="mt-4 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-full bg-[#0A52C4] text-sm sm:text-lg font-bold text-white">M</div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-gray-900 text-xs sm:text-sm">Mentor Alifa <span class="text-[#0A52C4]">●</span></p>
                                <p class="text-[10px] sm:text-xs text-gray-500 truncate">Mahasiswa PENS · D4 Teknik Informatika</p>
                                <p class="mt-1.5 text-[10px] sm:text-xs text-gray-500 flex flex-wrap items-center gap-1.5">
                                    <span>Hari ini, 19:30 WIB</span>
                                    <span class="rounded-md bg-[#FFF3E5] px-1.5 py-0.5 font-semibold text-[#C26A13]">1-on-1 Mentoring</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap sm:flex-nowrap gap-2 w-full lg:w-auto mt-2 lg:mt-0">
                            <a href="#" class="flex-1 sm:flex-none text-center rounded-xl bg-[#F28C28] px-4 py-2.5 text-xs font-bold text-white hover:bg-[#E07D1C]">🎥 Masuk Meet</a>
                            <a href="#" class="flex-1 sm:flex-none text-center rounded-xl border border-[#0A52C4]/20 px-4 py-2.5 text-xs font-bold text-[#0A52C4] hover:bg-slate-50">Reschedule</a>
                        </div>
                    </div>
                    @else
                    <div class="mt-4 rounded-xl border border-dashed border-gray-200 px-4 py-6 text-center">
                        <p class="text-xs sm:text-sm font-bold text-gray-700">Belum ada sesi terjadwal</p>
                        <p class="mt-1 text-[11px] sm:text-xs text-gray-400">Booking bimbingan dengan mentor pilihanmu segera hadir.</p>
                    </div>
                    @endif
                </section>

                <!-- Nalar Kilat -->
                <section class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">⚡ Nalar Kilat</h2>
                            <p class="mt-0.5 text-[11px] sm:text-xs text-gray-400">Latihan singkat setiap hari bikin kamu makin siap.</p>
                        </div>
                        <a href="#" class="text-xs font-bold text-[#0A52C4] shrink-0">Lihat Semua ›</a>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-2.5 sm:gap-4">
                        <a href="#" class="rounded-xl border border-green-100 bg-green-50 p-2.5 sm:p-4 text-center transition hover:border-green-300 hover:bg-green-100/50">
                            <p class="text-lg sm:text-xl">⚡</p>
                            <p class="mt-1.5 text-xs font-extrabold text-gray-800 sm:text-sm">5 Soal</p>
                            <p class="text-[9px] text-gray-500 sm:text-xs">± 5 Mnt</p>
                        </a>
                        <a href="#" class="rounded-xl border border-[#FFC000]/30 bg-[#FFF9E8] p-2.5 sm:p-4 text-center transition hover:border-[#FFC000] hover:bg-[#FFF9E8]/70">
                            <p class="text-lg sm:text-xl">🔥</p>
                            <p class="mt-1.5 text-xs font-extrabold text-gray-800 sm:text-sm">10 Soal</p>
                            <p class="text-[9px] text-gray-500 sm:text-xs">± 10 Mnt</p>
                        </a>
                        <a href="#" class="rounded-xl border border-blue-100 bg-blue-50 p-2.5 sm:p-4 text-center transition hover:border-blue-300 hover:bg-blue-100/50">
                            <p class="text-lg sm:text-xl">💪</p>
                            <p class="mt-1.5 text-xs font-extrabold text-gray-800 sm:text-sm">15 Soal</p>
                            <p class="text-[9px] text-gray-500 sm:text-xs">± 15 Mnt</p>
                        </a>
                    </div>
                </section>

                <!-- Ruang Nalar Quick Feed -->
                <section class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">📚 Ruang Nalar Quick Feed</h2>
                            <p class="mt-0.5 text-[11px] sm:text-xs text-gray-400">Materi populer untuk menemani belajarmu.</p>
                        </div>
                        <a href="{{ route('siswa.ruang-nalar.index') }}" class="text-xs font-bold text-[#0A52C4] shrink-0">Lihat Semua ›</a>
                    </div>
                    <div class="mt-4 grid gap-3 grid-cols-1 sm:grid-cols-2">
                        @forelse ($popularModules as $module)
                        <a href="{{ route('siswa.ruang-nalar.download', $module) }}" class="flex gap-3 rounded-xl border border-gray-100 p-3 transition hover:border-[#0A52C4]/20 hover:bg-[#F4F7FA]">
                            <div class="flex h-14 w-11 sm:h-16 sm:w-12 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#0A52C4] to-[#16418C] text-lg sm:text-xl text-white">📘</div>
                            <div class="min-w-0 flex-1">
                                <p class="line-clamp-2 text-xs sm:text-sm font-bold text-gray-800">{{ $module->title }}</p>
                                <p class="mt-0.5 text-[10px] sm:text-xs text-gray-400 truncate">oleh {{ $module->uploader?->name ?? 'Mentor KawanNalar' }}</p>
                                <p class="mt-1 text-[10px] sm:text-xs text-gray-500">⇩ {{ number_format($module->download_count) }} Unduhan</p>
                            </div>
                        </a>
                        @empty
                        <p class="col-span-1 sm:col-span-2 rounded-xl bg-[#F4F7FA] px-4 py-6 text-center text-xs sm:text-sm text-gray-500">Belum ada modul populer. Materi baru akan tampil di sini.</p>
                        @endforelse
                    </div>
                </section>

                <!-- Banner Beasiswa -->
                <section class="flex flex-col gap-4 rounded-2xl border border-[#F28C28]/30 bg-[#FFF8E8] p-4 sm:p-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <p class="font-extrabold text-gray-900 text-xs sm:text-sm flex items-center gap-1">
                            <span>🔔</span>
                            <span class="truncate">Deadline Beasiswa Pemkab Magetan — Sisa 2 Hari!</span>
                        </p>
                        <p class="mt-0.5 text-[10px] sm:text-xs text-gray-500">Jangan lewatkan kesempatan beasiswa untuk siswa berprestasi.</p>
                    </div>
                    <a href="#" class="w-full sm:w-auto text-center shrink-0 rounded-xl bg-[#F28C28] px-4 py-2.5 text-xs font-bold text-white hover:bg-[#E07D1C] transition-all">Lihat Detail & Daftar →</a>
                </section>
            </div>

            <!-- Sidebar Kanan (Aside) -->
            <aside class="space-y-6 lg:col-span-4">
                <!-- Nalar Focus -->
                <section x-data="{ running: false, seconds: 1500, timer: null }" class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-2">
                        <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">⏱ Nalar Focus</h2>
                        <span class="text-xs font-bold text-[#0A52C4] cursor-pointer">Lihat Semua ›</span>
                    </div>
                    <div class="py-5 text-center">
                        <p class="text-5xl font-extrabold tracking-tight text-[#0F1F3D] sm:text-6xl" x-text="`${String(Math.floor(seconds / 60)).padStart(2, '0')}:${String(seconds % 60).padStart(2, '0')}`">25:00</p>
                        <p class="mt-1.5 text-xs font-semibold text-green-600">🌱 Fokus Belajar</p>
                    </div>
                    <div class="flex justify-center gap-2">
                        <button @click="running = !running; if (running) { timer = setInterval(() => { if (seconds > 0) seconds--; else { clearInterval(timer); running = false } }, 1000) } else clearInterval(timer)" class="rounded-xl bg-[#F28C28] px-5 py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-[#E07D1C]" x-text="running ? 'Pause' : '▶ Mulai'">▶ Mulai</button>
                        <button @click="seconds = 1500; running = false; clearInterval(timer)" class="rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-500 hover:bg-slate-50">Reset</button>
                    </div>
                    <label class="sr-only" for="focus-audio">Audio Fokus</label>
                    <select id="focus-audio" class="field-input mt-4 text-xs">
                        <option>🎵 Lo-Fi Study</option>
                        <option>🎵 Rain Sounds</option>
                        <option>🎵 Deep Focus</option>
                    </select>
                </section>

                <!-- NalarBot AI -->
                <section class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm">
                    <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">🤖 NalarBot AI Quick Trigger</h2>
                    <div class="mt-4 grid grid-cols-3 gap-2">
                        <a href="#" class="rounded-xl bg-green-50 p-2.5 text-center text-[10px] sm:text-[11px] font-bold text-gray-700 transition hover:bg-green-100">🎯<br>Minat<br>Bakat</a>
                        <a href="#" class="rounded-xl bg-blue-50 p-2.5 text-center text-[10px] sm:text-[11px] font-bold text-gray-700 transition hover:bg-blue-100">📊<br>Peluang<br>PTN</a>
                        <a href="#" class="rounded-xl bg-pink-50 p-2.5 text-center text-[10px] sm:text-[11px] font-bold text-gray-700 transition hover:bg-pink-100">💖<br>Curhat<br>AI</a>
                    </div>
                </section>

                <!-- Magetan Champions -->
                <section class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-2">
                        <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">🏆 Magetan Champions</h2>
                        <a href="#" class="text-xs font-bold text-[#0A52C4]">Peringkat ›</a>
                    </div>
                    <div class="mt-4 divide-y divide-gray-100">
                        @foreach ($leaderboard as $rank => $leader)
                        <div class="flex items-center gap-3 py-3 first:pt-0">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full {{ $rank === 0 ? 'bg-[#FFC000] text-white' : ($rank === 1 ? 'bg-gray-200 text-gray-700' : 'bg-[#D99159] text-white') }} text-xs font-extrabold">{{ $rank + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="truncate text-xs font-bold text-gray-800">{{ $leader['name'] }}</p>
                                <p class="truncate text-[10px] text-gray-400">{{ $leader['school'] }}</p>
                            </div>
                            <span class="text-xs font-extrabold text-[#F28C28] shrink-0">{{ number_format($leader['xp']) }} XP</span>
                        </div>
                        @endforeach
                    </div>
                    <a href="#" class="mt-4 block rounded-xl border border-[#0A52C4]/15 py-2.5 text-center text-xs font-bold text-[#0A52C4] hover:bg-[#0A52C4]/5">📊 Lihat Peringkat Lengkap</a>
                </section>
            </aside>
        </div>
    </div>
</x-layouts.siswa>