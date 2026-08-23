<?php

use App\Http\Controllers\AdminMentorVerificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuangNalarController;
use App\Http\Controllers\StudentDashboardController;
use Illuminate\Support\Facades\Route;

// Landing page — publik
Route::get('/', fn() => view('landing.index'))->name('landing');

// Compatibility entry point for existing links.
Route::get('/dashboard', function () {
    return match (request()->user()->role) {
        'admin' => redirect()->route('dashboard.admin'),
        'mentor' => redirect()->route('dashboard.mentor'),
        default => redirect()->route('dashboard.siswa'),
    };
})->middleware('auth')->name('dashboard');

Route::view('/register/pending', 'auth.pending-mentor')->name('register.pending');

Route::middleware(['auth', 'role:siswa'])->get('/dashboard/siswa', [StudentDashboardController::class, 'index'])->name('dashboard.siswa');
Route::middleware(['auth', 'role:mentor'])->get('/dashboard/mentor', fn() => view('pages.mentor.dashboard.index'))->name('dashboard.mentor');
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/ruang-nalar', [RuangNalarController::class, 'index'])->name('siswa.ruang-nalar.index');
    Route::get('/siswa/ruang-nalar/create', [RuangNalarController::class, 'createStudent'])->name('siswa.ruang-nalar.create');
    Route::post('/siswa/ruang-nalar', [RuangNalarController::class, 'store'])->name('siswa.ruang-nalar.store');
    Route::get('/ruang-nalar/{module}/download', [RuangNalarController::class, 'download'])->name('siswa.ruang-nalar.download');
});
Route::middleware(['auth', 'role:mentor'])->group(function () {
    Route::get('/mentor/ruang-nalar', [RuangNalarController::class, 'mentorIndex'])->name('mentor.ruang-nalar.index');
    Route::get('/mentor/ruang-nalar/create', [RuangNalarController::class, 'create'])->name('mentor.ruang-nalar.create');
    Route::post('/mentor/ruang-nalar', [RuangNalarController::class, 'store'])->name('mentor.ruang-nalar.store');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [AdminMentorVerificationController::class, 'dashboard'])->name('dashboard.admin');
    Route::get('/admin/verification', [AdminMentorVerificationController::class, 'index'])->name('admin.verification.index');
    Route::get('/admin/modules/{module}/file', [AdminMentorVerificationController::class, 'moduleFile'])->name('admin.modules.file');
    Route::patch('/admin/modules/{module}/approve', [AdminMentorVerificationController::class, 'approveModule'])->name('admin.modules.approve');
    Route::patch('/admin/modules/{module}/reject', [AdminMentorVerificationController::class, 'rejectModule'])->name('admin.modules.reject');
    Route::get('/admin/mentors/{id}/ktm', [AdminMentorVerificationController::class, 'ktm'])->name('admin.mentors.ktm');
    Route::patch('/admin/mentors/{id}/approve', [AdminMentorVerificationController::class, 'approve'])->name('admin.mentors.approve');
    Route::patch('/admin/mentors/{id}/reject', [AdminMentorVerificationController::class, 'reject'])->name('admin.mentors.reject');
});

// Profile — perlu auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
