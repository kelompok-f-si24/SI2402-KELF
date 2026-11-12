<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create(): View
    {
        return view('auth.register', [
            'roles' => UserRole::registrable(),
        ]);
    }

    /**
     * Create a new account and send the user to their role dashboard.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route($user->dashboardRouteName())
            ->with('success', 'Selamat datang, '.$user->name.'! Akun '.$user->role->label().' Anda berhasil dibuat.');
    }
}
