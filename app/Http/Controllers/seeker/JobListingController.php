<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobSearchRequest;
use App\Models\JobListing;
use Illuminate\View\View;

class JobListingController extends Controller
{
    /**
     * Search active job listings inside the dashboard.
     */
    public function index(JobSearchRequest $request): View
    {
        return view('seeker.jobs.index', [
            'jobs' => JobListing::open()
                ->filter($request->validated())
                ->with('company')
                ->latest('published_at')
                ->paginate(10)
                ->withQueryString(),
        ]);
    }
}
