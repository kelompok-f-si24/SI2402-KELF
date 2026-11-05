<?php

namespace App\Http\Controllers\Employer;

use App\Enums\EmploymentType;
use App\Enums\JobCategory;
use App\Enums\JobStatus;
use App\Enums\WorkSystem;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\JobListingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JobListingController extends Controller
{
    public function create(): View
    {
        return view('employer.jobs.create', $this->formOptions());
    }

    /**
     * Publish a new job listing.
     */
    public function store(JobListingRequest $request): RedirectResponse
    {
        $request->user()->company->jobListings()->create([
            ...$request->validated(),
            'status' => JobStatus::Active,
            'published_at' => now(),
        ]);

        return redirect()->route('employer.jobs.index')->with('success', 'Lowongan berhasil dipublikasikan.');
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function formOptions(): array
    {
        return [
            'categories' => JobCategory::options(),
            'employmentTypes' => EmploymentType::options(),
            'workSystems' => WorkSystem::options(),
        ];
    }
}
