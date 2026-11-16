<form method="GET" action="{{ $action }}" class="card space-y-4 p-5">
    <div class="flex items-center justify-between">
        <h2 class="flex items-center gap-2 font-semibold text-slate-900"><x-icon name="filter" class="size-5" /> Filter</h2>
        @if (request()->hasAny(['q', 'location', 'category', 'employment_type', 'work_system', 'salary_min', 'salary_max']))
            <a href="{{ $action }}" class="text-xs font-medium text-navy-700 hover:underline">Reset</a>
        @endif
    </div>

    <x-form.input name="q" label="Kata Kunci" :value="request('q')" placeholder="Posisi, perusahaan, lokasi" maxlength="100" />
    <x-form.input name="location" label="Lokasi" :value="request('location')" placeholder="Contoh: Jakarta" maxlength="100" />
    <x-form.select name="category" label="Kategori" :options="\App\Enums\JobCategory::options()" :value="request('category')" placeholder="Semua kategori" />
    <x-form.select name="employment_type" label="Jenis Pekerjaan" :options="\App\Enums\EmploymentType::options()" :value="request('employment_type')" placeholder="Semua jenis" />
    <x-form.select name="work_system" label="Sistem Kerja" :options="\App\Enums\WorkSystem::options()" :value="request('work_system')" placeholder="Semua sistem" />

    <div>
        <span class="form-label">Rentang Gaji (Rp)</span>
        <div class="grid grid-cols-2 gap-2">
            <input type="number" name="salary_min" min="0" step="500000" value="{{ request('salary_min') }}" placeholder="Min" class="form-input" aria-label="Gaji minimum">
            <input type="number" name="salary_max" min="0" step="500000" value="{{ request('salary_max') }}" placeholder="Maks" class="form-input" aria-label="Gaji maksimum">
        </div>
        @error('salary_max')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-full">Terapkan Filter</button>
</form>
