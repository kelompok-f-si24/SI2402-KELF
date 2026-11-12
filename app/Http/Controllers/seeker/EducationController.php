<?php

namespace App\Http\Controllers\Seeker;

use App\Enums\EducationLevel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Seeker\Concerns\EnsuresOwnership;
use App\Http\Requests\Seeker\EducationRequest;
use App\Models\Education;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EducationController extends Controller
{
    use EnsuresOwnership;

    public function index(Request $request): View
    {
        return view('seeker.educations.index', [
            'educations' => $request->user()->educations()->orderByDesc('start_year')->get(),
            'levels' => EducationLevel::options(),
        ]);
    }

    public function store(EducationRequest $request): RedirectResponse
    {
        $request->user()->educations()->create($request->validated());

        return redirect()->route('seeker.educations.index')->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
    }

    public function edit(Education $education): View
    {
        $this->ensureOwnedByCurrentUser($education);

        return view('seeker.educations.edit', [
            'education' => $education,
            'levels' => EducationLevel::options(),
        ]);
    }

    public function update(EducationRequest $request, Education $education): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($education);

        $education->update($request->validated());

        return redirect()->route('seeker.educations.index')->with('success', 'Riwayat pendidikan berhasil diperbarui.');
    }

    public function destroy(Education $education): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($education);

        $education->delete();

        return redirect()->route('seeker.educations.index')->with('success', 'Riwayat pendidikan berhasil dihapus.');
    }
}
