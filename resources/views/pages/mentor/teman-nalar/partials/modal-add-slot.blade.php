{{-- Modal Tambah Slot / Live Class --}}
<div x-show="showModal"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
    style="display:none;" x-cloak>

    <div @click.outside="showModal = false"
        x-data="{
            type: '1on1',
            startTime: '',
            endTime: '',
            get durationVal() {
                if (!this.startTime || !this.endTime) return '';
                const [startH, startM] = this.startTime.split(':').map(Number);
                const [endH, endM] = this.endTime.split(':').map(Number);
                const startMin = startH * 60 + startM;
                const endMin   = endH * 60 + endM;
                return endMin - startMin;
            },
            get isValidTime() {
                if (!this.startTime || !this.endTime) return true;
                return this.durationVal > 0;
            }
        }"
        class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl flex flex-col max-h-[90vh]">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 shrink-0">
            <div>
                <h2 class="text-base font-extrabold text-gray-900">Buat Sesi Baru</h2>
                <p class="text-xs text-gray-500 mt-0.5">Tambah slot 1-on-1 atau jadwal Live Class</p>
            </div>
            <button @click="showModal = false" type="button"
                class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Scrollable Form Body --}}
        <form action="{{ route('mentor.teman-nalar.slot.store') }}" method="POST"
              class="flex-1 overflow-y-auto px-6 py-5 space-y-5">
            @csrf

            {{-- Tab: Jenis Sesi --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Sesi</label>
                <div class="flex rounded-xl border border-gray-200 bg-gray-50 p-1 gap-1">
                    <button type="button"
                        @click="type = '1on1'"
                        :class="type === '1on1'
                            ? 'bg-white shadow text-[#0A52C4] border-blue-200'
                            : 'text-gray-500 border-transparent hover:text-gray-700'"
                        class="flex-1 rounded-lg border py-2 text-sm font-bold transition">
                        👤 Bimbingan 1-on-1
                    </button>
                    <button type="button"
                        @click="type = 'live_class'"
                        :class="type === 'live_class'
                            ? 'bg-white shadow text-[#0A52C4] border-blue-200'
                            : 'text-gray-500 border-transparent hover:text-gray-700'"
                        class="flex-1 rounded-lg border py-2 text-sm font-bold transition">
                        🎥 Live Class
                    </button>
                </div>
                <input type="hidden" name="session_type" :value="type">
            </div>

            {{-- ── FIELDS: Bimbingan 1-on-1 ──────────────────────────────── --}}
            <div x-show="type === '1on1'" x-transition class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date"
                        :required="type === '1on1'"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-[#0A52C4] focus:ring-1 focus:ring-[#0A52C4] outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Jam Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_time"
                            x-model="startTime"
                            :required="type === '1on1'"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-[#0A52C4] focus:ring-1 focus:ring-[#0A52C4] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Jam Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="end_time"
                            x-model="endTime"
                            :required="type === '1on1'"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-[#0A52C4] focus:ring-1 focus:ring-[#0A52C4] outline-none"
                            :class="!isValidTime ? 'border-red-400 bg-red-50' : ''">
                    </div>
                </div>
                {{-- Durasi Auto + Validasi --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Durasi (dihitung otomatis)</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="duration"
                            :value="durationVal"
                            readonly
                            placeholder="Isi jam mulai & selesai"
                            class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none text-gray-600 font-bold cursor-not-allowed">
                        <span class="text-sm text-gray-500 font-medium" x-show="durationVal">menit</span>
                    </div>
                    <p x-show="!isValidTime"
                       class="mt-1 text-[11px] text-red-600 font-semibold flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Waktu selesai tidak boleh sama atau sebelum waktu mulai
                    </p>
                </div>
            </div>

            {{-- ── FIELDS: Live Class ──────────────────────────────────────── --}}
            <div x-show="type === 'live_class'" x-transition class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">
                        Judul Kelas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title"
                        :required="type === 'live_class'"
                        placeholder="Misal: Strategi Lolos SNBT 2025..."
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-[#0A52C4] focus:ring-1 focus:ring-[#0A52C4] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Kelas</label>
                    <textarea name="description" rows="2"
                        placeholder="Tulis ringkasan materi yang akan dibahas..."
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-[#0A52C4] focus:ring-1 focus:ring-[#0A52C4] outline-none resize-none"></textarea>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="live_date"
                            :required="type === 'live_class'"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-[#0A52C4] focus:ring-1 focus:ring-[#0A52C4] outline-none">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Pukul <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="live_time"
                            :required="type === 'live_class'"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-[#0A52C4] focus:ring-1 focus:ring-[#0A52C4] outline-none">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kuota Maks</label>
                        <input type="number" name="quota" min="1" value="30"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-[#0A52C4] focus:ring-1 focus:ring-[#0A52C4] outline-none">
                    </div>
                </div>
            </div>

            {{-- ── Meeting Link (General) ──────────────────────────────────── --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">
                    Tautan Meeting (GMeet / Zoom / Jitsi) <span class="text-red-500">*</span>
                </label>
                {{-- Pakai type="text" bukan "url" supaya browser tidak blokir format link pendek --}}
                <input type="text" name="meeting_link"
                    placeholder="https://meet.google.com/abc-def-ghi"
                    required
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-[#0A52C4] focus:ring-1 focus:ring-[#0A52C4] outline-none">
                <p class="mt-1 text-[11px] text-gray-400">
                    Link akan diberikan kepada siswa setelah booking disetujui.
                </p>
            </div>

            {{-- Validation Errors --}}
            @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700 space-y-0.5">
                @foreach($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
            @endif

            {{-- Footer Buttons --}}
            <div class="pt-4 border-t border-gray-100 flex gap-3">
                <button type="button" @click="showModal = false"
                    class="flex-1 rounded-xl border border-gray-200 bg-white py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                    :disabled="type === '1on1' && (!isValidTime || !startTime || !endTime)"
                    :class="(type === '1on1' && (!isValidTime || !startTime || !endTime))
                        ? 'opacity-50 cursor-not-allowed bg-gray-400'
                        : 'bg-[#FF6B00] hover:bg-[#E56000] cursor-pointer'"
                    class="flex-1 rounded-xl py-2.5 text-sm font-bold text-white shadow transition">
                    Simpan Sesi
                </button>
            </div>
        </form>
    </div>
</div>
