<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\CompanyRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Show the company profile form (create or edit).
     */
    public function edit(Request $request): View
    {
        return view('employer.company', ['company' => $request->user()->company]);
    }

    /**
     * Create or update the employer's company profile.
     */
    public function update(CompanyRequest $request): RedirectResponse
    {
        $user = $request->user();
        $company = $user->company;
        $data = $request->safe()->except('logo');

        if ($request->hasFile('logo')) {
            if ($company?->logo) {
                Storage::disk('public')->delete($company->logo);
            }

            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        if ($company) {
            $company->update($data);

            return redirect()->route('employer.company.edit')->with('success', 'Profil perusahaan berhasil diperbarui.');
        }

        $user->setRelation('company', $user->company()->create($data));

        return redirect()->route('employer.dashboard')->with('success', 'Profil perusahaan berhasil dibuat. Sekarang Anda dapat membuat lowongan.');
    }
}
