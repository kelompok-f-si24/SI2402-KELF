<x-layouts.dashboard title="Manajemen Perusahaan">
    <x-page-header title="Manajemen Perusahaan" subtitle="Awasi data perusahaan yang terdaftar di Lokerin." />

    <form method="GET" class="card mb-6 grid gap-3 p-4 md:grid-cols-[1fr_14rem_auto]">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama perusahaan atau industri" maxlength="100" class="form-input">
        <select name="verified" class="form-input" aria-label="Verifikasi">
            <option value="">Semua perusahaan</option>
            <option value="1" @selected(request('verified') === '1')>Terverifikasi</option>
            <option value="0" @selected(request('verified') === '0')>Belum terverifikasi</option>
        </select>
        <button type="submit" class="btn btn-primary"><x-icon name="search" class="size-4" /> Cari</button>
    </form>

    <div class="card overflow-hidden">
        @if ($companies->isEmpty())
            <x-empty-state icon="building" title="Perusahaan tidak ditemukan" />
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="table-th">Perusahaan</th>
                            <th class="table-th">Pemilik</th>
                            <th class="table-th">Lowongan</th>
                            <th class="table-th">Lamaran</th>
                            <th class="table-th">Verifikasi</th>
                            <th class="table-th text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($companies as $company)
                            <tr class="hover:bg-slate-50/60">
                                <td class="table-td">
                                    <div class="flex items-center gap-3">
                                        <x-avatar :src="$company->logoUrl()" :initials="$company->initials()" size="size-9" rounded="rounded-lg" />
                                        <div>
                                            <p class="font-medium text-slate-900">{{ $company->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $company->industry }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="table-td">{{ $company->user->name }}</td>
                                <td class="table-td">{{ $company->job_listings_count }}</td>
                                <td class="table-td">{{ $company->applications_count }}</td>
                                <td class="table-td"><x-badge :color="$company->is_verified ? 'sky' : 'gray'">{{ $company->is_verified ? 'Terverifikasi' : 'Belum' }}</x-badge></td>
                                <td class="table-td">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-secondary btn-sm"><x-icon name="eye" class="size-4" /> Detail</a>
                                        <form method="POST" action="{{ route('admin.companies.verify', $company) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-light">{{ $company->is_verified ? 'Batalkan' : 'Verifikasi' }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-4 py-3">{{ $companies->links() }}</div>
        @endif
    </div>
</x-layouts.dashboard>
