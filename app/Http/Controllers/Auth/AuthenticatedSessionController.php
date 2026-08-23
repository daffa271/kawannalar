<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();

        if (in_array($user->status, ['pending', 'rejected'], true)) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => $user->status === 'pending'
                    ? 'Akun Mentor Anda masih dalam proses verifikasi Admin. Silakan tunggu konfirmasi via WhatsApp.'
                    : 'Pendaftaran mentor Anda ditolak oleh Admin. Silakan hubungi tim KawanNalar.',
            ]);
        }

        $request->session()->regenerate();

        return match ($user->role) {
            'mentor' => redirect()->intended(route('dashboard.mentor')),
            'admin' => redirect()->intended(route('dashboard.admin')),
            default => redirect()->intended(route('dashboard.siswa')),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
