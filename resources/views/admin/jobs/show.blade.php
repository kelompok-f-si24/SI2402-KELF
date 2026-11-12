<x-layouts.dashboard :title="$job->title">
    <x-page-header title="Detail Lowongan" :back="route('admin.jobs.index')">
        <button type="button" data-modal-open="delete-job-modal" class="btn btn-danger"><x-icon name="trash" class="size-4" /> Hapus Lowongan</button>
    </x-page-header>

    <div class="grid gap-6 xl:grid-cols-[1fr_20rem]">
        @include('jobs.partials.detail', ['job' => $job, 'companyRoute' => route('admin.companies.show', $job->company)])

        <div class="card p-6 xl:sticky xl:top-24 xl:self-start">
            <h2 class="mb-4 font-semibold text-slate-900">Moderasi</h2>
            <dl class="mb-5 space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-slate-500">Pelamar</dt><dd class="font-semibold">{{ $job->applications_count }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Dibuat</dt><dd class="font-semibold">{{ $job->created_at->translatedFormat('d M Y') }}</dd></div>
            </dl>
            <form method="POST" action="{{ route('admin.jobs.status', $job) }}" class="space-y-3">
                @csrf
                @method('PATCH')
                <x-form.select name="status" label="Status Lowongan" :options="$statuses" :value="$job->status" :placeholder="false" required />
                <button type="submit" class="btn btn-primary w-full">Simpan Status</button>
            </form>
        </div>
    </div>

    <x-confirm-modal id="delete-job-modal" :action="route('admin.jobs.destroy', $job)" method="DELETE"
        title="Hapus lowongan ini?" :message="'Lowongan '.$job->title.' beserta semua lamarannya akan dihapus permanen.'" confirm-label="Ya, hapus permanen" />
</x-layouts.dashboard>
