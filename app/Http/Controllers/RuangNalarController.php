<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RuangNalarController extends Controller
{
    public function index(Request $request): View
    {
        $query = Module::query()->with('uploader:id,name')->where('status', 'approved');

        $query->when($request->filled('subject'), fn($builder) => $builder->where('subject', $request->string('subject')->toString()));
        $query->when($request->filled('grade'), fn($builder) => $builder->where('grade', $request->string('grade')->toString()));
        $query->when($request->filled('q'), function ($builder) use ($request): void {
            $search = $request->string('q')->toString();
            $builder->where(function ($searchQuery) use ($search): void {
                $searchQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });

        $modules = $query->latest()->paginate(12)->withQueryString();

        return view('pages.siswa.ruang-nalar.index', [
            'modules' => $modules,
            'myModules' => Module::query()->where('uploaded_by', Auth::id())->latest()->get(),
            'subjects' => Module::query()->where('status', 'approved')->distinct()->orderBy('subject')->pluck('subject'),
            'grades' => Module::query()->where('status', 'approved')->distinct()->orderBy('grade')->pluck('grade'),
            'totalDownloads' => Module::where('status', 'approved')->sum('download_count'),
        ]);
    }

    public function create(): View
    {
        return view('pages.mentor.ruang-nalar.create');
    }

    public function createStudent(): View
    {
        return view('pages.siswa.ruang-nalar.create');
    }

    public function mentorIndex(): View
    {
        $publishedModules = Module::query()
            ->where('status', 'approved')
            ->with('uploader:id,name,role')
            ->latest('approved_at')
            ->paginate(9, ['*'], 'published_page');

        $myModules = Module::query()
            ->where('uploaded_by', Auth::id())
            ->latest()
            ->get();

        return view('pages.mentor.ruang-nalar.index', compact('publishedModules', 'myModules'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'subject' => ['required', 'string', 'max:100'],
            'grade' => ['required', 'string', 'max:50'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:25600'],
        ]);

        $validated['file_path'] = $request->file('file')->store('modules', 'public');
        $validated['uploaded_by'] = $request->user()->id;
        $validated['status'] = 'pending';
        unset($validated['file']);

        Module::create($validated);

        $redirectRoute = $request->user()->role === 'mentor'
            ? 'mentor.ruang-nalar.create'
            : 'siswa.ruang-nalar.create';

        return redirect()->route($redirectRoute)
            ->with('status', 'Modul berhasil dikirim untuk ditinjau admin. Setelah disetujui, modul akan tampil di ruang berbagi.');
    }

    public function download(Module $module): BinaryFileResponse
    {
        abort_unless($module->status === 'approved', 404);
        abort_unless(Storage::disk('public')->exists($module->file_path), 404);

        $module->increment('download_count');

        return response()->download(
            Storage::disk('public')->path($module->file_path),
            str($module->title)->slug()->append('.')->append(pathinfo($module->file_path, PATHINFO_EXTENSION))->toString(),
        );
    }
}
