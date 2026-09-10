{{-- 
    Reset Password Page — KawanNalar.
--}}
<x-layouts.guest title="Atur Ulang Kata Sandi — KawanNalar">

    <div class="min-h-[calc(100vh-10rem)] flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xl p-8">

                {{-- Header --}}
                <div class="text-center mb-8">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#0A52C4]/10">
                        <svg
                            class="w-7 h-7 text-[#0A52C4]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-11V7a4 4 0 10-8 0v1h8z"
                            />
                        </svg>
                    </div>

                    <h1 class="text-2xl font-bold text-gray-900 mb-1">
                        Atur Ulang Kata Sandi
                    </h1>

                    <p class="text-sm text-gray-500 leading-relaxed">
                        Buat kata sandi baru untuk mengamankan kembali akun KawanNalar kamu.
                    </p>
                </div>

                {{-- Form Reset Password --}}
                <form
                    method="POST"
                    action="{{ route('password.store') }}"
                    class="space-y-5"
                >
                    @csrf

                    {{-- Token Reset --}}
                    <input
                        type="hidden"
                        name="token"
                        value="{{ request()->route('token') }}"
                    >

                    {{-- Email --}}
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-semibold text-gray-700 mb-1.5"
                        >
                            Alamat Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', request()->query('email')) }}"
                            readonly
                            required
                            autocomplete="email"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-600 bg-gray-100 cursor-not-allowed focus:outline-none"
                        >

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-1.5"
                        />
                    </div>

                    {{-- Password Baru --}}
                    <div>
                        <label
                            for="password"
                            class="block text-sm font-semibold text-gray-700 mb-1.5"
                        >
                            Kata Sandi Baru
                        </label>

                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-3 pr-12 rounded-xl border text-sm text-gray-800 placeholder-gray-400 bg-[#F4F7FA] focus:outline-none focus:ring-2 focus:ring-[#0A52C4]/20 focus:border-[#0A52C4] transition-all {{ $errors->has('password') ? 'border-red-300' : 'border-gray-200' }}"
                            >

                            <button
                                type="button"
                                onclick="togglePassword('password')"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-[#0A52C4] transition-colors"
                                aria-label="Tampilkan atau sembunyikan kata sandi"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                            </button>
                        </div>

                        {{-- Password Error --}}
                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-1.5"
                        />
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label
                            for="password_confirmation"
                            class="block text-sm font-semibold text-gray-700 mb-1.5"
                        >
                            Konfirmasi Kata Sandi
                        </label>

                        <div class="relative">
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Ulangi kata sandi baru"
                                class="w-full px-4 py-3 pr-12 rounded-xl border text-sm text-gray-800 placeholder-gray-400 bg-[#F4F7FA] focus:outline-none focus:ring-2 focus:ring-[#0A52C4]/20 focus:border-[#0A52C4] transition-all {{ $errors->has('password') ? 'border-red-300' : 'border-gray-200' }}"
                            >

                            <button
                                type="button"
                                onclick="togglePassword('password_confirmation')"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-[#0A52C4] transition-colors"
                                aria-label="Tampilkan atau sembunyikan kata sandi"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                            </button>
                        </div>

                        {{-- Confirmation Error --}}
                        @if ($errors->has('password'))
                            <p class="mt-1.5 flex items-start gap-1.5 text-xs text-red-600">
                                <svg
                                    class="w-4 h-4 mt-0.5 shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"
                                    />
                                </svg>

                                <span>
                                    {{ $errors->first('password') }}
                                </span>
                            </p>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full py-3 px-4 bg-[#F28C28] hover:bg-[#E07D1C] text-white font-bold rounded-xl transition-all hover:shadow-lg hover:-translate-y-0.5"
                        style="box-shadow: 0 4px 14px rgba(242,140,40,0.35);"
                    >
                        <span class="flex items-center justify-center gap-2">
                            Simpan Kata Sandi Baru

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"
                                />
                            </svg>
                        </span>
                    </button>
                </form>

                {{-- Back to Login --}}
                <div class="text-center mt-6">
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-[#0A52C4] transition-colors"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>

                        Kembali ke halaman masuk
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- Password Toggle --}}
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);

            if (!input) {
                return;
            }

            input.type = input.type === 'password'
                ? 'text'
                : 'password';
        }
    </script>

</x-layouts.guest>