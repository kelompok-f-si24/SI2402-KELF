<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobSearchRequest;
use App\Models\JobListing;
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
}
