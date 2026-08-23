{{--
    Reset Password Page — KawanNalar.
--}}
<x-layouts.guest title="Reset Kata Sandi — KawanNalar">

    <div class="min-h-[calc(100vh-10rem)] flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xl p-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">Atur Ulang Kata Sandi</h1>
                    <p class="text-sm text-gray-500">Masukkan kata sandi baru untuk akun kamu.</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Alamat Email
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $email ?? '') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-800 bg-[#F4F7FA] focus:outline-none focus:ring-2 focus:ring-[#0A52C4]/20 focus:border-[#0A52C4] transition-all"
                        >
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Kata Sandi Baru
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-800 placeholder-gray-400 bg-[#F4F7FA] focus:outline-none focus:ring-2 focus:ring-[#0A52C4]/20 focus:border-[#0A52C4] transition-all"
                        >
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Konfirmasi Kata Sandi
                        </label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Ulangi kata sandi baru"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-800 placeholder-gray-400 bg-[#F4F7FA] focus:outline-none focus:ring-2 focus:ring-[#0A52C4]/20 focus:border-[#0A52C4] transition-all"
                        >
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3 px-4 bg-[#F28C28] hover:bg-[#E07D1C] text-white font-bold rounded-xl transition-all hover:shadow-lg hover:-translate-y-0.5"
                        style="box-shadow: 0 4px 14px rgba(242,140,40,0.35);"
                    >
                        Simpan Kata Sandi Baru
                    </button>
                </form>

                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-[#0A52C4] transition-colors">
                        Kembali ke halaman masuk
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-layouts.guest>
