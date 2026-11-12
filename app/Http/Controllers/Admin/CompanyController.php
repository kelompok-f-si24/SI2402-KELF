<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * List all companies.
     */
    public function index(Request $request): View
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'verified' => ['nullable', Rule::in(['1', '0'])],
        ]);

        return view('admin.companies.index', [
            'companies' => Company::with('user')
                ->withCount(['jobListings', 'applications'])
                ->when($request->string('q')->toString(), fn ($query, string $keyword) => $query
                    ->where(fn ($query) => $query->where('name', 'like', "%{$keyword}%")->orWhere('industry', 'like', "%{$keyword}%")))
                ->when($request->filled('verified'), fn ($query) => $query->where('is_verified', $request->boolean('verified')))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
        ]);
    }

    /**
     * Show company details with its listings.
     */
    public function show(Company $company): View
    {
        return view('admin.companies.show', [
            'company' => $company->load('user'),
            'jobs' => $company->jobListings()->withCount('applications')->latest()->paginate(10),
        ]);
    }

    /**
     * Toggle the verified badge for a company.
     */
    public function toggleVerified(Company $company): RedirectResponse
    {
        $company->update(['is_verified' => ! $company->is_verified]);

        return back()->with('success', $company->name.' '.($company->is_verified ? 'berhasil diverifikasi.' : 'tidak lagi terverifikasi.'));
    }

    /**
     * Delete a company along with its listings and applications.
     */
    public function destroy(Company $company): RedirectResponse
    {
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->jobListings()->with('applications')->get()
            ->flatMap->applications
            ->pluck('cv_path')
            ->filter()
            ->each(fn (string $path) => Storage::disk('local')->delete($path));

        $name = $company->name;
        $company->delete();

        return redirect()->route('admin.companies.index')->with('success', 'Perusahaan '.$name.' beserta lowongannya berhasil dihapus.');
    }
}
