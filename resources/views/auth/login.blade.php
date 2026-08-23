<x-layouts.guest title="Masuk — KawanNalar">
    <div class="min-h-[calc(100vh-10rem)] bg-[#F4F7FA] px-4 py-8 sm:px-6 lg:py-12">
        <div class="mx-auto grid w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-xl lg:grid-cols-2">
            <aside class="hidden flex-col justify-between bg-[#0F1F3D] p-10 text-white lg:flex lg:p-12">
                <div><img src="{{ asset('images/logokawannalar.jpeg') }}" alt="Logo KawanNalar" class="h-12 w-12 rounded-xl object-cover">
                    <p class="mt-10 text-sm font-bold uppercase tracking-[0.18em] text-[#FFC000]">KawanNalar</p>
                    <h1 class="mt-3 text-4xl font-extrabold leading-tight">Kembali melanjutkan langkah menuju PTN impian.</h1>
                    <p class="mt-5 text-sm leading-relaxed text-white/65">Satu akun untuk belajar, bertanya, dan bertumbuh bersama mentor.</p>
                </div>
                <p class="border-t border-white/10 pt-6 text-sm text-white/60">Belajar bersama, raih impian.</p>
            </aside>
            <main class="w-full max-w-md justify-self-center p-7 sm:p-10 lg:p-12">
                <div class="mb-8">
                    <p class="text-sm font-semibold text-[#0A52C4]">Selamat datang kembali</p>
                    <h2 class="mt-1 text-2xl font-extrabold text-gray-900">Masuk ke KawanNalar</h2>
                    <p class="mt-2 text-sm text-gray-500">Lanjutkan perjalanan belajarmu hari ini.</p>
                </div>
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div><label for="email" class="field-label">Email atau Username</label><input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="field-input" placeholder="nama@email.com"><x-input-error :messages="$errors->get('email')" class="field-error" /></div>
                    <div x-data="{ visible: false }"><label for="password" class="field-label">Password</label>
                        <div class="relative"><input id="password" :type="visible ? 'text' : 'password'" name="password" required autocomplete="current-password" class="field-input pr-14" placeholder="Masukkan password"><button type="button" @click="visible = !visible" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-400" x-text="visible ? 'Sembunyikan' : 'Lihat'"></button></div><x-input-error :messages="$errors->get('password')" class="field-error" />
                    </div>
                    <div class="flex items-center justify-between gap-3"><label for="remember_me" class="inline-flex items-center gap-2 text-sm text-gray-600"><input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-[#0A52C4] focus:ring-[#0A52C4]/20">Ingat Saya</label>@if (Route::has('password.request'))<a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#0A52C4] hover:underline">Lupa Password?</a>@endif</div>
                    <button type="submit" class="w-full rounded-xl bg-[#F28C28] px-4 py-3.5 font-bold text-white transition hover:bg-[#E07D1C] hover:shadow-lg">Masuk ke KawanNalar</button>
                </form>
                <div class="my-7 flex items-center gap-4">
                    <div class="h-px flex-1 bg-gray-200"></div><span class="text-xs text-gray-400">atau</span>
                    <div class="h-px flex-1 bg-gray-200"></div>
                </div>
                <p class="text-center text-sm text-gray-500">Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-[#0A52C4] hover:underline">Daftar Gratis</a></p>
                <p class="mt-7 text-center"><a href="{{ route('landing') }}" class="text-sm text-gray-400 hover:text-[#0A52C4]">Kembali ke Beranda</a></p>
            </main>
        </div>
    </div>
</x-layouts.guest>