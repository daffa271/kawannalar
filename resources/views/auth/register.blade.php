<x-layouts.guest title="Daftar — KawanNalar">

    <div
        x-data="{
            role: @js(old('role', 'siswa')),
            showPassword: false,
            showConfirmation: false
        }"
        class="min-h-[calc(100dvh-10rem)] bg-[#F4F7FA] px-4 py-6 sm:px-6 lg:flex lg:items-center lg:justify-center lg:py-6"
    >

        {{-- =========================================================
            REGISTER CARD
            Desktop:
            - Tinggi mengikuti viewport
            - Maksimal 720px
            - Minimal 600px
        ========================================================== --}}
        <div
            class="
                mx-auto
                grid
                w-full
                max-w-6xl
                overflow-hidden
                rounded-3xl
                bg-white
                shadow-xl

                lg:h-[calc(100dvh-8rem)]
                lg:min-h-[600px]
                lg:max-h-[720px]

                lg:grid-cols-[0.9fr_1.1fr]
            "
        >

            {{-- =====================================================
                LEFT : ILLUSTRATION
                Tinggi panel selalu mengikuti tinggi card.
            ====================================================== --}}
            <aside
                class="
                    relative
                    hidden
                    h-full
                    min-h-0
                    overflow-hidden
                    bg-[#0F1F3D]
                    lg:block
                "
            >

                <img
                    src="{{ asset('images/sideimageregist.png') }}"
                    alt="Ilustrasi pendaftaran KawanNalar"
                    class="
                        absolute
                        inset-0
                        h-full
                        w-full
                        object-cover
                        object-top
                    "
                >

            </aside>


            {{-- =====================================================
                RIGHT : REGISTER FORM
                Desktop:
                - Tinggi tetap mengikuti card
                - Scroll hanya di area form
                - Ilustrasi kiri tidak ikut memanjang
            ====================================================== --}}
            <section
                class="
                    flex
                    min-h-0
                    h-full
                    flex-col
                    overflow-y-auto

                    px-5
                    py-7

                    sm:px-8
                    sm:py-8

                    lg:px-10
                    lg:py-8
                "
            >

                {{-- =================================================
                    HEADER
                ================================================== --}}
                <div class="mb-6 shrink-0">

                    <p class="text-sm font-semibold leading-5 text-[#0A52C4]">
                        Buat akun KawanNalar
                    </p>

                    <h2 class="mt-1 text-2xl font-extrabold leading-tight text-gray-900">
                        Bergabung dalam satu langkah.
                    </h2>

                    <p class="mt-2 text-sm leading-5 text-gray-500">
                        Pilih jenis akun yang paling sesuai denganmu.
                    </p>

                </div>


                {{-- =================================================
                    SESSION STATUS
                ================================================== --}}
                <x-auth-session-status
                    class="mb-4"
                    :status="session('status')"
                />


                {{-- =================================================
                    GENERAL ERROR
                ================================================== --}}
                @if ($errors->any())
                    <div
                        class="
                            mb-4
                            shrink-0
                            rounded-xl
                            border
                            border-red-100
                            bg-red-50
                            px-4
                            py-3
                            text-sm
                            leading-5
                            text-red-700
                        "
                    >
                        Periksa kembali data yang kamu masukkan.
                    </div>
                @endif


                {{-- =================================================
                    REGISTER FORM
                ================================================== --}}
                <form
                    method="POST"
                    action="{{ route('register') }}"
                    enctype="multipart/form-data"
                    class="flex-1 space-y-4"
                >

                    @csrf


                    {{-- =================================================
                        ROLE SELECTOR
                    ================================================== --}}
                    <div
                        class="
                            grid
                            grid-cols-2
                            rounded-xl
                            bg-[#F4F7FA]
                            p-1
                        "
                    >

                        {{-- Siswa --}}
                        <button
                            type="button"
                            @click="role = 'siswa'"
                            :class="
                                role === 'siswa'
                                    ? 'bg-[#0A52C4] text-white shadow-sm'
                                    : 'text-gray-500 hover:text-gray-700'
                            "
                            class="
                                rounded-lg
                                px-3
                                py-2.5
                                text-sm
                                font-bold
                                leading-5
                                transition
                            "
                        >
                            Saya Siswa
                        </button>


                        {{-- Mentor --}}
                        <button
                            type="button"
                            @click="role = 'mentor'"
                            :class="
                                role === 'mentor'
                                    ? 'bg-[#0A52C4] text-white shadow-sm'
                                    : 'text-gray-500 hover:text-gray-700'
                            "
                            class="
                                rounded-lg
                                px-3
                                py-2.5
                                text-sm
                                font-bold
                                leading-5
                                transition
                            "
                        >
                            Saya Mentor
                        </button>

                    </div>


                    {{-- =================================================
                        ROLE HIDDEN FIELD
                    ================================================== --}}
                    <input
                        type="hidden"
                        name="role"
                        :value="role"
                    >


                    {{-- =================================================
                        COMMON USER INFORMATION
                    ================================================== --}}
                    <div class="grid gap-4 sm:grid-cols-2">

                        {{-- Nama --}}
                        <div class="sm:col-span-2">

                            <label
                                for="name"
                                class="field-label"
                            >
                                Nama Lengkap
                            </label>

                            <input
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autocomplete="name"
                                class="field-input"
                                placeholder="Nama lengkap"
                            >

                            <x-input-error
                                :messages="$errors->get('name')"
                                class="field-error"
                            />

                        </div>


                        {{-- Email --}}
                        <div>

                            <label
                                for="email"
                                class="field-label"
                            >
                                Email
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="username"
                                class="field-input"
                                placeholder="nama@email.com"
                            >

                            <x-input-error
                                :messages="$errors->get('email')"
                                class="field-error"
                            />

                        </div>


                        {{-- WhatsApp --}}
                        <div>

                            <label
                                for="whatsapp"
                                class="field-label"
                            >
                                WhatsApp
                            </label>

                            <input
                                id="whatsapp"
                                name="whatsapp"
                                value="{{ old('whatsapp') }}"
                                required
                                class="field-input"
                                placeholder="08xxxxxxxxxx"
                            >

                            <x-input-error
                                :messages="$errors->get('whatsapp')"
                                class="field-error"
                            />

                        </div>


                        {{-- Password --}}
                        <div>

                            <label
                                for="password"
                                class="field-label"
                            >
                                Password
                            </label>

                            <div class="relative">

                                <input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    class="field-input pr-20"
                                    placeholder="Minimal 8 karakter"
                                >

                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="
                                        absolute
                                        right-3
                                        top-1/2
                                        -translate-y-1/2
                                        text-xs
                                        font-semibold
                                        leading-4
                                        text-gray-400
                                        transition
                                        hover:text-[#0A52C4]
                                    "
                                    x-text="
                                        showPassword
                                            ? 'Sembunyikan'
                                            : 'Lihat'
                                    "
                                ></button>

                            </div>

                            <x-input-error
                                :messages="$errors->get('password')"
                                class="field-error"
                            />

                        </div>


                        {{-- Konfirmasi Password --}}
                        <div>

                            <label
                                for="password_confirmation"
                                class="field-label"
                            >
                                Konfirmasi Password
                            </label>

                            <div class="relative">

                                <input
                                    id="password_confirmation"
                                    :type="showConfirmation ? 'text' : 'password'"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    class="field-input pr-20"
                                    placeholder="Ulangi password"
                                >

                                <button
                                    type="button"
                                    @click="showConfirmation = !showConfirmation"
                                    class="
                                        absolute
                                        right-3
                                        top-1/2
                                        -translate-y-1/2
                                        text-xs
                                        font-semibold
                                        leading-4
                                        text-gray-400
                                        transition
                                        hover:text-[#0A52C4]
                                    "
                                    x-text="
                                        showConfirmation
                                            ? 'Sembunyikan'
                                            : 'Lihat'
                                    "
                                ></button>

                            </div>

                            <x-input-error
                                :messages="$errors->get('password_confirmation')"
                                class="field-error"
                            />

                        </div>

                    </div>


                    {{-- =================================================
                        FORM SISWA
                    ================================================== --}}
                    <div
                        x-show="role === 'siswa'"
                        x-cloak
                        class="grid gap-4 sm:grid-cols-2"
                    >

                        {{-- Asal Sekolah --}}
                        <div>

                            <label
                                for="school"
                                class="field-label"
                            >
                                Asal Sekolah
                            </label>

                            <input
                                id="school"
                                type="text"
                                name="school"
                                value="{{ old('school') }}"
                                :required="role === 'siswa'"
                                class="field-input"
                                placeholder="Contoh: SMAN 1 Magetan"
                                autocomplete="organization"
                            >

                            <p class="mt-1 text-xs leading-4 text-gray-400">
                                Tulis nama lengkap sekolahmu.
                            </p>

                            <x-input-error
                                :messages="$errors->get('school')"
                                class="field-error"
                            />

                        </div>


                        {{-- Kelas --}}
                        <div>

                            <label
                                for="grade"
                                class="field-label"
                            >
                                Kelas
                            </label>

                            <select
                                id="grade"
                                name="grade"
                                :required="role === 'siswa'"
                                class="field-input"
                            >

                                <option value="">
                                    Pilih kelas
                                </option>

                                <option
                                    value="Kelas 10"
                                    @selected(old('grade') === 'Kelas 10')
                                >
                                    Kelas 10
                                </option>

                                <option
                                    value="Kelas 11"
                                    @selected(old('grade') === 'Kelas 11')
                                >
                                    Kelas 11
                                </option>

                                <option
                                    value="Kelas 12"
                                    @selected(old('grade') === 'Kelas 12')
                                >
                                    Kelas 12
                                </option>

                                <option
                                    value="Alumni/Gap Year"
                                    @selected(old('grade') === 'Alumni/Gap Year')
                                >
                                    Alumni/Gap Year
                                </option>

                            </select>

                            <x-input-error
                                :messages="$errors->get('grade')"
                                class="field-error"
                            />

                        </div>


                        {{-- Target PTN --}}
                        <div>

                            <label
                                for="target_university"
                                class="field-label"
                            >
                                Target PTN
                            </label>

                            <input
                                id="target_university"
                                name="target_university"
                                value="{{ old('target_university') }}"
                                :required="role === 'siswa'"
                                class="field-input"
                                placeholder="Contoh: ITS"
                            >

                            <x-input-error
                                :messages="$errors->get('target_university')"
                                class="field-error"
                            />

                        </div>


                        {{-- Jurusan Impian --}}
                        <div>

                            <label
                                for="target_major"
                                class="field-label"
                            >
                                Jurusan Impian
                            </label>

                            <input
                                id="target_major"
                                name="target_major"
                                value="{{ old('target_major') }}"
                                :required="role === 'siswa'"
                                class="field-input"
                                placeholder="Contoh: Teknik Informatika"
                            >

                            <x-input-error
                                :messages="$errors->get('target_major')"
                                class="field-error"
                            />

                        </div>

                    </div>


                    {{-- =================================================
                        FORM MENTOR
                    ================================================== --}}
                    <div
                        x-show="role === 'mentor'"
                        x-cloak
                        class="grid gap-4 sm:grid-cols-2"
                    >

                        {{-- Asal PTN --}}
                        <div>

                            <label
                                for="university"
                                class="field-label"
                            >
                                Asal PTN
                            </label>

                            <input
                                id="university"
                                name="university"
                                value="{{ old('university') }}"
                                :required="role === 'mentor'"
                                class="field-input"
                                placeholder="Contoh: ITS"
                            >

                            <x-input-error
                                :messages="$errors->get('university')"
                                class="field-error"
                            />

                        </div>


                        {{-- Jurusan --}}
                        <div>

                            <label
                                for="major"
                                class="field-label"
                            >
                                Jurusan Saat Ini
                            </label>

                            <input
                                id="major"
                                name="major"
                                value="{{ old('major') }}"
                                :required="role === 'mentor'"
                                class="field-input"
                                placeholder="Teknik Informatika"
                            >

                            <x-input-error
                                :messages="$errors->get('major')"
                                class="field-error"
                            />

                        </div>


                        {{-- Asal SMA --}}
                        <div>

                            <label
                                for="high_school"
                                class="field-label"
                            >
                                Asal SMA di Magetan
                            </label>

                            <input
                                id="high_school"
                                name="high_school"
                                value="{{ old('high_school') }}"
                                :required="role === 'mentor'"
                                class="field-input"
                                placeholder="Nama SMA asal"
                            >

                            <x-input-error
                                :messages="$errors->get('high_school')"
                                class="field-error"
                            />

                        </div>


                        {{-- Tahun Lulus --}}
                        <div>

                            <label
                                for="graduation_year"
                                class="field-label"
                            >
                                Tahun Lulus SMA
                            </label>

                            <input
                                id="graduation_year"
                                type="number"
                                name="graduation_year"
                                value="{{ old('graduation_year') }}"
                                :required="role === 'mentor'"
                                min="2000"
                                max="{{ date('Y') }}"
                                class="field-input"
                                placeholder="2023"
                            >

                            <x-input-error
                                :messages="$errors->get('graduation_year')"
                                class="field-error"
                            />

                        </div>


                        {{-- Semester --}}
                        <div>

                            <label
                                for="semester"
                                class="field-label"
                            >
                                Semester Saat Ini
                            </label>

                            <input
                                id="semester"
                                name="semester"
                                value="{{ old('semester') }}"
                                :required="role === 'mentor'"
                                class="field-input"
                                placeholder="Semester 6"
                            >

                            <x-input-error
                                :messages="$errors->get('semester')"
                                class="field-error"
                            />

                        </div>


                        {{-- Bidang Keahlian --}}
                        <div>

                            <label
                                for="expertise"
                                class="field-label"
                            >
                                Bidang Keahlian
                            </label>

                            <input
                                id="expertise"
                                name="expertise"
                                value="{{ old('expertise') }}"
                                :required="role === 'mentor'"
                                class="field-input"
                                placeholder="UTBK TPS, Literasi Bahasa"
                            >

                            <x-input-error
                                :messages="$errors->get('expertise')"
                                class="field-error"
                            />

                        </div>


                        {{-- Bukti KTM --}}
                        <div class="sm:col-span-2">

                            <label
                                for="ktm"
                                class="field-label"
                            >
                                Bukti KTM
                            </label>

                            <input
                                id="ktm"
                                type="file"
                                name="ktm"
                                :required="role === 'mentor'"
                                accept="image/*,.pdf"
                                class="
                                    block
                                    w-full
                                    rounded-xl
                                    border
                                    border-gray-200
                                    bg-[#F4F7FA]
                                    px-3
                                    py-2.5
                                    text-sm
                                    leading-5
                                    text-gray-500
                                    file:mr-3
                                    file:rounded-lg
                                    file:border-0
                                    file:bg-[#0A52C4]/10
                                    file:px-3
                                    file:py-2
                                    file:text-xs
                                    file:font-bold
                                    file:text-[#0A52C4]
                                "
                            >

                            <p class="mt-1 text-xs leading-4 text-gray-400">
                                JPG, PNG, atau PDF maksimal 5 MB.
                            </p>

                            <x-input-error
                                :messages="$errors->get('ktm')"
                                class="field-error"
                            />

                        </div>

                    </div>


                    {{-- =================================================
                        TERMS
                    ================================================== --}}
                    <p class="text-xs leading-5 text-gray-400">

                        Dengan mendaftar, kamu menyetujui

                        <a
                            href="#"
                            class="font-semibold text-[#0A52C4]"
                        >
                            Syarat & Ketentuan
                        </a>

                        dan

                        <a
                            href="#"
                            class="font-semibold text-[#0A52C4]"
                        >
                            Kebijakan Privasi
                        </a>

                        KawanNalar.

                    </p>


                    {{-- =================================================
                        SUBMIT BUTTON
                    ================================================== --}}
                    <button
                        type="submit"
                        class="
                            w-full
                            rounded-xl
                            bg-[#F28C28]
                            px-4
                            py-3.5
                            font-bold
                            leading-5
                            text-white
                            transition
                            hover:bg-[#E07D1C]
                            hover:shadow-lg
                            focus:outline-none
                            focus:ring-4
                            focus:ring-[#F28C28]/20
                        "
                    >

                        Daftar sebagai

                        <span
                            x-text="
                                role === 'siswa'
                                    ? 'Siswa'
                                    : 'Mentor'
                            "
                        ></span>

                    </button>

                </form>


                {{-- =================================================
                    LOGIN LINK
                ================================================== --}}
                <p class="mt-5 text-center text-sm leading-5 text-gray-500">

                    Sudah punya akun?

                    <a
                        href="{{ route('login') }}"
                        class="font-bold text-[#0A52C4] hover:underline"
                    >
                        Masuk di sini
                    </a>

                </p>

            </section>

        </div>

    </div>

</x-layouts.guest>