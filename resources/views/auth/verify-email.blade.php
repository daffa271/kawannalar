{{--
    Verify Email Page — KawanNalar.
--}}
<x-layouts.guest title="Verifikasi Email — KawanNalar">

    <div class="min-h-[calc(100vh-10rem)] flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xl p-8 text-center">
                <div class="w-16 h-16 bg-[#0A52C4]/10 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-[#0A52C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>

                <h1 class="text-xl font-bold text-gray-900 mb-2">Verifikasi Email Kamu</h1>
                <p class="text-sm text-gray-500 mb-6">
                    Kami telah mengirim link verifikasi ke email kamu. Silakan buka email dan klik link tersebut untuk mengaktifkan akun.
                </p>

                @if (session('status') === 'verification-link-sent')
                    <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3 mb-6">
                        Link verifikasi baru telah dikirim ke email kamu.
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 bg-[#F28C28] hover:bg-[#E07D1C] text-white font-bold rounded-xl transition-all mb-4" style="box-shadow: 0 4px 14px rgba(242,140,40,0.35);">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-[#0A52C4] transition-colors">
                        Keluar dan gunakan akun lain
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-layouts.guest>
