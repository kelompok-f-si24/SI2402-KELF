<x-layouts.dashboard title="Edit Pendidikan">
    <x-page-header title="Edit Pendidikan" :back="route('seeker.educations.index')" />

    <form method="POST" action="{{ route('seeker.educations.update', $education) }}" class="card max-w-2xl space-y-5 p-6">
        @csrf
        @method('PUT')
        @include('seeker.educations.form', ['education' => $education])
        <div class="flex justify-end gap-2 border-t border-slate-100 pt-5">
            <a href="{{ route('seeker.educations.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</x-layouts.dashboard>
