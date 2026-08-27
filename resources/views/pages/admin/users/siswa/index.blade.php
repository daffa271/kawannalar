<x-layouts.admin>
<x-slot name="title">User Management: Siswa — KawanNalar</x-slot>

<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('dashboard.admin') }}" class="hover:text-[#0A52C4]">Dashboard</a>
                <span>›</span><span class="font-semibold text-gray-700">User Management · Siswa</span>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900">Manajemen Siswa</h1>
            <p class="mt-1 text-sm text-gray-500">Total <span class="font-bold text-gray-800">{{ $students->total() }}</span> siswa terdaftar.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.mentor') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Lihat Mentor
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php $totalSiswa = $students->total(); @endphp
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Total Siswa</p>
            <p class="mt-1 text-2xl font-extrabold text-gray-900">{{ $totalSiswa }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Aktif</p>
            <p class="mt-1 text-2xl font-extrabold text-green-600">{{ \App\Models\User::where('role','siswa')->where('is_suspended', false)->count() }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Bulan Ini</p>
            <p class="mt-1 text-2xl font-extrabold text-[#0A52C4]">{{ \App\Models\User::where('role','siswa')->whereMonth('created_at', now()->month)->count() }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Suspended</p>
            <p class="mt-1 text-2xl font-extrabold text-red-500">{{ \App\Models\User::where('role','siswa')->where('is_suspended', true)->count() }}</p>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-bold text-green-700">
        {{ session('success') }}
    </div>
    @endif

    {{-- Table --}}
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-extrabold text-gray-800 text-sm">Daftar Siswa</h2>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Cari siswa..." class="rounded-lg border border-gray-200 pl-8 pr-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-[#0A52C4]/20">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 pl-5 pr-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">#</th>
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Nama</th>
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Asal Sekolah</th>
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Kelas</th>
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Total XP</th>
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Daftar</th>
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-3 py-3 text-right text-xs font-bold uppercase tracking-wide text-gray-500 pr-5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $i => $student)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3.5 pl-5 pr-3 text-xs font-bold text-gray-400">{{ $students->firstItem() + $i }}</td>
                        <td class="px-3 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-[#0A52C4] to-[#1565D8] flex items-center justify-center text-white text-xs font-black shrink-0">
                                    {{ strtoupper(substr($student->name,0,1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 leading-tight">{{ $student->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $student->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3.5 text-sm text-gray-600">{{ $student->studentProfile->school ?? '—' }}</td>
                        <td class="px-3 py-3.5 text-sm text-gray-600">{{ $student->studentProfile->grade ?? '—' }}</td>
                        <td class="px-3 py-3.5">
                            <span class="inline-flex items-center gap-1 text-sm font-bold text-yellow-700">
                                <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ $student->xp_points ?? 0 }} XP
                            </span>
                        </td>
                        <td class="px-3 py-3.5 text-xs text-gray-500">{{ $student->created_at->format('d M Y') }}</td>
                        <td class="px-3 py-3.5">
                            @if($student->is_suspended)
                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-[11px] font-bold text-red-700">Tersuspen</span>
                            @else
                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-[11px] font-bold text-green-700">Aktif</span>
                            @endif
                        </td>
                        <td class="px-3 py-3.5 text-right pr-5">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.users.toggle-suspend', $student->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status suspend siswa ini?');">
                                    @csrf
                                    @method('PATCH')
                                    @if($student->is_suspended)
                                        <button type="submit" class="rounded-lg border border-green-200 bg-green-50 px-2.5 py-1 text-xs font-bold text-green-600 hover:bg-green-100 transition">
                                            Aktifkan
                                        </button>
                                    @else
                                        <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-bold text-red-600 hover:bg-red-100 transition">
                                            Suspend
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-sm text-gray-400 font-medium">Belum ada siswa yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
        <div class="border-t border-gray-100 px-5 py-4">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>
</x-layouts.admin>
