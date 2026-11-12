<?php

namespace Tests\Feature;

use App\Enums\EmploymentType;
use App\Enums\WorkSystem;
use App\Models\Company;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_shows_latest_open_jobs(): void
    {
        $open = JobListing::factory()->create(['title' => 'Lowongan Terbuka']);
        JobListing::factory()->closed()->create(['title' => 'Lowongan Tertutup']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('LOKERIN')
            ->assertSee($open->title)
            ->assertDontSee('Lowongan Tertutup');
    }

    public function test_job_index_only_lists_open_jobs(): void
    {
        JobListing::factory()->create(['title' => 'Aktif Satu']);
        JobListing::factory()->closed()->create(['title' => 'Sudah Ditutup']);
        JobListing::factory()->expired()->create(['title' => 'Sudah Kedaluwarsa']);

        $this->get(route('jobs.index'))
            ->assertOk()
            ->assertSee('Aktif Satu')
            ->assertDontSee('Sudah Ditutup')
            ->assertDontSee('Sudah Kedaluwarsa');
    }

    public function test_job_search_matches_title_company_and_location(): void
    {
        $company = Company::factory()->create(['name' => 'PT Maju Bersama']);
        JobListing::factory()->for($company)->create(['title' => 'Kasir Toko', 'location' => 'Medan']);
        JobListing::factory()->create(['title' => 'Backend Engineer', 'location' => 'Bandung']);
        JobListing::factory()->create(['title' => 'Ilustrator Buku', 'location' => 'Surabaya']);

        $this->get(route('jobs.index', ['q' => 'Backend']))->assertSee('Backend Engineer')->assertDontSee('Ilustrator Buku');
        $this->get(route('jobs.index', ['q' => 'Maju Bersama']))->assertSee('Kasir Toko')->assertDontSee('Ilustrator Buku');
        $this->get(route('jobs.index', ['q' => 'Surabaya']))->assertSee('Ilustrator Buku')->assertDontSee('Kasir Toko');
    }

    public function test_job_filters_apply(): void
    {
        JobListing::factory()->create([
            'title' => 'Remote Fulltime',
            'location' => 'Jakarta',
            'employment_type' => EmploymentType::FullTime,
            'work_system' => WorkSystem::Remote,
            'salary_min' => 8_000_000,
            'salary_max' => 12_000_000,
        ]);
        JobListing::factory()->create([
            'title' => 'Onsite Magang',
            'location' => 'Bali',
            'employment_type' => EmploymentType::Internship,
            'work_system' => WorkSystem::Onsite,
            'salary_min' => 2_000_000,
            'salary_max' => 3_000_000,
        ]);

        $this->get(route('jobs.index', ['work_system' => 'remote']))->assertSee('Remote Fulltime')->assertDontSee('Onsite Magang');
        $this->get(route('jobs.index', ['employment_type' => 'internship']))->assertSee('Onsite Magang')->assertDontSee('Remote Fulltime');
        $this->get(route('jobs.index', ['location' => 'Bali']))->assertSee('Onsite Magang')->assertDontSee('Remote Fulltime');
        $this->get(route('jobs.index', ['salary_min' => 7_000_000]))->assertSee('Remote Fulltime')->assertDontSee('Onsite Magang');
    }

    public function test_job_detail_shows_full_information(): void
    {
        $job = JobListing::factory()->create(['qualifications' => "Menguasai Laravel\nTeliti"]);

        $this->get(route('jobs.show', $job))
            ->assertOk()
            ->assertSee($job->title)
            ->assertSee($job->company->name)
            ->assertSee('Menguasai Laravel')
            ->assertSee('Masuk untuk Melamar');
    }

    public function test_company_pages_render(): void
    {
        $company = Company::factory()->create(['name' => 'PT Nusantara Digital']);
        JobListing::factory()->for($company)->create(['title' => 'Data Analyst']);

        $this->get(route('companies.index'))->assertOk()->assertSee('PT Nusantara Digital');
        $this->get(route('companies.index', ['q' => 'tidak-ada']))->assertOk()->assertSee('Perusahaan tidak ditemukan');
        $this->get(route('companies.show', $company))->assertOk()->assertSee('Data Analyst');
    }
}
