<section class="relative z-10 mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 gap-0 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm lg:grid-cols-4">
        @php
        $pills = [
            ['icon' => 'money_off', 'title' => '100% Gratis', 'subtitle' => 'Tanpa biaya apapun'],
            ['icon' => 'school', 'title' => 'Mentor PTN', 'subtitle' => 'Mahasiswa PTN Favorit'],
            ['icon' => 'smart_toy', 'title' => 'NalarBot AI', 'subtitle' => 'Segera hadir'],
            ['icon' => 'devices', 'title' => 'Akses Kapan Saja', 'subtitle' => 'Di mana saja, kapan saja'],
        ];
        @endphp

        @foreach ($pills as $pill)
        <div class="group flex items-center gap-3 border-b border-gray-100 p-4 last:border-0 even:border-l lg:border-b-0 lg:border-l lg:first:border-0">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                <x-icon :name="$pill['icon']" class="h-5 w-5" />
            </div>
            <div class="min-w-0">
                <p class="font-bold text-gray-900 text-sm group-hover:text-primary transition-colors">{{ $pill['title'] }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $pill['subtitle'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>
