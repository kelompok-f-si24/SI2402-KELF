@php($education ??= null)

<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-form.input name="institution" label="Institusi" :value="$education?->institution" required maxlength="255" placeholder="Contoh: Telkom University" />
    </div>
    <x-form.select name="level" label="Jenjang" :options="$levels" :value="$education?->level" required />
    <x-form.input name="major" label="Jurusan" :value="$education?->major" maxlength="255" placeholder="Contoh: Sistem Informasi" />
    <x-form.input name="start_year" type="number" label="Tahun Mulai" :value="$education?->start_year" required min="1950" :max="now()->year" />
    <x-form.input name="end_year" type="number" label="Tahun Selesai" :value="$education?->end_year" min="1950" :max="now()->year + 7" hint="Kosongkan jika masih menempuh pendidikan." />
</div>
