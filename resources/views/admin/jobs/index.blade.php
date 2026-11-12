<x-layouts.dashboard title="Manajemen Lowongan">
    <x-page-header title="Manajemen Lowongan" subtitle="Awasi seluruh lowongan dan kelola statusnya." />

    <form method="GET" class="card mb-6 grid gap-3 p-4 md:grid-cols-[1fr_12rem_14rem_auto]">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul atau perusahaan" maxlength="100" class="form-input">
        <select name="status" class="form-input" aria-label="Status">
            <option value="">Semua status</option>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="category" class="form-input" aria-label="Kategori">
            <option value="">Semua kategori</option>
            @foreach ($categories as $value => $label)
                <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary"><x-icon name="search" class="size-4" /> Cari</button>
    </form>

    <div class="card overflow-hidden">
        @if ($jobs->isEmpty())
            <x-empty-state icon="briefcase" title="Lowongan tidak ditemukan" />
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="table-th">Lowongan</th>
                            <th class="table-th">Perusahaan</th>
                            <th class="table-th">Pelamar</th>
                            <th class="table-th">Batas</th>
                            <th class="table-th">Status</th>
                            <th class="table-th text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($jobs as $job)
                            <tr class="hover:bg-slate-50/60">
                                <td class="table-td">
                                    <p class="font-medium text-slate-900">{{ $job->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $job->category->label() }} &middot; {{ $job->location }}</p>
                                </td>
                                <td class="table-td">{{ $job->company->name }}</td>
                                <td class="table-td">{{ $job->applications_count }}</td>
                                <td class="table-td whitespace-nowrap">{{ $job->deadline->translatedFormat('d M Y') }}</td>
                                <td class="table-td"><x-badge :color="$job->status->color()">{{ $job->status->label() }}</x-badge></td>
                                <td class="table-td">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-secondary btn-sm"><x-icon name="eye" class="size-4" /> Detail</a>
                                        <form method="POST" action="{{ route('admin.jobs.status', $job) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $job->status === \App\Enums\JobStatus::Active ? 'closed' : 'active' }}">
                                            <button type="submit" class="btn btn-sm btn-light">{{ $job->status === \App\Enums\JobStatus::Active ? 'Tutup' : 'Aktifkan' }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-4 py-3">{{ $jobs->links() }}</div>
        @endif
    </div>
</x-layouts.dashboard>
