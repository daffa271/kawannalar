<x-layouts.siswa title="Dashboard Siswa — KawanNalar">
    @php
    $profile = $student->studentProfile;
    $firstName = explode(' ', trim($student->name))[0];
    @endphp
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-8">
            <div class="space-y-6 lg:col-span-8">
                <section class="relative overflow-hidden rounded-2xl bg-[#0A52C4] p-8 text-white shadow-lg sm:p-10">
                    <div class="absolute -right-16 -top-24 h-72 w-72 rounded-full border-[40px] border-white/5"></div>
                    <div class="relative z-10 max-w-2xl">
                        <p class="text-sm font-semibold text-blue-100">{{ $profile?->target_university ? 'Target: '.$profile->target_university : 'Perjalanan belajarmu dimulai di sini' }}</p>
                        <h1 class="mt-2 text-2xl font-extrabold leading-tight sm:text-3xl">Selamat Datang Kembali, {{ $firstName }}! 👋</h1>
                        <p class="mt-3 text-sm leading-relaxed text-blue-100">Tetap semangat, 120 Hari lagi menuju UTBK SNBT 2026!<br>Target Kampus: <strong class="text-white">{{ $profile?->target_major ?? 'Tentukan target jurusan impianmu' }}{{ $profile?->target_university ? ' · '.$profile->target_university : '' }}</strong></p>
                        <div class="mt-6 grid max-w-2xl grid-cols-3 gap-2 sm:gap-3">
                            <div class="rounded-xl border border-white/10 bg-white/10 px-3 py-2.5">
                                <p class="text-lg">🔥</p>
                                <p class="mt-1 text-[11px] text-blue-100">Streak</p>
                                <p class="text-sm font-extrabold">{{ $streakDays }} Hari</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/10 px-3 py-2.5">
                                <p class="text-lg">🏆</p>
                                <p class="mt-1 text-[11px] text-blue-100">Poin</p>
                                <p class="text-sm font-extrabold">{{ number_format($xp) }} XP</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/10 px-3 py-2.5">
                                <p class="text-lg">⏱️</p>
                                <p class="mt-1 text-[11px] text-blue-100">Waktu Fokus</p>
                                <p class="text-sm font-extrabold">{{ $focusHours }} Jam</p>
                            </div>
                        </div>
                    </div>
                    <div class="absolute bottom-0 right-8 hidden text-[90px] leading-none opacity-30 sm:block">🏫</div>
                </section>
                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-extrabold text-gray-900">Sesi Mentoring Mendatang</h2>
                            <p class="mt-1 text-xs text-gray-400">Teman Nalar membantu langkahmu lebih terarah.</p>
                        </div><a href="#" class="text-xs font-bold text-[#0A52C4]">Lihat Semua ›</a>
                    </div>@if ($upcomingMentoring)<div class="mt-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#0A52C4] text-lg font-bold text-white">M</div>
                            <div>
                                <p class="font-bold text-gray-900">Mentor Alifa <span class="text-[#0A52C4]">●</span></p>
                                <p class="text-xs text-gray-500">Mahasiswa PENS · D4 Teknik Informatika</p>
                                <p class="mt-2 text-xs text-gray-500">Hari ini, 19:30 WIB <span class="ml-2 rounded-md bg-[#FFF3E5] px-2 py-1 font-semibold text-[#C26A13]">1-on-1 Mentoring</span></p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2"><a href="#" class="rounded-xl bg-[#F28C28] px-4 py-2.5 text-xs font-bold text-white hover:bg-[#E07D1C]">🎥 Masuk Google Meet</a><a href="#" class="rounded-xl border border-[#0A52C4]/20 px-4 py-2.5 text-xs font-bold text-[#0A52C4]">Reschedule</a></div>
                    </div>@else<div class="mt-5 rounded-xl border border-dashed border-gray-200 px-4 py-6 text-center">
                        <p class="text-sm font-bold text-gray-700">Belum ada sesi terjadwal</p>
                        <p class="mt-1 text-xs text-gray-400">Booking bimbingan dengan mentor pilihanmu segera hadir.</p>
                    </div>@endif
                </section>
                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-extrabold text-gray-900">⚡ Nalar Kilat</h2>
                            <p class="mt-1 text-xs text-gray-400">Latihan singkat setiap hari bikin kamu makin siap.</p>
                        </div><a href="#" class="text-xs font-bold text-[#0A52C4]">Lihat Semua ›</a>
                    </div>
                    <div class="mt-5 grid gap-3 sm:grid-cols-3"><a href="#" class="rounded-xl border border-green-100 bg-green-50 p-4 hover:border-green-300">
                            <p class="text-xl">⚡</p>
                            <p class="mt-2 text-sm font-extrabold text-gray-800">5 Soal</p>
                            <p class="text-xs text-gray-500">± 5 Menit</p>
                        </a><a href="#" class="rounded-xl border border-[#FFC000]/30 bg-[#FFF9E8] p-4 hover:border-[#FFC000]">
                            <p class="text-xl">🔥</p>
                            <p class="mt-2 text-sm font-extrabold text-gray-800">10 Soal</p>
                            <p class="text-xs text-gray-500">± 10 Menit</p>
                        </a><a href="#" class="rounded-xl border border-blue-100 bg-blue-50 p-4 hover:border-blue-300">
                            <p class="text-xl">💪</p>
                            <p class="mt-2 text-sm font-extrabold text-gray-800">15 Soal</p>
                            <p class="text-xs text-gray-500">± 15 Menit</p>
                        </a></div>
                </section>
                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-extrabold text-gray-900">📚 Ruang Nalar Quick Feed</h2>
                            <p class="mt-1 text-xs text-gray-400">Materi populer untuk menemani belajarmu.</p>
                        </div><a href="{{ route('siswa.ruang-nalar.index') }}" class="text-xs font-bold text-[#0A52C4]">Lihat Semua ›</a>
                    </div>
                    <div class="mt-5 grid gap-3 sm:grid-cols-2">@forelse ($popularModules as $module)<a href="{{ route('siswa.ruang-nalar.download', $module) }}" class="flex gap-3 rounded-xl border border-gray-100 p-3 transition hover:border-[#0A52C4]/20 hover:bg-[#F4F7FA]">
                            <div class="flex h-16 w-12 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#0A52C4] to-[#16418C] text-xl text-white">📘</div>
                            <div class="min-w-0">
                                <p class="line-clamp-2 text-sm font-bold text-gray-800">{{ $module->title }}</p>
                                <p class="mt-1 text-xs text-gray-400">oleh {{ $module->uploader?->name ?? 'Mentor KawanNalar' }}</p>
                                <p class="mt-1 text-xs text-gray-500">⇩ {{ number_format($module->download_count) }}</p>
                            </div>
                        </a>@empty<p class="col-span-2 rounded-xl bg-[#F4F7FA] px-4 py-6 text-center text-sm text-gray-500">Belum ada modul populer. Materi baru akan tampil di sini.</p>@endforelse</div>
                </section>
                <section class="flex flex-col items-start justify-between gap-4 rounded-2xl border border-[#F28C28]/30 bg-[#FFF8E8] p-5 sm:flex-row sm:items-center sm:p-6">
                    <div>
                        <p class="font-extrabold text-gray-900">🔔 Deadline Beasiswa Pemkab Magetan — Sisa 2 Hari Lagi!</p>
                        <p class="mt-1 text-xs text-gray-500">Jangan lewatkan kesempatan beasiswa untuk siswa berprestasi.</p>
                    </div><a href="#" class="shrink-0 rounded-xl bg-[#F28C28] px-4 py-2.5 text-xs font-bold text-white hover:bg-[#E07D1C]">Lihat Detail & Daftar →</a>
                </section>
            </div>
            <aside class="space-y-6 lg:col-span-4">
                <section x-data="{ running: false, seconds: 1500, timer: null }" class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h2 class="font-extrabold text-gray-900">⏱ Nalar Focus</h2><span class="text-xs font-bold text-[#0A52C4]">Lihat Semua ›</span>
                    </div>
                    <div class="py-5 text-center">
                        <p class="text-6xl font-extrabold tracking-tight text-[#0F1F3D] md:text-7xl" x-text="`${String(Math.floor(seconds / 60)).padStart(2, '0')}:${String(seconds % 60).padStart(2, '0')}`">25:00</p>
                        <p class="mt-2 text-xs font-semibold text-green-600">🌱 Fokus Belajar</p>
                    </div>
                    <div class="flex justify-center gap-2"><button @click="running = !running; if (running) { timer = setInterval(() => { if (seconds > 0) seconds--; else { clearInterval(timer); running = false } }, 1000) } else clearInterval(timer)" class="rounded-xl bg-[#F28C28] px-5 py-2.5 text-sm font-bold text-white" x-text="running ? 'Pause' : '▶ Mulai'">▶ Mulai</button><button @click="seconds = 1500; running = false; clearInterval(timer)" class="rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-500">Reset</button></div><select class="field-input mt-4">
                        <option>🎵 Lo-Fi Study</option>
                        <option>🎵 Rain Sounds</option>
                        <option>🎵 Deep Focus</option>
                    </select>
                </section>
                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <h2 class="font-extrabold text-gray-900">🤖 NalarBot AI Quick Trigger</h2>
                    <div class="mt-4 grid grid-cols-3 gap-2"><a href="#" class="rounded-xl bg-green-50 p-3 text-center text-[11px] font-bold text-gray-700">🎯<br>Minat<br>Bakat</a><a href="#" class="rounded-xl bg-blue-50 p-3 text-center text-[11px] font-bold text-gray-700">📊<br>Peluang<br>PTN</a><a href="#" class="rounded-xl bg-pink-50 p-3 text-center text-[11px] font-bold text-gray-700">💖<br>Curhat<br>AI</a></div>
                </section>
                <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h2 class="font-extrabold text-gray-900">🏆 Magetan Champions</h2><a href="#" class="text-xs font-bold text-[#0A52C4]">Peringkat ›</a>
                    </div>
                    <div class="mt-4 divide-y divide-gray-100">@foreach ($leaderboard as $rank => $leader)<div class="flex items-center gap-3 py-3 first:pt-0"><span class="flex h-7 w-7 items-center justify-center rounded-full {{ $rank === 0 ? 'bg-[#FFC000] text-white' : ($rank === 1 ? 'bg-gray-200 text-gray-700' : 'bg-[#D99159] text-white') }} text-xs font-extrabold">{{ $rank + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="truncate text-xs font-bold text-gray-800">{{ $leader['name'] }}</p>
                                <p class="truncate text-[10px] text-gray-400">{{ $leader['school'] }}</p>
                            </div><span class="text-xs font-extrabold text-[#F28C28]">{{ number_format($leader['xp']) }} XP</span>
                        </div>@endforeach</div><a href="#" class="mt-3 block rounded-xl border border-[#0A52C4]/15 py-2.5 text-center text-xs font-bold text-[#0A52C4]">📊 Lihat Peringkat Lengkap</a>
                </section>
            </aside>
        </div>
    </div>
</x-layouts.siswa>