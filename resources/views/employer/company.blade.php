<x-layouts.dashboard title="Profil Perusahaan">
    <x-page-header title="Profil Perusahaan" :subtitle="$company ? 'Perbarui informasi perusahaan agar tetap akurat.' : 'Buat profil perusahaan agar dapat mempublikasikan lowongan.'">
        @if ($company)
            <a href="{{ route('companies.show', $company) }}" target="_blank" class="btn btn-secondary"><x-icon name="eye" class="size-4" /> Lihat Halaman Publik</a>
        @endif
    </x-page-header>

    <form method="POST" action="{{ route('employer.company.update') }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @method('PUT')

        <div class="card p-6 text-center lg:self-start">
            <x-avatar :src="$company?->logoUrl()" :initials="$company?->initials() ?: 'LG'" size="size-28" rounded="rounded-2xl" class="mx-auto text-2xl" />
            <label class="btn btn-secondary mt-5 w-full cursor-pointer">
                <x-icon name="upload" class="size-4" /> {{ $company?->logo ? 'Ganti Logo' : 'Unggah Logo' }}
                <input type="file" name="logo" accept="image/jpeg,image/png,image/webp" class="sr-only" data-file-input="logo-name">
            </label>
            <p id="logo-name" class="mt-2 truncate text-xs text-slate-500">JPG, PNG, atau WEBP. Maks 2 MB.</p>
            @error('logo')
                <p class="form-error">{{ $message }}</p>
            @enderror
            @if ($company?->is_verified)
                <x-badge color="sky" class="mt-4"><x-icon name="badge-check" class="size-4" /> Terverifikasi</x-badge>
            @endif
        </div>

        <div class="card space-y-5 p-6 lg:col-span-2">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-form.input name="name" label="Nama Perusahaan" :value="$company?->name" required maxlength="255" />
                <x-form.input name="industry" label="Industri" :value="$company?->industry" required maxlength="100" placeholder="Contoh: Teknologi" />
                <x-form.input name="email" type="email" label="Email Perusahaan" :value="$company?->email ?? auth()->user()->email" required maxlength="255" />
                <x-form.input name="phone" type="tel" label="Nomor Telepon" :value="$company?->phone ?? auth()->user()->phone" required pattern="^(\+62|62|0)[0-9]{8,13}$" title="Contoh: 0215551234" />
                <div class="sm:col-span-2">
                    <x-form.input name="website" type="url" label="Website" :value="$company?->website" maxlength="255" placeholder="https://perusahaan.com" />
                </div>
            </div>
            <x-form.textarea name="address" label="Alamat" :value="$company?->address" rows="2" required maxlength="500" />
            <x-form.textarea name="description" label="Deskripsi Perusahaan" :value="$company?->description" rows="6" required minlength="30" maxlength="5000"
                hint="Minimal 30 karakter. Ceritakan visi, budaya kerja, dan bidang usaha perusahaan." />

            <div class="flex justify-end border-t border-slate-100 pt-5">
                <button type="submit" class="btn btn-primary">{{ $company ? 'Simpan Perubahan' : 'Buat Profil Perusahaan' }}</button>
            </div>
        </div>
    </form>
</x-layouts.dashboard>
