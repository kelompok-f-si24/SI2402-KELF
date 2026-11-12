@props(['job', 'href'])

<a href="{{ $href }}" class="card group flex flex-col gap-4 p-5 transition hover:-translate-y-0.5 hover:border-navy-200 hover:shadow-md">
    <div class="flex items-start gap-3">
        <x-avatar :src="$job->company->logoUrl()" :initials="$job->company->initials()" size="size-12" rounded="rounded-xl" />
        <div class="min-w-0 flex-1">
            <h3 class="truncate font-semibold text-slate-900 group-hover:text-navy-700">{{ $job->title }}</h3>
            <p class="flex items-center gap-1 truncate text-sm text-slate-500">
                {{ $job->company->name }}
                @if ($job->company->is_verified)
                    <x-icon name="badge-check" class="size-4 text-sky-500" />
                @endif
            </p>
        </div>
        @unless ($job->isOpen())
            <x-badge color="gray">Ditutup</x-badge>
        @endunless
    </div>

    <div class="flex flex-wrap gap-2">
        <x-badge color="navy">{{ $job->employment_type->label() }}</x-badge>
        <x-badge color="sky">{{ $job->work_system->label() }}</x-badge>
        <x-badge color="gray">{{ $job->category->label() }}</x-badge>
    </div>

    <div class="mt-auto space-y-1.5 text-sm text-slate-600">
        <p class="flex items-center gap-2"><x-icon name="map-pin" class="size-4 text-slate-400" /> {{ $job->location }}</p>
        <p class="flex items-center gap-2 font-medium text-navy-800"><x-icon name="currency" class="size-4 text-slate-400" /> {{ $job->salaryRange() }}</p>
    </div>

    <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-xs text-slate-500">
        <span>{{ $job->published_at?->diffForHumans() }}</span>
        <span>Batas {{ $job->deadline->translatedFormat('d M Y') }}</span>
    </div>
</a>
