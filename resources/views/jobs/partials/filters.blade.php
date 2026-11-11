<form method="GET" action="{{ $action }}" class="card space-y-4 p-5">
    <div class="flex items-center justify-between">
        <h2 class="flex items-center gap-2 font-semibold text-slate-900"><x-icon name="search" class="size-5" /> Cari Lowongan</h2>
        @if (request()->filled('q'))
            <a href="{{ $action }}" class="text-xs font-medium text-navy-700 hover:underline">Reset</a>
        @endif
    </div>

    <x-form.input name="q" label="Kata Kunci" :value="request('q')" placeholder="Posisi, perusahaan, lokasi" maxlength="100" />

    <button type="submit" class="btn btn-primary w-full">Cari</button>
</form>
