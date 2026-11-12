<?php

namespace Tests\Feature;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Cv;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SeekerApplicationTest extends TestCase
{
    use RefreshDatabase;

    private User $seeker;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->seeker = User::factory()->jobSeeker()->create();
        $cv = Cv::factory()->for($this->seeker)->create(['original_name' => 'cv-saya.pdf']);
        Storage::disk('local')->put($cv->file_path, 'isi cv');
    }

    public function test_seeker_can_apply_to_open_job(): void
    {
        $job = JobListing::factory()->create();

        $this->actingAs($this->seeker)
            ->get(route('seeker.jobs.show', $job))
            ->assertOk()
            ->assertSee('Kirim Lamaran');

        $response = $this->post(route('seeker.applications.store', $job), ['cover_letter' => 'Saya tertarik.']);

        $application = Application::first();
        $response->assertRedirect(route('seeker.applications.show', $application))->assertSessionHas('success');
        $this->assertSame(ApplicationStatus::Pending, $application->status);
        $this->assertSame('cv-saya.pdf', $application->cv_original_name);
        Storage::disk('local')->assertExists($application->cv_path);
    }

    public function test_seeker_cannot_apply_twice(): void
    {
        $job = JobListing::factory()->create();
        $this->actingAs($this->seeker)->post(route('seeker.applications.store', $job));

        $this->post(route('seeker.applications.store', $job))->assertSessionHas('error', 'Anda sudah pernah melamar lowongan ini.');

        $this->assertSame(1, Application::count());
    }

    public function test_closed_or_expired_jobs_reject_applications(): void
    {
        $closed = JobListing::factory()->closed()->create();
        $expired = JobListing::factory()->expired()->create();

        $this->actingAs($this->seeker);
        $this->post(route('seeker.applications.store', $closed))->assertSessionHas('error');
        $this->post(route('seeker.applications.store', $expired))->assertSessionHas('error');

        $this->get(route('seeker.jobs.show', $closed))->assertSee('sudah ditutup');
        $this->assertSame(0, Application::count());
    }

    public function test_seeker_without_cv_must_upload_first(): void
    {
        $seeker = User::factory()->jobSeeker()->create();
        $job = JobListing::factory()->create();

        $this->actingAs($seeker)->get(route('seeker.jobs.show', $job))->assertSee('Upload CV');
        $this->post(route('seeker.applications.store', $job))->assertRedirect(route('seeker.cv.show'));

        $this->assertSame(0, Application::count());
    }

    public function test_only_job_seekers_can_apply(): void
    {
        $employer = User::factory()->employer()->create();
        $job = JobListing::factory()->create();

        $this->actingAs($employer)->post(route('seeker.applications.store', $job))->assertForbidden();
    }

    public function test_seeker_sees_application_history_and_status(): void
    {
        $passed = Application::factory()->for($this->seeker)->create(['status' => ApplicationStatus::Passed]);
        $rejected = Application::factory()->for($this->seeker)->create(['status' => ApplicationStatus::Rejected]);
        $othersApplication = Application::factory()->create();

        $this->actingAs($this->seeker);

        $this->get(route('seeker.applications.index'))
            ->assertOk()
            ->assertSee($passed->jobListing->title)
            ->assertSee($passed->jobListing->company->name)
            ->assertSee('Lolos Seleksi')
            ->assertSee('Ditolak');

        $this->get(route('seeker.applications.status', ['status' => 'passed']))
            ->assertOk()
            ->assertSee($passed->jobListing->title)
            ->assertDontSee($rejected->jobListing->title);

        $this->get(route('seeker.applications.show', $passed))->assertOk()->assertSee('Progres Seleksi');
        $this->get(route('seeker.applications.show', $othersApplication))->assertForbidden();
    }
}
