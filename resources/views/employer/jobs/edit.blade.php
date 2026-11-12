<x-layouts.dashboard title="Edit Lowongan">
    <x-page-header title="Edit Lowongan" :subtitle="$job->title" :back="route('employer.jobs.index')">
        <x-badge :color="$job->status->color()">{{ $job->status->label() }}</x-badge>
    </x-page-header>

    <form method="POST" action="{{ route('employer.jobs.update', $job) }}" class="max-w-4xl space-y-6">
        @csrf
        @method('PUT')
        @include('employer.jobs.form', ['job' => $job])
        <div class="flex justify-end gap-2">
            <a href="{{ route('employer.jobs.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</x-layouts.dashboard>
