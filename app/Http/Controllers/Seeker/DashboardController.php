<?php

namespace App\Http\Controllers\Seeker;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the job seeker dashboard.
     */
    public function __invoke(Request $request): View
    {
        // Tabel skills/work_experiences/cvs dibuat oleh tiket lain yang belum
        // di-merge — hitung hanya relasi yang tabelnya sudah ada agar dashboard
        // tetap tampil. Begitu migration teman masuk, data otomatis muncul.
        $user = $request->user()->load('profile')->loadCount(array_values(array_filter([
            'educations',
            Schema::hasTable('skills') ? 'skills' : null,
            Schema::hasTable('work_experiences') ? 'workExperiences' : null,
        ])));

        if (Schema::hasTable('cvs')) {
            $user->load('cv');
        } else {
            $user->setRelation('cv', null);
        }

        $user->skills_count ??= 0;
        $user->work_experiences_count ??= 0;

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
