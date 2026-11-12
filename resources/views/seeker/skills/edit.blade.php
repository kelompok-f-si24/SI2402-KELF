<x-layouts.dashboard title="Edit Skill">
    <x-page-header title="Edit Skill" :back="route('seeker.skills.index')" />

    <form method="POST" action="{{ route('seeker.skills.update', $skill) }}" class="card max-w-xl space-y-5 p-6">
        @csrf
        @method('PUT')
        @include('seeker.skills.form', ['skill' => $skill])
        <div class="flex justify-end gap-2 border-t border-slate-100 pt-5">
            <a href="{{ route('seeker.skills.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</x-layouts.dashboard>
