{{--
    Dashboard Siswa — KawanNalar.
    Menggunakan layout internal app dengan sidebar dan topbar.
--}}
<x-layouts.app title="Dashboard — KawanNalar">

    {{-- Welcome Card --}}
    <div class="mb-8">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">
            Selamat datang, Daffa! 👋
        </h1>
        <p class="text-gray-500 mt-1">Lanjutkan perjalananmu menuju PTN favorit.</p>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
        $quickStats = [
        ['label' => 'XP Kamu', 'value' => '2,450', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', 'color_class' => 'bg-accent/20 text-accent-dark'],
        ['label' => 'Modul Diselesaikan', 'value' => '12', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'color_class' => 'bg-primary/10 text-primary'],
        ['label' => 'Tryout Diikuti', 'value' => '5', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'color_class' => 'bg-cta/10 text-cta'],
        ['label' => 'Streak Hari', 'value' => '7 🔥', 'icon' => 'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z', 'color_class' => 'bg-red-50 text-red-500'],
        ];
        @endphp

        @foreach ($quickStats as $stat)
        <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $stat['color_class'] }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-gray-900">{{ $stat['value'] }}</p>
            <p class="text-xs text-gray-400 font-medium">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Recent Activity --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Aktivitas Terakhir</h3>
        <div class="space-y-4">
            @php
            $activities = [
            ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'text-green-500', 'bg' => 'bg-green-50', 'text' => 'Menyelesaikan Modul Logika Dasar', 'time' => '2 jam lalu'],
            ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'text-[#0A52C4]', 'bg' => 'bg-[#0A52C4]/5', 'text' => 'Konsultasi dengan Mentor UGM', 'time' => 'Kemarin'],
            ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'text-[#F28C28]', 'bg' => 'bg-[#F28C28]/5', 'text' => 'Tryout Matematika Selesai — Skor 820', 'time' => '2 hari lalu'],
            ];
            @endphp
            @foreach ($activities as $activity)
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 {{ $activity['bg'] }} rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 {{ $activity['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $activity['icon'] }}" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">{{ $activity['text'] }}</p>
                    <p class="text-xs text-gray-400">{{ $activity['time'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</x-layouts.app>