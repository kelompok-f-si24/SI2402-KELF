<?php

namespace App\Http\Controllers\Admin;

use App\Enums\JobCategory;
use App\Enums\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JobListingController extends Controller
{
    /**
     * List all job listings on the platform.
     */
    public function index(Request $request): View
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::enum(JobStatus::class)],
            'category' => ['nullable', Rule::enum(JobCategory::class)],
        ]);

        return view('admin.jobs.index', [
            'jobs' => JobListing::with('company')
                ->withCount('applications')
                ->when($request->string('q')->toString(), fn ($query, string $keyword) => $query
                    ->where(fn ($query) => $query->where('title', 'like', "%{$keyword}%")
                        ->orWhereHas('company', fn ($query) => $query->where('name', 'like', "%{$keyword}%"))))
                ->when($request->enum('status', JobStatus::class), fn ($query, JobStatus $status) => $query->where('status', $status))
                ->when($request->enum('category', JobCategory::class), fn ($query, JobCategory $category) => $query->where('category', $category))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'statuses' => JobStatus::options(),
            'categories' => JobCategory::options(),
        ]);
    }

    /**
     * Show listing details for moderation.
     */
    public function show(JobListing $jobListing): View
    {
        return view('admin.jobs.show', [
            'job' => $jobListing->load('company')->loadCount('applications'),
            'statuses' => JobStatus::options(),
        ]);
    }

    /**
     * Change the listing status.
     */
    public function updateStatus(Request $request, JobListing $jobListing): RedirectResponse
    {
        $validated = $request->validate(['status' => ['required', Rule::enum(JobStatus::class)]]);

        $jobListing->update($validated);

        return back()->with('success', 'Status lowongan "'.$jobListing->title.'" diubah menjadi '.$jobListing->status->label().'.');
    }

    /**
     * Remove a listing that violates platform rules.
     */
    public function destroy(JobListing $jobListing): RedirectResponse
    {
        $jobListing->applications()->pluck('cv_path')->filter()
            ->each(fn (string $path) => Storage::disk('local')->delete($path));

        $title = $jobListing->title;
        $jobListing->delete();

        return redirect()->route('admin.jobs.index')->with('success', 'Lowongan "'.$title.'" berhasil dihapus.');
    }
}
