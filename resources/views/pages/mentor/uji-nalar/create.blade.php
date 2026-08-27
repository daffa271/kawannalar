<x-layouts.mentor title="Buat Paket Soal Uji Nalar — Mentor">
<div
    x-data="{
        totalQuestions: 5,
        questions: [],
        init() {
            this.updateQuestions();
        },
        updateQuestions() {
            const count = parseInt(this.totalQuestions);
            while (this.questions.length < count) {
                this.questions.push({
                    question_text: '',
                    option_a: '',
                    option_b: '',
                    option_c: '',
                    option_d: '',
                    option_e: '',
                    correct_answer: 'A',
                    explanation: ''
                });
            }
            if (this.questions.length > count) {
                this.questions = this.questions.slice(0, count);
            }
        }
    }"
    class="mx-auto max-w-5xl space-y-6 px-1 sm:px-0"
>
    <div>
        <a href="{{ route('mentor.uji-nalar.index') }}" class="text-xs font-bold text-[#0A52C4] hover:underline">‹ Kembali ke Kelola Soal</a>
        <h1 class="mt-2 text-2xl font-extrabold text-gray-900 sm:text-3xl">Buat Paket Soal Baru</h1>
        <p class="mt-1 text-sm text-gray-500">Isi detail paket soal dan pertanyaan beserta opsi jawaban A–E dan pembahasannya.</p>
    </div>

    @if ($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-xs font-semibold text-red-700">
        <p class="font-bold mb-1">Terjadi kesalahan pada input:</p>
        <ul class="list-disc pl-5 space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('mentor.uji-nalar.store') }}" class="space-y-6">
        @csrf

        {{-- ── INFORMASI UTAMA PAKET ─────────────────────────────────── --}}
        <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm space-y-4">
            <h2 class="font-extrabold text-gray-900 text-base border-b border-gray-100 pb-3">📌 Informasi Paket Soal</h2>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Judul Paket Soal <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="{{ old('title') }}"
                           placeholder="Contoh: Latihan Soal Barisan dan Deret Aritmatika"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#0A52C4] focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select name="subject_id" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm focus:border-[#0A52C4] focus:bg-white focus:outline-none">
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Target Kelas <span class="text-red-500">*</span></label>
                    <select name="class_level" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm focus:border-[#0A52C4] focus:bg-white focus:outline-none">
                        <option value="10" @selected(old('class_level') == '10')>Kelas 10</option>
                        <option value="11" @selected(old('class_level') == '11')>Kelas 11</option>
                        <option value="12" @selected(old('class_level') == '12')>Kelas 12</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-2">Preset Jumlah Soal <span class="text-red-500">*</span></label>
                    <div class="flex gap-3">
                        @foreach([5, 10, 15] as $preset)
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="total_questions" value="{{ $preset }}" x-model="totalQuestions" @change="updateQuestions()" class="sr-only peer">
                            <div class="rounded-xl border border-gray-200 py-3 text-center text-xs font-extrabold text-gray-600 peer-checked:border-[#0A52C4] peer-checked:bg-[#EEF4FF] peer-checked:text-[#0A52C4] hover:bg-gray-50 transition">
                                {{ $preset }} Soal
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ── DAFTAR PERTANYAAN (DYNAMIC ALPINE) ────────────────────── --}}
        <div class="space-y-5">
            <template x-for="(q, index) in questions" :key="index">
                <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="font-extrabold text-[#0A52C4] text-sm" x-text="`Soal #${index + 1}`"></h3>
                        <span class="text-xs font-bold text-gray-400">Pilihan Ganda A–E</span>
                    </div>

                    {{-- Pertanyaan --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pertanyaan Soal <span class="text-red-500">*</span></label>
                        <textarea :name="`questions[${index}][question_text]`" x-model="q.question_text" rows="3" required
                                  placeholder="Tuliskan teks pertanyaan soal di sini..."
                                  class="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm focus:border-[#0A52C4] focus:bg-white focus:outline-none"></textarea>
                    </div>

                    {{-- Opsi A - E --}}
                    <div class="grid gap-3 sm:grid-cols-2">
                        <template x-for="opt in ['a','b','c','d','e']" :key="opt">
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1" x-text="`Opsi ${opt.toUpperCase()}`"></label>
                                <input type="text" :name="`questions[${index}][option_${opt}]`" x-model="q[`option_${opt}`]" required
                                       :placeholder="`Jawaban ${opt.toUpperCase()}`"
                                       class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs focus:border-[#0A52C4] focus:bg-white focus:outline-none">
                            </div>
                        </template>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kunci Jawaban Benar <span class="text-red-500">*</span></label>
                            <select :name="`questions[${index}][correct_answer]`" x-model="q.correct_answer" required
                                    class="w-full rounded-xl border border-gray-200 bg-green-50 px-3.5 py-2 text-xs font-extrabold text-green-800 focus:border-green-500 focus:outline-none">
                                <option value="A">Jawaban Benar: A</option>
                                <option value="B">Jawaban Benar: B</option>
                                <option value="C">Jawaban Benar: C</option>
                                <option value="D">Jawaban Benar: D</option>
                                <option value="E">Jawaban Benar: E</option>
                            </select>
                        </div>
                    </div>

                    {{-- Pembahasan --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pembahasan / Penjelasan Soal</label>
                        <textarea :name="`questions[${index}][explanation]`" x-model="q.explanation" rows="2"
                                  placeholder="Jelaskan langkah penyelesaian atau konsep di balik jawaban benar..."
                                  class="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-xs focus:border-[#0A52C4] focus:bg-white focus:outline-none"></textarea>
                    </div>
                </section>
            </template>
        </div>

        {{-- SUBMIT BUTTON --}}
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('mentor.uji-nalar.index') }}" class="rounded-xl border border-gray-200 bg-white px-6 py-3 text-xs font-bold text-gray-600 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="rounded-xl bg-[#F28C28] px-8 py-3 text-xs font-bold text-white shadow-md hover:bg-[#E07D1C] transition">
                🚀 Submit Paket Soal untuk Moderasi
            </button>
        </div>
    </form>
</div>
</x-layouts.mentor>
