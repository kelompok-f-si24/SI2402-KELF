<?php

namespace App\Http\Controllers\Seeker;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the job seeker dashboard.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user()->load(['profile', 'cv'])->loadCount(['educations', 'skills', 'workExperiences']);

        $statusCounts = $user->applications()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $completeness = collect([
            'Data diri' => filled($user->phone) && filled($user->profile?->domicile) && filled($user->profile?->summary),
            'Foto profil' => filled($user->profile?->photo),
            'Pendidikan' => $user->educations_count > 0,
            'Skill' => $user->skills_count > 0,
            'Pengalaman kerja' => $user->work_experiences_count > 0,
            'CV' => $user->cv !== null,
        ]);

        $appliedListingIds = $user->applications()->pluck('job_listing_id');

        return view('seeker.dashboard', [
            'user' => $user,
            'totalApplications' => $statusCounts->sum(),
            'statusCounts' => $statusCounts,
            'statuses' => ApplicationStatus::cases(),
            'completeness' => $completeness,
            'completenessPercent' => (int) round($completeness->filter()->count() / $completeness->count() * 100),
            'recentApplications' => $user->applications()->with('jobListing.company')->latest()->take(5)->get(),
            'recommendedJobs' => JobListing::open()
                ->with('company')
                ->whereNotIn('id', $appliedListingIds)
                ->when($user->profile?->domicile, fn ($query, string $domicile) => $query->orderByRaw('location like ? desc', ["%{$domicile}%"]))
                ->latest('published_at')
                ->take(4)
                ->get(),
        ]);
    }
}
