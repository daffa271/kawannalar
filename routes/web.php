<?php

use App\Http\Controllers\AdminMentorVerificationController;
use App\Http\Controllers\Mentor\UjiNalarController as MentorUjiNalarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuangNalarController;
use App\Http\Controllers\Siswa\UjiNalarController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\Mentor\DashboardController as MentorDashboardController;
use App\Http\Controllers\TemanNalarController;
use App\Http\Controllers\BookingMentoringController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\MentorTemanNalarController;
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

Route::get('/suspended', function () {
    return view('errors.suspended');
})->name('suspended');

Route::view('/register/pending', 'auth.pending-mentor')->name('register.pending');

Route::middleware(['auth', 'role:siswa'])->get('/dashboard/siswa', [StudentDashboardController::class, 'index'])->name('dashboard.siswa');
Route::middleware(['auth', 'role:mentor'])->get('/dashboard/mentor', [MentorDashboardController::class, 'index'])->name('dashboard.mentor');
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/ruang-nalar', [RuangNalarController::class, 'index'])->name('siswa.ruang-nalar.index');
    Route::get('/siswa/ruang-nalar/create', [RuangNalarController::class, 'createStudent'])->name('siswa.ruang-nalar.create');
    Route::post('/siswa/ruang-nalar', [RuangNalarController::class, 'store'])->name('siswa.ruang-nalar.store');
    Route::get('/ruang-nalar/{module}/download', [RuangNalarController::class, 'download'])->name('siswa.ruang-nalar.download');
    Route::get('/ruang-nalar/{module}/preview', [RuangNalarController::class, 'preview'])->name('siswa.ruang-nalar.preview');

    // Uji Nalar
    Route::get('/uji-nalar', [UjiNalarController::class, 'index'])->name('siswa.uji-nalar.index');
    Route::get('/uji-nalar/{quiz}', [UjiNalarController::class, 'show'])->name('siswa.uji-nalar.show');
    Route::post('/uji-nalar/{quiz}/submit', [UjiNalarController::class, 'submit'])->name('siswa.uji-nalar.submit');

    // Teman Nalar
    Route::get('/teman-nalar', [TemanNalarController::class, 'index'])->name('siswa.teman-nalar.index');
    Route::post('/teman-nalar/booking', [BookingMentoringController::class, 'store'])->name('siswa.teman-nalar.booking.store');
});
Route::middleware(['auth', 'role:mentor'])->group(function () {
    Route::get('/mentor/ruang-nalar', [RuangNalarController::class, 'mentorIndex'])->name('mentor.ruang-nalar.index');
    Route::get('/mentor/ruang-nalar/create', [RuangNalarController::class, 'create'])->name('mentor.ruang-nalar.create');
    Route::post('/mentor/ruang-nalar', [RuangNalarController::class, 'store'])->name('mentor.ruang-nalar.store');
    Route::get('/mentor/ruang-nalar/{module}/download', [RuangNalarController::class, 'download'])->name('mentor.ruang-nalar.download');
    Route::get('/mentor/ruang-nalar/{module}/preview', [RuangNalarController::class, 'preview'])->name('mentor.ruang-nalar.preview');

    // Uji Nalar — Mentor
    Route::get('/mentor/uji-nalar', [MentorUjiNalarController::class, 'index'])->name('mentor.uji-nalar.index');
    Route::get('/mentor/uji-nalar/create', [MentorUjiNalarController::class, 'create'])->name('mentor.uji-nalar.create');
    Route::post('/mentor/uji-nalar', [MentorUjiNalarController::class, 'store'])->name('mentor.uji-nalar.store');
    Route::get('/mentor/uji-nalar/{quiz}', [MentorUjiNalarController::class, 'show'])->name('mentor.uji-nalar.show');

    // Sesi Mentoring
    Route::get('/mentor/teman-nalar', [MentorTemanNalarController::class, 'index'])->name('mentor.teman-nalar.index');
    Route::post('/mentor/teman-nalar/slot', [MentorTemanNalarController::class, 'storeSlot'])->name('mentor.teman-nalar.slot.store');
    Route::delete('/mentor/teman-nalar/slot/{id}', [MentorTemanNalarController::class, 'destroySlot'])->name('mentor.teman-nalar.slot.destroy');
    Route::patch('/mentor/teman-nalar/booking/{id}/approve', [MentorTemanNalarController::class, 'approveBooking'])->name('mentor.teman-nalar.booking.approve');
    Route::patch('/mentor/teman-nalar/booking/{id}/reject', [MentorTemanNalarController::class, 'rejectBooking'])->name('mentor.teman-nalar.booking.reject');
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

    // Admin — Moderasi Paket Soal
    Route::get('/admin/quizzes', [AdminMentorVerificationController::class, 'quizzes'])->name('admin.quizzes.index');
    Route::patch('/admin/quizzes/{quiz}/approve', [AdminMentorVerificationController::class, 'approveQuiz'])->name('admin.quizzes.approve');
    Route::patch('/admin/quizzes/{quiz}/reject', [AdminMentorVerificationController::class, 'rejectQuiz'])->name('admin.quizzes.reject');

    // Admin User Management Hub
    Route::get('/admin/users/siswa', [AdminUserController::class, 'siswaIndex'])->name('admin.users.siswa');
    Route::get('/admin/users/mentor', [AdminUserController::class, 'mentorIndex'])->name('admin.users.mentor');
    Route::patch('/admin/users/{id}/toggle-suspend', [AdminUserController::class, 'toggleSuspend'])->name('admin.users.toggle-suspend');
});

// Profile — perlu auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
