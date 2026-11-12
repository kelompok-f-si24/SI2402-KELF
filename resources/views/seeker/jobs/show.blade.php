<x-layouts.dashboard :title="$job->title">
    <x-page-header title="Detail Lowongan" :back="route('seeker.jobs.index')" />

    <div class="grid gap-6 xl:grid-cols-[1fr_22rem]">
        @include('jobs.partials.detail', ['job' => $job, 'companyRoute' => route('companies.show', $job->company)])
        <aside class="xl:sticky xl:top-24 xl:self-start">
            @include('jobs.partials.apply-panel', compact('job', 'existingApplication', 'cv'))
        </aside>
    </div>
</x-layouts.dashboard>
