<?php

namespace App\Http\Controllers\Employer;

use App\Enums\EmploymentType;
use App\Enums\JobCategory;
use App\Enums\JobStatus;
use App\Enums\WorkSystem;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\JobListingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JobListingController extends Controller
{
    /**
     * List the company's job listings.
     */
    public function index(Request $request): View
    {
        $request->validate([
            'status' => ['nullable', Rule::enum(JobStatus::class)],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        return view('employer.jobs.index', [
            'jobs' => $request->user()->company->jobListings()
                ->withCount('applications')
                ->when($request->enum('status', JobStatus::class), fn ($query, JobStatus $status) => $query->where('status', $status))
                ->when($request->string('q')->toString(), fn ($query, string $keyword) => $query->where('title', 'like', "%{$keyword}%"))
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'statuses' => JobStatus::options(),
        ]);
    }

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
