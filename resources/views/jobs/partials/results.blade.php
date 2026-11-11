<div>
    <p class="mb-4 text-sm text-slate-500">Menampilkan <span class="font-semibold text-slate-900">{{ $jobs->total() }}</span> lowongan aktif</p>

    @if ($jobs->isEmpty())
        <div class="card">
            <x-empty-state icon="search" title="Lowongan tidak ditemukan" message="Coba ubah kata kunci atau kurangi filter pencarian Anda." />
        </div>
    @else
        <div class="grid gap-5 md:grid-cols-2">
            @foreach ($jobs as $job)
                <x-job-card :job="$job" :href="route($route, $job)" />
            @endforeach
        </div>
        <div class="mt-8">{{ $jobs->links() }}</div>
    @endif
</div>
