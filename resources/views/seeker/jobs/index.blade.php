<x-layouts.dashboard title="Cari Lowongan">
    <x-page-header title="Cari Lowongan" subtitle="Temukan lowongan aktif yang sesuai dengan Anda." />

    <div class="grid gap-6 xl:grid-cols-[18rem_1fr]">
        <aside class="xl:sticky xl:top-24 xl:self-start">
            @include('jobs.partials.filters', ['action' => route('seeker.jobs.index')])
        </aside>
        @include('jobs.partials.results', ['jobs' => $jobs, 'route' => 'seeker.jobs.show'])
    </div>
</x-layouts.dashboard>
