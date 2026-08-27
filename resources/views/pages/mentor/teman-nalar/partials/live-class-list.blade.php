<div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-extrabold text-gray-800 text-sm">Daftar Live Class Anda</h2>
    </div>
    
    <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($liveClasses as $class)
        <div class="w-full rounded-xl border border-gray-200 p-4 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <span class="rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-600 flex items-center gap-1">
                    <span class="h-1.5 w-1.5 rounded-full bg-red-500 animate-pulse"></span> Live Class
                </span>
                <span class="text-xs text-gray-500 font-medium">{{ \Carbon\Carbon::parse($class->schedule_time)->translatedFormat('d M Y') }}</span>
            </div>
            
            <h3 class="font-bold text-gray-900 text-base mb-1">{{ $class->title }}</h3>
            <p class="text-xs text-gray-500 mb-4">{{ \Carbon\Carbon::parse($class->schedule_time)->translatedFormat('H:i') }} WIB</p>
            
            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                <div class="flex justify-between items-center text-xs mb-1.5">
                    <span class="font-bold text-gray-700">Kuota Terisi</span>
                    <span class="font-medium text-gray-500">{{ $class->registered_count }}/{{ $class->quota }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-[#0A52C4] h-1.5 rounded-full" style="width: {{ $class->quota > 0 ? ($class->registered_count / $class->quota) * 100 : 0 }}%"></div>
                </div>
            </div>
            
            <a href="{{ $class->meet_link }}" target="_blank" class="w-full block text-center rounded-lg border border-[#0A52C4] py-2 text-sm font-bold text-[#0A52C4] hover:bg-[#0A52C4] hover:text-white transition">
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
