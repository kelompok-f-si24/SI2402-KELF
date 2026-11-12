<x-layouts.auth title="Masuk" heading="Selamat Datang Kembali" subheading="Masuk untuk melanjutkan ke dashboard Anda.">
    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
        @csrf

        <x-form.input name="email" type="email" label="Email" required autofocus autocomplete="email" placeholder="nama@email.com" />
        <x-form.input name="password" type="password" label="Password" required autocomplete="current-password" />

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember" value="1" class="size-4 rounded border-slate-300 text-navy-700">
            Ingat saya
        </label>

        <button type="submit" class="btn btn-primary w-full py-3">Masuk</button>

        <p class="text-center text-sm text-slate-500">
            Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-navy-700 hover:underline">Daftar gratis</a>
        </p>
    </form>

    @if (app()->isLocal())
        <div class="mt-8 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 text-xs text-slate-600">
            <p class="mb-2 font-semibold text-slate-700">Akun demo (password: <code>password</code>)</p>
            <ul class="space-y-1">
                <li>Admin: <code>admin@lokerin.test</code></li>
                <li>Employer: <code>employer@lokerin.test</code></li>
                <li>Job Seeker: <code>seeker@lokerin.test</code></li>
            </ul>
        </div>
    @endif
</x-layouts.auth>
