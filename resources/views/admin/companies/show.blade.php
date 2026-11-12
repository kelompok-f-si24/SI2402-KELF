<x-layouts.dashboard :title="$company->name">
    <x-page-header title="Detail Perusahaan" :back="route('admin.companies.index')">
        <form method="POST" action="{{ route('admin.companies.verify', $company) }}">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-light"><x-icon name="badge-check" class="size-4" /> {{ $company->is_verified ? 'Batalkan Verifikasi' : 'Verifikasi Perusahaan' }}</button>
        </form>
        <button type="button" data-modal-open="delete-company-modal" class="btn btn-danger"><x-icon name="trash" class="size-4" /> Hapus</button>
    </x-page-header>

    @include('companies.partials.profile', ['company' => $company])

    <div class="card mt-6 p-6">
        <p class="text-sm text-slate-500">Pemilik akun: <a href="{{ route('admin.users.show', $company->user) }}" class="font-semibold text-navy-700 hover:underline">{{ $company->user->name }}</a> ({{ $company->user->email }})</p>
    </div>

    <div class="card mt-6 overflow-hidden">
        <h2 class="border-b border-slate-100 px-6 py-4 font-semibold text-slate-900">Lowongan Perusahaan</h2>
        @if ($jobs->isEmpty())
            <x-empty-state icon="briefcase" title="Belum ada lowongan" />
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="table-th">Lowongan</th>
                            <th class="table-th">Status</th>
                            <th class="table-th">Pelamar</th>
                            <th class="table-th">Dibuat</th>
                            <th class="table-th text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($jobs as $job)
                            <tr>
                                <td class="table-td font-medium text-slate-900">{{ $job->title }}</td>
                                <td class="table-td"><x-badge :color="$job->status->color()">{{ $job->status->label() }}</x-badge></td>
                                <td class="table-td">{{ $job->applications_count }}</td>
                                <td class="table-td">{{ $job->created_at->translatedFormat('d M Y') }}</td>
                                <td class="table-td text-right"><a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-secondary btn-sm">Kelola</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-4 py-3">{{ $jobs->links() }}</div>
        @endif
    </div>

    <x-confirm-modal id="delete-company-modal" :action="route('admin.companies.destroy', $company)" method="DELETE"
        title="Hapus perusahaan ini?" :message="'Perusahaan '.$company->name.' beserta semua lowongan dan lamarannya akan dihapus permanen. Akun pemiliknya tetap ada.'"
        confirm-label="Ya, hapus permanen" />
</x-layouts.dashboard>
