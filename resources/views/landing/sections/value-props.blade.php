<section class="relative z-10 mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 gap-0 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm lg:grid-cols-4">
        @php
        $pills = [
        [
        'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'title' => '100% Gratis',
        'subtitle' => 'Tanpa biaya apapun',
        'color_class' => 'bg-cta/10 text-cta',
        ],
        [
        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'title' => 'Mentor PTN',
        'subtitle' => 'Mahasiswa PTN Favorit',
        'color_class' => 'bg-primary/10 text-primary',
        ],
        [
        'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'title' => 'AI Assistant',
        'subtitle' => 'Bantu belajar 24/7',
        'color_class' => 'bg-primary/10 text-primary',
        ],
        [
        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'title' => 'Akses Kapan Saja',
        'subtitle' => 'di Mana Saja & Kapan Saja',
        'color_class' => 'bg-accent/20 text-accent-dark',
        ],
        ];
        @endphp

        @foreach ($pills as $pill)
        <div class="group flex items-center gap-3 border-b border-gray-100 p-4 last:border-0 even:border-l lg:border-b-0 lg:border-l lg:first:border-0">
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full transition-colors {{ $pill['color_class'] }}">
                <svg
                    class="h-5 w-5 transition-colors"
                    aria-hidden="true"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $pill['icon'] }}" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm group-hover:text-[#0A52C4] transition-colors">{{ $pill['title'] }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $pill['subtitle'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>