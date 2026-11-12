<x-layouts.public title="Lowongan Kerja">
    <section class="bg-navy-900">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-white">Lowongan Kerja</h1>
            <p class="mt-2 text-navy-200">Temukan pekerjaan yang sesuai dengan keahlian dan minat Anda.</p>
        </div>
    </section>

    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[18rem_1fr] lg:px-8">
        <aside class="lg:sticky lg:top-24 lg:self-start">
            @include('jobs.partials.filters', ['action' => route('jobs.index')])
        </aside>
        @include('jobs.partials.results', ['jobs' => $jobs, 'route' => 'jobs.show'])
    </div>
</x-layouts.public>
