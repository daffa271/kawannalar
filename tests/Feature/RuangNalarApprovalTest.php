<?php

use App\Models\Module;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('approves uploaded materials before students can see them', function () {
    Storage::fake('public');

    $admin = User::factory()->create([
        'role' => 'admin',
        'status' => 'active',
    ]);

    $student = User::factory()->create([
        'role' => 'siswa',
        'status' => 'active',
    ]);

    $module = Module::create([
        'title' => 'Modul Draft Siswa',
        'description' => 'Belum disetujui',
        'subject' => 'Matematika',
        'grade' => 'Kelas 12',
        'file_path' => 'modules/test.pdf',
        'uploaded_by' => $student->id,
        'status' => 'pending',
        'download_count' => 0,
    ]);

    $this->actingAs($student)
        ->get(route('siswa.ruang-nalar.index'))
        ->assertSeeText('Modul Draft Siswa')
        ->assertSeeText('Menunggu Review');

    $this->actingAs($admin)
        ->patch(route('admin.modules.approve', $module))
        ->assertRedirect();

    $this->assertDatabaseHas('modules', ['id' => $module->id, 'status' => 'approved']);

    $this->actingAs($student)
        ->get(route('siswa.ruang-nalar.index'))
        ->assertSeeText('Modul Draft Siswa');
});
