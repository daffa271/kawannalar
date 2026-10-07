<?php

const LANDING_EMOJI = '/[\x{1F000}-\x{1FAFF}\x{2300}-\x{23FF}\x{2600}-\x{27BF}]/u';

/** Isi landing di antara navbar dan footer guest. */
function landingMain(string $html): string
{
    $start = strpos($html, '<main>');

    return substr($html, $start, strpos($html, '</main>') - $start);
}

/** Semua file view yang membentuk landing page. */
function landingViewSources(): array
{
    $files = glob(resource_path('views/landing/sections/*.blade.php'));
    $files[] = resource_path('views/components/navbar-guest.blade.php');
    $files[] = resource_path('views/components/footer-guest.blade.php');

    return array_combine(array_map('basename', $files), array_map('file_get_contents', $files));
}

it('renders the landing page without emoji, using brand vector icons', function () {
    $html = $this->get('/')->assertOk()->getContent();

    expect(preg_match(LANDING_EMOJI, $html))->toBe(0)
        ->and(substr_count(landingMain($html), 'viewBox="0 -960 960 960"'))->toBeGreaterThan(30)
        ->and($html)->not->toContain('animate-bounce')
        ->not->toContain('Solusi Nyata');
});

it('animates every landing statistic from its final, server-rendered value', function () {
    $html = $this->get('/')->assertOk()->getContent();

    foreach ([[2345, '+', '2.345+'], [340, '+', '340+'], [1200, '+', '1.200+'], [95, '%', '95%'], [73, '%', '73%'], [31, '+', '31+']] as [$to, $suffix, $text]) {
        expect($html)->toContain("<span data-count-to=\"{$to}\" data-count-suffix=\"{$suffix}\">{$text}</span>");
    }

    $script = file_get_contents(resource_path('js/modules/landing/count-up.js'));
    expect(file_get_contents(resource_path('js/app.js')))->toContain('./modules/landing/count-up.js')
        ->and($script)->toContain('IntersectionObserver')
        ->toContain('prefers-reduced-motion')
        ->toContain("new Intl.NumberFormat('id-ID')");
});

it('keeps the landing palette to brand blue, CTA orange, navy, and neutrals', function () {
    foreach (landingViewSources() as $file => $source) {
        expect(preg_match('/\b(?:bg|text|from|to|border|ring|shadow)-(?:purple|green|yellow|slate|blue|amber|emerald|pink|indigo)-\d{2,3}\b/', $source))
            ->toBe(0, "{$file} memakai warna di luar palet")
            ->and($source)->not->toContain('#FFC000')
            // Tombol oranye wajib teks navy (6,7:1), bukan putih (2,5:1).
            ->and(preg_match('/class="[^"]*bg-cta\b[^"]*text-white|class="[^"]*text-white[^"]*bg-cta\b/', $source))
            ->toBe(0, "{$file} memakai teks putih di tombol oranye");
    }

    expect(landingViewSources()['footer-guest.blade.php'])->toContain('bg-navy')
        ->and(landingViewSources()['navbar-guest.blade.php'])->toContain('bg-cta text-navy');
});

it('labels features that are not available yet as coming soon', function () {
    $main = landingMain($this->get('/')->assertOk()->getContent());

    expect(substr_count($main, '>Segera Hadir</span>'))->toBe(4)
        ->and($main)->toContain('NalarBot AI segera hadir')
        ->not->toContain('Bantu belajar 24/7');
});

it('sends the contact form to WhatsApp instead of posting to a dead endpoint', function () {
    $main = landingMain($this->get('/')->assertOk()->getContent());
    $form = substr($main, strpos($main, '<form'), strpos($main, '</form>') - strpos($main, '<form'));

    expect($form)->not->toContain('action="#"')
        ->not->toContain('method="POST"')
        ->toContain('@submit.prevent')
        ->toContain('https://wa.me/6285904300285?text=')
        ->toContain('encodeURIComponent');
});
