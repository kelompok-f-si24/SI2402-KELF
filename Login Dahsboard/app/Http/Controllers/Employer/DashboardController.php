<?php

namespace App\Http\Controllers\Employer;

use App\Enums\ApplicationStatus;
use App\Enums\JobStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the employer dashboard.
     */
    public function __invoke(Request $request): View
    {
        $company = $request->user()->company;

        if (! $company) {
            return view('employer.dashboard', ['company' => null]);
        }

        $statusCounts = $company->applications()
            ->selectRaw('applications.status, count(*) as total')
            ->groupBy('applications.status')
            ->pluck('total', 'status');

        return view('employer.dashboard', [
            'company' => $company,
            'activeJobs' => $company->jobListings()->where('status', JobStatus::Active)->count(),
            'closedJobs' => $company->jobListings()->where('status', JobStatus::Closed)->count(),
            'totalApplicants' => $statusCounts->sum(),
            'pendingApplicants' => $statusCounts[ApplicationStatus::Pending->value] ?? 0,
            'statusCounts' => $statusCounts,
            'statuses' => ApplicationStatus::cases(),
            'recentApplicants' => $company->applications()->with(['user', 'jobListing'])->latest('applications.created_at')->take(6)->get(),
            'topJobs' => $company->jobListings()->withCount('applications')->orderByDesc('applications_count')->take(5)->get(),
        ]);
    }
}
