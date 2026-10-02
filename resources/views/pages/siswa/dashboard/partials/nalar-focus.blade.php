{{-- Nalar Focus: Pomodoro 25/5 + musik ambience. Logika & suara: public/js/nalar-focus.js --}}
<script src="{{ asset('js/nalar-focus.js') }}?v={{ filemtime(public_path('js/nalar-focus.js')) }}"></script>

<section x-data="nalarFocus()" id="nalar-focus" class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-5">
    <div class="flex items-center justify-between gap-2">
        <div class="flex min-w-0 items-center gap-2">
            <h2 class="font-extrabold text-gray-900 text-sm sm:text-base">Nalar Focus</h2>
            <span class="rounded-full bg-[#EEF4FF] px-2 py-0.5 text-[10px] font-bold text-[#0A52C4]" x-text="`Sesi ${session}`">Sesi 1</span>
        </div>
        <a href="{{ route('siswa.nalar-focus') }}" class="shrink-0 text-xs font-bold text-[#0A52C4]">Riwayat ›</a>
    </div>

    <div class="py-5 text-center">
        <p class="text-5xl font-extrabold tabular-nums tracking-tight sm:text-6xl"
            :class="mode === 'break' ? 'text-green-700' : 'text-[#0F1F3D]'"
            x-text="clock">25:00</p>
        <p class="mt-1.5 text-xs font-semibold"
            :class="status === 'done' ? 'text-[#C26A13]' : (mode === 'break' ? 'text-green-600' : 'text-[#0A52C4]')"
            x-text="statusText">Fokus belajar · 25 menit</p>
        <div class="mx-auto mt-3 h-1.5 w-full max-w-[220px] overflow-hidden rounded-full bg-gray-100" role="progressbar" aria-label="Progres sesi" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">
            <div class="h-full rounded-full transition-[width] duration-500"
                :class="mode === 'break' ? 'bg-green-500' : 'bg-[#F28C28]'"
                :style="`width: ${progress}%`" style="width: 0%"></div>
        </div>
    </div>

    <div class="flex gap-2">
        <button type="button" @click="primary()"
            class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-[#F28C28] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#E07D1C]">
            <x-icon name="play_arrow" class="h-5 w-5" x-show="primaryIcon === 'play'" />
            <x-icon name="pause" class="h-5 w-5" x-show="primaryIcon === 'pause'" x-cloak />
            <x-icon name="coffee" class="h-5 w-5" x-show="primaryIcon === 'coffee'" x-cloak />
            <span x-text="primaryLabel">Mulai Fokus</span>
        </button>
        <button type="button" @click="secondary()"
            class="inline-flex shrink-0 items-center justify-center gap-1 rounded-xl border border-gray-200 px-3.5 py-2.5 text-xs font-bold text-gray-600 transition hover:bg-slate-50">
            <x-icon name="restart_alt" class="h-4 w-4" x-show="!canSkip" />
            <x-icon name="skip_next" class="h-4 w-4" x-show="canSkip" x-cloak />
            <span x-text="canSkip ? 'Lewati' : 'Reset'">Reset</span>
        </button>
    </div>

    <div x-show="audioSupported" class="mt-4 rounded-xl bg-[#F4F7FA] p-3">
        <div class="flex items-center gap-2">
            <label for="focus-audio" class="sr-only">Musik fokus</label>
            <select id="focus-audio" x-model="track" @change="changeTrack()"
                class="min-w-0 flex-1 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#0A52C4]/20">
                <option value="lofi">Lo-fi Study</option>
                <option value="rain">Suara Hujan</option>
                <option value="deep">Deep Focus (Brown Noise)</option>
            </select>
            <button type="button" @click="toggleMusic()" :aria-label="playing ? 'Jeda musik' : 'Putar musik'" :aria-pressed="playing.toString()"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#0A52C4] text-white transition hover:bg-[#0843a1]">
                <x-icon name="music_note" class="h-5 w-5" x-show="!playing" />
                <x-icon name="pause" class="h-5 w-5" x-show="playing" x-cloak />
            </button>
        </div>
        <div class="mt-2.5 flex items-center gap-2 text-gray-400">
            <x-icon name="volume_off" class="h-4 w-4" />
            <input type="range" min="0" max="100" step="5" x-model.number="volume" @input="changeVolume()" aria-label="Volume musik"
                class="h-1.5 w-full cursor-pointer accent-[#0A52C4]">
            <x-icon name="volume_up" class="h-4 w-4" />
        </div>
        <p class="mt-2 text-[11px] leading-relaxed text-gray-500">Musik berputar saat fokus dan berhenti otomatis ketika 25 menit selesai, tanda waktunya istirahat.</p>
    </div>
    <p x-show="!audioSupported" x-cloak class="mt-4 text-[11px] text-gray-500">Browser ini belum mendukung musik fokus. Timer tetap bisa dipakai.</p>
</section>
