<x-layouts.public :title="$company->name">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('companies.index') }}" class="mb-6 inline-flex items-center gap-1 text-sm font-medium text-slate-500 hover:text-navy-700">
            <x-icon name="arrow-left" class="size-4" /> Kembali ke daftar perusahaan
        </a>

        @include('companies.partials.profile', ['company' => $company])

        <h2 class="mt-10 mb-5 text-xl font-bold text-slate-900">Lowongan Aktif ({{ $jobs->count() }})</h2>
        @if ($jobs->isEmpty())
            <div class="card"><x-empty-state icon="briefcase" title="Belum ada lowongan aktif" message="Perusahaan ini belum membuka lowongan saat ini." /></div>
        @else
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($jobs as $job)
                    <x-job-card :job="$job" :href="Route::has('jobs.show') ? route('jobs.show', $job) : null" />
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.public>
