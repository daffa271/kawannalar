<x-layouts.admin title="Dashboard Admin — KawanNalar">
    <div class="space-y-8">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-bold text-[#0A52C4]">Admin Control Center</p>
                <h1 class="mt-1 text-2xl font-extrabold text-gray-900 lg:text-3xl">Ringkasan Platform</h1>
                <p class="mt-2 text-sm text-gray-500">Pantau traffic pengguna dan antrean verifikasi mentor.</p>
            </div><a href="{{ route('admin.verification.index') }}" class="inline-flex w-fit rounded-xl bg-[#0A52C4] px-4 py-2.5 text-sm font-bold text-white hover:bg-[#0842A0]">Buka Verification Hub</a>
        </div>
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Siswa</p>
                <p class="mt-2 text-3xl font-extrabold text-[#0A52C4]">{{ $totalStudents }}</p>
                <p class="mt-1 text-xs text-gray-400">Pengguna terdaftar</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Mentor Aktif</p>
                <p class="mt-2 text-3xl font-extrabold text-green-600">{{ $activeMentors }}</p>
                <p class="mt-1 text-xs text-gray-400">Sudah dapat mengakses platform</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Pending Verifikasi</p>
                <p class="mt-2 text-3xl font-extrabold text-[#F28C28]">{{ $pendingMentorCount }}</p>
                <p class="mt-1 text-xs text-gray-400">Membutuhkan review admin</p>
            </div>
        </div>
    </div>
</x-layouts.admin>