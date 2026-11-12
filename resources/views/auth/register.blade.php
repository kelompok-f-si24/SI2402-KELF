<x-layouts.auth title="Daftar" heading="Buat Akun Baru" subheading="Daftar gratis dan gunakan Lokerin sesuai peran Anda.">
    <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
        @csrf

        <x-form.input name="name" label="Nama Lengkap" required autofocus autocomplete="name" placeholder="Nama Anda" />
        <x-form.input name="email" type="email" label="Email" required autocomplete="email" placeholder="nama@email.com" />
        <x-form.input name="password" type="password" label="Password" required autocomplete="new-password" hint="Minimal 8 karakter" />
        <x-form.input name="password_confirmation" type="password" label="Konfirmasi Password" required autocomplete="new-password" placeholder="Ulangi password" />

        <div>
            <p class="form-label">Saya mendaftar sebagai <span class="text-red-500">*</span></p>
            <div class="grid grid-cols-2 gap-3">
                @foreach ($roles as $value => $label)
                    <label class="flex cursor-pointer flex-col gap-1 rounded-xl border p-4 transition {{ old('role', \App\Enums\UserRole::JobSeeker->value) === $value ? 'border-navy-700 bg-navy-50 ring-1 ring-navy-700' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                        <input type="radio" name="role" value="{{ $value }}" {{ old('role', \App\Enums\UserRole::JobSeeker->value) === $value ? 'checked' : '' }} class="sr-only">
                        <span class="text-sm font-semibold text-navy-800">{{ $label }}</span>
                        <span class="text-xs text-slate-500">{{ $value === 'employer' ? 'Pasang lowongan & kelola pelamar' : 'Cari lowongan & kirim lamaran' }}</span>
                    </label>
                @endforeach
            </div>
            @error('role')
                <p class="form-error mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-full py-3">Daftar</button>

        <p class="text-center text-sm text-slate-500">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-navy-700 hover:underline">Masuk</a>
        </p>
    </form>
</x-layouts.auth>
