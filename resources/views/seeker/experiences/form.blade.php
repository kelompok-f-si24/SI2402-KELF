@php($experience ??= null)

<div class="grid gap-5 sm:grid-cols-2">
    <x-form.input name="company_name" label="Nama Perusahaan" :value="$experience?->company_name" required maxlength="255" />
    <x-form.input name="position" label="Jabatan" :value="$experience?->position" required maxlength="255" />
    <div class="sm:col-span-2">
        <x-form.select name="employment_type" label="Jenis Pekerjaan" :options="$employmentTypes" :value="$experience?->employment_type" required />
    </div>
    <x-form.input name="start_date" type="date" label="Tanggal Mulai" :value="$experience?->start_date?->format('Y-m-d')" required :max="today()->format('Y-m-d')" />
    <x-form.input name="end_date" type="date" label="Tanggal Selesai" :value="$experience?->end_date?->format('Y-m-d')" :max="today()->format('Y-m-d')" hint="Kosongkan jika masih bekerja di sini." />
    <div class="sm:col-span-2">
        <x-form.textarea name="description" label="Deskripsi Pengalaman" :value="$experience?->description" rows="4" maxlength="2000"
            placeholder="Jelaskan tugas dan pencapaian Anda." />
    </div>
</div>
