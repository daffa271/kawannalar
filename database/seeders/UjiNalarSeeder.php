<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UjiNalarSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. Subjects ─────────────────────────────────────────────────────────
        $subjects = [
            ['name' => 'Matematika',       'code' => 'MTK'],
            ['name' => 'Fisika',           'code' => 'FIS'],
            ['name' => 'Kimia',            'code' => 'KIM'],
            ['name' => 'Biologi',          'code' => 'BIO'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIND'],
            ['name' => 'Bahasa Inggris',   'code' => 'BING'],
            ['name' => 'Sejarah',          'code' => 'SEJ'],
            ['name' => 'Ekonomi',          'code' => 'EKO'],
        ];

        foreach ($subjects as $s) {
            Subject::firstOrCreate(['code' => $s['code']], $s);
        }

        // ─── 2. Dummy Siswa Leaderboard ──────────────────────────────────────────
        $students = [
            ['name' => 'Rizky Maulana',    'school_name' => 'SMAN 1 Magetan',  'xp_points' => 2890, 'streak_days' => 12],
            ['name' => 'Alifa Nur Aini',   'school_name' => 'SMAN 1 Magetan',  'xp_points' => 3450, 'streak_days' => 21],
            ['name' => 'Nanda Putri',      'school_name' => 'MAN 1 Magetan',   'xp_points' => 2450, 'streak_days' => 9],
            ['name' => 'Fadhil Muhammad',  'school_name' => 'SMAN 1 Magetan',  'xp_points' => 1250, 'streak_days' => 7],
            ['name' => 'Salma Azzahra',    'school_name' => 'MAN 1 Magetan',   'xp_points' => 1120, 'streak_days' => 5],
            ['name' => 'Fajar Ramadhan',   'school_name' => 'SMKN 1 Magetan',  'xp_points' => 980,  'streak_days' => 4],
            ['name' => 'Daffa Putra',      'school_name' => 'SMAN 2 Magetan',  'xp_points' => 875,  'streak_days' => 3],
            ['name' => 'Vina Aulia',       'school_name' => 'SMAN 3 Magetan',  'xp_points' => 760,  'streak_days' => 6],
            ['name' => 'Bima Aditya',      'school_name' => 'SMAN 1 Magetan',  'xp_points' => 620,  'streak_days' => 2],
            ['name' => 'Nurul Hikmah',     'school_name' => 'MAN 2 Magetan',   'xp_points' => 580,  'streak_days' => 1],
        ];

        foreach ($students as $i => $data) {
            User::firstOrCreate(
                ['email' => 'siswa' . ($i + 1) . '@kawannalar.test'],
                array_merge($data, [
                    'password'   => Hash::make('password'),
                    'role'       => 'siswa',
                    'status'     => 'active',
                ])
            );
        }

        // ─── 3. Dummy Mentor ─────────────────────────────────────────────────────
        $mentor = User::firstOrCreate(
            ['email' => 'mentor.demo@kawannalar.test'],
            [
                'name'       => 'Budi Santoso',
                'password'   => Hash::make('password'),
                'role'       => 'mentor',
                'status'     => 'active',
                'school_name'=> 'KawanNalar',
                'xp_points'  => 0,
                'streak_days'=> 0,
            ]
        );

        $mathSubject = Subject::where('code', 'MTK')->first();
        $fisSubject  = Subject::where('code', 'FIS')->first();

        // ─── 4. Paket Soal 5 Soal: Matematika Kelas 11 ──────────────────────────
        $quiz5 = Quiz::firstOrCreate(
            ['title' => 'Latihan Kilat Turunan Fungsi - Kelas 11'],
            [
                'mentor_id'      => $mentor->id,
                'subject_id'     => $mathSubject->id,
                'class_level'    => '11',
                'total_questions'=> 5,
                'status'         => 'approved',
            ]
        );

        if ($quiz5->questions()->count() === 0) {
            $questions5 = [
                [
                    'question_text' => 'Apa rumus turunan dari fungsi f(x) = u(x) · v(x)?',
                    'option_a'      => "f'(x) = u'(x) · v(x)",
                    'option_b'      => "f'(x) = u'(x) · v(x) + u(x) · v'(x)",
                    'option_c'      => "f'(x) = u(x) · v'(x)",
                    'option_d'      => "f'(x) = u'(x) / v'(x)",
                    'option_e'      => "f'(x) = u'(x) - v'(x)",
                    'correct_answer'=> 'B',
                    'explanation'   => 'Rumus perkalian dua fungsi (Product Rule): f\'(x) = u\'(x)·v(x) + u(x)·v\'(x). Setiap fungsi diturunkan secara bergantian lalu dijumlahkan.',
                    'order'         => 1,
                ],
                [
                    'question_text' => 'Turunan dari f(x) = 3x³ − 5x² + 2x − 7 adalah...',
                    'option_a'      => "f'(x) = 9x² − 10x + 2",
                    'option_b'      => "f'(x) = 9x² − 5x + 2",
                    'option_c'      => "f'(x) = 3x² − 10x + 2",
                    'option_d'      => "f'(x) = 9x² − 10x",
                    'option_e'      => "f'(x) = 9x³ − 10x + 2",
                    'correct_answer'=> 'A',
                    'explanation'   => 'Gunakan aturan pangkat: turunkan tiap suku. 3x³ → 9x², -5x² → -10x, 2x → 2, konstanta -7 → 0.',
                    'order'         => 2,
                ],
                [
                    'question_text' => 'Jika f(x) = sin(2x), maka f\'(x) = ...',
                    'option_a'      => 'cos(2x)',
                    'option_b'      => '2cos(2x)',
                    'option_c'      => '-2cos(2x)',
                    'option_d'      => 'sin(2x)',
                    'option_e'      => '2sin(2x)',
                    'correct_answer'=> 'B',
                    'explanation'   => 'Turunan sin(ax) = a·cos(ax). Jadi f\'(x) = 2·cos(2x).',
                    'order'         => 3,
                ],
                [
                    'question_text' => 'Nilai f\'(2) dari f(x) = x² + 3x − 1 adalah...',
                    'option_a'      => '5',
                    'option_b'      => '6',
                    'option_c'      => '7',
                    'option_d'      => '8',
                    'option_e'      => '9',
                    'correct_answer'=> 'C',
                    'explanation'   => "f'(x) = 2x + 3, lalu substitusi x=2: f'(2) = 2(2) + 3 = 7.",
                    'order'         => 4,
                ],
                [
                    'question_text' => 'Fungsi f(x) = e^(3x) memiliki turunan...',
                    'option_a'      => 'e^(3x)',
                    'option_b'      => '3·e^(3x)',
                    'option_c'      => '3x·e^(3x)',
                    'option_d'      => 'e^x',
                    'option_e'      => '(1/3)·e^(3x)',
                    'correct_answer'=> 'B',
                    'explanation'   => 'Turunan e^(ax) = a·e^(ax). Jadi f\'(x) = 3·e^(3x).',
                    'order'         => 5,
                ],
            ];

            foreach ($questions5 as $q) {
                Question::create(array_merge($q, ['quiz_id' => $quiz5->id]));
            }
        }

        // ─── 5. Paket Soal 10 Soal: Fisika Kelas 12 ─────────────────────────────
        $quiz10 = Quiz::firstOrCreate(
            ['title' => 'Bank Soal Fisika Modern - Kelas 12'],
            [
                'mentor_id'      => $mentor->id,
                'subject_id'     => $fisSubject->id,
                'class_level'    => '12',
                'total_questions'=> 10,
                'status'         => 'approved',
            ]
        );

        if ($quiz10->questions()->count() === 0) {
            $questions10 = [
                [
                    'question_text' => 'Energi foton cahaya dengan panjang gelombang 500 nm adalah... (h = 6,6×10⁻³⁴ J·s, c = 3×10⁸ m/s)',
                    'option_a'      => '3,96 × 10⁻¹⁹ J',
                    'option_b'      => '4,20 × 10⁻¹⁹ J',
                    'option_c'      => '3,30 × 10⁻¹⁹ J',
                    'option_d'      => '5,00 × 10⁻¹⁹ J',
                    'option_e'      => '2,64 × 10⁻¹⁹ J',
                    'correct_answer'=> 'A',
                    'explanation'   => 'E = hc/λ = (6,6×10⁻³⁴ × 3×10⁸) / (500×10⁻⁹) = 3,96×10⁻¹⁹ J',
                    'order'         => 1,
                ],
                [
                    'question_text' => 'Pernyataan yang benar tentang efek fotolistrik adalah...',
                    'option_a'      => 'Elektron terlepas bergantung pada intensitas cahaya',
                    'option_b'      => 'Elektron terlepas bergantung pada frekuensi cahaya',
                    'option_c'      => 'Energi kinetik elektron bertambah jika intensitas naik',
                    'option_d'      => 'Efek ini terjadi pada semua panjang gelombang',
                    'option_e'      => 'Waktu tunda bergantung pada intensitas',
                    'correct_answer'=> 'B',
                    'explanation'   => 'Efek fotolistrik bergantung pada frekuensi (bukan intensitas). Elektron hanya terlepas jika f ≥ f₀ (frekuensi ambang).',
                    'order'         => 2,
                ],
                [
                    'question_text' => 'Atom hidrogen berpindah dari kulit n=3 ke n=1. Panjang gelombang yang dipancarkan berada pada deret...',
                    'option_a'      => 'Balmer',
                    'option_b'      => 'Paschen',
                    'option_c'      => 'Lyman',
                    'option_d'      => 'Brackett',
                    'option_e'      => 'Pfund',
                    'correct_answer'=> 'C',
                    'explanation'   => 'Transisi ke n=1 menghasilkan deret Lyman (ultraviolet). Balmer = n=2, Paschen = n=3.',
                    'order'         => 3,
                ],
                [
                    'question_text' => 'Dalam reaksi fisi nuklir ²³⁵U, energi dihasilkan karena...',
                    'option_a'      => 'Penggabungan dua inti atom',
                    'option_b'      => 'Peluruhan alpha',
                    'option_c'      => 'Massa produk > massa reaktan',
                    'option_d'      => 'Defek massa dikonversi menjadi energi (E=mc²)',
                    'option_e'      => 'Elektron terlepas dari orbit',
                    'correct_answer'=> 'D',
                    'explanation'   => 'Energi nuklir berasal dari defek massa (Δm) yang dikonversi lewat E=Δm·c² sesuai relativitas Einstein.',
                    'order'         => 4,
                ],
                [
                    'question_text' => 'Partikel alfa dipancarkan oleh inti ₉₂²³⁸U. Inti anak yang terbentuk adalah...',
                    'option_a'      => '₉₀²³⁴Th',
                    'option_b'      => '₉₂²³⁴U',
                    'option_c'      => '₉₀²³⁸Th',
                    'option_d'      => '₈₈²³⁴Ra',
                    'option_e'      => '₉₁²³⁴Pa',
                    'correct_answer'=> 'A',
                    'explanation'   => 'Peluruhan alfa: Z berkurang 2, A berkurang 4. Jadi 92−2=90 (Th) dan 238−4=234. Hasilnya ₉₀²³⁴Th.',
                    'order'         => 5,
                ],
                [
                    'question_text' => 'Berapakah waktu paruh suatu zat radioaktif jika setelah 60 hari tersisa 1/8 bagian?',
                    'option_a'      => '10 hari',
                    'option_b'      => '15 hari',
                    'option_c'      => '20 hari',
                    'option_d'      => '25 hari',
                    'option_e'      => '30 hari',
                    'correct_answer'=> 'C',
                    'explanation'   => '1/8 = (1/2)³ berarti 3 waktu paruh. 60 hari / 3 = 20 hari per waktu paruh.',
                    'order'         => 6,
                ],
                [
                    'question_text' => 'Prinsip ketidakpastian Heisenberg menyatakan...',
                    'option_a'      => 'Kecepatan elektron tidak dapat diukur',
                    'option_b'      => 'Posisi dan momentum tidak dapat diketahui secara bersamaan secara tepat',
                    'option_c'      => 'Energi elektron selalu tetap',
                    'option_d'      => 'Massa partikel selalu berubah',
                    'option_e'      => 'Cahaya selalu berupa gelombang',
                    'correct_answer'=> 'B',
                    'explanation'   => 'Heisenberg: Δx·Δp ≥ h/4π. Semakin tepat posisi diketahui, semakin tidak pasti momentumnya, dan sebaliknya.',
                    'order'         => 7,
                ],
                [
                    'question_text' => 'Dualisme gelombang-partikel ditunjukkan oleh hipotesis...',
                    'option_a'      => 'Planck',
                    'option_b'      => 'Einstein',
                    'option_c'      => 'de Broglie',
                    'option_d'      => 'Bohr',
                    'option_e'      => 'Rutherford',
                    'correct_answer'=> 'C',
                    'explanation'   => 'De Broglie mengusulkan bahwa partikel materi juga memiliki sifat gelombang dengan λ = h/p.',
                    'order'         => 8,
                ],
                [
                    'question_text' => 'Panjang gelombang de Broglie elektron dengan momentum p = 2×10⁻²⁴ kg·m/s adalah... (h = 6,6×10⁻³⁴ J·s)',
                    'option_a'      => '3,3 × 10⁻¹⁰ m',
                    'option_b'      => '2,0 × 10⁻¹⁰ m',
                    'option_c'      => '1,3 × 10⁻¹⁰ m',
                    'option_d'      => '4,0 × 10⁻¹⁰ m',
                    'option_e'      => '5,5 × 10⁻¹⁰ m',
                    'correct_answer'=> 'A',
                    'explanation'   => 'λ = h/p = (6,6×10⁻³⁴)/(2×10⁻²⁴) = 3,3×10⁻¹⁰ m.',
                    'order'         => 9,
                ],
                [
                    'question_text' => 'Dalam model atom Bohr, elektron yang berpindah dari orbit luar ke orbit dalam akan...',
                    'option_a'      => 'Menyerap foton',
                    'option_b'      => 'Memancarkan foton',
                    'option_c'      => 'Tidak mengalami perubahan energi',
                    'option_d'      => 'Bertambah massanya',
                    'option_e'      => 'Berubah menjadi partikel alfa',
                    'correct_answer'=> 'B',
                    'explanation'   => 'Perpindahan ke orbit lebih dalam (energi lebih rendah) melepaskan energi dalam bentuk foton. Perpindahan ke luar = menyerap foton.',
                    'order'         => 10,
                ],
            ];

            foreach ($questions10 as $q) {
                Question::create(array_merge($q, ['quiz_id' => $quiz10->id]));
            }
        }
    }
}
