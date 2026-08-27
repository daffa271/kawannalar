<x-layouts.guest title="Akun Ditangguhkan — KawanNalar">
    <main class="flex min-h-[calc(100vh-10rem)] items-center justify-center bg-[#F4F7FA] px-4 py-12">
        <section class="w-full max-w-lg rounded-3xl bg-white p-8 text-center shadow-xl sm:p-12 border border-red-100">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-red-100 text-3xl">⚠️</div>
            
            <p class="mt-6 text-sm font-bold uppercase tracking-[0.15em] text-red-600">Akses Ditangguhkan</p>
            <h1 class="mt-2 text-2xl font-extrabold text-gray-900">Akun Anda Telah Ditangguhkan</h1>
            
            <p class="mt-4 text-sm leading-relaxed text-gray-500">
                Akun Anda sedang ditangguhkan oleh Admin. Silakan hubungi dukungan KawanNalar untuk klarifikasi dan bantuan lebih lanjut.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="mailto:support@kawannalar.com" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-red-600 px-6 py-3 font-bold text-white hover:bg-red-700 transition">
                    Hubungi Dukungan
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 font-bold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                        Keluar / Logout
                    </button>
                </form>
            </div>
        </section>
    </main>
</x-layouts.guest>
