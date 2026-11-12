<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployerHasCompany
{
    /**
     * Require employers to complete their company profile before managing listings.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->company) {
            return redirect()->route('employer.company.edit')
                ->with('error', 'Lengkapi profil perusahaan terlebih dahulu sebelum mengelola lowongan.');
        }

        return $next($request);
    }
}
