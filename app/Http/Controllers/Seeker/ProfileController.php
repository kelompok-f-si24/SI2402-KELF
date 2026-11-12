<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seeker\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('seeker.profile', [
            'user' => $request->user(),
            'profile' => $request->user()->profile()->firstOrCreate(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $profile = $user->profile()->firstOrCreate();
        $data = $request->validated();

        $user->update($request->safe()->only(['name', 'email', 'phone']));

        $profileData = $request->safe()->only(['domicile', 'summary', 'portfolio_url']);

        if ($request->hasFile('photo')) {
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }

            $profileData['photo'] = $data['photo']->store('photos', 'public');
        }

        $profile->update($profileData);

        return redirect()->route('seeker.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function destroyPhoto(Request $request): RedirectResponse
    {
        $profile = $request->user()->profile;

        if ($profile?->photo) {
            Storage::disk('public')->delete($profile->photo);
            $profile->update(['photo' => null]);
        }

        return redirect()->route('seeker.profile.edit')->with('success', 'Foto profil berhasil dihapus.');
    }
}
