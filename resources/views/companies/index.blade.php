<x-layouts.public title="Perusahaan">
    <section class="bg-navy-900">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-white">Daftar Perusahaan</h1>
            <p class="mt-2 text-navy-200">Kenali perusahaan-perusahaan yang merekrut melalui Lokerin.</p>

            <form method="GET" action="{{ route('companies.index') }}" class="mt-6 flex max-w-xl gap-2">
                <input type="text" name="q" value="{{ request('q') }}" maxlength="100" placeholder="Cari nama, industri, atau lokasi" class="form-input flex-1">
                <button type="submit" class="btn btn-light"><x-icon name="search" class="size-4" /> Cari</button>
            </form>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @if ($companies->isEmpty())
            <div class="card"><x-empty-state icon="building" title="Perusahaan tidak ditemukan" message="Coba gunakan kata kunci lain." /></div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($companies as $company)
                    <a href="{{ route('companies.show', $company) }}" class="card flex flex-col p-6 transition hover:-translate-y-0.5 hover:border-navy-200 hover:shadow-md">
                        <div class="flex items-center gap-4">
                            <x-avatar :src="$company->logoUrl()" :initials="$company->initials()" size="size-14" rounded="rounded-2xl" />
                            <div class="min-w-0">
                                <p class="flex items-center gap-1 font-semibold text-slate-900">
                                    <span class="truncate">{{ $company->name }}</span>
                                    @if ($company->is_verified)
                                        <x-icon name="badge-check" class="size-5 shrink-0 text-sky-500" />
                                    @endif
                                </p>
                                <p class="text-sm text-slate-500">{{ $company->industry }}</p>
                            </div>
                        </div>
                        <p class="mt-4 line-clamp-3 text-sm text-slate-600">{{ $company->description }}</p>
                        <div class="mt-auto flex items-center justify-between pt-5 text-sm">
                            <span class="flex items-center gap-1 truncate text-slate-500"><x-icon name="map-pin" class="size-4 shrink-0" /> <span class="truncate">{{ \Illuminate\Support\Str::limit($company->address, 30) }}</span></span>
                            <x-badge color="sky">{{ $company->active_job_listings_count }} lowongan</x-badge>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $companies->links() }}</div>
        @endif
    </div>
</x-layouts.public>
