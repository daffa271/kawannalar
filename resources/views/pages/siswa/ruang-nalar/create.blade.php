<x-layouts.siswa title="Upload Modul — KawanNalar">
    <div class="mx-auto max-w-3xl space-y-8">
        <div>
            <p class="text-sm font-bold text-[#0A52C4]">Ruang Nalar</p>
            <h1 class="mt-1 text-2xl font-extrabold text-gray-900 lg:text-3xl">Bagikan Materi ke Ruang Berbagi</h1>
            <p class="mt-2 text-sm text-gray-500">Bagikan catatan atau materi untuk seluruh siswa setelah disetujui admin.</p>
        </div>

        @if (session('status'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('status') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Periksa kembali data modul dan file yang dipilih.
        </div>
        @endif

        <form method="POST" action="{{ route('siswa.ruang-nalar.store') }}" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
            @csrf

            <div>
                <label for="title" class="field-label">Judul Modul</label>
                <input id="title" name="title" value="{{ old('title') }}" required class="field-input" placeholder="Contoh: Ringkasan TPS Penalaran Logis">
                <x-input-error :messages="$errors->get('title')" class="field-error" />
            </div>

            <div>
                <label for="description" class="field-label">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="field-input" placeholder="Jelaskan isi materi dan manfaatnya untuk teman-teman lain...">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="field-error" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="subject" class="field-label">Mata Pelajaran</label>
                    <select id="subject" name="subject" required class="field-input">
                        <option value="">Pilih mata pelajaran</option>
                        <option>Matematika</option>
                        <option>Fisika</option>
                        <option>Kimia</option>
                        <option>Biologi</option>
                        <option>UTBK TPS</option>
                        <option>Literasi Bahasa</option>
                        <option>Penalaran Umum</option>
                    </select>
                    <x-input-error :messages="$errors->get('subject')" class="field-error" />
                </div>

                <div>
                    <label for="grade" class="field-label">Target Kelas</label>
                    <select id="grade" name="grade" required class="field-input">
                        <option value="">Pilih target kelas</option>
                        <option>Kelas 10</option>
                        <option>Kelas 11</option>
                        <option>Kelas 12</option>
                        <option>UTBK</option>
                    </select>
                    <x-input-error :messages="$errors->get('grade')" class="field-error" />
                </div>
            </div>

            <div>
                <label for="file" class="field-label">File Catatan atau Materi</label>
                <input id="file" type="file" name="file" required accept="application/pdf,image/png,image/jpeg,.pdf,.png,.jpg,.jpeg" class="block w-full rounded-xl border border-gray-200 bg-[#F4F7FA] px-3 py-2.5 text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-[#0A52C4]/10 file:px-3 file:py-2 file:text-xs file:font-bold file:text-[#0A52C4]">
                <p class="mt-1 text-xs text-gray-400">Format PDF, PNG, JPG atau JPEG, maksimal 25 MB.</p>
                <x-input-error :messages="$errors->get('file')" class="field-error" />
            </div>

            <div class="flex justify-end border-t border-gray-100 pt-5">
                <button type="submit" class="rounded-xl bg-[#F28C28] px-6 py-3 text-sm font-bold text-white hover:bg-[#E07D1C]">
                    Kirim ke Admin
                </button>
            </div>
        </form>
    </div>
</x-layouts.siswa>