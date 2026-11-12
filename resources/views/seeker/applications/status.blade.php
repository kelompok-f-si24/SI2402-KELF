<x-layouts.dashboard title="Status Lamaran">
    <x-page-header title="Status Lamaran" subtitle="Pantau perkembangan proses seleksi lamaran Anda." />

    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('seeker.applications.status') }}" @class(['btn btn-sm', 'btn-primary' => ! $activeStatus, 'btn-secondary' => $activeStatus])>
            Semua ({{ $statusCounts->sum() }})
        </a>
        @foreach ($statuses as $status)
            <a href="{{ route('seeker.applications.status', ['status' => $status->value]) }}" @class(['btn btn-sm', 'btn-primary' => $activeStatus === $status, 'btn-secondary' => $activeStatus !== $status])>
                {{ $status->label() }} ({{ $statusCounts[$status->value] ?? 0 }})
            </a>
        @endforeach
    </div>

    @if ($applications->isEmpty())
        <div class="card"><x-empty-state icon="check-circle" title="Tidak ada lamaran" message="Belum ada lamaran dengan status ini." /></div>
    @else
        <div class="grid gap-4 md:grid-cols-2">
            @foreach ($applications as $application)
                <a href="{{ route('seeker.applications.show', $application) }}" class="card flex flex-col gap-4 p-5 transition hover:border-navy-200 hover:shadow-md">
                    <div class="flex items-start gap-3">
                        <x-avatar :src="$application->jobListing->company->logoUrl()" :initials="$application->jobListing->company->initials()" rounded="rounded-lg" />
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-semibold text-slate-900">{{ $application->jobListing->title }}</p>
                            <p class="truncate text-sm text-slate-500">{{ $application->jobListing->company->name }}</p>
                        </div>
                        <x-badge :color="$application->status->color()">{{ $application->status->label() }}</x-badge>
                    </div>
                    <p class="text-sm text-slate-600">{{ $application->status->description() }}</p>
                    <p class="text-xs text-slate-400">Diperbarui {{ ($application->status_updated_at ?? $application->updated_at)->diffForHumans() }}</p>
                </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $applications->links() }}</div>
    @endif
</x-layouts.dashboard>
