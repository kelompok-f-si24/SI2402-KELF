<x-layouts.dashboard title="Buat Lowongan">
    <x-page-header title="Buat Lowongan Baru" subtitle="Lowongan akan langsung dipublikasikan dan dapat dilihat pencari kerja." :back="route('employer.jobs.index')" />

    <form method="POST" action="{{ route('employer.jobs.store') }}" class="max-w-4xl space-y-6">
        @csrf
        @include('employer.jobs.form')
        <div class="flex justify-end gap-2">
            <a href="{{ route('employer.jobs.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Publikasikan Lowongan</button>
        </div>
    </form>
</x-layouts.dashboard>
