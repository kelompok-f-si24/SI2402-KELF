<div class="card overflow-hidden">
    <div class="h-28 bg-gradient-to-r from-navy-900 via-navy-700 to-sky-500"></div>
    <div class="px-6 pb-6">
        <div class="-mt-10 flex flex-col gap-4 sm:flex-row sm:items-end">
            <x-avatar :src="$company->logoUrl()" :initials="$company->initials()" size="size-20" rounded="rounded-2xl" class="bg-white ring-4 ring-white" />
            <div class="flex-1">
                <h1 class="flex items-center gap-2 text-2xl font-bold text-slate-900">
                    {{ $company->name }}
                    @if ($company->is_verified)
                        <x-icon name="badge-check" class="size-6 text-sky-500" />
                    @endif
                </h1>
                <p class="text-slate-500">{{ $company->industry }}</p>
            </div>
        </div>

        <div class="mt-6 grid gap-3 text-sm text-slate-600 sm:grid-cols-2">
            <p class="flex items-start gap-2"><x-icon name="map-pin" class="size-5 shrink-0 text-slate-400" /> {{ $company->address }}</p>
            <p class="flex items-center gap-2"><x-icon name="mail" class="size-5 text-slate-400" /> {{ $company->email }}</p>
            <p class="flex items-center gap-2"><x-icon name="phone" class="size-5 text-slate-400" /> {{ $company->phone }}</p>
            @if ($company->website)
                <p class="flex items-center gap-2"><x-icon name="globe" class="size-5 text-slate-400" />
                    <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="text-navy-700 hover:underline">{{ $company->website }}</a>
                </p>
            @endif
        </div>

        <div class="mt-6 border-t border-slate-100 pt-6">
            <h2 class="mb-2 font-semibold text-slate-900">Tentang Perusahaan</h2>
            <p class="text-sm leading-relaxed whitespace-pre-line text-slate-600">{{ $company->description }}</p>
        </div>
    </div>
</div>
