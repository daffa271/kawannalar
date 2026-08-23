<x-layouts.admin title="Verification Hub — KawanNalar">
    <div class="space-y-8">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-bold text-[#0A52C4]">Admin Control Center</p>
                <h1 class="mt-1 text-2xl font-extrabold text-gray-900 lg:text-3xl">Verification Hub</h1>
                <p class="mt-2 text-sm text-gray-500">Tinjau pendaftaran mentor dan kelola akses platform.</p>
            </div><span class="inline-flex w-fit items-center rounded-full bg-[#FFC000]/15 px-3 py-1.5 text-xs font-bold text-[#8A6500]">{{ $pendingMentorCount }} antrean pending</span>
        </div>
        @if (session('status'))<div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">{{ session('status') }}</div>@endif
        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex flex-col gap-2 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="font-extrabold text-gray-900">Antrean Verifikasi Mentor Baru</h2>
                    <p class="mt-1 text-xs text-gray-400">Periksa data sebelum memberikan akses mentor.</p>
                </div><span class="text-xs font-semibold text-gray-400">{{ $pendingMentorCount }} pendaftar</span>
            </div>
            @if ($pendingMentors->isEmpty())<div class="px-6 py-14 text-center">
                <p class="font-bold text-gray-700">Belum ada antrean verifikasi</p>
                <p class="mt-1 text-sm text-gray-400">Pendaftar mentor baru akan muncul di sini.</p>
            </div>@else
            <div class="hidden overflow-x-auto md:block">
                <table class="min-w-[980px] w-full text-left text-sm">
                    <thead class="bg-[#F4F7FA] text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-bold">Nama</th>
                            <th class="px-5 py-3 font-bold">Asal PTN & Jurusan</th>
                            <th class="px-5 py-3 font-bold">Asal SMA</th>
                            <th class="px-5 py-3 font-bold">WhatsApp</th>
                            <th class="px-5 py-3 font-bold">File KTM</th>
                            <th class="px-5 py-3 font-bold">Tanggal Daftar</th>
                            <th class="px-5 py-3 text-right font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">@foreach ($pendingMentors as $mentor) @php($profile = $mentor->mentorProfile)<tr class="align-top hover:bg-gray-50/70">
                            <td class="px-5 py-4">
                                <p class="font-bold text-gray-800">{{ $mentor->name }}</p>
                                <p class="mt-1 text-xs text-gray-400">{{ $mentor->email }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-700">{{ $profile?->university ?? '-' }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $profile?->major ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $profile?->high_school ?? '-' }}
                                <p class="mt-1 text-xs text-gray-400">Lulus {{ $profile?->graduation_year ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4"><a href="https://wa.me/{{ preg_replace('/\D+/', '', $profile?->whatsapp ?? '') }}" target="_blank" rel="noopener" class="font-semibold text-[#0A52C4] hover:underline">{{ $profile?->whatsapp ?? '-' }}</a></td>
                            <td class="px-5 py-4">@if ($profile?->ktm_path)<a href="{{ route('admin.mentors.ktm', $mentor->id) }}" target="_blank" rel="noopener" class="font-semibold text-[#0A52C4] hover:underline">Lihat KTM</a>@else<span class="text-gray-400">Tidak ada</span>@endif</td>
                            <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $mentor->created_at?->format('d M Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.mentors.approve', $mentor->id) }}">@csrf @method('PATCH')<button type="submit" class="rounded-lg bg-green-600 px-3 py-2 text-xs font-bold text-white hover:bg-green-700">Setujui</button></form>
                                    <form method="POST" action="{{ route('admin.mentors.reject', $mentor->id) }}">@csrf @method('PATCH')<button type="submit" class="rounded-lg border border-red-200 px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50">Tolak</button></form>
                                </div>
                            </td>
                        </tr>@endforeach</tbody>
                </table>
            </div>@endif
            @if ($pendingMentors->isNotEmpty())
            <div class="space-y-3 p-4 md:hidden">
                @foreach ($pendingMentors as $mentor) @php($profile = $mentor->mentorProfile)
                <article class="rounded-xl border border-gray-100 bg-[#F4F7FA] p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="font-bold text-gray-800">{{ $mentor->name }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $mentor->email }}</p>
                        </div><span class="text-xs text-gray-500">{{ $mentor->created_at?->format('d M Y') }}</span>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-gray-700">{{ $profile?->university ?? '-' }}</p>
                    <p class="text-xs text-gray-500">{{ $profile?->major ?? '-' }} · {{ $profile?->high_school ?? '-' }}</p>
                    <div class="mt-4 flex gap-2">
                        <form method="POST" action="{{ route('admin.mentors.approve', $mentor->id) }}" class="flex-1">@csrf @method('PATCH')<button type="submit" class="w-full rounded-lg bg-green-600 px-3 py-2 text-xs font-bold text-white">Setujui</button></form>
                        <form method="POST" action="{{ route('admin.mentors.reject', $mentor->id) }}" class="flex-1">@csrf @method('PATCH')<button type="submit" class="w-full rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-bold text-red-600">Tolak</button></form>
                    </div>
                </article>
                @endforeach
            </div>
            @endif
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex flex-col gap-2 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="font-extrabold text-gray-900">Antrean Review Modul Ruang Nalar</h2>
                    <p class="mt-1 text-xs text-gray-400">Materi dari mentor dan siswa menunggu persetujuan admin.</p>
                </div>
                <span class="text-xs font-semibold text-gray-400">{{ $pendingModuleCount ?? 0 }} modul menunggu</span>
            </div>

            @if (($pendingModules ?? collect())->isEmpty())
            <div class="px-6 py-14 text-center">
                <p class="font-bold text-gray-700">Belum ada modul menunggu review</p>
                <p class="mt-1 text-sm text-gray-400">Semua materi yang dikirim akan muncul di sini.</p>
            </div>
            @else
            <div class="hidden overflow-x-auto md:block">
                <table class="min-w-[980px] w-full text-left text-sm">
                    <thead class="bg-[#F4F7FA] text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-bold">Judul</th>
                            <th class="px-5 py-3 font-bold">Kategori</th>
                            <th class="px-5 py-3 font-bold">Pengunggah</th>
                            <th class="px-5 py-3 font-bold">File</th>
                            <th class="px-5 py-3 font-bold">Tanggal</th>
                            <th class="px-5 py-3 text-right font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($pendingModules as $module)
                        <tr class="align-top hover:bg-gray-50/70">
                            <td class="px-5 py-4">
                                <p class="font-bold text-gray-800">{{ $module->title }}</p>
                                <p class="mt-1 max-w-md text-xs text-gray-500">{{ Str::limit($module->description ?: 'Tidak ada deskripsi', 120) }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-700">{{ $module->subject }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $module->grade }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-700">{{ $module->uploader?->name ?? 'Unknown' }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $module->uploader?->role ?? 'user' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.modules.file', $module) }}" target="_blank" rel="noopener" class="font-semibold text-[#0A52C4] hover:underline">Lihat File</a>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $module->created_at?->format('d M Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.modules.approve', $module->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="rounded-lg bg-green-600 px-3 py-2 text-xs font-bold text-white hover:bg-green-700">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.modules.reject', $module->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="rounded-lg border border-red-200 px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50">Tolak</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="space-y-3 p-4 md:hidden">
                @foreach ($pendingModules as $module)
                <article class="rounded-xl border border-gray-100 bg-[#F4F7FA] p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="font-bold text-gray-800">{{ $module->title }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $module->subject }} · {{ $module->grade }}</p>
                        </div><span class="text-xs text-gray-500">{{ $module->created_at?->format('d M Y') }}</span>
                    </div>
                    <p class="mt-3 text-sm text-gray-600">{{ Str::limit($module->description ?: 'Tidak ada deskripsi', 120) }}</p>
                    <p class="mt-2 text-xs text-gray-500">Pengunggah: {{ $module->uploader?->name ?? 'Unknown' }} ({{ $module->uploader?->role ?? 'user' }})</p>
                    <div class="mt-4 flex items-center gap-2"><a href="{{ route('admin.modules.file', $module) }}" target="_blank" rel="noopener" class="flex-1 rounded-lg border border-[#0A52C4]/20 bg-white px-3 py-2 text-center text-xs font-bold text-[#0A52C4]">Lihat File</a>
                        <form method="POST" action="{{ route('admin.modules.approve', $module->id) }}" class="flex-1">@csrf @method('PATCH')<button type="submit" class="w-full rounded-lg bg-green-600 px-3 py-2 text-xs font-bold text-white">Setujui</button></form>
                        <form method="POST" action="{{ route('admin.modules.reject', $module->id) }}" class="flex-1">@csrf @method('PATCH')<button type="submit" class="w-full rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-bold text-red-600">Tolak</button></form>
                    </div>
                </article>
                @endforeach
            </div>
            @endif
        </section>
    </div>
</x-layouts.admin>