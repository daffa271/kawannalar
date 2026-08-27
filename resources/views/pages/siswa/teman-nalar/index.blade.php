<x-layouts.app>
<x-slot name="title">Teman Nalar — KawanNalar</x-slot>

<div x-data="{
    tab: '1on1',
    show: false,
    mentor: null,
    topic: 'Rasionalisasi SNBP',
    topics: ['Rasionalisasi SNBP','Strategi UTBK','Curhat Jurusan'],
    selectedSlot: null,
    message: '',
    openModal(m) { this.mentor = m; this.topic = 'Rasionalisasi SNBP'; this.selectedSlot = null; this.message = ''; this.show = true; },
    formatDate(d) {
        const days=['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
        const months=['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        const dt=new Date(d); return days[dt.getDay()]+', '+dt.getDate()+' '+months[dt.getMonth()];
    }
}" class="space-y-6">

    {{-- Hero Banner --}}
    <div class="flex items-center gap-4 rounded-2xl bg-gradient-to-r from-[#0A52C4] to-[#1565D8] p-5 md:p-6 shadow-lg">
        <div class="hidden sm:flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-3xl">🤝</div>
        <div>
            <h1 class="text-lg md:text-2xl font-extrabold text-white leading-snug">Teman Nalar: Bimbingan 1-on-1 &amp; Kelas Online</h1>
            <p class="mt-1 text-sm text-blue-100">Belajar langsung dari mentor alumni Magetan di PTN favorit (PENS, ITS, UNAIR, UB, UGM).</p>
        </div>
    </div>

    {{-- Alert Telegram --}}
    <div class="flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3">
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#0A52C4]/10">
            <svg class="w-4 h-4 text-[#0A52C4]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"/></svg>
        </div>
        <p class="text-sm text-blue-800"><span class="font-bold">Notifikasi Instan:</span> Tautan Google Meet dikirim otomatis via Bot Telegram ke HP kamu.</p>
    </div>

    @if(session('success'))
    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-bold text-green-800 flex items-center gap-2">
        ✅ {{ session('success') }}
    </div>
    @endif

    {{-- Toggle Tabs --}}
    <div class="flex rounded-xl border border-gray-200 bg-white p-1 shadow-sm">
        <button @click="tab='1on1'" :class="tab==='1on1' ? 'bg-[#0A52C4] text-white shadow-md' : 'text-gray-500 hover:text-gray-800'"
            class="flex flex-1 items-center justify-center gap-2 rounded-lg py-2.5 text-sm font-bold transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Bimbingan Privat 1-on-1
        </button>
        <button @click="tab='live'" :class="tab==='live' ? 'bg-[#0A52C4] text-white shadow-md' : 'text-gray-500 hover:text-gray-800'"
            class="flex flex-1 items-center justify-center gap-2 rounded-lg py-2.5 text-sm font-bold transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Kelas Online Kelompok
        </button>
        <button @click="tab='my-bookings'" :class="tab==='my-bookings' ? 'bg-[#0A52C4] text-white shadow-md' : 'text-gray-500 hover:text-gray-800'"
            class="flex flex-1 items-center justify-center gap-2 rounded-lg py-2.5 text-sm font-bold transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Booking Saya
        </button>
    </div>

    {{-- ── Tab 1-on-1 ── --}}
    <div x-show="tab==='1on1'" class="space-y-5">
        {{-- Filter Bar --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Cari mentor, jurusan, atau topik..." class="w-full rounded-xl border border-gray-200 bg-white pl-9 pr-4 py-2.5 text-sm focus:border-[#0A52C4] focus:outline-none focus:ring-2 focus:ring-[#0A52C4]/20">
            </div>
            <select class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 focus:border-[#0A52C4] focus:outline-none">
                <option>Target PTN: Semua</option><option>PENS</option><option>ITS</option><option>UGM</option><option>UNAIR</option><option>UB</option>
            </select>
            <select class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 focus:border-[#0A52C4] focus:outline-none">
                <option>Mapel / Topik: Semua</option><option>Matematika</option><option>Fisika</option><option>SNBP</option><option>UTBK</option>
            </select>
        </div>

        {{-- Mentor Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($mentors as $mentor)
            @php
                $slotCount = count($mentor->available_slots ?? []);
                $profile   = $mentor->mentorProfile;
                $slotsJson = json_encode(collect($mentor->available_slots)->map(fn($s) => ['id'=>$s->id,'date'=>$s->date,'start_time'=>$s->start_time,'end_time'=>$s->end_time]));
                $mentorJson = json_encode(['id'=>$mentor->id,'name'=>$mentor->name,'prodi'=>$profile->major??'','ptn'=>$profile->university??'','slots'=>collect($mentor->available_slots)->map(fn($s)=>['id'=>$s->id,'date'=>$s->date,'start_time'=>$s->start_time,'end_time'=>$s->end_time])]);
            @endphp
            <div class="w-full flex flex-col rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="p-5 flex-1 space-y-4">
                    {{-- Avatar + Name + Rating --}}
                    <div class="flex items-start gap-3">
                        <div class="relative shrink-0">
                            <div class="h-14 w-14 rounded-full bg-gradient-to-br from-[#0A52C4] to-[#1565D8] flex items-center justify-center text-white text-xl font-black shadow-md">
                                {{ strtoupper(substr($mentor->name,0,1)) }}
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 flex h-5 w-5 items-center justify-center rounded-full border-2 border-white bg-[#F28C28] shadow">
                                <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-extrabold text-gray-900 truncate">{{ $mentor->name }}</p>
                            <div class="flex items-center gap-1 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs font-bold text-gray-800">4.9</span>
                                <span class="text-xs text-gray-400">({{ rand(10,35) }} Sesi)</span>
                            </div>
                        </div>
                    </div>

                    {{-- Prodi & Asal --}}
                    <div class="space-y-1.5">
                        <span class="inline-block rounded-md bg-blue-50 border border-blue-100 px-2.5 py-1 text-[11px] font-bold text-[#0A52C4]">
                            {{ $profile->major ?? 'Jurusan' }} — {{ $profile->university ?? 'PTN' }}
                        </span>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="font-medium">Alumni {{ $profile->school ?? 'SMA Magetan' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Card Footer --}}
                <div class="border-t border-gray-100 bg-gray-50 px-5 py-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Slot Minggu Ini</span>
                        @if($slotCount > 0)
                        <span class="flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-[11px] font-bold text-green-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span> {{ $slotCount }} Slot Minggu Ini
                        </span>
                        @else
                        <span class="rounded-full bg-gray-200 px-2.5 py-0.5 text-[11px] font-bold text-gray-500">Penuh</span>
                        @endif
                    </div>
                    @if(auth()->user()->is_suspended)
                        <button
                            disabled
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-gray-200 py-2.5 text-sm font-bold text-gray-400 cursor-not-allowed">
                            🚀 Booking Bimbingan
                        </button>
                    @else
                        <button
                            @click="openModal({{ $mentorJson }})"
                            @disabled($slotCount === 0)
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#FF6B00] py-2.5 text-sm font-bold text-white shadow-md shadow-[#FF6B00]/25 transition-all hover:bg-[#E56000] hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                            🚀 Booking Bimbingan
                        </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full rounded-2xl border border-dashed border-gray-300 py-16 text-center bg-white">
                <p class="text-gray-400 font-medium">Belum ada mentor aktif saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ── Tab Live Class ── --}}
    <div x-show="tab==='live'" style="display:none" class="space-y-4">
        <h2 class="font-extrabold text-gray-800">Kelas Online Kelompok (Live Class)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($liveClasses as $class)
            @php
                $pct = $class->quota > 0 ? round(($class->registered_count / $class->quota) * 100) : 0;
                $mp  = $class->mentor->mentorProfile;
                $firstName = explode(' ', $class->mentor->name)[0];
            @endphp
            <div class="w-full flex flex-col rounded-2xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-lg transition-shadow duration-300 gap-4">
                <div class="space-y-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-red-100 bg-red-50 px-2.5 py-0.5 text-[10px] font-extrabold text-red-600">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-500 animate-pulse"></span> LIVE CLASS
                    </span>
                    <h3 class="font-bold text-gray-900 text-[15px] leading-snug">{{ $class->title }}</h3>
                    <div class="space-y-1 text-xs text-gray-500">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Oleh: Kak {{ $firstName }} ({{ $mp->university ?? 'PTN' }})</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ \Carbon\Carbon::parse($class->schedule_time)->translatedFormat('D, d M Y • H:i') }} WIB</span>
                        </div>
                        <div class="flex items-center gap-1.5 font-semibold text-gray-700">
                            <svg class="w-3.5 h-3.5 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Sisa {{ $class->quota - $class->registered_count }} Kuota ({{ $class->registered_count }}/{{ $class->quota }} Terdaftar)
                        </div>
                    </div>
                </div>
                <div>
                    <div class="mb-3 h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full rounded-full bg-[#F28C28] transition-all" style="width:{{ $pct }}%"></div>
                    </div>
                    @if(auth()->user()->is_suspended)
                        <button disabled class="w-full flex items-center justify-center gap-2 rounded-xl border-2 border-gray-200 bg-gray-50 py-2.5 text-sm font-bold text-gray-400 cursor-not-allowed">
                            🚀 Ikuti Kelas (Ditangguhkan)
                        </button>
                    @else
                        <a href="{{ $class->meet_link }}" target="_blank" class="w-full flex items-center justify-center gap-2 rounded-xl border-2 border-[#FF6B00] bg-white py-2.5 text-sm font-bold text-[#FF6B00] transition hover:bg-[#FF6B00] hover:text-white">
                            🚀 Ikuti Kelas Gratis
                        </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full rounded-2xl border border-dashed border-gray-300 py-16 text-center bg-white">
                <p class="text-gray-400 font-medium">Belum ada jadwal Live Class.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ── Tab Booking Saya ── --}}
    <div x-show="tab==='my-bookings'" style="display:none" class="space-y-4">
        <h2 class="font-extrabold text-gray-800">Status Booking & Jadwal Saya</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($myBookings as $booking)
            <div class="w-full flex flex-col rounded-2xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition-shadow duration-300 gap-4">
                <div class="flex items-center justify-between">
                    @if($booking->status === 'pending')
                        <span class="rounded bg-yellow-100 px-2 py-0.5 text-[10px] font-bold text-yellow-700">Menunggu Persetujuan</span>
                    @elseif($booking->status === 'approved')
                        <span class="rounded bg-green-100 px-2 py-0.5 text-[10px] font-bold text-green-700">Disetujui</span>
                    @else
                        <span class="rounded bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-700">Ditolak</span>
                    @endif
                    <span class="text-[10px] font-bold text-gray-400">{{ $booking->created_at->diffForHumans() }}</span>
                </div>
                
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Bimbingan dgn Kak {{ $booking->mentor->name }}</h3>
                    <p class="text-xs text-gray-500 font-semibold mt-1">Topik: {{ $booking->topic }}</p>
                </div>
                
                <div class="rounded-lg bg-gray-50 p-3 flex flex-col gap-1.5 text-xs">
                    <div class="flex items-center gap-2 text-gray-700">
                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="font-bold">{{ \Carbon\Carbon::parse($booking->slot->date)->translatedFormat('l, d M Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-700">
                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="font-bold">{{ substr($booking->slot->start_time, 0, 5) }} - {{ substr($booking->slot->end_time, 0, 5) }} WIB</span>
                    </div>
                </div>
                
                <div class="mt-auto">
                    @if($booking->status === 'approved' && $booking->slot->meeting_link)
                    <a href="{{ $booking->slot->meeting_link }}" target="_blank" class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#0A52C4] py-2.5 text-sm font-bold text-white shadow hover:bg-[#0843a1] transition">
                        🎥 Masuk Meeting
                    </a>
                    @elseif($booking->status === 'pending')
                    <button disabled class="w-full rounded-xl bg-gray-100 text-gray-400 py-2.5 text-sm font-bold cursor-not-allowed">
                        Menunggu Mentor...
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full rounded-2xl border border-dashed border-gray-300 py-16 text-center bg-white">
                <p class="text-gray-400 font-medium">Kamu belum memiliki riwayat booking.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ═══════════ BOOKING MODAL ═══════════ --}}
    <div x-show="show"
        x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        style="display:none">

        <div @click.outside="show=false"
            x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl overflow-y-auto max-h-[90vh]">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <div>
                    <h2 class="text-base font-extrabold text-gray-900">Booking Bimbingan 1-on-1</h2>
                    <p class="text-xs text-gray-500 mt-0.5" x-text="'Bersama Kak ' + (mentor?.name ?? '')"></p>
                </div>
                <button @click="show=false" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('siswa.teman-nalar.booking.store') }}" method="POST" class="px-6 py-5 space-y-6">
                @csrf
                <input type="hidden" name="mentor_id" :value="mentor?.id">
                <input type="hidden" name="topic" :value="topic">
                <input type="hidden" name="mentor_slot_id" :value="selectedSlot">
                <input type="hidden" name="message" :value="message">

                {{-- Step 1: Topik --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#0A52C4] text-[10px] font-black text-white">1</span>
                        <p class="text-sm font-bold text-gray-800">Pilih Topik Bimbingan</p>
                    </div>
                    <div class="space-y-2">
                        <template x-for="t in topics" :key="t">
                            <label @click="topic=t" class="flex cursor-pointer items-center gap-3 rounded-xl border p-3.5 transition-all"
                                :class="topic===t ? 'border-[#0A52C4] bg-blue-50 ring-1 ring-[#0A52C4]' : 'border-gray-200 hover:border-gray-300 bg-white'">
                                <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full border-2 transition"
                                    :class="topic===t ? 'border-[#0A52C4]' : 'border-gray-300'">
                                    <span x-show="topic===t" class="h-2 w-2 rounded-full bg-[#0A52C4]"></span>
                                </span>
                                <span class="text-sm font-semibold text-gray-800" x-text="t"></span>
                            </label>
                        </template>
                    </div>
                </div>

                {{-- Step 2: Pilih Jadwal --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#0A52C4] text-[10px] font-black text-white">2</span>
                        <p class="text-sm font-bold text-gray-800">Pilih Jadwal</p>
                    </div>
                    <div class="flex gap-2 overflow-x-auto pb-1 scroll-smooth">
                        <template x-for="slot in (mentor?.slots ?? [])" :key="slot.id">
                            <button type="button" @click="selectedSlot=slot.id"
                                class="shrink-0 rounded-xl border px-4 py-3 text-center text-xs font-bold transition-all min-w-[110px]"
                                :class="selectedSlot===slot.id ? 'border-[#0A52C4] bg-[#0A52C4] text-white shadow-lg shadow-[#0A52C4]/25' : 'border-gray-200 bg-white text-gray-700 hover:border-[#0A52C4]/40 hover:bg-blue-50'">
                                <div class="font-bold" x-text="formatDate(slot.date)"></div>
                                <div class="mt-1 font-extrabold text-sm" x-text="slot.start_time.substring(0,5)+' WIB'"></div>
                            </button>
                        </template>
                        <p x-show="!(mentor?.slots?.length)" class="text-xs italic text-red-500 py-3">Tidak ada slot tersedia.</p>
                    </div>
                </div>

                {{-- Step 3: Pesan Opsional --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gray-300 text-[10px] font-black text-white">3</span>
                        <p class="text-sm font-bold text-gray-800">Pesan untuk Mentor <span class="font-normal text-gray-400">(Opsional)</span></p>
                    </div>
                    <textarea x-model="message" maxlength="200" rows="3"
                        placeholder="Tulis pesan singkat untuk mentor (misal: topik yang ingin dibahas)..."
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-[#0A52C4] focus:outline-none focus:ring-2 focus:ring-[#0A52C4]/20 resize-none"></textarea>
                    <div class="mt-1 flex justify-end">
                        <span class="text-xs text-gray-400" x-text="message.length + '/200'"></span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex gap-3 pt-1 border-t border-gray-100">
                    <button type="button" @click="show=false"
                        class="flex-1 rounded-xl border border-gray-300 bg-white py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        :disabled="!selectedSlot"
                        :class="!selectedSlot ? 'opacity-50 cursor-not-allowed bg-[#FF6B00]' : 'bg-[#FF6B00] hover:bg-[#E56000]'"
                        class="flex-1 flex items-center justify-center gap-2 rounded-xl py-2.5 text-sm font-bold text-white shadow-md shadow-[#FF6B00]/30 transition">
                        🚀 Konfirmasi Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</x-layouts.app>
