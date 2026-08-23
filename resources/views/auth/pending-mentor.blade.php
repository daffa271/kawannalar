<x-layouts.guest title="Pendaftaran Mentor — KawanNalar">
    <main class="flex min-h-[calc(100vh-10rem)] items-center justify-center bg-[#F4F7FA] px-4 py-12">
        <section class="w-full max-w-lg rounded-3xl bg-white p-8 text-center shadow-xl sm:p-12">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#FFC000]/20 text-3xl">⏳</div>
            <p class="mt-6 text-sm font-bold uppercase tracking-[0.15em] text-[#0A52C4]">Pendaftaran Berhasil!</p>
            <h1 class="mt-2 text-2xl font-extrabold text-gray-900">Akun Anda sedang ditinjau Admin.</h1>
            <p class="mt-4 text-sm leading-relaxed text-gray-500">Tim KawanNalar akan menghubungi Anda via WhatsApp setelah disetujui.</p>
            <a href="{{ route('login') }}" class="mt-8 inline-flex rounded-xl bg-[#F28C28] px-6 py-3 font-bold text-white hover:bg-[#E07D1C]">Kembali ke Login</a>
        </section>
    </main>
</x-layouts.guest>