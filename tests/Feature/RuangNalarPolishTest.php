<?php

use App\Models\MentorProfile;
use App\Models\Module;
use App\Models\StudentProfile;
use App\Models\User;

const RN_EMOJI = '/[\x{1F000}-\x{1FAFF}\x{2300}-\x{23FF}\x{2600}-\x{27BF}\x{25A0}-\x{25FF}]/u';

function rnUser(string $role, array $attributes = []): User
{
    return User::factory()->create(array_merge(['role' => $role, 'status' => 'active'], $attributes))->fresh();
}

function rnModule(User $uploader, string $status, array $attributes = []): Module
{
    return Module::create(array_merge([
        'title' => 'Modul '.uniqid(),
        'description' => 'Ringkasan materi',
        'subject' => 'Matematika',
        'grade' => 'Kelas 12',
        'file_path' => 'modules/contoh.pdf',
        'uploaded_by' => $uploader->id,
        'status' => $status,
        'download_count' => 0,
    ], $attributes));
}

/** Isi halaman tanpa navbar, sidebar, dan footer aplikasi. */
function rnContent(string $html): string
{
    $start = strpos($html, '<main');
    $end = strrpos($html, '<footer');

    return substr($html, $start, ($end ?: strpos($html, '</main>')) - $start);
}

// ── +10 XP saat disetujui ───────────────────────────────────────────────────

it('gives the uploader +10 XP exactly once when a pending module is approved', function () {
    $admin = rnUser('admin');
    $student = rnUser('siswa');
    $module = rnModule($student, 'pending');

    $this->actingAs($admin)->patch(route('admin.modules.approve', $module))
        ->assertSessionHas('status', 'Modul berhasil disetujui dan dapat dilihat siswa. Pengunggah mendapat +10 XP.');
    expect($student->fresh()->xp_points)->toBe(10)
        ->and($module->fresh()->status)->toBe('approved');

    // Klik ganda / persetujuan ulang tidak menambah XP lagi.
    $this->actingAs($admin)->patch(route('admin.modules.approve', $module))
        ->assertSessionHas('status', 'Modul berhasil disetujui dan dapat dilihat siswa.');
    expect($student->fresh()->xp_points)->toBe(10);

    // Mentor juga mendapat XP untuk modulnya.
    $mentor = rnUser('mentor');
    $this->actingAs($admin)->patch(route('admin.modules.approve', rnModule($mentor, 'pending')));
    expect($mentor->fresh()->xp_points)->toBe(10);

    // Modul yang bukan pending tetap bisa disetujui, tapi tanpa XP.
    $rejected = rnModule($student, 'rejected');
    $this->actingAs($admin)->patch(route('admin.modules.approve', $rejected));
    expect($rejected->fresh()->status)->toBe('approved')
        ->and($student->fresh()->xp_points)->toBe(10);
});

// ── Urutan & filter mapel ───────────────────────────────────────────────────

it('sorts the catalog by downloads when Terpopuler is chosen', function () {
    $mentor = rnUser('mentor');
    rnModule($mentor, 'approved', ['title' => 'Modul Paling Populer', 'download_count' => 50])
        ->forceFill(['created_at' => now()->subDay()])->save();
    rnModule($mentor, 'approved', ['title' => 'Modul Jarang Diunduh', 'download_count' => 1]);
    $student = rnUser('siswa');

    // Terpopuler: unduhan terbanyak dulu, walau modulnya lebih lama.
    $this->actingAs($student)->get(route('siswa.ruang-nalar.index', ['sort' => 'popular']))
        ->assertOk()
        ->assertSeeInOrder(['Modul Paling Populer', 'Modul Jarang Diunduh']);

    // Terbaru (default): modul yang paling baru dulu.
    $this->actingAs($student)->get(route('siswa.ruang-nalar.index'))
        ->assertOk()
        ->assertSeeInOrder(['Modul Jarang Diunduh', 'Modul Paling Populer']);
});

it('builds subject chips from the catalog and uses one subject list in every upload form', function () {
    $mentor = rnUser('mentor');
    rnModule($mentor, 'approved', ['subject' => 'UTBK']);
    rnModule($mentor, 'pending', ['subject' => 'Kimia']);
    $student = rnUser('siswa');

    $html = $this->actingAs($student)->get(route('siswa.ruang-nalar.index'))->assertOk()->getContent();
    expect($html)->toContain('href="'.route('siswa.ruang-nalar.index', ['subject' => 'UTBK']).'"')
        ->not->toContain('href="'.route('siswa.ruang-nalar.index', ['subject' => 'Kimia']).'"'); // belum tayang → tanpa chip

    foreach ([[$student, route('siswa.ruang-nalar.index')], [$student, route('siswa.ruang-nalar.create')], [$mentor, route('mentor.ruang-nalar.create')]] as [$user, $url]) {
        $page = $this->actingAs($user)->get($url)->assertOk()->getContent();
        foreach (Module::SUBJECTS as $subject) {
            expect($page)->toContain('<option value="'.$subject.'"');
        }
        expect($page)->not->toContain('UTBK TPS')->not->toContain('Literasi Bahasa');
    }

    $this->actingAs($mentor)->post(route('mentor.ruang-nalar.store'), ['title' => 'Coba', 'subject' => 'Penalaran Umum', 'grade' => 'Kelas 12'])
        ->assertSessionHasErrors('subject');
});

it('keeps the chosen class selected when the upload form fails validation', function () {
    $student = rnUser('siswa');

    $this->actingAs($student)->from(route('siswa.ruang-nalar.index'))->followingRedirects()
        ->post(route('siswa.ruang-nalar.store'), ['title' => 'Catatan', 'subject' => 'Fisika', 'grade' => 'Kelas 11'])
        ->assertOk()
        ->assertSee('<option value="Kelas 11" selected>', false)
        ->assertSee('<option value="Fisika" selected>', false)
        ->assertSee('uploadOpen: true', false);
});

// ── Label pengunggah ────────────────────────────────────────────────────────

it('labels student and mentor uploaders accurately without dummy campus names', function () {
    $mentor = rnUser('mentor', ['name' => 'Bima Mentor']);
    MentorProfile::create(['user_id' => $mentor->id, 'whatsapp' => '0812', 'university' => 'Universitas Airlangga', 'major' => 'Kedokteran', 'high_school' => 'SMAN 1 Magetan']);
    $student = rnUser('siswa', ['name' => 'Sinta Pelajar']);
    StudentProfile::create(['user_id' => $student->id, 'school' => 'SMAN 3 Magetan']);
    $noProfileMentor = rnUser('mentor', ['name' => 'Tanpa Profil']);
    rnModule($mentor, 'approved');
    rnModule($student, 'approved');
    rnModule($noProfileMentor, 'approved');

    $this->actingAs(rnUser('siswa'))->get(route('siswa.ruang-nalar.index'))
        ->assertOk()
        ->assertSeeText('Kak Bima Mentor')
        ->assertSeeText('Mentor · Universitas Airlangga')
        ->assertSeeText('Sinta Pelajar')
        ->assertSeeText('SMAN 3 Magetan')
        ->assertDontSeeText('Kak Sinta Pelajar')
        ->assertSeeText('Mentor · Mentor KawanNalar')
        ->assertDontSeeText('Teknik Informatika PENS');
});

// ── Pratinjau, tombol, ikon ─────────────────────────────────────────────────

it('uses the mobile-friendly preview, no dead buttons, and no emoji on student and mentor pages', function () {
    $mentor = rnUser('mentor');
    rnModule($mentor, 'approved');
    rnModule($mentor, 'rejected', ['rejection_reason' => 'File kurang jelas.']);
    $student = rnUser('siswa');
    rnModule($student, 'pending');

    foreach ([[$student, route('siswa.ruang-nalar.index')], [$mentor, route('mentor.ruang-nalar.index')]] as [$user, $url]) {
        $content = rnContent($this->actingAs($user)->get($url)->assertOk()->getContent());

        expect(preg_match(RN_EMOJI, $content))->toBe(0, $url)
            ->and($content)->toContain('x-data="modulePreview()"')
            ->toContain("\$dispatch('module-preview'")
            ->not->toContain('<iframe')
            ->not->toContain('M12 5v.01') // tombol "⋮" tanpa fungsi
            ->and(preg_match('/class="[^"]*bg-cta\b[^"]*text-white|class="[^"]*text-white[^"]*bg-cta\b/', $content))->toBe(0)
            ->and(substr_count($content, 'viewBox="0 -960 960 960"'))->toBeGreaterThan(5);
    }

    $this->actingAs($student)->get(route('siswa.ruang-nalar.index'))
        ->assertSeeText('Menunggu Review Admin');
});
