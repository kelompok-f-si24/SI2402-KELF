<?php

namespace App\Http\Controllers\Seeker;

use App\Enums\EmploymentType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Seeker\Concerns\EnsuresOwnership;
use App\Http\Requests\Seeker\WorkExperienceRequest;
use App\Models\WorkExperience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkExperienceController extends Controller
{
    use EnsuresOwnership;

    public function index(Request $request): View
    {
        return view('seeker.experiences.index', [
            'experiences' => $request->user()->workExperiences()->orderByDesc('start_date')->get(),
            'employmentTypes' => EmploymentType::options(),
        ]);
    }

    public function store(WorkExperienceRequest $request): RedirectResponse
    {
        $request->user()->workExperiences()->create($request->validated());

        return redirect()->route('seeker.experiences.index')->with('success', 'Pengalaman kerja berhasil ditambahkan.');
    }

    public function edit(WorkExperience $experience): View
    {
        $this->ensureOwnedByCurrentUser($experience);

        return view('seeker.experiences.edit', [
            'experience' => $experience,
            'employmentTypes' => EmploymentType::options(),
        ]);
    }

    public function update(WorkExperienceRequest $request, WorkExperience $experience): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($experience);

        $experience->update($request->validated());

        return redirect()->route('seeker.experiences.index')->with('success', 'Pengalaman kerja berhasil diperbarui.');
    }

    public function destroy(WorkExperience $experience): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($experience);

        $experience->delete();

        return redirect()->route('seeker.experiences.index')->with('success', 'Pengalaman kerja berhasil dihapus.');
    }
}
