<x-layouts.dashboard title="Edit Pengalaman">
    <x-page-header title="Edit Pengalaman Kerja" :back="route('seeker.experiences.index')" />

    <form method="POST" action="{{ route('seeker.experiences.update', $experience) }}" class="card max-w-2xl space-y-5 p-6">
        @csrf
        @method('PUT')
        @include('seeker.experiences.form', ['experience' => $experience])
        <div class="flex justify-end gap-2 border-t border-slate-100 pt-5">
            <a href="{{ route('seeker.experiences.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</x-layouts.dashboard>
