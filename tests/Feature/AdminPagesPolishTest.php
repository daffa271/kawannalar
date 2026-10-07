<?php

use App\Models\MentorProfile;
use App\Models\Module;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Blade;

const AP_EMOJI = '/[\x{1F000}-\x{1FAFF}\x{2300}-\x{23FF}\x{2600}-\x{27BF}\x{25A0}-\x{25FF}\x{2039}\x{203A}]/u';

/** Data minimal agar setiap antrean admin (mentor, modul, paket soal) tampil terisi. */
function apSeedQueues(): void
{
    $mentor = User::factory()->create(['role' => 'mentor', 'status' => 'pending', 'name' => 'Calon Mentor']);
    MentorProfile::create(['user_id' => $mentor->id, 'whatsapp' => '0812', 'university' => 'ITS', 'major' => 'Informatika', 'high_school' => 'SMAN 1 Magetan', 'ktm_path' => 'ktm/x.jpg']);

    Module::create(['title' => 'Modul Menunggu', 'subject' => 'Matematika', 'grade' => 'Kelas 12', 'file_path' => 'modules/x.pdf', 'uploaded_by' => $mentor->id, 'status' => 'pending', 'download_count' => 0]);

    $subjectId = Subject::firstOrCreate(['code' => 'MTK'], ['name' => 'Matematika'])->id;
    foreach (['pending' => 'Paket Menunggu', 'approved' => 'Paket Tayang'] as $status => $title) {
        $quiz = Quiz::create(['mentor_id' => $mentor->id, 'subject_id' => $subjectId, 'class_level' => '12', 'title' => $title, 'total_questions' => 1, 'status' => $status]);
        Question::create(['quiz_id' => $quiz->id, 'question_text' => 'Soal '.$title, 'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D', 'option_e' => 'E', 'correct_answer' => 'A', 'order' => 1]);
    }

    User::factory()->create(['role' => 'siswa', 'status' => 'active']);
}

it('renders every admin page with vector icons instead of emoji', function (string $route) {
    apSeedQueues();
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);

    $html = $this->actingAs($admin)->get(route($route))->assertOk()->getContent();

    expect(preg_match_all(AP_EMOJI, $html, $found))->toBe(0, 'Emoji tersisa: '.implode(' ', $found[0]));
})->with([
    'dashboard' => 'dashboard.admin',
    'verification' => 'admin.verification.index',
    'quizzes' => 'admin.quizzes.index',
    'users siswa' => 'admin.users.siswa',
    'users mentor' => 'admin.users.mentor',
]);

it('opens the mentor KTM in an in-page preview instead of a new tab', function () {
    apSeedQueues();
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
    $ktmUrl = route('admin.mentors.ktm', User::where('role', 'mentor')->first()->id);

    foreach (['admin.verification.index' => 2, 'dashboard.admin' => 1] as $route => $links) {
        $html = $this->actingAs($admin)->get(route($route))->assertOk()->getContent();

        preg_match_all('/<a href="'.preg_quote($ktmUrl, '/').'"[^>]*>/', $html, $anchors);
        expect($anchors[0])->toHaveCount($links);
        foreach ($anchors[0] as $anchor) {
            expect($anchor)->not->toContain('target=')->toContain("@click.prevent=\"\$dispatch('module-preview'");
        }

        expect($html)->toContain('Pratinjau KTM')->toContain(asset('js/module-preview.js'));
    }
});

it('previews a KTM as an image or a PDF based on the uploaded file', function (string $file, string $type) {
    $mentor = User::factory()->create(['role' => 'mentor', 'status' => 'pending']);
    MentorProfile::create(['user_id' => $mentor->id, 'whatsapp' => '0812', 'university' => 'ITS', 'major' => 'Informatika', 'high_school' => 'SMAN 1', 'ktm_path' => $file]);

    $html = Blade::render('<x-ktm-preview-link :mentor="$mentor">Lihat KTM</x-ktm-preview-link>', ['mentor' => $mentor->fresh()]);

    expect($html)->toContain($type)->not->toContain($type === 'image' ? 'pdf' : 'image');
})->with([
    'jpg' => ['mentor-ktm/a.jpg', 'image'],
    'png' => ['mentor-ktm/a.PNG', 'image'],
    'pdf' => ['mentor-ktm/a.pdf', 'pdf'],
]);

it('keeps the admin moderation actions after the icon swap', function () {
    apSeedQueues();
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);

    $this->actingAs($admin)->get(route('admin.quizzes.index'))
        ->assertOk()
        ->assertSeeText(['Menunggu Persetujuan', 'Setujui', 'Tolak', 'Riwayat Paket Soal Disetujui', 'Batalkan Persetujuan'])
        ->assertSee('rejectModal = true', false);

    $this->actingAs($admin)->get(route('admin.verification.index'))
        ->assertOk()
        ->assertSeeText(['Lihat KTM', 'Setujui', 'Tolak'])
        ->assertSee("\$dispatch('moderation'", false);

    $this->actingAs($admin)->get(route('dashboard.admin'))
        ->assertOk()
        ->assertSeeText(['Verifikasi Mentor & Modul', 'Moderasi Uji Nalar', 'Lihat Semua', 'System Health & Platform Status']);
});
