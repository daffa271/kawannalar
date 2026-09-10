{{-- 
    Forgot Password Page — KawanNalar.
--}}
<x-layouts.guest title="Lupa Kata Sandi — KawanNalar">

    <div class="min-h-[calc(100vh-10rem)] flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xl p-8">

                {{-- Header --}}
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">
                        Lupa Kata Sandi?
                    </h1>

                    <p class="text-sm text-gray-500 leading-relaxed">
                        Tenang, kami bantu reset. Masukkan email yang terdaftar di akun KawanNalar kamu.
                    </p>
                </div>

                {{-- Success Message --}}
                @if (session('status'))
                    <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3">
                        <div class="flex items-start gap-3">
                            <svg
                                class="w-5 h-5 mt-0.5 text-green-600 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                            <div>
                                <p class="text-sm font-semibold text-green-700">
                                    Link reset kata sandi telah dikirim.
                                </p>

                                <p class="text-xs text-green-600 mt-1 leading-relaxed">
                                    Silakan periksa email kamu dan klik link yang kami kirim untuk membuat kata sandi baru.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- General Error Message --}}
                @if ($errors->any() && !$errors->has('email'))
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                        <div class="flex items-start gap-3">
                            <svg
                                class="w-5 h-5 mt-0.5 text-red-600 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71 3L13.71 3.86z"
                                />
                            </svg>

                            <div>
                                <p class="text-sm font-semibold text-red-700">
                                    Terjadi kesalahan.
                                </p>

                                <p class="text-xs text-red-600 mt-1">
                                    Silakan coba lagi beberapa saat.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Forgot Password Form --}}
                <form
                    method="POST"
                    action="{{ route('password.email') }}"
                    class="space-y-5"
                >
                    @csrf

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
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="nama@email.com"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-800 placeholder-gray-400 bg-[#F4F7FA] focus:outline-none focus:ring-2 focus:ring-[#0A52C4]/20 focus:border-[#0A52C4] transition-all"
                        >

                        {{-- Email Validation Error --}}
                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-1.5"
                        />
                    </div>

                    {{-- Submit Button --}}
                    <button
                        type="submit"
                        class="w-full py-3 px-4 bg-[#F28C28] hover:bg-[#E07D1C] text-white font-bold rounded-xl transition-all hover:shadow-lg hover:-translate-y-0.5"
                        style="box-shadow: 0 4px 14px rgba(242,140,40,0.35);"
                    >
                        <span class="flex items-center justify-center gap-2">
                            Kirim Link Reset

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

</x-layouts.guest>