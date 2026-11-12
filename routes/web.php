<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Employer;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\Seeker;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/lowongan', [JobListingController::class, 'index'])->name('jobs.index');
Route::get('/lowongan/{jobListing}', [JobListingController::class, 'show'])->name('jobs.show');
Route::get('/perusahaan', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/perusahaan/{company}', [CompanyController::class, 'show'])->name('companies.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', fn () => redirect()->route(auth()->user()->dashboardRouteName()))->name('dashboard');
});

Route::middleware(['auth', 'role:job_seeker'])->prefix('seeker')->name('seeker.')->group(function () {
    Route::get('/dashboard', Seeker\DashboardController::class)->name('dashboard');

    Route::get('/profil', [Seeker\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [Seeker\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil/foto', [Seeker\ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');

    Route::resource('pendidikan', Seeker\EducationController::class)
        ->except(['create', 'show'])->parameters(['pendidikan' => 'education'])->names('educations');
    Route::resource('skill', Seeker\SkillController::class)
        ->except(['create', 'show'])->parameters(['skill' => 'skill'])->names('skills');
    Route::resource('pengalaman', Seeker\WorkExperienceController::class)
        ->except(['create', 'show'])->parameters(['pengalaman' => 'experience'])->names('experiences');

    Route::get('/cv', [Seeker\CvController::class, 'show'])->name('cv.show');
    Route::post('/cv', [Seeker\CvController::class, 'store'])->name('cv.store');
    Route::delete('/cv', [Seeker\CvController::class, 'destroy'])->name('cv.destroy');
    Route::get('/cv/unduh', [Seeker\CvController::class, 'download'])->name('cv.download');

    Route::get('/lowongan', [Seeker\JobListingController::class, 'index'])->name('jobs.index');
    Route::get('/lowongan/{jobListing}', [Seeker\JobListingController::class, 'show'])->name('jobs.show');
    Route::post('/lowongan/{jobListing}/lamar', [Seeker\ApplicationController::class, 'store'])->name('applications.store');

    Route::get('/lamaran', [Seeker\ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/lamaran/status', [Seeker\ApplicationController::class, 'status'])->name('applications.status');
    Route::get('/lamaran/{application}', [Seeker\ApplicationController::class, 'show'])->name('applications.show');
    Route::get('/lowongan', [Seeker\JobListingController::class, 'index'])->name('jobs.index');
});

Route::middleware(['auth', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/dashboard', Employer\DashboardController::class)->name('dashboard');

    Route::get('/perusahaan', [Employer\CompanyController::class, 'edit'])->name('company.edit');
    Route::put('/perusahaan', [Employer\CompanyController::class, 'update'])->name('company.update');

    Route::middleware('company.required')->group(function () {
        Route::resource('lowongan', Employer\JobListingController::class)
            ->except(['show', 'destroy'])->parameters(['lowongan' => 'jobListing'])->names('jobs');
        Route::patch('/lowongan/{jobListing}/tutup', [Employer\JobListingController::class, 'close'])->name('jobs.close');
        Route::patch('/lowongan/{jobListing}/buka', [Employer\JobListingController::class, 'reopen'])->name('jobs.reopen');

        Route::get('/pelamar', [Employer\ApplicantController::class, 'index'])->name('applicants.index');
        Route::get('/pelamar/{application}', [Employer\ApplicantController::class, 'show'])->name('applicants.show');
        Route::patch('/pelamar/{application}/status', [Employer\ApplicantController::class, 'updateStatus'])->name('applicants.status');
        Route::get('/pelamar/{application}/cv', [Employer\ApplicantController::class, 'downloadCv'])->name('applicants.cv');
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pengguna', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/pengguna/{user}', [Admin\UserController::class, 'show'])->name('users.show');
    Route::patch('/pengguna/{user}/status', [Admin\UserController::class, 'toggleActive'])->name('users.toggle');

    Route::get('/perusahaan', [Admin\CompanyController::class, 'index'])->name('companies.index');
    Route::get('/perusahaan/{company}', [Admin\CompanyController::class, 'show'])->name('companies.show');
    Route::patch('/perusahaan/{company}/verifikasi', [Admin\CompanyController::class, 'toggleVerified'])->name('companies.verify');
    Route::delete('/perusahaan/{company}', [Admin\CompanyController::class, 'destroy'])->name('companies.destroy');

    Route::get('/lowongan', [Admin\JobListingController::class, 'index'])->name('jobs.index');
    Route::get('/lowongan/{jobListing}', [Admin\JobListingController::class, 'show'])->name('jobs.show');
    Route::patch('/lowongan/{jobListing}/status', [Admin\JobListingController::class, 'updateStatus'])->name('jobs.status');
    Route::delete('/lowongan/{jobListing}', [Admin\JobListingController::class, 'destroy'])->name('jobs.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Landing admin — route admin.dashboard harus ada agar login & /dashboard tidak error.
    // Halaman monitoring admin sendiri masih menjadi tiket tim.
    Route::get('/dashboard', fn () => redirect()->route('admin.companies.index'))->name('dashboard');

    Route::get('/perusahaan', [Admin\CompanyController::class, 'index'])->name('companies.index');
    Route::get('/perusahaan/{company}', [Admin\CompanyController::class, 'show'])->name('companies.show');
    Route::patch('/perusahaan/{company}/verifikasi', [Admin\CompanyController::class, 'toggleVerified'])->name('companies.verify');
    Route::delete('/perusahaan/{company}', [Admin\CompanyController::class, 'destroy'])->name('companies.destroy');

    Route::get('/lowongan', [Admin\JobListingController::class, 'index'])->name('jobs.index');
    Route::get('/lowongan/{jobListing}', [Admin\JobListingController::class, 'show'])->name('jobs.show');
    Route::patch('/lowongan/{jobListing}/status', [Admin\JobListingController::class, 'updateStatus'])->name('jobs.status');
    Route::delete('/lowongan/{jobListing}', [Admin\JobListingController::class, 'destroy'])->name('jobs.destroy');
});
