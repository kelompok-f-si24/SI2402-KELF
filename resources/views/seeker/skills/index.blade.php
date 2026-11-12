<x-layouts.dashboard title="Skill">
    <x-page-header title="Soft Skill & Hard Skill" subtitle="Tunjukkan keterampilan yang Anda kuasai kepada perusahaan." />

    <div class="grid gap-6 lg:grid-cols-5">
        <div class="space-y-6 lg:col-span-3">
            @foreach ($types as $typeValue => $typeLabel)
                @php($skills = $skillsByType[$typeValue] ?? collect())
                <div class="card p-6">
                    <h2 class="mb-4 flex items-center gap-2 font-semibold text-slate-900">
                        {{ $typeLabel }} <x-badge :color="$typeValue === 'hard' ? 'indigo' : 'sky'">{{ $skills->count() }}</x-badge>
                    </h2>
                    @if ($skills->isEmpty())
                        <x-empty-state icon="sparkles" :title="'Belum ada '.strtolower($typeLabel)" message="Tambahkan melalui formulir di samping." class="py-8" />
                    @else
                        <div class="space-y-4">
                            @foreach ($skills as $skill)
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="font-medium text-slate-900">{{ $skill->name }}</span>
                                            <span class="text-slate-500">{{ $skill->level->label() }}</span>
                                        </div>
                                        <div class="mt-1.5 h-2 rounded-full bg-slate-100">
                                            <div class="h-2 rounded-full {{ $typeValue === 'hard' ? 'bg-navy-700' : 'bg-sky-500' }}" style="width: {{ $skill->level->percentage() }}%"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('seeker.skills.edit', $skill) }}" class="btn btn-secondary btn-sm" aria-label="Edit {{ $skill->name }}"><x-icon name="pencil" class="size-4" /></a>
                                        <button type="button" data-modal-open="delete-skill-{{ $skill->id }}" class="btn btn-sm bg-red-50 text-red-700 hover:bg-red-100" aria-label="Hapus {{ $skill->name }}"><x-icon name="trash" class="size-4" /></button>
                                    </div>
                                </div>
                                <x-confirm-modal :id="'delete-skill-'.$skill->id" :action="route('seeker.skills.destroy', $skill)" method="DELETE"
                                    title="Hapus skill?" :message="'Skill '.$skill->name.' akan dihapus dari profil Anda.'" confirm-label="Ya, hapus" />
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('seeker.skills.store') }}" class="card space-y-5 p-6 lg:col-span-2 lg:self-start">
            @csrf
            <h2 class="font-semibold text-slate-900">Tambah Skill</h2>
            @include('seeker.skills.form')
            <button type="submit" class="btn btn-primary w-full"><x-icon name="plus" class="size-4" /> Tambah Skill</button>
        </form>
    </div>
</x-layouts.dashboard>
