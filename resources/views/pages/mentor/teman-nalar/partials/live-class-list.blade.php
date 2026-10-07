<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-gray-100 px-5 py-5">
        <h2 class="text-sm font-extrabold leading-6 text-gray-800">Daftar Belajar Bersama Anda</h2>
    </div>

    <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse($liveClasses as $class)
        @php($isPast = \Carbon\Carbon::parse($class->schedule_time)->isPast())
        <div class="w-full rounded-xl border border-gray-200 p-5 transition {{ $isPast ? 'bg-gray-50' : 'hover:shadow-md' }}">
            <div class="mb-4 flex items-center justify-between gap-3">
                @if($isPast)
                <span class="flex items-center gap-1 rounded-full bg-gray-200 px-2 py-1 text-[10px] font-bold text-gray-600">
                    <x-icon name="event_busy" class="h-3.5 w-3.5" /> Sudah lewat
                </span>
                @else
                <span class="flex items-center gap-1 rounded-full bg-blue-100 px-2 py-1 text-[10px] font-bold text-[#0A52C4]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#0A52C4] animate-pulse"></span> Belajar Bersama
                </span>
                @endif
                <span class="shrink-0 text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($class->schedule_time)->translatedFormat('d M Y') }}</span>
            </div>

            <h3 class="text-base font-bold leading-6 {{ $isPast ? 'text-gray-500' : 'text-gray-900' }}">{{ $class->title }}</h3>
            <p class="mt-1 flex items-center gap-1 text-xs leading-5 text-gray-500"><x-icon name="schedule" class="h-3.5 w-3.5" /> {{ \Carbon\Carbon::parse($class->schedule_time)->translatedFormat('H:i') }} WIB</p>

            <div class="mt-4 flex items-center gap-1.5 rounded-lg bg-primary/5 p-3 text-xs font-bold leading-5 text-primary">
                <x-icon name="groups" class="h-4 w-4" /> Terbuka untuk semua siswa tanpa kuota.
            </div>

            @if($isPast)
            <p class="mt-4 rounded-lg border border-dashed border-gray-200 py-2.5 text-center text-xs font-semibold text-gray-400">Sesi sudah selesai</p>
            @else
            <a href="{{ $class->meet_link }}" target="_blank" rel="noopener" class="mt-4 flex w-full items-center justify-center gap-1.5 rounded-lg border border-[#0A52C4] py-2.5 text-center text-sm font-bold text-[#0A52C4] transition hover:bg-[#0A52C4] hover:text-white">
                <x-icon name="videocam" class="h-4 w-4" /> Masuk Meeting
            </a>
            @endif
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-sm text-gray-400 font-medium border border-dashed border-gray-200 rounded-xl">
            Anda belum membuat jadwal Belajar Bersama apa pun.
        </div>
        @endforelse
    </div>
</div>