<x-layouts.dashboard title="Profil Saya">
    <x-page-header title="Profil Saya" subtitle="Informasi ini akan dilihat perusahaan saat Anda melamar." />

    <form method="POST" action="{{ route('seeker.profile.update') }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @method('PUT')

        <div class="card p-6 text-center">
            <x-avatar :src="$profile->photoUrl()" :initials="$user->initials()" size="size-28" class="mx-auto text-2xl" />
            <p class="mt-4 font-semibold text-slate-900">{{ $user->name }}</p>
            <p class="text-sm text-slate-500">{{ $user->email }}</p>

            <label class="btn btn-secondary mt-5 w-full cursor-pointer">
                <x-icon name="upload" class="size-4" /> Pilih Foto
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="sr-only" data-file-input="photo-name">
            </label>
            <p id="photo-name" class="mt-2 truncate text-xs text-slate-500">JPG, PNG, atau WEBP. Maks 2 MB.</p>
            @error('photo')
                <p class="form-error">{{ $message }}</p>
            @enderror

            @if ($profile->photo)
                <button type="button" data-modal-open="delete-photo-modal" class="mt-3 text-sm font-medium text-red-600 hover:underline">Hapus foto</button>
            @endif
        </div>

        <div class="card space-y-5 p-6 lg:col-span-2">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-form.input name="name" label="Nama Lengkap" :value="$user->name" required maxlength="255" />
                <x-form.input name="email" type="email" label="Email" :value="$user->email" required maxlength="255" />
                <x-form.input name="phone" type="tel" label="Nomor Telepon" :value="$user->phone" required pattern="^(\+62|62|0)8[0-9]{7,12}$" title="Contoh: 081234567890" />
                <x-form.input name="domicile" label="Domisili" :value="$profile->domicile" maxlength="100" placeholder="Contoh: Bandung" />
            </div>
            <x-form.textarea name="summary" label="Ringkasan Diri" :value="$profile->summary" rows="5" maxlength="2000"
                placeholder="Ceritakan singkat tentang diri, keahlian, dan tujuan karier Anda." />
            <x-form.input name="portfolio_url" type="url" label="Link Portofolio" :value="$profile->portfolio_url" maxlength="255" placeholder="https://portofolio-anda.com" />

            <div class="flex justify-end border-t border-slate-100 pt-5">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </form>

    @if ($profile->photo)
        <x-confirm-modal id="delete-photo-modal" :action="route('seeker.profile.photo.destroy')" method="DELETE"
            title="Hapus foto profil?" message="Foto profil Anda akan dihapus permanen." confirm-label="Ya, hapus" />
    @endif
</x-layouts.dashboard>
