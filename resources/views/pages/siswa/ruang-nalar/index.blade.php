<x-layouts.siswa title="Ruang Nalar — KawanNalar">
    <div x-data="{ uploadOpen: {{ $errors->any() ? 'true' : 'false' }} }" class="space-y-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-bold text-[#0A52C4]">Ruang Nalar</p>
                <h1 class="mt-1 text-2xl font-extrabold text-gray-900 lg:text-3xl">Temukan dan Bagikan Catatan</h1>
                <p class="mt-2 max-w-2xl text-sm text-gray-500">Materi pilihan dari mentor dan teman belajar, sudah melalui review admin.</p>
            </div>
            <button type="button" @click="uploadOpen = true" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F28C28] px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-[#E07D1C]"><span class="text-lg leading-none">+</span> Unggah Catatan</button>
        </div>

        <form method="GET" action="{{ route('siswa.ruang-nalar.index') }}" class="grid gap-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm md:grid-cols-[1fr_180px_180px_auto]">
            <label class="sr-only" for="q">Cari modul</label><input id="q" name="q" value="{{ request('q') }}" placeholder="Cari judul atau deskripsi modul..." class="field-input">
            <label class="sr-only" for="subject">Mata pelajaran</label><select id="subject" name="subject" class="field-input">
                <option value="">Semua mata pelajaran</option>@foreach ($subjects as $subject)<option value="{{ $subject }}" @selected(request('subject')===$subject)>{{ $subject }}</option>@endforeach
            </select>
            <label class="sr-only" for="grade">Kelas</label><select id="grade" name="grade" class="field-input">
                <option value="">Semua kelas</option>@foreach ($grades as $grade)<option value="{{ $grade }}" @selected(request('grade')===$grade)>{{ $grade }}</option>@endforeach
            </select>
            <button type="submit" class="rounded-xl bg-[#0A52C4] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#0842A0]">Cari Modul</button>
        </form>

        <div class="flex items-center justify-between rounded-2xl border border-[#0A52C4]/10 bg-white px-5 py-4 shadow-sm">
            <div>
                <p class="text-xs text-gray-400">Total materi dibagikan</p>
                <p class="mt-1 text-xl font-extrabold text-[#0A52C4]">{{ number_format($totalDownloads) }} kali diunduh</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400">Unggahanmu</p>
                <p class="mt-1 text-xl font-extrabold text-[#F28C28]">{{ $myModules->count() }} catatan</p>
            </div>
        </div>

        @if ($modules->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center">
            <p class="font-bold text-gray-700">Modul belum ditemukan</p>
            <p class="mt-1 text-sm text-gray-400">Coba ubah kata kunci atau filter pencarianmu.</p>
        </div>
        @else
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($modules as $module)
            <article class="flex flex-col rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3"><span class="rounded-full bg-[#0A52C4]/10 px-2.5 py-1 text-[11px] font-bold text-[#0A52C4]">{{ $module->subject }}</span><span class="text-xs text-gray-400">{{ $module->grade }}</span></div>
                <h2 class="mt-4 text-lg font-extrabold text-gray-900">{{ $module->title }}</h2>
                <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-gray-500">{{ $module->description ?: 'Modul pembelajaran terverifikasi KawanNalar.' }}</p>
                <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">
                    <div>
                        <p class="text-xs text-gray-400">Diunggah oleh</p>
                        <p class="text-xs font-semibold text-gray-700">{{ $module->uploader?->name ?? 'Mentor KawanNalar' }}</p>
                        <p class="mt-1 text-xs text-gray-400">{{ number_format($module->download_count) }} download</p>
                    </div><a href="{{ route('siswa.ruang-nalar.download', $module) }}" class="rounded-xl bg-[#F28C28] px-3.5 py-2.5 text-xs font-bold text-white hover:bg-[#E07D1C]">Download PDF</a>
                </div>
            </article>
            @endforeach
        </div>
        <div>{{ $modules->links() }}</div>
        @endif

        @if ($myModules->isNotEmpty())
        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-4">
                <h2 class="font-extrabold text-gray-900">Status Unggahanmu</h2>
                <p class="mt-1 text-xs text-gray-400">Materi akan tampil di katalog setelah disetujui admin.</p>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach ($myModules as $module)
                <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $module->title }}</p>
                        <p class="mt-1 text-xs text-gray-400">{{ $module->subject }} · {{ $module->created_at?->format('d M Y') }}</p>
                    </div><span class="w-fit rounded-full px-3 py-1 text-xs font-bold {{ $module->status === 'approved' ? 'bg-green-50 text-green-700' : ($module->status === 'rejected' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') }}">{{ $module->status === 'approved' ? 'Disetujui · Tayang' : ($module->status === 'rejected' ? 'Ditolak Admin' : 'Menunggu Review') }}</span>
                </div>
                @endforeach
            </div>
        </section>
        @endif
        <div x-show="uploadOpen" x-cloak @keydown.escape.window="uploadOpen = false" class="fixed inset-0 z-50 flex items-center justify-center bg-[#0F1F3D]/40 p-4" role="dialog" aria-modal="true" aria-labelledby="upload-title">
            <div @click.outside="uploadOpen = false" class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-bold text-[#0A52C4]">Ruang Nalar</p>
                        <h2 id="upload-title" class="mt-1 text-xl font-extrabold text-gray-900">Unggah Catatan atau Materi</h2>
                    </div><button type="button" @click="uploadOpen = false" class="text-2xl leading-none text-gray-400 hover:text-gray-700" aria-label="Tutup">&times;</button>
                </div>
                <form method="POST" action="{{ route('siswa.ruang-nalar.store') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                    @csrf
                    <div><label for="modal-title" class="field-label">Judul Modul / Catatan</label><input id="modal-title" name="title" value="{{ old('title') }}" required class="field-input" placeholder="Contoh: Ringkasan Rumus Trigonometri Kelas 10"><x-input-error :messages="$errors->get('title')" class="field-error" /></div>
                    <div><label for="modal-description" class="field-label">Deskripsi Singkat</label><textarea id="modal-description" name="description" rows="3" class="field-input" placeholder="Ceritakan isi materi ini...">{{ old('description') }}</textarea><x-input-error :messages="$errors->get('description')" class="field-error" /></div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div><label for="modal-subject" class="field-label">Mata Pelajaran</label><select id="modal-subject" name="subject" required class="field-input">
                                <option value="">Pilih mata pelajaran</option>
                                <option>Matematika</option>
                                <option>Fisika</option>
                                <option>Kimia</option>
                                <option>Biologi</option>
                                <option>UTBK TPS</option>
                                <option>Literasi Bahasa</option>
                                <option>Penalaran Umum</option>
                            </select><x-input-error :messages="$errors->get('subject')" class="field-error" /></div>
                        <div><label for="modal-grade" class="field-label">Target Kelas</label><select id="modal-grade" name="grade" required class="field-input">
                                <option value="">Pilih target kelas</option>
                                <option>Kelas 10</option>
                                <option>Kelas 11</option>
                                <option>Kelas 12</option>
                                <option>UTBK</option>
                            </select><x-input-error :messages="$errors->get('grade')" class="field-error" /></div>
                    </div>
                    <div><label for="modal-file" class="field-label">File</label><input id="modal-file" type="file" name="file" required accept="application/pdf,image/png,image/jpeg,.pdf,.png,.jpg,.jpeg" class="block w-full rounded-xl border border-gray-200 bg-[#F4F7FA] px-3 py-2.5 text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-[#0A52C4]/10 file:px-3 file:py-2 file:text-xs file:font-bold file:text-[#0A52C4]">
                        <p class="mt-1 text-xs text-gray-400">PDF, PNG, JPG atau JPEG · maksimal 25 MB.</p><x-input-error :messages="$errors->get('file')" class="field-error" />
                    </div>
                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-5"><button type="button" @click="uploadOpen = false" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-bold text-gray-600 hover:bg-gray-50">Batal</button><button type="submit" class="rounded-xl bg-[#F28C28] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#E07D1C]">Kirim untuk Moderasi Admin</button></div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.siswa>