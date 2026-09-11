<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-gray-100 px-5 py-5">
        <h2 class="text-sm font-extrabold leading-6 text-gray-800">Daftar Belajar Bersama Anda</h2>
    </div>

    <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse($liveClasses as $class)
        <div class="w-full rounded-xl border border-gray-200 p-5 transition hover:shadow-md">
            <div class="mb-4 flex items-center justify-between gap-3">
                <span class="flex items-center gap-1 rounded-full bg-blue-100 px-2 py-1 text-[10px] font-bold text-[#0A52C4]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#0A52C4] animate-pulse"></span> Belajar Bersama
                </span>
                <span class="shrink-0 text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($class->schedule_time)->translatedFormat('d M Y') }}</span>
            </div>

            <h3 class="text-base font-bold leading-6 text-gray-900">{{ $class->title }}</h3>
            <p class="mt-1 text-xs leading-5 text-gray-500">{{ \Carbon\Carbon::parse($class->schedule_time)->translatedFormat('H:i') }} WIB</p>

            <div class="mt-4 rounded-lg bg-emerald-50 p-3 text-xs font-bold leading-5 text-emerald-700">Terbuka untuk semua siswa tanpa kuota.</div>

            <a href="{{ $class->meet_link }}" target="_blank" class="mt-4 block w-full rounded-lg border border-[#0A52C4] py-2.5 text-center text-sm font-bold text-[#0A52C4] transition hover:bg-[#0A52C4] hover:text-white">
                Masuk Meeting
            </a>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-sm text-gray-400 font-medium border border-dashed border-gray-200 rounded-xl">
            Anda belum membuat jadwal Live Class apa pun.
        </div>
        @endforelse
    </div>
</div>