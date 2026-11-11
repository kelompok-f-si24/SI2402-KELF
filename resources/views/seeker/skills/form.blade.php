@php($skill ??= null)

<x-form.input name="name" label="Nama Skill" :value="$skill?->name" required maxlength="100" placeholder="Contoh: Laravel, Komunikasi" />
<x-form.select name="type" label="Jenis Skill" :options="$types" :value="$skill?->type" required />
<x-form.select name="level" label="Tingkat Penguasaan" :options="$levels" :value="$skill?->level" required />
