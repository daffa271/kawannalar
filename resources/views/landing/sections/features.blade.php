{{--
    Features Grid — Landing Page KawanNalar.
    8 Fitur Utama, grid 2 baris x 4 kolom (4 atas, 4 bawah) di desktop,
    2 kolom di mobile.

    CATATAN: tidak ada lagi atribut style="..." dengan interpolasi Blade
    di file ini — semua warna dinamis dipindah ke class Tailwind lewat
    $variants di bawah. Ini menghindari false-positive "at-rule or
    selector expected" dari CSS validator editor (karena style="" dibaca
    sebagai CSS mentah, sedangkan class="" tidak pernah divalidasi
    sebagai CSS oleh editor manapun).
--}}
<section id="fitur" class="bg-[#F4F7FA] py-20 scroll-mt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-[#0A52C4]/10 text-[#0A52C4] rounded-full text-sm font-semibold mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                8 Fitur Unggulan
            </span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                Ekosistem Belajar <span class="text-[#F28C28]">Lengkap</span> dalam<br>
                <span class="text-[#0A52C4]">Satu Platform</span>
            </h2>
            <p class="text-gray-600">Semua yang kamu butuhkan untuk meraih impian kuliah di PTN favorit dalam genggaman.</p>
        </div>

        {{-- Features Grid --}}
        @php
            // Palet warna per varian — literal & statis, supaya Tailwind JIT
            // bisa mendeteksinya (harus utuh tertulis di file, bukan dirakit
            // dari potongan string saat runtime).
            $variants = [
                'blue'   => ['badge' => 'bg-[#EAF0FB]', 'icon' => 'text-[#0A52C4]'],
                'orange' => ['badge' => 'bg-[#FDF0E2]', 'icon' => 'text-[#F28C28]'],
                'yellow' => ['badge' => 'bg-[#FFF6DE]', 'icon' => 'text-[#FFC000]'],
            ];

            $features = [
                [
                    'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                    'label' => 'Ruang Nalar',
                    'desc' => 'Akses & bagikan modul, ringkasan materi, dan e-book berkualitas dari mentor.',
                    'variant' => 'blue',
                ],
                [
                    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                    'label' => 'Teman Nalar',
                    'desc' => 'Belajar langsung dengan mentor mahasiswa PTN melalui kelas online & mentoring privat.',
                    'variant' => 'orange',
                ],
                [
                    'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                    'label' => 'Nalar Diskusi',
                    'desc' => 'Tanya, diskusi, dan berbagi solusi soal bersama komunitas siswa dan mentor aktif.',
                    'variant' => 'blue',
                ],
                [
                    'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                    'label' => 'NalarBot AI',
                    'desc' => 'Asisten cerdas untuk karir, prediksi PTN, dan ruang aman konseling pribadimu.',
                    'variant' => 'yellow',
                ],
                [
                    'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
                    'label' => 'Uji Nalar',
                    'desc' => 'Latihan, tryout UTBK, flip cards, dan leaderboard sekolah se-Magetan.',
                    'variant' => 'blue',
                ],
                [
                    'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
                    'label' => 'Jejak Nalar',
                    'desc' => 'Temukan jejak alumni Magetan di PTN favorit beserta tips dan strategi sukses.',
                    'variant' => 'orange',
                ],
                [
                    'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z',
                    'label' => 'Kabar Nalar',
                    'desc' => 'Info beasiswa, lomba, dan berita kampus terkini dalam satu tempat yang terupdate.',
                    'variant' => 'blue',
                ],
                [
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    'label' => 'Nalar Focus',
                    'desc' => 'Pomodoro timer dan instrumental music untuk membantumu fokus saat belajar.',
                    'variant' => 'yellow',
                ],
            ];
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6 items-stretch">
            @foreach ($features as $feature)
                @php($v = $variants[$feature['variant']])
                <div class="group h-full flex flex-col bg-white rounded-2xl p-5 lg:p-6 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    {{-- Icon --}}
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110 {{ $v['badge'] }}">
                        <svg
                            class="w-6 h-6 transition-colors {{ $v['icon'] }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}" />
                        </svg>
                    </div>

                    {{-- Label --}}
                    <h3 class="font-bold text-gray-900 text-base mb-2 group-hover:text-[#0A52C4] transition-colors">
                        {{ $feature['label'] }}
                    </h3>

                    {{-- Desc --}}
                    <p class="text-sm text-gray-500 leading-relaxed">
                        {{ $feature['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>