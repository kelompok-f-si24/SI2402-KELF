<?php

namespace App\Http\Controllers;

use App\Enums\JobCategory;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the landing page.
     */
    public function __invoke(): View
    {
        $categoryCounts = JobListing::open()
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return view('home', [
            'latestJobs' => JobListing::open()->with('company')->latest('published_at')->take(6)->get(),
            'featuredCompanies' => Company::withCount('activeJobListings')->orderByDesc('active_job_listings_count')->take(8)->get(),
            'categories' => collect(JobCategory::cases())->map(fn (JobCategory $category) => [
                'category' => $category,
                'total' => $categoryCounts[$category->value] ?? 0,
            ]),
            'stats' => [
                'jobs' => JobListing::open()->count(),
                'companies' => Company::count(),
                'seekers' => User::where('role', UserRole::JobSeeker)->count(),
            ],
        ]);
    }
}
