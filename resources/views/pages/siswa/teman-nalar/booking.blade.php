<x-layouts.app>
    <x-slot name="title">Booking Bimbingan 1-on-1 - KawanNalar</x-slot>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div x-data="{ topic: @js(old('topic', 'Rasionalisasi SNBP')) }" class="mx-auto max-w-3xl space-y-6">
        <a href="{{ route('siswa.teman-nalar.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#0A52C4] hover:underline">
            &larr; Kembali ke Teman Nalar
        </a>

        <section class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 bg-gradient-to-r from-[#0A52C4] to-[#1565D8] px-5 py-6 text-white sm:px-8">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-100">Teman Nalar</p>
                <h1 class="mt-1 text-xl font-extrabold sm:text-2xl">Booking Bimbingan 1-on-1</h1>
                <p class="mt-1 text-sm text-blue-100">Pilih sesi yang tersedia dan tunggu konfirmasi mentor.</p>
            </div>

            <div class="space-y-6 p-5 sm:p-8">
                <div class="grid gap-4 rounded-xl bg-gray-50 p-4 sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Mentor</p>
                        <p class="mt-1 font-extrabold text-gray-900">{{ $mentor->name }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">PTN / Kampus</p>
                        <p class="mt-1 font-bold text-gray-800">{{ $mentor->mentorProfile?->university ?? 'PTN belum diisi' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Prodi</p>
                        <p class="mt-1 font-semibold text-gray-700">{{ $mentor->mentorProfile?->major ?? 'Prodi belum diisi' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Alumni</p>
                        <p class="mt-1 font-semibold text-gray-700">{{ $mentor->mentorProfile?->high_school ?? 'SMA belum diisi' }}</p>
                    </div>
                </div>

                @if($slots->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                    <p class="font-bold text-gray-700">Tidak ada sesi yang tersedia untuk mentor ini.</p>
                    <a href="{{ route('siswa.teman-nalar.index') }}" class="mt-3 inline-flex text-sm font-bold text-[#0A52C4] hover:underline">Pilih mentor lain</a>
                </div>
                @else
                <form method="POST" action="{{ route('siswa.teman-nalar.booking.store') }}" class="space-y-6">
                    @csrf

                    <fieldset>
                        <legend class="mb-3 text-sm font-extrabold text-gray-800">Pilih Jadwal</legend>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach($slots as $slot)
                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 bg-white p-4 transition hover:border-[#0A52C4] has-[:checked]:border-[#0A52C4] has-[:checked]:bg-blue-50">
                                <input type="radio" name="mentor_slot_id" value="{{ $slot->id }}" @checked(old('mentor_slot_id')==$slot->id) required class="mt-1 text-[#0A52C4] focus:ring-[#0A52C4]">
                                <span>
                                    <span class="block font-bold text-gray-800">{{ \Carbon\Carbon::parse($slot->date)->translatedFormat('d F Y') }}</span>
                                    <span class="mt-1 block text-sm text-gray-500">{{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }} WIB</span>
                                    @if($slot->topic)
                                    <span class="mt-1 block text-xs font-semibold text-[#0A52C4]">{{ $slot->topic }}</span>
                                    @endif
                                </span>
                            </label>
                            @endforeach
                        </div>
                        @error('mentor_slot_id')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </fieldset>

                    <fieldset>
                        <legend class="mb-3 text-sm font-extrabold text-gray-800">Topik Pembahasan</legend>
                        <div class="grid gap-2 sm:grid-cols-2">
                            @foreach(['Rasionalisasi SNBP', 'Strategi UTBK', 'Curhat', 'Lainnya'] as $item)
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 p-3 text-sm font-semibold text-gray-700 transition hover:border-[#0A52C4]">
                                <input type="radio" name="topic" value="{{ $item }}" x-model="topic" @checked(old('topic', 'Rasionalisasi SNBP' )===$item) required class="text-[#0A52C4] focus:ring-[#0A52C4]">
                                {{ $item }}
                            </label>
                            @endforeach
                        </div>
                        @error('topic')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        <div x-show="topic === 'Lainnya'" x-cloak class="mt-3">
                            <label for="custom_topic" class="mb-1 block text-xs font-bold text-gray-600">Topik lainnya</label>
                            <input id="custom_topic" name="custom_topic" value="{{ old('custom_topic') }}" maxlength="255" :required="topic === 'Lainnya'" placeholder="Tulis topik custom" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-[#0A52C4] focus:outline-none focus:ring-1 focus:ring-[#0A52C4]">
                            @error('custom_topic')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </fieldset>

                    <div>
                        <label for="message" class="mb-1 block text-sm font-extrabold text-gray-800">Pesan untuk Mentor <span class="font-normal text-gray-400">(opsional)</span></label>
                        <textarea id="message" name="message" maxlength="200" rows="3" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-[#0A52C4] focus:outline-none focus:ring-1 focus:ring-[#0A52C4]">{{ old('message') }}</textarea>
                    </div>

                    <div class="flex gap-3 border-t border-gray-100 pt-5">
                        <a href="{{ route('siswa.teman-nalar.index') }}" class="flex-1 rounded-xl border border-gray-300 px-4 py-2.5 text-center text-sm font-bold text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit" class="flex-1 rounded-xl bg-[#F28C28] px-4 py-2.5 text-sm font-extrabold text-white shadow-sm hover:bg-[#D97706]">Booking Bimbingan</button>
                    </div>
                </form>
                @endif
            </div>
        </section>
    </div>
</x-layouts.app>