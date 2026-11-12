<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * List companies registered on the platform.
     */
    public function index(Request $request): View
    {
        $request->validate(['q' => ['nullable', 'string', 'max:100']]);

        return view('companies.index', [
            'companies' => Company::withCount('activeJobListings')
                ->when($request->string('q')->toString(), fn ($query, string $keyword) => $query
                    ->where(fn ($query) => $query->where('name', 'like', "%{$keyword}%")
                        ->orWhere('industry', 'like', "%{$keyword}%")
                        ->orWhere('address', 'like', "%{$keyword}%")))
                ->orderByDesc('is_verified')
                ->orderBy('name')
                ->paginate(12)
                ->withQueryString(),
        ]);
    }

    /**
     * Show a company profile with its open listings.
     */
    public function show(Company $company): View
    {
        return view('companies.show', [
            'company' => $company,
            'jobs' => $company->jobListings()->open()->with('company')->latest('published_at')->get(),
        ]);
    }
}
