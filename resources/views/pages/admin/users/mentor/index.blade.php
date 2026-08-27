<x-layouts.admin>
<x-slot name="title">User Management: Mentor — KawanNalar</x-slot>

<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('dashboard.admin') }}" class="hover:text-[#0A52C4]">Dashboard</a>
                <span>›</span><span class="font-semibold text-gray-700">User Management · Mentor</span>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900">Manajemen Mentor</h1>
            <p class="mt-1 text-sm text-gray-500">Total <span class="font-bold text-gray-800">{{ $mentors->total() }}</span> mentor terdaftar.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.siswa') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Lihat Siswa
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $totalMentor  = $mentors->total();
            $verified     = \App\Models\User::where('role','mentor')->where('status','active')->count();
            $pending      = \App\Models\User::where('role','mentor')->where('status','pending')->count();
        @endphp
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Total Mentor</p>
            <p class="mt-1 text-2xl font-extrabold text-gray-900">{{ $totalMentor }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Verified</p>
            <p class="mt-1 text-2xl font-extrabold text-green-600">{{ $verified }}</p>
        </div>
        <div class="rounded-2xl border border-[#F28C28]/30 bg-orange-50 p-4 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-[#F28C28]">Pending</p>
            <p class="mt-1 text-2xl font-extrabold text-[#F28C28]">{{ $pending }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Bulan Ini</p>
            <p class="mt-1 text-2xl font-extrabold text-[#0A52C4]">{{ \App\Models\User::where('role','mentor')->whereMonth('created_at', now()->month)->count() }}</p>
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
            <h2 class="font-extrabold text-gray-800 text-sm">Daftar Mentor</h2>
            <div class="flex items-center gap-2">
                <span class="rounded-lg bg-yellow-50 border border-yellow-200 px-2.5 py-1 text-xs font-bold text-yellow-700">⚡ {{ $pending }} Pending Verifikasi</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 pl-5 pr-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">#</th>
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Nama</th>
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">PTN / Kampus</th>
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Prodi</th>
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Modul</th>
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Rating</th>
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-3 py-3 text-right text-xs font-bold uppercase tracking-wide text-gray-500 pr-5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($mentors as $i => $mentor)
                    @php $mp = $mentor->mentorProfile; @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ $mentor->status === 'pending' ? 'bg-yellow-50/40' : '' }}">
                        <td class="py-3.5 pl-5 pr-3 text-xs font-bold text-gray-400">{{ $mentors->firstItem() + $i }}</td>
                        <td class="px-3 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="relative shrink-0">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-[#0A52C4] to-[#1565D8] flex items-center justify-center text-white text-sm font-black">
                                        {{ strtoupper(substr($mentor->name,0,1)) }}
                                    </div>
                                    @if($mentor->status === 'active')
                                    <span class="absolute -bottom-0.5 -right-0.5 flex h-4 w-4 items-center justify-center rounded-full border-2 border-white bg-[#F28C28] shadow-sm">
                                        <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 leading-tight">{{ $mentor->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $mentor->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3.5 text-sm text-gray-600 font-medium">{{ $mp->university ?? '—' }}</td>
                        <td class="px-3 py-3.5 text-sm text-gray-600">{{ $mp->major ?? '—' }}</td>
                        <td class="px-3 py-3.5">
                            <span class="text-sm font-bold text-gray-800">{{ $mentor->modules->count() }}</span>
                            <span class="text-xs text-gray-400 ml-1">modul</span>
                        </td>
                        <td class="px-3 py-3.5">
                            <div class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs font-bold text-gray-700">4.9</span>
                            </div>
                        </td>
                        <td class="px-3 py-3.5">
                            @if($mentor->is_suspended)
                            <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-0.5 text-[11px] font-bold text-red-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Tersuspen
                            </span>
                            @elseif($mentor->status === 'active')
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-[11px] font-bold text-green-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Verified
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-2.5 py-0.5 text-[11px] font-bold text-yellow-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Pending
                            </span>
                            @endif
                        </td>
                        <td class="px-3 py-3.5 text-right pr-5">
                            <div class="flex items-center justify-end gap-2">
                                @if($mentor->status !== 'active')
                                <form action="{{ route('admin.mentors.approve', $mentor->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="rounded-lg border border-green-300 bg-green-50 px-2.5 py-1 text-xs font-bold text-green-700 hover:bg-green-100 transition flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Verify
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('admin.mentors.ktm', $mentor->id) }}" class="rounded-lg border border-gray-200 px-2.5 py-1 text-xs font-bold text-gray-700 hover:bg-gray-50 transition">Detail</a>
                                <form action="{{ route('admin.users.toggle-suspend', $mentor->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status suspend mentor ini?');">
                                    @csrf
                                    @method('PATCH')
                                    @if($mentor->is_suspended)
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
                        <td colspan="8" class="py-12 text-center text-sm text-gray-400 font-medium">Belum ada mentor yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($mentors->hasPages())
        <div class="border-t border-gray-100 px-5 py-4">
            {{ $mentors->links() }}
        </div>
        @endif
    </div>
</div>
</x-layouts.admin>
