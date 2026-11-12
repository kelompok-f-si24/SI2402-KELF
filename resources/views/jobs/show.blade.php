<x-layouts.public :title="$job->title">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('jobs.index') }}" class="mb-6 inline-flex items-center gap-1 text-sm font-medium text-slate-500 hover:text-navy-700">
            <x-icon name="arrow-left" class="size-4" /> Kembali ke daftar lowongan
        </a>

        <div class="grid gap-8 lg:grid-cols-[1fr_22rem]">
            @include('jobs.partials.detail', ['job' => $job, 'companyRoute' => route('companies.show', $job->company)])

            <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
                @include('jobs.partials.apply-panel', compact('job', 'existingApplication', 'cv'))

                @if ($relatedJobs->isNotEmpty())
                    <div class="card p-6">
                        <h2 class="mb-4 font-semibold text-slate-900">Lowongan Serupa</h2>
                        <div class="space-y-4">
                            @foreach ($relatedJobs as $related)
                                <a href="{{ route('jobs.show', $related) }}" class="block rounded-xl p-3 hover:bg-slate-50">
                                    <p class="font-medium text-slate-900">{{ $related->title }}</p>
                                    <p class="text-sm text-slate-500">{{ $related->company->name }} &middot; {{ $related->location }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</x-layouts.public>
