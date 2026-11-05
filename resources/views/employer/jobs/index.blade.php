<x-layouts.dashboard title="Daftar Lowongan">
    <x-page-header title="Daftar Lowongan" subtitle="Pantau dan kelola semua lowongan perusahaan Anda.">
        <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary"><x-icon name="plus" class="size-4" /> Buat Lowongan</a>
    </x-page-header>

    <form method="GET" class="card mb-6 flex flex-col gap-3 p-4 sm:flex-row">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul lowongan" maxlength="100" class="form-input flex-1">
        <select name="status" class="form-input sm:w-48" aria-label="Status">
            <option value="">Semua status</option>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary"><x-icon name="search" class="size-4" /> Cari</button>
    </form>

    <div class="card overflow-hidden">
        @if ($jobs->isEmpty())
            <x-empty-state icon="briefcase" title="Belum ada lowongan" message="Buat lowongan pertama Anda untuk mulai menerima pelamar.">
                <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary">Buat Lowongan</a>
            </x-empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="table-th">Lowongan</th>
                            <th class="table-th">Dipublikasikan</th>
                            <th class="table-th">Batas</th>
                            <th class="table-th">Status</th>
                            <th class="table-th">Pelamar</th>
                            <th class="table-th text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($jobs as $job)
                            <tr class="hover:bg-slate-50/60">
                                <td class="table-td">
                                    <p class="font-medium text-slate-900">{{ $job->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $job->location }} &middot; {{ $job->employment_type->label() }}</p>
                                </td>
                                <td class="table-td whitespace-nowrap">{{ $job->published_at?->translatedFormat('d M Y') ?? '-' }}</td>
                                <td class="table-td whitespace-nowrap {{ $job->deadline->isPast() && ! $job->deadline->isToday() ? 'text-red-600' : '' }}">{{ $job->deadline->translatedFormat('d M Y') }}</td>
                                <td class="table-td"><x-badge :color="$job->status->color()">{{ $job->status->label() }}</x-badge></td>
                                <td class="table-td">
                                    <a href="{{ route('employer.applicants.index', ['job' => $job->id]) }}" class="font-semibold text-navy-700 hover:underline">{{ $job->applications_count }} pelamar</a>
                                </td>
                                <td class="table-td">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('jobs.show', $job) }}" target="_blank" class="btn btn-secondary btn-sm" aria-label="Lihat"><x-icon name="eye" class="size-4" /></a>
                                        <a href="{{ route('employer.jobs.edit', $job) }}" class="btn btn-secondary btn-sm"><x-icon name="pencil" class="size-4" /> Edit</a>
                                        @if ($job->status === \App\Enums\JobStatus::Active)
                                            <button type="button" data-modal-open="close-job-{{ $job->id }}" class="btn btn-sm bg-red-50 text-red-700 hover:bg-red-100"><x-icon name="lock" class="size-4" /> Tutup</button>
                                        @else
                                            <button type="button" data-modal-open="reopen-job-{{ $job->id }}" class="btn btn-sm bg-emerald-50 text-emerald-700 hover:bg-emerald-100"><x-icon name="unlock" class="size-4" /> Buka</button>
                                        @endif
                                    </div>
                                    @if ($job->status === \App\Enums\JobStatus::Active)
                                        <x-confirm-modal :id="'close-job-'.$job->id" :action="route('employer.jobs.close', $job)" method="PATCH"
                                            title="Tutup lowongan ini?" :message="'Lowongan '.$job->title.' tidak akan menerima lamaran baru setelah ditutup.'" confirm-label="Ya, tutup lowongan" />
                                    @else
                                        <x-confirm-modal :id="'reopen-job-'.$job->id" :action="route('employer.jobs.reopen', $job)" method="PATCH" variant="primary"
                                            title="Buka kembali lowongan?" :message="'Lowongan '.$job->title.' akan kembali menerima lamaran.'" confirm-label="Ya, buka kembali" />
                                    @endif
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
