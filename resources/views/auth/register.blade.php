<x-layouts.guest title="Daftar — KawanNalar">
    <div x-data="{ role: '{{ old('role', 'siswa') }}', showPassword: false, showConfirmation: false }" class="min-h-[calc(100vh-10rem)] bg-[#F4F7FA] px-4 py-8 sm:px-6 lg:py-12">
        <div class="mx-auto grid w-full max-w-6xl overflow-hidden rounded-3xl bg-white shadow-xl lg:grid-cols-[0.9fr_1.1fr]">
            <aside class="relative overflow-hidden bg-[#0F1F3D] px-6 py-8 text-white sm:px-10 lg:px-12 lg:py-12">
                <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-[#0A52C4]/30"></div>
                <div class="relative flex h-full flex-col justify-between gap-12">
                    <div>
                        <div class="mb-10 flex items-center gap-3"><img src="{{ asset('images/logokawannalar.jpeg') }}" alt="Logo KawanNalar" class="h-11 w-11 rounded-xl object-cover">
                            <div>
                                <p class="text-lg font-bold">Kawan<span class="text-[#F28C28]">Nalar</span></p>
                                <p class="text-xs text-white/60">Belajar Bersama, Raih Impian</p>
                            </div>
                        </div>
                        <p class="mb-3 text-sm font-semibold uppercase tracking-[0.18em] text-[#FFC000]">Mulai langkahmu</p>
                        <h1 class="max-w-sm text-3xl font-extrabold leading-tight sm:text-4xl">Tumbuh bersama komunitas yang percaya pada potensimu.</h1>
                        <p class="mt-5 max-w-sm text-sm leading-relaxed text-white/65">Akses mentor, latihan terarah, dan ruang belajar yang membantu kamu menembus PTN impian.</p>
                    </div>
                    <div class="border-t border-white/10 pt-6">
                        <p class="text-sm leading-relaxed text-white/80">“KawanNalar membuat persiapan kuliah terasa lebih jelas, terarah, dan tidak sendirian.”</p>
                        <p class="mt-3 text-xs font-semibold text-white/45">Komunitas pelajar Magetan</p>
                    </div>
                </div>
            </aside>
            <section class="px-6 py-8 sm:px-10 lg:px-12 lg:py-12">
                <div class="mb-7">
                    <p class="text-sm font-semibold text-[#0A52C4]">Buat akun KawanNalar</p>
                    <h2 class="mt-1 text-2xl font-extrabold text-gray-900">Bergabung dalam satu langkah.</h2>
                    <p class="mt-2 text-sm text-gray-500">Pilih jenis akun yang paling sesuai denganmu.</p>
                </div>
                <x-auth-session-status class="mb-4" :status="session('status')" />
                @if ($errors->any())<div class="mb-5 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">Periksa kembali data yang kamu masukkan.</div>@endif
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-2 rounded-xl bg-[#F4F7FA] p-1"><button type="button" @click="role = 'siswa'" :class="role === 'siswa' ? 'bg-[#0A52C4] text-white shadow-sm' : 'text-gray-500'" class="rounded-lg px-3 py-2.5 text-sm font-bold transition">🎓 Saya Siswa</button><button type="button" @click="role = 'mentor'" :class="role === 'mentor' ? 'bg-[#0A52C4] text-white shadow-sm' : 'text-gray-500'" class="rounded-lg px-3 py-2.5 text-sm font-bold transition">🤝 Saya Mentor</button></div>
                    <input type="hidden" name="role" :value="role">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2"><label for="name" class="field-label">Nama Lengkap</label><input id="name" name="name" value="{{ old('name') }}" required autocomplete="name" class="field-input" placeholder="Nama lengkap"><x-input-error :messages="$errors->get('name')" class="field-error" /></div>
                        <div><label for="email" class="field-label">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="field-input" placeholder="nama@email.com"><x-input-error :messages="$errors->get('email')" class="field-error" /></div>
                        <div><label for="whatsapp" class="field-label">WhatsApp</label><input id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" required class="field-input" placeholder="08xxxxxxxxxx"><x-input-error :messages="$errors->get('whatsapp')" class="field-error" /></div>
                        <div><label for="password" class="field-label">Password</label>
                            <div class="relative"><input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="new-password" class="field-input pr-14" placeholder="Minimal 8 karakter"><button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-400" x-text="showPassword ? 'Sembunyikan' : 'Lihat'"></button></div><x-input-error :messages="$errors->get('password')" class="field-error" />
                        </div>
                        <div><label for="password_confirmation" class="field-label">Konfirmasi Password</label>
                            <div class="relative"><input id="password_confirmation" :type="showConfirmation ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" class="field-input pr-14" placeholder="Ulangi password"><button type="button" @click="showConfirmation = !showConfirmation" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-400" x-text="showConfirmation ? 'Sembunyikan' : 'Lihat'"></button></div>
                        </div>
                    </div>
                    <div x-show="role === 'siswa'" x-cloak class="grid gap-4 sm:grid-cols-2">
                        <div><label for="school" class="field-label">Asal Sekolah</label><select id="school" name="school" :required="role === 'siswa'" class="field-input">
                                <option value="">Pilih sekolah</option>
                                <option>SMAN 1 Magetan</option>
                                <option>SMAN 2 Magetan</option>
                                <option>SMKN 1 Magetan</option>
                                <option>SMAN 1 Gorang-Gareng</option>
                                <option>Sekolah lainnya</option>
                            </select></div>
                        <div><label for="grade" class="field-label">Kelas</label><select id="grade" name="grade" :required="role === 'siswa'" class="field-input">
                                <option value="">Pilih kelas</option>
                                <option>Kelas 10</option>
                                <option>Kelas 11</option>
                                <option>Kelas 12</option>
                                <option>Alumni/Gap Year</option>
                            </select></div>
                        <div><label for="target_university" class="field-label">Target PTN</label><input id="target_university" name="target_university" value="{{ old('target_university') }}" :required="role === 'siswa'" class="field-input" placeholder="Contoh: ITS"></div>
                        <div><label for="target_major" class="field-label">Jurusan Impian</label><input id="target_major" name="target_major" value="{{ old('target_major') }}" :required="role === 'siswa'" class="field-input" placeholder="Contoh: Teknik Informatika"></div>
                    </div>
                    <div x-show="role === 'mentor'" x-cloak class="grid gap-4 sm:grid-cols-2">
                        <div><label for="university" class="field-label">Asal PTN</label><input id="university" name="university" value="{{ old('university') }}" :required="role === 'mentor'" class="field-input" placeholder="Contoh: ITS"></div>
                        <div><label for="major" class="field-label">Jurusan Saat Ini</label><input id="major" name="major" value="{{ old('major') }}" :required="role === 'mentor'" class="field-input" placeholder="Teknik Informatika"></div>
                        <div><label for="high_school" class="field-label">Asal SMA di Magetan</label><input id="high_school" name="high_school" value="{{ old('high_school') }}" :required="role === 'mentor'" class="field-input" placeholder="Nama SMA asal"></div>
                        <div><label for="graduation_year" class="field-label">Tahun Lulus SMA</label><input id="graduation_year" type="number" name="graduation_year" value="{{ old('graduation_year') }}" :required="role === 'mentor'" class="field-input" placeholder="2023"></div>
                        <div><label for="semester" class="field-label">Semester Saat Ini</label><input id="semester" name="semester" value="{{ old('semester') }}" :required="role === 'mentor'" class="field-input" placeholder="Semester 6"></div>
                        <div><label for="expertise" class="field-label">Bidang Keahlian</label><input id="expertise" name="expertise" value="{{ old('expertise') }}" :required="role === 'mentor'" class="field-input" placeholder="UTBK TPS, Literasi Bahasa"></div>
                        <div class="sm:col-span-2"><label for="ktm" class="field-label">Bukti KTM</label><input id="ktm" type="file" name="ktm" :required="role === 'mentor'" accept="image/*,.pdf" class="block w-full rounded-xl border border-gray-200 bg-[#F4F7FA] px-3 py-2.5 text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-[#0A52C4]/10 file:px-3 file:py-2 file:text-xs file:font-bold file:text-[#0A52C4]">
                            <p class="mt-1 text-xs text-gray-400">JPG, PNG, atau PDF maksimal 5 MB.</p>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-400">Dengan mendaftar, kamu menyetujui <a href="#" class="font-semibold text-[#0A52C4]">Syarat & Ketentuan</a> dan <a href="#" class="font-semibold text-[#0A52C4]">Kebijakan Privasi</a> KawanNalar.</p>
                    <button type="submit" class="w-full rounded-xl bg-[#F28C28] px-4 py-3.5 font-bold text-white transition hover:bg-[#E07D1C] hover:shadow-lg">Daftar sebagai <span x-text="role === 'siswa' ? 'Siswa' : 'Mentor'"></span></button>
                </form>
                <p class="mt-6 text-center text-sm text-gray-500">Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-[#0A52C4] hover:underline">Masuk di sini</a></p>
            </section>
        </div>
    </div>
</x-layouts.guest>