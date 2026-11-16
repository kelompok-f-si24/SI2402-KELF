<x-layouts.dashboard title="Pengalaman Kerja">
    <x-page-header title="Pengalaman Kerja" subtitle="Tunjukkan riwayat profesional Anda." />

    <div class="grid gap-6 lg:grid-cols-5">
        <div class="card p-6 lg:col-span-3">
            <h2 class="mb-4 font-semibold text-slate-900">Riwayat Pengalaman</h2>
            @if ($experiences->isEmpty())
                <x-empty-state icon="briefcase" title="Belum ada pengalaman kerja" message="Tambahkan pengalaman magang, freelance, atau pekerjaan sebelumnya." />
            @else
                <div class="space-y-4">
                    @foreach ($experiences as $experience)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="flex gap-3">
                                    <div class="flex size-11 shrink-0 items-center justify-center rounded-lg bg-navy-50 text-navy-700"><x-icon name="building" class="size-5" /></div>
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $experience->position }}</p>
                                        <p class="text-sm text-slate-600">{{ $experience->company_name }}</p>
                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                            <x-badge color="navy">{{ $experience->employment_type->label() }}</x-badge>
                                            {{ $experience->period() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('seeker.experiences.edit', $experience) }}" class="btn btn-secondary btn-sm"><x-icon name="pencil" class="size-4" /> Edit</a>
                                    <button type="button" data-modal-open="delete-experience-{{ $experience->id }}" class="btn btn-sm bg-red-50 text-red-700 hover:bg-red-100"><x-icon name="trash" class="size-4" /> Hapus</button>
                                </div>
                            </div>
                            @if ($experience->description)
                                <p class="mt-3 text-sm whitespace-pre-line text-slate-600">{{ $experience->description }}</p>
                            @endif
                        </div>
                        <x-confirm-modal :id="'delete-experience-'.$experience->id" :action="route('seeker.experiences.destroy', $experience)" method="DELETE"
                            title="Hapus pengalaman kerja?" :message="'Pengalaman sebagai '.$experience->position.' akan dihapus permanen.'" confirm-label="Ya, hapus" />
                    @endforeach
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('seeker.experiences.store') }}" class="card space-y-5 p-6 lg:col-span-2 lg:self-start">
            @csrf
            <h2 class="font-semibold text-slate-900">Tambah Pengalaman</h2>
            @include('seeker.experiences.form')
            <button type="submit" class="btn btn-primary w-full"><x-icon name="plus" class="size-4" /> Tambah Pengalaman</button>
        </form>
    </div>
</x-layouts.dashboard>
