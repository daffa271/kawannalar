<?php

use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

const DASHBOARD_EMOJI = '/[\x{1F000}-\x{1FAFF}\x{2300}-\x{23FF}\x{2600}-\x{27BF}]/u';

function dashStudent(): User
{
    return User::factory()->create(['role' => 'siswa', 'status' => 'active'])->fresh();
}

function dashModule(string $title, string $status, int $downloads, string $file = 'modules/materi.pdf'): Module
{
    return Module::create([
        'title' => $title,
        'description' => 'Materi uji',
        'subject' => 'Matematika',
        'grade' => 'Kelas 12',
        'file_path' => $file,
        'uploaded_by' => User::factory()->create(['role' => 'mentor', 'status' => 'active'])->id,
        'status' => $status,
        'download_count' => $downloads,
    ]);
}

/** Isi utama dashboard (tanpa navbar, sidebar, dan footer). */
function dashContent(string $html): string
{
    $start = strpos($html, 'Selamat Datang Kembali');
    $end = strrpos($html, '</aside>');

    return substr($html, $start, $end - $start);
}

it('shows only approved modules in the Quick Feed with separate preview and download actions', function () {
    $approved = dashModule('Ringkasan Integral', 'approved', 5);
    $image = dashModule('Peta Konsep Sel', 'approved', 3, 'modules/peta.jpg');
    dashModule('Modul Masih Pending', 'pending', 99);
    dashModule('Modul Sudah Ditolak', 'rejected', 80);

    $html = $this->actingAs(dashStudent())->get(route('dashboard.siswa'))
        ->assertOk()
        ->assertSeeText('Ringkasan Integral')
        ->assertSeeText('Peta Konsep Sel')
        ->assertDontSeeText('Modul Masih Pending')
        ->assertDontSeeText('Modul Sudah Ditolak')
        ->assertSee('x-data="modulePreview()"', false)
        ->assertSee(asset('js/module-preview.js'), false)
        ->getContent();

    // @js() menulis URL di dalam JSON.parse('...') sehingga "/" menjadi "\\\/".
    $escaped = fn (string $url) => str_replace('/', '\\\\\\/', $url);

    // Kartu tidak lagi langsung mengunduh: unduhan hanya lewat tombol "Unduh".
    expect(substr_count($html, 'href="'.route('siswa.ruang-nalar.download', $approved).'"'))->toBe(1)
        ->and($html)->toContain($escaped(route('siswa.ruang-nalar.preview', $approved)))
        ->toContain('\u0022type\u0022:\u0022pdf\u0022')
        ->toContain('\u0022type\u0022:\u0022image\u0022')
        ->and(substr_count($html, "\$dispatch('module-preview'"))->toBe(4)
        ->and(preg_match_all('/<\/svg>\s*Pratinjau\s*<\/button>/', $html))->toBe(2);

    expect($image->fresh()->download_count)->toBe(3);
});

it('previews a module inline without counting it as a download', function () {
    Storage::fake('public');
    Storage::disk('public')->put('modules/materi.pdf', '%PDF-1.4 contoh');
    $module = dashModule('Ringkasan Integral', 'approved', 5);

    $this->actingAs(dashStudent())->get(route('siswa.ruang-nalar.preview', $module))
        ->assertOk()
        ->assertHeader('Content-Disposition', 'inline');

    expect($module->fresh()->download_count)->toBe(5);
});

it('replaces the dummy scholarship banner with a Kabar Nalar coming-soon card', function () {
    $student = dashStudent();

    $this->actingAs($student)->get(route('dashboard.siswa'))
        ->assertOk()
        ->assertDontSeeText('Deadline Beasiswa Pemkab Magetan')
        ->assertDontSeeText('Sisa 2 Hari')
        ->assertSeeText('Kabar Nalar segera hadir')
        ->assertSee('href="'.route('siswa.kabar-nalar').'"', false);

    $this->actingAs($student)->get(route('siswa.kabar-nalar'))
        ->assertOk()
        ->assertSeeText('Kabar Nalar — Segera Hadir')
        ->assertSeeText('diverifikasi Admin sebelum tayang');
});

it('leaves no dead links on the dashboard and sends unfinished features to their coming-soon page', function () {
    $student = dashStudent();
    $content = dashContent($this->actingAs($student)->get(route('dashboard.siswa'))->assertOk()->getContent());

    expect($content)->not->toContain('href="#"')
        ->and(substr_count($content, 'href="'.route('siswa.nalarbot').'"'))->toBe(3)
        ->and($content)->toContain('href="'.route('siswa.nalar-focus').'"');

    $this->actingAs($student)->get(route('siswa.nalarbot'))
        ->assertOk()
        ->assertSeeText('NalarBot AI — Segera Hadir')
        ->assertSeeText('konsultasi langsung dengan mentor di Teman Nalar');

    $this->actingAs($student)->get(route('siswa.nalar-focus'))
        ->assertOk()
        ->assertSeeText('Timer Pomodoro dan musik fokus sudah bisa dipakai di Dashboard');
});

it('links Magetan Champions to the full leaderboard on Uji Nalar', function () {
    $student = dashStudent();
    $target = route('siswa.uji-nalar.index').'#peringkat';

    $html = $this->actingAs($student)->get(route('dashboard.siswa'))->assertOk()->getContent();
    expect(substr_count($html, 'href="'.$target.'"'))->toBe(2);

    $this->actingAs($student)->get(route('siswa.uji-nalar.index'))
        ->assertOk()
        ->assertSee('id="peringkat"', false)
        ->assertSeeText('Papan Peringkat Pelajar Magetan');
});

it('renders the Nalar Focus timer with playable focus music', function () {
    $this->actingAs(dashStudent())->get(route('dashboard.siswa'))
        ->assertOk()
        ->assertSee('x-data="nalarFocus()"', false)
        ->assertSee(asset('js/nalar-focus.js'), false)
        ->assertSee('<option value="lofi">Lo-fi Study</option>', false)
        ->assertSee('<option value="rain">Suara Hujan</option>', false)
        ->assertSee('<option value="deep">Deep Focus (Brown Noise)</option>', false)
        ->assertSeeText('berhenti otomatis ketika 25 menit selesai');

    $script = file_get_contents(public_path('js/nalar-focus.js'));
    expect($script)->toContain('const FOCUS_SECONDS = 25 * 60;')
        ->toContain('const BREAK_SECONDS = 5 * 60;')
        ->toContain('this.stopMusic();');
});

it('uses vector icons instead of emoji on the student dashboard and coming-soon pages', function () {
    $student = dashStudent();
    dashModule('Ringkasan Integral', 'approved', 5);

    $content = dashContent($this->actingAs($student)->get(route('dashboard.siswa'))->assertOk()->getContent());
    expect(preg_match(DASHBOARD_EMOJI, $content))->toBe(0)
        ->and($content)->toContain('viewBox="0 -960 960 960"');

    $maintenance = $this->actingAs($student)->get(route('siswa.kabar-nalar'))->assertOk()->getContent();
    $card = substr($maintenance, strpos($maintenance, 'maintenancefitur.png'));
    expect(preg_match(DASHBOARD_EMOJI, substr($card, 0, strpos($card, '</section>'))))->toBe(0);
});
