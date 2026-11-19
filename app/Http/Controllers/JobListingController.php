<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobSearchRequest;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobListingController extends Controller
{
    /**
     * List active job listings with search and filters.
     */
    public function index(JobSearchRequest $request): View
    {
        return view('jobs.index', [
            'jobs' => JobListing::open()
                ->filter($request->validated())
                ->with('company')
                ->latest('published_at')
                ->paginate(12)
                ->withQueryString(),
        ]);
    }

    /**
     * Show a job listing detail page.
     */
    public function show(Request $request, JobListing $jobListing): View
    {
        $user = $request->user();

        return view('jobs.show', [
            'job' => $jobListing->load('company')->loadCount('applications'),
            'existingApplication' => $user?->applications()->whereBelongsTo($jobListing)->first(),
            // Fitur CV belum ada — jangan sentuh relasi yang model/tabelnya belum dibuat.
            'cv' => $user && class_exists(\App\Models\Cv::class) && \Illuminate\Support\Facades\Schema::hasTable('cvs')
                ? $user->cv
                : null,
            'relatedJobs' => JobListing::open()
                ->whereKeyNot($jobListing->getKey())
                ->where('category', $jobListing->category->value)
                ->with('company')
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }
}
