<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:siswa,mentor'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'whatsapp' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated += $request->role === 'siswa'
            ? $request->validate([
                'school' => ['required', 'string', 'max:255'],
                'grade' => ['required', 'string', 'max:50'],
                'target_university' => ['required', 'string', 'max:255'],
                'target_major' => ['required', 'string', 'max:255'],
            ])
            : $request->validate([
                'university' => ['required', 'string', 'max:255'],
                'major' => ['required', 'string', 'max:255'],
                'high_school' => ['required', 'string', 'max:255'],
                'graduation_year' => ['required', 'integer', 'between:1950,' . now()->year],
                'semester' => ['required', 'string', 'max:50'],
                'expertise' => ['required', 'string', 'max:255'],
                'ktm' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            ]);

        $user = DB::transaction(function () use ($request, $validated): User {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => $validated['role'] === 'mentor' ? 'pending' : 'active',
            ]);

            if ($validated['role'] === 'siswa') {
                $user->studentProfile()->create([
                    'whatsapp' => $validated['whatsapp'],
                    'school' => $validated['school'],
                    'grade' => $validated['grade'],
                    'target_university' => $validated['target_university'],
                    'target_major' => $validated['target_major'],
                ]);
            } else {
                $user->mentorProfile()->create([
                    'whatsapp' => $validated['whatsapp'],
                    'university' => $validated['university'],
                    'major' => $validated['major'],
                    'high_school' => $validated['high_school'],
                    'graduation_year' => $validated['graduation_year'],
                    'semester' => $validated['semester'],
                    'expertise' => $validated['expertise'],
                    'ktm_path' => $request->file('ktm')->store('mentor-ktm', 'public'),
                ]);
            }

            return $user;
        });

        event(new Registered($user));

        return $user->role === 'mentor'
            ? redirect()->route('register.pending')
            : redirect()->route('login')->with('status', 'Pendaftaran berhasil! Silakan masuk dengan akun Anda.');
    }
}
