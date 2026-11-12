<?php

namespace App\Http\Controllers\Seeker;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seeker\ApplicationRequest;
use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    /**
     * Application history.
     */
    public function index(Request $request): View
    {
        return view('seeker.applications.index', [
            'applications' => $request->user()->applications()
                ->with('jobListing.company')
                ->latest()
                ->paginate(10),
        ]);
    }

    /**
     * Application status overview grouped by selection stage.
     */
    public function status(Request $request): View
    {
        $request->validate(['status' => ['nullable', Rule::enum(ApplicationStatus::class)]]);

        $statusCounts = $request->user()->applications()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('seeker.applications.status', [
            'statuses' => ApplicationStatus::cases(),
            'statusCounts' => $statusCounts,
            'activeStatus' => $request->enum('status', ApplicationStatus::class),
            'applications' => $request->user()->applications()
                ->with('jobListing.company')
                ->when($request->enum('status', ApplicationStatus::class), fn ($query, ApplicationStatus $status) => $query->where('status', $status))
                ->latest('status_updated_at')
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    /**
     * Show a single application and its status.
     */
    public function show(Application $application): View
    {
        abort_unless($application->user_id === auth()->id(), 403, 'Lamaran ini bukan milik Anda.');

        return view('seeker.applications.show', [
            'application' => $application->load('jobListing.company'),
            'statuses' => ApplicationStatus::cases(),
        ]);
    }

    /**
     * Submit an application for an open job listing.
     */
    public function store(ApplicationRequest $request, JobListing $jobListing): RedirectResponse
    {
        $user = $request->user();

        if (! Gate::allows('apply', $jobListing)) {
            return back()->with('error', 'Lowongan ini sudah ditutup dan tidak menerima lamaran baru.');
        }

        if ($user->applications()->whereBelongsTo($jobListing)->exists()) {
            return back()->with('error', 'Anda sudah pernah melamar lowongan ini.');
        }

        $cv = $user->cv;

        if (! $cv || ! Storage::disk('local')->exists($cv->file_path)) {
            return redirect()->route('seeker.cv.show')->with('error', 'Unggah CV terlebih dahulu sebelum melamar.');
        }

        $snapshotPath = 'applications/'.$jobListing->id.'-'.$user->id.'-'.basename($cv->file_path);
        Storage::disk('local')->copy($cv->file_path, $snapshotPath);

        $application = $user->applications()->create([
            'job_listing_id' => $jobListing->id,
            'cover_letter' => $request->validated('cover_letter'),
            'cv_path' => $snapshotPath,
            'cv_original_name' => $cv->original_name,
            'status' => ApplicationStatus::Pending,
            'status_updated_at' => now(),
        ]);

        return redirect()->route('seeker.applications.show', $application)
            ->with('success', 'Lamaran berhasil dikirim ke '.$jobListing->company->name.'.');
    }
}
