<x-layouts.dashboard title="Pendidikan">
    <x-page-header title="Riwayat Pendidikan" subtitle="Tambahkan latar belakang pendidikan Anda." />

    <div class="grid gap-6 lg:grid-cols-5">
        <div class="card p-6 lg:col-span-3">
            <h2 class="mb-4 font-semibold text-slate-900">Daftar Pendidikan</h2>
            @if ($educations->isEmpty())
                <x-empty-state icon="academic" title="Belum ada riwayat pendidikan" message="Gunakan formulir di samping untuk menambahkan pendidikan pertama Anda." />
            @else
                <ol class="relative space-y-6 border-l-2 border-sky-100 pl-6">
                    @foreach ($educations as $education)
                        <li class="relative">
                            <span class="absolute top-1 -left-[33px] flex size-4 items-center justify-center rounded-full bg-navy-700 ring-4 ring-white"></span>
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $education->institution }}</p>
                                    <p class="text-sm text-slate-600">{{ $education->level->label() }}{{ $education->major ? ' - '.$education->major : '' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $education->period() }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('seeker.educations.edit', $education) }}" class="btn btn-secondary btn-sm"><x-icon name="pencil" class="size-4" /> Edit</a>
                                    <button type="button" data-modal-open="delete-education-{{ $education->id }}" class="btn btn-sm bg-red-50 text-red-700 hover:bg-red-100"><x-icon name="trash" class="size-4" /> Hapus</button>
                                </div>
                            </div>
                            <x-confirm-modal :id="'delete-education-'.$education->id" :action="route('seeker.educations.destroy', $education)" method="DELETE"
                                title="Hapus riwayat pendidikan?" :message="'Data pendidikan di '.$education->institution.' akan dihapus permanen.'" confirm-label="Ya, hapus" />
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>

        <form method="POST" action="{{ route('seeker.educations.store') }}" class="card space-y-5 p-6 lg:col-span-2 lg:self-start">
            @csrf
            <h2 class="font-semibold text-slate-900">Tambah Pendidikan</h2>
            @include('seeker.educations.form')
            <button type="submit" class="btn btn-primary w-full"><x-icon name="plus" class="size-4" /> Tambah Pendidikan</button>
        </form>
    </div>
</x-layouts.dashboard>
