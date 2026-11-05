@php($job ??= null)

<div class="card space-y-5 p-6">
    <h2 class="font-semibold text-slate-900">Informasi Umum</h2>
    <x-form.input name="title" label="Judul Pekerjaan" :value="$job?->title" required maxlength="255" placeholder="Contoh: Backend Developer" />
    <div class="grid gap-5 sm:grid-cols-2">
        <x-form.select name="category" label="Kategori" :options="$categories" :value="$job?->category" required />
        <x-form.input name="location" label="Lokasi" :value="$job?->location" required maxlength="100" placeholder="Contoh: Jakarta Selatan" />
        <x-form.select name="employment_type" label="Jenis Pekerjaan" :options="$employmentTypes" :value="$job?->employment_type" required />
        <x-form.select name="work_system" label="Sistem Kerja" :options="$workSystems" :value="$job?->work_system" required />
        <x-form.input name="salary_min" type="number" label="Gaji Minimum (Rp)" :value="$job?->salary_min" min="0" step="100000" placeholder="5000000" />
        <x-form.input name="salary_max" type="number" label="Gaji Maksimum (Rp)" :value="$job?->salary_max" min="0" step="100000" placeholder="8000000" hint="Kosongkan keduanya jika gaji dirahasiakan." />
        <x-form.input name="deadline" type="date" label="Batas Akhir Lamaran" :value="$job?->deadline?->format('Y-m-d')" required :min="today()->format('Y-m-d')" />
    </div>
</div>

<div class="card space-y-5 p-6">
    <h2 class="font-semibold text-slate-900">Detail Pekerjaan</h2>
    <x-form.textarea name="description" label="Deskripsi Pekerjaan" :value="$job?->description" rows="5" required minlength="30" maxlength="5000" />
    <x-form.textarea name="responsibilities" label="Tanggung Jawab" :value="$job?->responsibilities" rows="5" required maxlength="5000" hint="Tulis satu poin per baris." />
    <x-form.textarea name="qualifications" label="Kualifikasi" :value="$job?->qualifications" rows="5" required maxlength="5000" hint="Tulis satu poin per baris." />
</div>
