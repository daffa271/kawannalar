<?php

use App\Models\Question;
use App\Models\Quiz;
use App\Models\Subject;
use App\Models\User;

const POLISH_EMOJI = '/[\x{1F000}-\x{1FAFF}\x{2300}-\x{23FF}\x{2600}-\x{27BF}\x{25A0}-\x{25FF}\x{2039}\x{203A}]/u';

function polishStudent(): User
{
    return User::factory()->create(['role' => 'siswa', 'status' => 'active'])->fresh();
}

function polishQuiz(string $title, string $kelas, string $subjectCode, string $subjectName, int $count = 3): Quiz
{
    $quiz = Quiz::create([
        'mentor_id' => User::factory()->create(['role' => 'mentor', 'status' => 'active'])->id,
        'subject_id' => Subject::firstOrCreate(['code' => $subjectCode], ['name' => $subjectName])->id,
        'class_level' => $kelas,
        'title' => $title,
        'total_questions' => $count,
        'status' => 'approved',
    ]);

    foreach (range(1, $count) as $i) {
        Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => "{$title} pertanyaan {$i}",
            'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D', 'option_e' => 'E',
            'correct_answer' => 'B',
            'explanation' => "Pembahasan {$i}",
            'order' => $i,
        ]);
    }

    return $quiz;
}

/** Isi halaman Uji Nalar saja (tanpa navbar, sidebar, dan footer aplikasi). */
function polishContent(string $html): string
{
    $start = strpos($html, '<main');
    $end = strrpos($html, '<footer');

    return substr($html, $start, ($end ?: strpos($html, '</main>')) - $start);
}

it('renders the Uji Nalar home without emoji, dead links, or white text on orange', function () {
    polishQuiz('Paket Aljabar', '11', 'MTK', 'Matematika', 5);

    $content = polishContent($this->actingAs(polishStudent())->get(route('siswa.uji-nalar.index'))->assertOk()->getContent());

    expect(preg_match(POLISH_EMOJI, $content))->toBe(0)
        ->and(substr_count($content, 'viewBox="0 -960 960 960"'))->toBeGreaterThan(15)
        ->and($content)->not->toContain('href="#"')
        ->not->toContain('Berlath')
        ->toContain('Hari berlatih')
        ->and(preg_match('/class="[^"]*bg-cta\b[^"]*text-white|class="[^"]*text-white[^"]*bg-cta\b/', $content))->toBe(0);
});

it('filters Bank Soal packages by class and subject', function () {
    polishQuiz('Paket Kelas Sepuluh', '10', 'MTK', 'Matematika');
    $match = polishQuiz('Paket Kelas Sebelas', '11', 'MTK', 'Matematika');
    polishQuiz('Paket Fisika Sebelas', '11', 'FIS', 'Fisika');
    $student = polishStudent();

    $this->actingAs($student)->get(route('siswa.uji-nalar.index', ['kelas' => '11', 'subject' => $match->subject_id]))
        ->assertOk()
        ->assertSeeText('Paket Kelas Sebelas')
        ->assertDontSeeText('Paket Kelas Sepuluh')
        ->assertDontSeeText('Paket Fisika Sebelas')
        ->assertSee('<option value="11" selected>', false)
        ->assertSee('<option value="'.$match->subject_id.'" selected>', false)
        ->assertSeeText('Filter: Kelas 11 · Matematika')
        ->assertSeeText('Reset filter')
        ->assertSee('action="'.route('siswa.uji-nalar.index').'#paket-soal"', false);

    // Nilai tak dikenal diabaikan: semua paket tampil, tanpa tautan reset.
    $this->actingAs($student)->get(route('siswa.uji-nalar.index', ['kelas' => '99', 'subject' => 'abc']))
        ->assertOk()
        ->assertSeeText('Paket Kelas Sepuluh')
        ->assertSeeText('Paket Kelas Sebelas')
        ->assertSeeText('Paket Fisika Sebelas')
        ->assertDontSeeText('Reset filter');

    $fisika = Subject::where('code', 'FIS')->value('id');
    $this->actingAs($student)->get(route('siswa.uji-nalar.index', ['kelas' => '10', 'subject' => $fisika]))
        ->assertOk()
        ->assertSeeText('Belum ada paket soal untuk filter ini.');
});

it('guards quiz submission with a time warning, auto-submit, and an unanswered-question confirmation', function () {
    $quiz = polishQuiz('Paket Waktu', '12', 'MTK', 'Matematika');
    $student = polishStudent();

    foreach ([route('siswa.uji-nalar.show', $quiz), route('siswa.uji-nalar.kilat', 5)] as $url) {
        if (str_contains($url, 'kilat')) {
            polishQuiz('Paket Kilat', '12', 'MTK', 'Matematika', 5);
        }

        $content = polishContent($this->actingAs($student)->get($url)->assertOk()->getContent());

        expect(preg_match(POLISH_EMOJI, $content))->toBe(0)
            ->and($content)->toContain('get warning() { return this.timeLeft <= 60; }')
            ->toContain('this.$refs.form.submit();')
            ->toContain('if (this.timeLeft <= 0) { clearInterval(this.timer); this.timeUp(); }')
            ->toContain('Masih ada ${unanswered} soal belum dijawab.')
            ->toContain('Tetap Kumpulkan')
            ->toContain('Waktu habis')
            ->not->toContain('bg-[#22C55E]')
            ->and(preg_match('/class="[^"]*bg-cta\b[^"]*text-white/', $content))->toBe(0);
    }
});

it('shows results with icons, an unanswered marker, and plain retry labels', function () {
    $quiz = polishQuiz('Paket Hasil', '12', 'MTK', 'Matematika');
    $student = polishStudent();

    $content = polishContent($this->actingAs($student)
        ->post(route('siswa.uji-nalar.submit', $quiz), ['answers' => [$quiz->questions->first()->id => 'B']])
        ->assertOk()
        ->getContent());

    expect(preg_match(POLISH_EMOJI, $content))->toBe(0)
        ->and($content)->toContain('Ulangi Quiz')
        ->toContain('Tidak dijawab')
        ->toContain('Kembali ke Uji Nalar');

    polishQuiz('Paket Kilat', '12', 'MTK', 'Matematika', 5);
    $this->actingAs($student)->get(route('siswa.uji-nalar.kilat', 5))->assertOk();

    $this->actingAs($student)->post(route('siswa.uji-nalar.kilat.submit'), ['answers' => []])
        ->assertOk()
        ->assertSeeText('Latihan Kilat Lagi');
});
